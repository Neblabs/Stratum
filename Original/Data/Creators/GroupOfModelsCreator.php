<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Creator\BaseModelCreator;
use Stratum\Original\Data\GroupOf;

Class GroupOfModelsCreator extends BaseModelCreator
{
    protected $dataObjects = [];
    protected $modelCreator;
    protected $aliases = [];
    protected $tablePrefix;

    public function __construct()
    {
        $this->modelCreator = new ModelCreator;
    }

    public function setDataObjects(array $dataObjects)
    {
        $this->dataObjects = $dataObjects;
    }

    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    public function setEntityType($fullyQualifiedEntityClassName)
    {
        $this->modelCreator->setEntityType($fullyQualifiedEntityClassName);
    } 

    public function setPrimaryKey($primaryKey)
    {
        $this->modelCreator->setPrimaryKey($primaryKey);
    }

    /**
     * Optional, will override the one from the constructor.
     * 
     * @param ModelCreator $modelCreator 
     * 
     */
    public function setModelCreator(ModelCreator $modelCreator)
    {
        $this->modelCreator = $modelCreator;
    }

    public function create()
    {
        (array) $models = [];

        foreach ($this->dataObjects as $data) {
            $this->modelCreator->setData($data);
            $this->modelCreator->setAliases($this->aliases);
            $this->modelCreator->setTablePrefix($this->tablePrefix);

            $models[] = $this->modelCreator->create();
        }

        return new GroupOf($models);
    }


}