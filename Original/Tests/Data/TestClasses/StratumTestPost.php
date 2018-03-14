<?php

namespace Stratum\Custom\Model\MYSQL;

use Stratum\Original\Data\Model;

Class StratumTestPost extends Model
{
    protected $alias = 'test_table_posts';
    public $oneToManyRelationships = ['comments', 'meta', 'commentsFinderTest'];
    public $manyToOneRelationships = ['authors'];
}