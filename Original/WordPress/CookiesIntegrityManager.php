<?php 

namespace Stratum\Original\WordPress;

/*
    Some plugins (especially caching ones) modify the cookies array, thus creating problems when using auth apis.
*/
Class CookiesIntegrityManager
{
    protected static $originalCookies = [];

    public static function storeOriginalCookies()
    {
        Static::$originalCookies = $_COOKIE;
    }

    public function restoreOriginalCookies()
    {   
        if ($this->thereisNoCacheCopy()) {
            return;
        }

        (integer) $highPriority = 1;
        add_filter('secure_signon_cookie', [$this, 'swapModifiedCacheArrayWithOrginalOne'], $highPriority); 
        add_filter('determine_current_user', [$this, 'swapModifiedCacheArrayWithOrginalOne'], $highPriority);
        add_action('after_setup_theme', [$this, 'swapModifiedCacheArrayWithOrginalOne'], $highPriority);
    }

    public function swapModifiedCacheArrayWithOrginalOne()
    {
        global $current_user;

        $current_user = null;
        
        $_COOKIE = static::$originalCookies; 

    }

    protected function thereisNoCacheCopy()
    {
        return empty(Static::$originalCookies);
    }
}