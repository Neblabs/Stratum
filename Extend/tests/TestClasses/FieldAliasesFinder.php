<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class FieldAliasesFinder extends MYSQL
{

    protected $fieldAliases = [
        'title' => 'post_title'
    ];


}