<?php

namespace Stratum\Original\Data;

use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Finder
{
    use ClassName;
    
    protected $state = 'DirectEntity';
    protected $eventTrace = [];
    protected $secondaryEventTraces = [];
    protected $builderHasNotEnded = true;
    protected $alias;

    abstract protected function onBuilderStart();

    abstract protected function onBuilderEnd();

    abstract protected function onQuery();

    public function find()
    {
        $this->finishBuilder();

        $this->addEventToTrace('onQuery');

        return $this->onQuery();
    }

    public function stateIs($state)
    { 
        return $this->state === $state;
    }

    protected function finishBuilder()
    {
        if ($this->builderHasNotEnded) {

            $this->onBuilderEnd();

            $this->addEventToTrace('onBuilderEnd');

            $this->builderHasNotEnded = false;
        }
        
    }

    protected function setCurrentStateTo($state)
    {
        $this->state = $state;
    }

    public function className()
    {
        if ($this->classAliasExist()) {
            return $this->classAlias();
        }

        return $this->singleClassName();
    }

    protected function classAliasExist()
    {
        (boolean) $doesTheAliasPropertyHaveAValue = !empty($this->alias);

        return $doesTheAliasPropertyHaveAValue;
    }

    public function classAlias()
    {
        return $this->alias;
    }

    public function addEventToTrace($event)
    {
        $this->eventTrace[] = $event;
    }

    public function eventTraces()
    {
        (array) $eventTraces[] = $this->eventTrace;

        foreach ($this->secondaryEventTraces as $eventTrace) {
            foreach ($eventTrace as $secondaryEventTraces) {
                
                $eventTraces[] = $secondaryEventTraces;
            }
        }

        return $eventTraces;
    }
















}