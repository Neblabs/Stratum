<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Creator\GroupOfModelsCreator;
use Stratum\Original\Data\Creator\ModelCreator;
use Stratum\Original\Data\Data;

Class SingleModelOrGroupOfModelsCreator
{
    protected $modelClassName;
    protected $queryResult;
    protected $modelCreator;
    protected $groupOfModelsCreator;
    protected $aliases = [];
    protected $aSingleModelIsMeantToBeReturned = false;
    protected $tablePrefix = '';

    public function __construct()
    {
        $this->modelCreator = new ModelCreator;
        $this->groupOfModelsCreator = new GroupOfModelsCreator;
    }
    public function setEntityType($fulyQualifiedModelClassName)
    {
        $this->modelClassName = $fulyQualifiedModelClassName; 
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->modelCreator->setPrimaryKey($primaryKey);
        $this->groupOfModelsCreator->setPrimaryKey($primaryKey);
    }

    public function setTablePrefix($tablePrefix)
    {
        $this->tablePrefix = $tablePrefix;
    }

    public function setQueryResult($queryResult)
    {   
        $this->queryResult = $queryResult;
    }

    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    public function setWhetherOnlyOneSingleModelIsMeantToBeReturned($aSingleModelIsMeantToBeReturned)
    {
        $this->aSingleModelIsMeantToBeReturned = $aSingleModelIsMeantToBeReturned;
    }

    /**
     * Overrides the modelCreator created in constructor.
     * @param ModelCreator $modelCreator 
     */
    public function setModelCreator(ModelCreator $modelCreator)
    {
        $this->modelCreator = $modelCreator;
    }

    /**
     * Overrides the groupOfModelsCreator created in constructor.
     * @param GroupOfModelsCreator $groupOfModelsCreator 
     */
    public function setGroupOfModelsCreator(GroupOfModelsCreator $groupOfModelsCreator)
    {
        $this->groupOfModelsCreator = $groupOfModelsCreator;
    }

    public function create()
    {

        $this->groupOfModelsCreator->setEntityType($this->modelClassName);
        $this->modelCreator->setEntityType($this->modelClassName);

        $this->groupOfModelsCreator->setTablePrefix($this->tablePrefix);
        $this->modelCreator->setTablePrefix($this->tablePrefix);

        $this->groupOfModelsCreator->setAliases($this->aliases);

        if ($this->moreThanOneSingleModelIsMeantToBeReturned()) {

            $this->groupOfModelsCreator->setDataObjects($this->queryResult);

            return $this->groupOfModelsCreator->create();

        } elseif ($this->onlyOneSingleModelIsMeantToBeReturned() and $this->entitiesWereFound()) {

            $this->modelCreator->setData(!empty($this->queryResult[0]) ? $this->queryResult[0] : new Data);
            $this->modelCreator->setAliases($this->aliases);

            return $this->modelCreator->create();

        }
    }



    protected function moreThanOneSingleModelIsMeantToBeReturned()
    {
        return  ! $this->aSingleModelIsMeantToBeReturned;
    }

    protected function onlyOneSingleModelIsMeantToBeReturned()
    {
        return  $this->aSingleModelIsMeantToBeReturned;
    }

    protected function entitiesWereFound()
    {
        return !empty($this->queryResult);
    }

}