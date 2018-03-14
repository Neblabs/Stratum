<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL\Wordpress;

Class Users extends Wordpress
{

    public $oneToManyRelationships = [
        'posts', 'userMeta', 'comments'
    ];

    protected $fieldAliases = [
        'id' => 'ID',
        'name' => 'user_login',
        'password' => 'user_pass',
        'nickName' => 'user_nicename',
        'email' => 'user_email',
        'website' => 'user_url',
        'registrationDate' => 'user_registered',
        'activationKey' => 'user_activation_key',
        'status' => 'user_status',
        'publicName' => 'display_name',
        'meta' => 'userMeta'
    ];  

    protected $primaryKey = 'ID';

}