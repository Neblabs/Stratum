<?php

namespace Stratum\Original\Data;

Class DynamicFieldResolver extends DynamicMethodResolver
{
    protected $fieldName;
    protected $isFieldNameOnly = false;
    public $isSetterForSameEntity = false;
    protected $isOrderByDescending = false;
    protected $isOrderByAscending = false;
    protected $fieldAliases = [];

    public function setFieldAliases(array $fieldAliases)
    {
        $this->fieldAliases = $fieldAliases;
    }

    protected function generateFieldName()
    {   
        if ($this->methodNameStartsWith('with')) {

            $this->isSetterForSameEntity = true;
            $this->fieldName = $this->removeWordFromMethodName('with');

        } elseif ($this->methodNameStartsWith('by')) {

            $this->isSetterForSameEntity = true;
            $this->fieldName = $this->removeWordFromMethodName('by');

        } elseif ($this->methodNameStartsWith('in')) {

            $this->isSetterForSameEntity = true;
            $this->fieldName = $this->removeWordFromMethodName('in');

        } elseif ($this->methodNameStartsWith('ableTo')) {

            $this->isSetterForSameEntity = true;
            $this->fieldName = $this->removeWordFromMethodName('ableTo');

        } elseif ($this->methodNameStartsWith('highest')) {

            $this->isSetterForSameEntity = true;
            $this->isOrderByDescending = true;
            $this->fieldName = $this->fieldFromOrderByDescending();

        } elseif ($this->methodNameStartsWith('lowest')) {

            $this->isSetterForSameEntity = true;
            $this->isOrderByAscending = true;
            $this->fieldName = $this->fieldFromOrderByAscending();

        } else {
            $this->fieldName = $this->methodName;
            $this->isFieldNameOnly = true;
        }
    }

    public function fieldName()
    {
        return $this->useAliasFor($this->fieldName);
    }

    public function isFieldNameOnly()
    {
        return $this->isFieldNameOnly;
    }

    public function isNotFieldNameOnly()
    {
        return !$this->isFieldNameOnly();
    }

    public function isSetterForSameEntity()
    {
        return $this->isSetterForSameEntity;
    }

    public function isOrderByDescending()
    {
        return $this->isOrderByDescending;
    }

    public function isOrderByAscending()
    {
        return $this->isOrderByAscending;
    }

    protected function useAliasFor($fieldName)
    {
        $fieldName = lcfirst($fieldName);

        (boolean) $aliasExistForField = isset($this->fieldAliases[$fieldName]);

        if ($aliasExistForField) {
            return $this->fieldAliases[$fieldName];
        }

        return $fieldName;
    }

    protected function fieldFromOrderByDescending()
    {
        (string) $methodWithoutHighest = $this->removeWordFromMethodName('highest');
        (string) $fieldName = substr(
            $methodWithoutHighest,
            0,
            strlen($methodWithoutHighest) - strlen('first') 
        );

        return $this->useAliasFor($fieldName);
    }

    protected function fieldFromOrderByAscending()
    {
        (string) $methodWithoutHighest = $this->removeWordFromMethodName('lowest');
        (string) $fieldName = substr(
            $methodWithoutHighest,
            0,
            strlen($methodWithoutHighest) - strlen('first') 
        );

        return $this->useAliasFor($fieldName);
    }

   















}