<?php

namespace Stratum\Custom\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\MYSQL;

Class CustomPrimaryKey extends MYSQL
{
    public $oneToManyRelationships = ['Meta'];
    public $manyToOneRelationships = ['Authors', 'Posts'];

    protected $primaryKey = 'comment_id';

    public $foreignKeys = [
        'post' => 'comment_post_id'
    ];

    protected $fieldAliases = [
        'id' => 'comment_id'
    ];
}