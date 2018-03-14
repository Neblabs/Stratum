<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Data;

Class ArrayOfDataCreator
{
    protected $sets = [];
    protected $dataObjects = [];

    public function __construct(array $arrayOfSets)
    {
        $this->sets = $arrayOfSets;
    }

    public function create()
    {
        foreach ($this->sets as $set) {
            (object) $data = new Data;

            foreach ($set as $fieldName => $fieldValue) {
                $data->$fieldName = $fieldValue;
            }

            $this->dataObjects[] = $data;
        }

        return $this->dataObjects;
    }
}