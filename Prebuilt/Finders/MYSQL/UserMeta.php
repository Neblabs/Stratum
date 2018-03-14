<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class UserMeta extends Wordpress
{
    // Inflector wrongly pluralizes meta
    protected $alias= 'usermeta';

    public $manyToOneRelationships = [
        'users'
    ];

    protected $fieldAliases = [
        'id' => 'umeta_id',  
        'userId' => 'user_id',
        'key' => 'meta_key',
        'value' => 'meta_value'
    ];

    protected $primaryKey = 'umeta_id';

    protected $foreignKeys = [
        'users' => 'user_id',
    ];
}