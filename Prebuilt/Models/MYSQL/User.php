<?php

namespace Stratum\Prebuilt\Model\MYSQL;

use Stratum\Original\Data\Model;

Class User extends Model
{
    
    protected static $fieldAliases = [
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

}