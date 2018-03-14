<?php

namespace Stratum\Extend\Finder\WordPress;

use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder\RelatableEntityFinder;

Class Posts extends RelatableEntityFinder
{
    public $oneToManyRelationships = [];

    public $manyToOneRelationships = [];

    public $manyToManyRelationships = [];

    protected function onBuilderStart()
    {

    }

    protected function onBuilderEnd()
    {
        
    }

    protected function onQuery()
    {
        
    }

    protected function onConditionalAND()
    {
 
    }

    protected function onConditionalOR()
    {
 
    }

    protected function onEqualityField(Field $field)
    {
        
    }

    protected function onMoreThanField(Field $field)
    {
        
    }

    protected function onLessThanField(Field $field)
    {
        
    }

    protected function onManyToOneRelationshipStart(EntityData $entity)
    {
        
    }

    protected function onOneToManyRelationshipStart(EntityData $entity)
    {
        
    }

    protected function onManyToManyRelationshipStart(EntityData $entity)
    {
        
    }

    protected function onManyToOneRelationshipEnd()
    {
        
    }

    protected function onOneToManyRelationshipEnd()
    {
        
    }

    protected function onManyToManyRelationshipEnd()
    {
        
    }

    protected function relatedFinder()
    {
        return $this->relatedFinder;
    }

}