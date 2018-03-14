<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Custom\Model\MYSQL\Option;
use Stratum\Extend\Finder\MYSQL\Wordpress;

Class Options extends Wordpress
{

    protected $fieldAliases = [
        'id' => 'option_id',  
        'name' => 'option_name',
        'value' => 'option_value'
    ];

    protected $primaryKey = 'option_id';

    public static function idOfPostToRecache()
    {
        (object) $setting = Options::withName('idOfPostToRecache')->find();

        if (!$setting->wereFound()) {
            $setting = (new Option)->withName('idOfPostToRecache')
                                   ->withAutoload('no');
        } else {
            $setting = $setting->first();
        }
        return $setting;
    }

}








