<?php

namespace Stratum\Custom\Model\MYSQL;

use Stratum\Original\Data\Model;

Class StratumModelWithFakeDomain extends Model
{
    protected $alias = 'test_table_posts';
    public $oneToManyRelationships = ['comments', 'meta', 'commentsFinderTest'];
    public $manyToOneRelationships = ['authors'];
}