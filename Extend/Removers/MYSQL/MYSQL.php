<?php

namespace Stratum\Extend\Remover\MYSQL;

use Stratum\Original\Data\Data;
use Stratum\Original\Data\DatabaseQuerier;

Class MYSQL
{
    protected $data;
    protected $singleEntityType;
    protected $primaryKey = 'id';
    protected $querier;
    protected $sqlParameters;
    protected $databaseQuerier;
    protected $tablePrefix;

    public function __construct(Data $data)
    {
        $this->data = $data;
        $this->databaseQuerier = new DatabaseQuerier;
        $this->databaseQuerier->isNotSelect();
    }

    public function setSingleEntityType($singleEntityType)
    {
        $this->singleEntityType = $singleEntityType;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    public function remove()
    {
        $this->databaseQuerier->setSQL($this->removeSQL());
        $this->databaseQuerier->setSQLParameters([$this->data->{$this->primaryKey}]);
        $this->databaseQuerier->query();
    }

    protected function removeSQL()
    {
        return "DELETE FROM {$this->table()} WHERE {$this->primaryKey} = ?";
    }

    protected function table()
    {
        (string) $entityType = strtolower($this->singleEntityType);
        
        return "{$this->tablePrefix}{$entityType}";
    }




}