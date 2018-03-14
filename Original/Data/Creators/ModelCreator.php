<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Creator\BaseModelCreator;
use Stratum\Original\Data\Data;

Class ModelCreator extends BaseModelCreator
{
    protected $data;
    protected $primaryKey;
    protected $tablePrefix = '';

    public function setEntityType($fullyQualifiedEntityClassName)
    {
        $this->entityType = $fullyQualifiedEntityClassName;
    } 

    public function setData(Data $data)
    {
        $this->data = $data;
    }

    public function setAliases(array $aliases)
    {
        $this->data->setAliases($aliases);
    }

    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function create()
    {
        (string) $Model = $this->generateCustomModelClassName();
        
        (object) $Model = new $Model($this->data);

        $Model->setPrimaryKey($this->primaryKey);
        $Model->setTablePrefix($this->tablePrefix);

        return $Model;

    }













}