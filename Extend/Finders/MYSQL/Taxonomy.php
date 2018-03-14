<?php

namespace Stratum\Extend\Finder\MYSQL;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Extend\Finder\MYSQL\MYSQL;
use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Field;

Class Taxonomy extends Wordpress
{

    protected function onBuilderStart()
    {

        $this->columns = 'DISTINCT terms.*'; 

        $this->table = "{$this->tablePrefix()}terms AS terms "
                      ."JOIN {$this->tablePrefix()}term_taxonomy AS taxonomy ON taxonomy.term_id = terms.term_id "
                      ;
                      
    }

    protected function onManyToManyRelationshipStart(EntityData $entityData)
    {
    
        $this->taxonomyManyToManyHasStarted = true;

        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entityData->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->table .= "JOIN {$this->tablePrefix()}term_relationships AS term_relationships ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id 
                            JOIN {$this->tablePrefix()}posts AS posts ON posts.id = term_relationships.object_id ";

        $this->conditions.= "taxonomy.taxonomy = '" . Inflector::singularize($this->className()) .  "' AND posts.id = ? ";
           
    }

    protected function onManyToManyRelationshipEnd()
    {
        $this->addSqlParametersFromRelatedEntity();
    }  


}