<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class AuthorsFinder extends MYSQL
{
    protected $alias = 'Authors';

    public $oneToManyRelationships = ['meta', 'commentsFinderTest'];
    public $manyToOneRelationships = ['authorsFinder'];
    protected $fieldAliases = [
        'name' => 'finder_name',
        'comments' => 'commentsFinderTest',
        'authors' => 'authorsFinder'
    ];
}