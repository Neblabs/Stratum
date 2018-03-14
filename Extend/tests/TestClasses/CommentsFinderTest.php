<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class CommentsFinderTest extends MYSQL
{
    protected $alias = 'Comments';
    
    public $oneToManyRelationships = ['meta'];
    public $manyToOneRelationships = ['posts'];

    protected $primaryKey = ['comment_id'];
    protected $foreignKeys = [
        'concreteFinder' => 'concreteFinder_id'
    ];
}