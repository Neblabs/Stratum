<?php

namespace Stratum\Extend\Finder\MYSQL;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Extend\Finder\MYSQL\MYSQL;
use Stratum\Original\Data\EntityData;

Class Posts extends Wordpress
{
    protected function onManyToManyRelationshipStart(EntityData $entityData)
    {
        
        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entityData->entityType");

        $this->relatedFinder = $this->finderCreator->create();
        
        $this->relatedFinder->taxonomyManyToManyHasStarted = true;

        $this->columns = $this->columns === '*' ? "{$this->tablePrefix()}posts.*" : $this->columns; 

        $this->table = "{$this->tablePrefix()}posts "
                      ."JOIN {$this->tablePrefix()}term_relationships AS term_relationships ON {$this->tablePrefix()}posts.id = term_relationships.object_id "
                      ."JOIN {$this->tablePrefix()}term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                      ."JOIN {$this->tablePrefix()}terms AS terms ON taxonomy.term_id = terms.term_id ";

        $this->conditions.= "(taxonomy = '" . Inflector::singularize($this->relatedFinder->className()) .  "' ";

           
    }

    protected function onManyToManyRelationshipEnd()
    {
        $this->conditions.= "AND terms.{$this->relatedFinder->termIdentifier} = ?) ";

        $this->sqlParameters[] = $this->relatedFinder->termNameOrId;

    }  


}