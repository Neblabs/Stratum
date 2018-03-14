<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class PostMeta extends Wordpress
{
    // Inflector wrongly pluralizes meta
    protected $alias= 'postmeta';

    public $manyToOneRelationships = [
        'posts'
    ];

    protected $fieldAliases = [
        'id' => 'meta_id',  
        'postId' => 'post_id',
        'key' => 'meta_key',
        'name' => 'meta_key',
        'value' => 'meta_value'
    ];

    protected $primaryKey = 'meta_id';

    protected $foreignKeys = [
        'posts' => 'post_id',
    ];
}