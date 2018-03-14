<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Data;

Class WPPModelObjectToDataObjectConverter 
{
    protected $WPModel;
    protected $fieldNames;
    protected $fieldAliases = [];

    public function __construct($WPModel)
    {
        $this->WPModel = $WPModel;
    }

    public function setFieldsToConvert(array $fieldNames)
    {
        $this->fieldNames = $fieldNames;
    }

    public function setfieldAliases(array $fieldAliases)
    {
        $this->fieldAliases = $fieldAliases;
    }

    public function convertedDataObject()
    {
        return $this->createADataItemObjectFromWP_PostObject();
    }

    protected function createADataItemObjectFromWP_PostObject()
    {
        (object) $data = new Data;

        $data->setAliases($this->fieldAliases);

        foreach (get_object_vars($this->WPModel) as $fieldName => $fieldValue) {

            $data->$fieldName = $this->WPModel->$fieldName;

        }

        return $data;
    }










}