<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class AliasedFinder extends MYSQL
{
    protected $alias = 'comments';
    public $oneToManyRelationships = ['Meta'];
    public $manyToOneRelationships = ['Authors', 'Posts'];

    public $foreignKeys = [
        'post' => 'comment_post_id'
    ];
}