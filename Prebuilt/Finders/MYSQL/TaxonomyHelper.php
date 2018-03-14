<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL;
use Stratum\Original\Data\Field;

Abstract Class TaxonomyHelper extends MYSQL\Taxonomy
{
    public $taxonomyManyToManyHasStarted;

    public $manyToManyRelationships = [
        'posts'
    ];
    
    protected $fieldAliases = [
        'id' => 'term_id'   
    ];

    protected function onEqualityField(Field $field)
    {
        if ($this->taxonomyManyToManyHasStarted) {
            $this->termNameOrId = $field->value;
            $this->termIdentifier = $field->name;
        } else {
            $field->name = 'terms.' . $field->name;
            parent::onEqualityField($field);
        }

    }

    protected function onMoreThanField(Field $field)
    {
        $field->name = 'terms.' . $field->name;
            parent::onMoreThanField($field);
    }

    public function onBuilderEnd()
    {
        $this->conditions = "({$this->conditions})";
        $this->conditions.= "AND (taxonomy.taxonomy = 'category' OR taxonomy.taxonomy = 'post_tag')";

    }
}