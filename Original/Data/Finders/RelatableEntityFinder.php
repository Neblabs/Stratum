<?php

namespace Stratum\Original\Data\Finder;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Original\Data\Creator\EntityDataCreator;
use Stratum\Original\Data\Creator\FinderCreator;
use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Exception\UnsupportedRelatableEntityType;
use Stratum\Original\Data\QueryBuilderDynamicFieldResolver;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class RelatableEntityFinder extends SingleEntityFinder
{
    
    public $oneToManyRelationships = []; 
    public $manyToOneRelationships = [];
    public $manyToManyRelationships = [];

    protected $foreignKeys = [];

    protected $onOneToManyRelationShipHasNotEnded = false;
    protected $onManyToOneRelationShipHasNotEnded = false;
    protected $onManyToManyRelationShipHasNotEnded = false;
    protected $relatedFinder;
    protected $isInsideRelationship;
    protected $foreignKeyForRelatedFinder;
    protected $groupOperatorForRelatedFinder;
    protected $relatedFindersExisted = false;
    protected $eventTraceForCurrentRelatedFinderHasNotBeenAdded = true;

    abstract protected function onOneToManyRelationShipStart(EntityData $entityData);
    abstract protected function onManyToOneRelationShipStart(EntityData $entityData);
    abstract protected function onManyToManyRelationShipStart(EntityData $entityData);
    abstract protected function onOneToManyRelationShipEnd();
    abstract protected function onManyToOneRelationShipEnd();
    abstract protected function onManyToManyRelationShipEnd();

    public function __construct()
    {
        $this->entityDataCreator = new EntityDataCreator;
        $this->finderCreator = new FinderCreator;

        parent::__construct();

    }

    public function setEntityDataCreator(EntityDataCreator $entityDataCreator)
    {
        $this->entityDataCreator = $entityDataCreator;
    }

    public function setFinderCreator(FinderCreator $finderCreator)
    {
        $this->finderCreator = $finderCreator;
    }    

    protected function ___with($numberOfEntities = null)
    {
        $this->throwExceptionIfMoreOrLessThanIsBeingSet();

        if ($this->stateIs('Relationship')) {
            $this->delegateToTheRelatedFinder(['name' => $method, 'arguments' => $arguments]);
            return $this;
        }

        $this->callOnConditionalANDEvent();

        return $this->prepareAndChangeStateToRelationshipWith($numberOfEntities);
    }

    protected function in($numberOfEntities = null)
    {
        return $this->___with($numberOfEntities);
    }

    protected function by($numberOfEntities = null)
    {
        return $this->___with($numberOfEntities);
    }

    public function andWith($numberOfEntities = null)
    {
        return $this->___with($numberOfEntities);
    }

    public function andIn($numberOfEntities = null)
    {
        return $this->___with($numberOfEntities);
    }

    public function andBy($numberOfEntities = null)
    {
        return $this->___with($numberOfEntities);
    }

    public function orWith($numberOfEntities = null)
    {
        $this->throwExceptionIfMoreOrLessThanIsBeingSet();

        if ($this->stateIs('Relationship')) {
            $this->delegateToTheRelatedFinder(['name' => $method, 'arguments' => $arguments]);

            return $this;
        }

        $this->callOnConditionalOREvent();

        return $this->prepareAndChangeStateToRelationshipWith($numberOfEntities);
    }

    public function orIn($numberOfEntities = null)
    {
        return $this->orWith($numberOfEntities);
    }

    public function orBy($numberOfEntities = null)
    {
        return $this->orWith($numberOfEntities);
    }

    public function higherThan($number)
    {
        if ($this->stateIs('Relationship')) {
            $this->delegateToTheRelatedFinder(['name' => 'higherThan', 'arguments' => [$number]]);

            return $this;
        }

        return parent::higherThan($number);
    }

    public function lowerThan($number)
    {
        if ($this->stateIs('Relationship')) {
            $this->delegateToTheRelatedFinder(['name' => 'lowerThan', 'arguments' => [$number]]);

            return $this;
        }

        return parent::lowerThan($number);
    }


    public function find()
    {
        $this->callOnRelationshipEndIfHasntEnded();

        $this->callOnRelationshipEndIfHasntEnded();

        $this->addEventTracesFromRelatedFindersIfAny();

        return parent::find();
    }

    public function addEventTracesFromRelatedFindersIfAny()
    {

        if ($this->relatedFindersExisted and $this->eventTraceForCurrentRelatedFinderHasNotBeenAdded) {

            $this->secondaryEventTraces[] = $this->relatedFinder->eventTraces();

            $this->eventTraceForCurrentRelatedFinderHasNotBeenAdded = false;

        }
    }

    public function eventTraces()
    {
        $this->callOnStartBuilderEventIfBuilderHasJustStarted();
        $this->callOnRelationshipEndIfHasntEnded();

        $this->finishBuilder();

        return parent::eventTraces();
    }



    abstract protected function relatedFinder();

    protected function prepareAndChangeStateToRelationshipWith($numberOfEntities)
    {
        $this->throwExceptionIfStateIsNot('DirectEntity');

        $this->numberOfEntities = $numberOfEntities;

        $this->setCurrentStateTo('SetRelationship');

        return $this;
    }

    public function __call($method, $arguments)
    { 
        (object) $dynamicField = new QueryBuilderDynamicFieldResolver($method);

        $dynamicField->setFieldAliases($this->fieldAliases);

        if ($this->stateIs('Relationship') and !$this->isExplicitRelationshipEnd($dynamicField)) {
            $this->delegateToTheRelatedFinder(['name' => $method, 'arguments' => $arguments]);
            return $this;
        }

        $this->callOnStartBuilderEventIfBuilderHasJustStarted();

        $this->onBuilderStartHasNotBeenCalled = false;

        if ($method === 'with' or $method === 'in' or $method === 'by') {
             call_user_func_array([$this, '___with'], $arguments);

             return $this;
        }

        if ($method === 'first') {
            $this->callOnStartBuilderEventIfBuilderHasJustStarted();
            return $this->first($arguments[0]);
        } 

        if ($dynamicField->isFieldNameOnly() or $dynamicField->isOrMoreMethod() or $dynamicField->isOrLessMethod()) {

            $this->callOnOneToManyOrManyToManyRelationShipStartWithDataFrom($dynamicField);

            $this->setCurrentStateTo('Relationship');

            $this->relatedFinder = $this->relatedFinder();

            $this->relatedFindersExisted = true;
            $this->eventTraceForCurrentRelatedFinderHasNotBeenAdded = true;

            return $this;
            
        }

        if ($this->dynamicFieldIsAManyToOneEntityType($dynamicField)) {

                if ($dynamicField->isANDCondition()) $this->callOnConditionalANDEvent();
                if ($dynamicField->isORCondition()) $this->callOnConditionalOREvent();

                $this->callOnManyToOneRelationShipStartWithDataFrom($dynamicField);

                $this->setCurrentStateTo('Relationship');

                $this->relatedFinder = $this->relatedFinder();

                $this->relatedFindersExisted = true;
                 $this->eventTraceForCurrentRelatedFinderHasNotBeenAdded = true;

                return $this;
        }

        if ($this->isExplicitRelationshipEnd($dynamicField)) {

            

               $this->callOnRelationshipEndIfHasntEnded();

               $this->addEventTracesFromRelatedFindersIfAny();

                $this->setCurrentStateTo('DirectEntity');

                $this->isRestart = true;
    
                if ($dynamicField->methodStartsOnlyWithAnd()) $this->callOnConditionalANDEvent();
                if ($dynamicField->methodStartsOnlyWithOr()) $this->callOnConditionalOrEvent();
    
                $this->isRestart = false;
                $this->isfirstStart = true;
                
                
                return $this; 
            
            
        }

        return parent::__call($method, $arguments);
    }

    protected function isExplicitRelationshipEnd($dynamicField)
    {
        return ($dynamicField->methodStartsOnlyWithAnd() or $dynamicField->methodStartsOnlyWithOr()) and $this->dynamicFieldIsTheSameAsTheCurrentEntityType($dynamicField);
    }

    protected function delegateToTheRelatedFinder(array $method)
    {
        return call_user_func_array([$this->relatedFinder, $method['name']], $method['arguments']);
    }

    protected function callOnOneToManyOrManyToManyRelationShipStartWithDataFrom($dynamicField)
    {
        $this->throwExceptionIfStateIsNot('SetRelationship');
        $this->throwExceptionIfEntityTypeIsNotSupportedByTheFinder($dynamicField);

        (object) $entityData = $this->entityDataCreator->createFrom([
            'entityType' => $dynamicField->fieldName(),
            'numberOfEntities' => $this->numberOfEntities,
            'isMoreThan' => $dynamicField->isOrMoreMethod(),
            'isLessThan' => $dynamicField->isOrLessMethod()
        ]);

        if ($this->isOneToManyRelationship($dynamicField)) {

            $this->onOneToManyRelationShipStart($entityData);

            $this->addEventToTrace('onOneToManyRelationShipStart');

            $this->setCurrentStateTo('Relationship');

            $this->onOneToManyRelationShipHasNotEnded = true;
        } 
        if ($this->isManyToManyRelationship($dynamicField)) {

            $this->onManyToManyRelationShipStart($entityData);

            $this->addEventToTrace('onManyToManyRelationShipStart');
            
            $this->setCurrentStateTo('Relationship');

            $this->onManyToManyRelationShipHasNotEnded = true;
        }
    }

    protected function callOnRelationshipEndIfHasntEnded()
    {
        if ($this->onOneToManyRelationShipHasNotEnded) {

            $this->onOneToManyRelationShipEnd();

            $this->addEventToTrace('onOneToManyRelationShipEnd');

            $this->setCurrentStateTo('DirectEntity');

            $this->onOneToManyRelationShipHasNotEnded = false;

        } elseif ($this->onManyToOneRelationShipHasNotEnded) {

            $this->onManyToOneRelationShipEnd();

            $this->addEventToTrace('onManyToOneRelationShipEnd');

            $this->setCurrentStateTo('DirectEntity');

            $this->onManyToOneRelationShipHasNotEnded = false;

        } elseif ($this->onManyToManyRelationShipHasNotEnded) {

            $this->onManyToManyRelationShipEnd();

            $this->addEventToTrace('onManyToManyRelationShipEnd');

            $this->setCurrentStateTo('DirectEntity');

            $this->onManyToManyRelationShipHasNotEnded = false;

        }


    }

    protected function throwExceptionIfEntityTypeIsNotSupportedByTheFinder(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        (boolean) $oneToManyEntityTypeDoesIsNotSupportedByTheFinder = 
                        array_search($dynamicField->fieldName(), $this->oneToManyRelationships) === false;

        (boolean) $manyToManyentityTypeDoesIsNotSupportedByTheFinder = 
                        array_search($dynamicField->fieldName(), $this->manyToManyRelationships) === false;

        if ($oneToManyEntityTypeDoesIsNotSupportedByTheFinder and $manyToManyentityTypeDoesIsNotSupportedByTheFinder) {
            throw new UnsupportedRelatableEntityType(
                "Entity Type: {$dynamicField->fieldName()} cannot be related to {$this->className()}"
            );
        }

    }

    protected function isManyToManyRelationship($dynamicField)
    {
        (boolean) $manyToManyentityTypeDoesIsNotSupportedByTheFinder = 
                        array_search($dynamicField->fieldName(), $this->manyToManyRelationships) === false;

        return !$manyToManyentityTypeDoesIsNotSupportedByTheFinder;
    }

    protected function isOneToManyRelationship($dynamicField)
    {
        (boolean) $oneToManyEntityTypeDoesIsNotSupportedByTheFinder = 
                        array_search($dynamicField->fieldName(), $this->oneToManyRelationships) === false;

        return !$oneToManyEntityTypeDoesIsNotSupportedByTheFinder;
    }

    protected function dynamicFieldIsTheSameAsTheCurrentEntityType(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        (boolean) $isDynamicFieldNameTheSameAsTheCurrentClassName = 
                                        $dynamicField->fieldName() === lcfirst($this->singleClassName());

        return $isDynamicFieldNameTheSameAsTheCurrentClassName;
    }

    protected function dynamicFieldIsAManyToOneEntityType(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        (boolean) $FieldNameIsAManyToOneEntity = 
                            array_search($dynamicField->fieldName(), $this->manyToOneRelationships) !== false;

        return $dynamicField->isSetterForSameEntity() and $FieldNameIsAManyToOneEntity;
    }

    protected function callOnManyToOneRelationShipStartWithDataFrom(QueryBuilderDynamicFieldResolver $dynamicField)
    {
        (object) $entityData = $this->entityDataCreator->createFrom([
            'entityType' => $dynamicField->fieldName(),
            'numberOfEntities' => null,
            'isMoreThan' => null,
            'isLessThan' => null
        ]);

        $this->onManyToOneRelationShipStart($entityData);

        $this->addEventToTrace('onManyToOneRelationShipStart');

        $this->onManyToOneRelationShipHasNotEnded = true;
    }

    protected function generateGroupByOperatorBasedOn(EntityData $entityData)
    {
        (string) $operator = '';

        if ($entityData->isMoreThan) {
            $operator = '>=';
        } elseif ($entityData->isLessThan) {
            $operator = '<=';
        } else {
            $operator = '=';
        }

        return $operator;
    }

    public function foreignKeyFor($entityType)
    { 
        $entityType = lcfirst($entityType);

        (boolean) $foreignKeyForRelatedFinderExist = !empty($this->foreignKeys[$entityType]); 
        if ($foreignKeyForRelatedFinderExist) {
            return $this->foreignKeys[$entityType];
        }
        return  Inflector::singularize($entityType)."_id";
    }










}