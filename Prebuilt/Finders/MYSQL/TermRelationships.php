<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class TermRelationships extends Wordpress
{
    protected $alias= 'term_relationships';

    protected $fieldAliases = [
        'id' => 'object_id',  
        'taxonomyId' => 'term_taxonomy_id',
        'order' => 'term_order'
    ];

    protected $primaryKey = 'object_id';

}