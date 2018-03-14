<?php

namespace Stratum\Original\Data;

use Stratum\Original\Data\Ability\Savable;
use Stratum\Original\Data\Data;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Saver implements Savable
{
    protected $data;
    protected $singleEntityType;
    protected $dataWasSaved = false;
    protected $primaryKey = 'id';
    
    abstract public function save();

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function setSingleEntityType($singleEntityType)
    {
        $this->singleEntityType = $singleEntityType;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function wasSaved()
    {
        return $this->dataWasSaved;
    }

    protected function hasPrimaryKey()
    {
        return $this->data->{$this->primaryKey} !== null;
    }

}