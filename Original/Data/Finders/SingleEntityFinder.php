<?php

namespace Stratum\Original\Data\Finder;

use Stratum\Original\Data\Creator\FieldCreator;
use Stratum\Original\Data\Creator\SingleModelOrGroupOfModelsCreator;
use Stratum\Original\Data\Exception\MissingActionException;
use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder;
use Stratum\Original\Data\QueryBuilderDynamicFieldResolver;

Abstract Class SingleEntityFinder extends Finder
{

    protected $isfirstStart = true;
    protected $isRestart = false;
    protected $onBuilderStartHasNotBeenCalled = true;
    protected $noOtherConditionWasMade = true;
    protected $primaryKeyFieldWasRequested = false;
    protected $state = 'DirectEntity';
    protected $moreOrLessThanIsNotBeignSet = true;
    protected $primaryKey = 'id';
    protected $secondaryKeys = [];
    protected $SingleModelOrGroupOfModelsCreator;

    protected $fieldAliases = [];

    abstract protected function onEqualityField(Field $field); 

    abstract protected function onMoreThanField(Field $field);

    abstract protected function onLessThanField(Field $field);

    abstract protected function onConditionalAND();

    abstract protected function onConditionalOR();

    abstract protected function onOrderByAscending(Field $field);

    abstract protected function onOrderByDescending(Field $field);

    public function __construct()
    {
        $this->fieldCreator = new FieldCreator;
        $this->SingleModelOrGroupOfModelsCreator = new SingleModelOrGroupOfModelsCreator;
    }

    public function setFieldCreator(FieldCreator $fieldCreator) 
    {
        $this->fieldCreator = $fieldCreator;
    }

    public function setSingleModelOrGroupOfModelsCreator(SingleModelOrGroupOfModelsCreator $SingleModelOrGroupOfModelsCreator)
    {
        $this->SingleModelOrGroupOfModelsCreator = $SingleModelOrGroupOfModelsCreator;
    }

    public function higherThan($value)
    {
        return $this->callOnMoreOrLessThanFieldEvent([
            'event' => 'onMoreThanField',
            'value' => $value
        ]);
    }

    public function lowerThan($value)
    {
        return $this->callOnMoreOrLessThanFieldEvent([
            'event' => 'onLessThanField',
            'value' => $value
        ]);   
    }

    public function fieldAliases()
    {
        return $this->fieldAliases;
    }  

    public function hasOneSingleEntityBeenRequested()
    {
        return $this->primaryKeyFieldWasRequested and $this->noOtherConditionWasMade;
    }

    public static function __callStatic($method, $arguments = null)
    {
        (object) $finder = new Static;

        call_user_func_array([$finder, $method], $arguments);

        return $finder;
    }

    public function __call($method, $arguments)
    {
        (object) $dynamicField = new QueryBuilderDynamicFieldResolver($method);

        $dynamicField->setFieldAliases($this->fieldAliases);
        
        (boolean) $dynamicFieldIsStartOfMoreOrLessComparison = empty($arguments);
     
        if ($dynamicField->isSetterForSameEntity()) {

            $this->throwExceptionIfStateIsNot('DirectEntity');

            $this->callOnStartBuilderEventIfBuilderHasJustStarted();

            if ($dynamicField->isOrderByAscending() or $dynamicField->isOrderByDescending()) {
                
                $this->callOrderByBasedOn($dynamicField);
                return $this;
            }

            $this->callOnConditionalANDEventIfDynamicFieldIsNotORorFieldAndBuilderHasAlreadyStarted($dynamicField);
            $this->callOnConditionalOREventIfDynamicFieldIsanORConditionedField($dynamicField);

            $this->setWhetherPrimaryKeyHasBeenRequestOrNotBasedOn($dynamicField, $dynamicFieldIsStartOfMoreOrLessComparison);
        
            if ($dynamicFieldIsStartOfMoreOrLessComparison) {

                $this->throwExceptionIfMoreOrLessThanIsBeingSet();

                $this->moreOrLessThanIsNotBeignSet = false;

                $this->fieldName = $dynamicField->fieldName();

            } else {

                $this->onEqualityField($this->fieldCreator->createFrom([
                    'fieldName' => $dynamicField->fieldName(),
                    'fieldValue' => $arguments[0]
                ]));

                $this->addEventToTrace('onEqualityField');

            }
            
            $this->isfirstStart = false;

            return $this;

        }

        throw new \BadMethodCallException($dynamicField->fieldName());
    }

    protected function callOnStartBuilderEventIfBuilderHasJustStarted()
    {
        if ($this->isfirstStart and $this->onBuilderStartHasNotBeenCalled) {

            $this->onBuilderStart();
            $this->onBuilderStartHasNotBeenCalled = false;
            $this->addEventToTrace('onBuilderStart');
        }
    }

    protected function callOnConditionalANDEventIfDynamicFieldIsNotORorFieldAndBuilderHasAlreadyStarted(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        
        if ($dynamicField->isANDCondition()) {
            $this->callOnConditionalANDEvent();
        }
    }

    protected function callOnConditionalOREventIfDynamicFieldIsanORConditionedField(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        if ($dynamicField->isORCondition()) {
            $this->callOnConditionalOREvent();
        }
    }

    protected function callOnConditionalANDEvent()
    {
        if (!$this->isfirstStart or $this->isRestart) {
            $this->onConditionalAND();
            $this->addEventToTrace('onConditionalAND');

            $this->noOtherConditionWasMade = false;
        }
    }

    protected function callOnConditionalOREvent()
    {
        if (!$this->isfirstStart or $this->isRestart) {
            $this->onConditionalOR();
            $this->addEventToTrace('onConditionalOR');

            $this->noOtherConditionWasMade = false;
            
        }
    }

    public function callOrderByBasedOn(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        if ($dynamicField->isOrderByDescending()) {
            $this->onOrderByDescending($this->fieldCreator->createFrom([
                'fieldName' => $dynamicField->fieldName(),
                'fieldValue' => null
            ]));
            $this->addEventToTrace('onOrderByDescending');
        } elseif ($dynamicField->isOrderByAscending()) {
            $this->onOrderByAscending($this->fieldCreator->createFrom([
                'fieldName' => $dynamicField->fieldName(),
                'fieldValue' => null
            ]));
            $this->addEventToTrace('onOrderByAscending');
        }

    }

    protected function setWhetherPrimaryKeyHasBeenRequestOrNotBasedOn(QueryBuilderDynamicFieldResolver $dynamicField, $dynamicFieldIsStartOfMoreOrLessComparison)
    {
        (boolean) $requestedFieldIsPrimaryKey = $dynamicField->fieldName() === $this->primaryKey;
        (boolean) $isSecondaryKey = in_array($dynamicField->fieldName(), $this->secondaryKeys);

        if (($requestedFieldIsPrimaryKey or $isSecondaryKey) and !$dynamicFieldIsStartOfMoreOrLessComparison) {
            $this->primaryKeyFieldWasRequested = true;
        }
    }

    protected function throwExceptionIfMoreOrLessThanIsBeingSet()
    {
        if (!$this->moreOrLessThanIsNotBeignSet) {
            throw new MissingActionException("A more or less than field is being set");
        }
    }

    protected function throwExceptionIfMoreOrLessThanIsNotBeignSet()
    {
        if ($this->moreOrLessThanIsNotBeignSet) {
            throw new MissingActionException("A field with no arguments as method must be called prior a more or less than field");
        }
    }

    protected function throwExceptionIfStateIsNot($state)
    {
        (boolean) $requestedStateIsDifferentFromCurrentState = $this->state === $state ? false : true;
        
        if ($requestedStateIsDifferentFromCurrentState) {
            throw new MissingActionException("State Must be $state, current state: $this->state");
        }
    }

    protected function callOnMoreOrLessThanFieldEvent(array $eventData)
    {
        $this->throwExceptionIfMoreOrLessThanIsNotBeignSet();

        (object) $field = $this->fieldCreator->createFrom([
                    'fieldName' => $this->fieldName,
                    'fieldValue' => $eventData['value']
        ]);

        switch ($eventData['event']) {

            case 'onMoreThanField':

                $this->onMoreThanField($field);

                $this->addEventToTrace('onMoreThanField');

                break;
    
            case 'onLessThanField':

                $this->onLessThanField($field);

                $this->addEventToTrace('onLessThanField');

                break;
        }
        

        $this->moreOrLessThanIsNotBeignSet = true;

        return $this;
    }






















}