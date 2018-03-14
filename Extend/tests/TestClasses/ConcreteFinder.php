<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class ConcreteFinder extends MYSQL
{
    public $oneToManyRelationships = ['meta', 'commentsFinderTest'];
    public $manyToOneRelationships = ['authorsFinder'];
    protected $fieldAliases = [
        'name' => 'finder_name',
        'comments' => 'commentsFinderTest',
        'authors' => 'authorsFinder'
    ];

    public $foreignKeys = [
        'authorsFinder' => 'author_id'
    ];
}