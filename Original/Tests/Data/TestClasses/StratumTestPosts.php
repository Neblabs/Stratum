<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class StratumTestPosts extends MYSQL
{
    protected $alias = 'test_table_posts';
    public $oneToManyRelationships = ['comments', 'meta', 'commentsFinderTest'];
    public $manyToOneRelationships = ['authors'];
}