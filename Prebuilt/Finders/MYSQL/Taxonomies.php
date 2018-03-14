<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class Taxonomies extends Wordpress
{
    protected $alias= 'term_taxonomy';

    protected $fieldAliases = [
        'id' => 'term_taxonomy_id',  
        'termId' => 'term_id',
        'name' => 'taxonomy'
    ];

    protected $primaryKey = 'term_taxonomy_id';

}