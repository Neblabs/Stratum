<?php

namespace Stratum\Original\Data;

Class QueryBuilderDynamicFieldResolver extends DynamicFieldResolver
{

    protected $isorMoreMethod = false;
    protected $isorLessMethod = false;
    
    protected $methodStartsOnlyWithAnd = false;
    protected $methodStartsOnlyWithOr = false;

    protected function generateFieldName()
    {   
        
        if ($this->methodNameStartsWith('andWith')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'AND';
            $this->fieldName = $this->removeWordFromMethodName('andWith');

        } elseif ($this->methodNameStartsWith('andIn')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'AND';
            $this->fieldName = $this->removeWordFromMethodName('andIn');

        } elseif ($this->methodNameStartsWith('andBy')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'AND';
            $this->fieldName = $this->removeWordFromMethodName('andBy');

        } elseif ($this->methodNameStartsWith('andAbleTo')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'AND';
            $this->fieldName = $this->removeWordFromMethodName('andAbleTo');

        } elseif ($this->methodNameStartsWith('orWith')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'OR';
            $this->fieldName = $this->removeWordFromMethodName('orWith');

        } elseif ($this->methodNameStartsWith('orIn')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'OR';
            $this->fieldName = $this->removeWordFromMethodName('orIn');

        } elseif ($this->methodNameStartsWith('orBy')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'OR';
            $this->fieldName = $this->removeWordFromMethodName('orBy');

        } elseif ($this->methodNameStartsWith('orAbleTo')) {

            $this->isSetterForSameEntity = true;
            $this->conditionalType = 'OR';
            $this->fieldName = $this->removeWordFromMethodName('orAbleTo');

        } elseif ($this->methodNameStartsWith('orMore')) {

            $this->isorMoreMethod = true;
            $this->fieldName = $this->removeWordFromMethodName('orMore');

        } elseif ($this->methodNameStartsWith('orLess')) {

            $this->isorLessMethod = true;
            $this->fieldName = $this->removeWordFromMethodName('orLess');

        } elseif ($this->methodNameStartsWith('and')) {   

            $this->methodStartsOnlyWithAnd = true;
            $this->fieldName = $this->removeWordFromMethodName('and');

        } elseif ($this->methodNameStartsWith('or')) {
            $this->methodStartsOnlyWithOr = true;
            $this->fieldName = $this->removeWordFromMethodName('or');

        } else {

            $this->conditionalType = 'AND';
            parent::generateFieldName();

        }

    }

    public function isANDCondition()
    {
        (boolean) $conditionalTypeIsAND = $this->conditionalType === 'AND';

        return $conditionalTypeIsAND;

    }

    public function isORCondition()
    {
        (boolean) $conditionalTypeIsOR = $this->conditionalType === 'OR';

        return $conditionalTypeIsOR;

    }

    public function isOrMoreMethod()
    {
        return $this->isorMoreMethod;
    }

     public function isOrLessMethod()
    {
        return $this->isorLessMethod;
    }

    public function methodStartsOnlyWithAnd()
    {
        return $this->methodStartsOnlyWithAnd;
    }

    public function methodStartsOnlyWithOr()
    {
        return $this->methodStartsOnlyWithOr;
    }





















}