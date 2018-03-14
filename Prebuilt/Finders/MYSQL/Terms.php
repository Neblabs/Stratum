<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class Terms extends Wordpress
{

    protected $fieldAliases = [
        'id' => 'term_id',  
        'termGroup' => 'term_group'
    ];

    protected $primaryKey = 'term_id';

}