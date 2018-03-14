<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class CommentMeta extends Wordpress
{
    // Inflector wrongly pluralizes meta
    protected $alias= 'commentmeta';

    public $manyToOneRelationships = [
        'comments'
    ];

    protected $fieldAliases = [
        'id' => 'meta_id',  
        'commentId' => 'comment_id',
        'key' => 'meta_key',
        'value' => 'meta_value'
    ];

    protected $primaryKey = 'meta_id';

    protected $foreignKeys = [
        'comments' => 'comment_id',
    ];
}