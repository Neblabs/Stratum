<?php

namespace Stratum\Extend\Saver\MYSQL;

use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\Saver;

Class MYSQL extends Saver
{
    protected $querier;
    protected $sqlParameters;
    protected $databaseQuerier;
    protected $tablePrefix;

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    public function save()
    {
        $this->setQuerier();

        if ($this->hasPrimaryKey()) {
            $this->updateExistingRow();
        } else {
            $this->insertNewRow();
        }
    }

    public function lastInsertId()
    {
        return $this->querier->lastInsertId();
    }

    public function setDataBaseQuerier(DatabaseQuerier $databaseQuerier)
    {
        $this->databaseQuerier = $databaseQuerier;
    }

    protected function setQuerier()
    {
        $this->querier = is_null($this->databaseQuerier) ? new DatabaseQuerier : $this->databaseQuerier;
    }

    protected function updateExistingRow()
    {
        $this->querier->setSql($this->UPDATEsql());
        $this->result = $this->query();
    }

    protected function insertNewRow()
    {
        $this->querier->setSql($this->INSERTsql());

        $this->result = $this->query();
    }

    protected function query()
    {
        $this->querier->isNotSELECT();
        $this->querier->setSqlParameters($this->sqlParameters());

        (boolean) $result = $this->querier->query();

        $this->dataWasSaved = $result;

        return $result;
    }

    public function INSERTsql()
    {
        $this->sqlParameters = [];
        (string) $fieldNames = '';
        (string) $placeholders = '';

        foreach (get_object_vars($this->data) as $fieldName => $value) {
            $fieldNames .= "$fieldName, ";
            $placeholders .= "?, ";
            $values[] = $value;
        }

        $fieldNames = rtrim($fieldNames, ', ');
        $placeholders = rtrim($placeholders, ', ');

        (string) $sql = "INSERT INTO {$this->table()} "
                       ."($fieldNames) VALUES ($placeholders) ";

        $this->sqlParameters = $values;

        return $sql;
    }

    public function UPDATEsql()
    {
        $this->sqlParameters = [];
        (string) $fieldsFromAllPublicProperties = '';

        foreach (get_object_vars($this->data) as $fieldName => $value) {
            $fieldsFromAllPublicProperties.= "$fieldName=?, ";
            $values[] = $value;
        }

        $values[] = $this->data->{$this->primaryKey};

        $fieldsFromAllPublicProperties = rtrim($fieldsFromAllPublicProperties, ', ');

        (string) $sql = "UPDATE {$this->table()} "
                       ."SET $fieldsFromAllPublicProperties "
                       ."WHERE $this->primaryKey = ?";

        $this->sqlParameters = $values;

        return $sql;
    }

    protected function table()
    {
        (string) $entityType = strtolower($this->singleEntityType);
        
        return "{$this->tablePrefix}{$entityType}";
    }

    public function sqlParameters()
    {
        return $this->sqlParameters;

    }

    public function __sleep()
    {
        return [
            'data',
            'singleEntityType',
            'dataWasSaved ',
            'primaryKey ',
            'tablePrefix',
        ];
    }

}