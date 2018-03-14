<?php

namespace Stratum\Original\Presentation\Balance\Cache;

use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Custom\Finder\MYSQL\Settings;
use Stratum\Original\HTTP\Request\ApplicationController;

Class CacheManager
{
    protected $componentCache;
    protected $viewCache;

    protected static $cacheNeedsToBeCleared = false;

    public function __construct()
    {
        ignore_user_abort(true);
        set_time_limit(0);
        
        $this->componentCache = new ComponentCache;
        $this->viewCache = new ViewCache;
    }

    public static function clear()
    {
        (object) $cacheManager = new Static;

        $cacheManager->clearCache();
    }

    public function clearCache()
    {
        $this->viewCache->clearCacheMap();
        $this->componentCache->clearCacheMap();

        $this->addHomepageCacheQueue();

        $this->viewCache->clearCache();
        $this->componentCache->clearCache();
    }

    protected function addHomepageCacheQueue()
    {
        update_option('recacheEssentialPages', 'true');
    }

    public static function registerCacheCleaningEvents()
    {
        # Posts and Pages
        add_action('save_post', static::getRegisterCacheCleaningHandler(), 0);
        add_action('publish_post', static::getRegisterCacheCleaningHandler(), 0);
        add_action('edit_post', static::getRegisterCacheCleaningHandler(), 0);
        add_action('trashed_post', static::getRegisterCacheCleaningHandler(), 0);
        add_action('untrash_post', static::getRegisterCacheCleaningHandler(), 0);
        add_action('delete_post', static::getRegisterCacheCleaningHandler(), 0);

        # Categories, Tags and Menus
        add_action('create_term', static::getRegisterCacheCleaningHandler(), 0);
        add_action('created_term', static::getRegisterCacheCleaningHandler(), 0);
        add_action('edited_term', static::getRegisterCacheCleaningHandler(), 0);
        add_action('delete_term', static::getRegisterCacheCleaningHandler(), 0);

        # Widgets
        add_filter('pre_update_option_sidebars_widgets', static::getRegisterCacheCleaningHandler(), 0);

        # Users
        add_filter('profile_update', static::getRegisterCacheCleaningHandler(), 0);

        # Options
        add_filter('profile_update', static::getRegisterCacheCleaningHandler(), 0);

        add_action('admin_enqueue_scripts', function() {
            (string) $scriptsPath = get_template_directory_uri() . '/Prebuilt/Scripts';

            (object) $reCacheEssentialPages = get_option('recacheEssentialPages');

            if ($reCacheEssentialPages === 'true') {

                (string) $scriptSource = "{$scriptsPath}/ReCacher.js";
                wp_enqueue_script('stratum_recacher', $scriptSource);
                wp_localize_script('stratum_recacher', 'stratumSiteUrl', ['url' => get_option('siteurl')]);

                update_option('recacheEssentialPages', 'false');
            }

             (object) $idOfPostToRecache = Options::idOfPostToRecache();

             if ($idOfPostToRecache->value != '') {

                (string) $scriptSource = "{$scriptsPath}/PostReCacher.js";
                wp_enqueue_script('stratum_post_recacher', $scriptSource);
                wp_localize_script(
                    'stratum_post_recacher', 
                    'stratumPostUrl', 
                    ['url' => get_permalink((integer) $idOfPostToRecache->value)]);

                $idOfPostToRecache->value = '';
                $idOfPostToRecache->save();
             }
            
        }, 0);
    }

    public static function getRegisterCacheCleaningHandler()
    {
        return function ($valueForFilters = null) {

            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

            static::$cacheNeedsToBeCleared = true;

            if (isset($GLOBALS['post']) && ($GLOBALS['post']->post_status === 'publish')) {

                (object) $idOfPostToRecache = Options::idOfPostToRecache();
                $idOfPostToRecache->value = $GLOBALS['post']->ID;
                $idOfPostToRecache->save();
            }

            (object) $cacheCleaningMethod = static::cacheCleaningMethod();

            ApplicationController::addCloseHandler($cacheCleaningMethod);

            // Following one in case program execution is explicitly halted by client code.
            register_shutdown_function($cacheCleaningMethod);
            
            return $valueForFilters;
        };
    }

    protected static function cacheCleaningMethod()
    {
        return function() {
            if (static::$cacheNeedsToBeCleared) {

                Static::clear();

                static::$cacheNeedsToBeCleared = false;
            }
        };
    }







}