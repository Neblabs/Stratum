<?php 

namespace Stratum\Original\WordPress;

use Stratum\Original\HTTP\Wordpress\TemplateIncluder;
use Stratum\Original\HTTP\Wordpress\WordpressRouterHandler;
use Stratum\Original\Installer\InstallationManager;
use Stratum\Original\Installer\SetupManager;
use Stratum\Original\Presentation\Balance\Cache\CacheManager;
use Stratum\Original\WordPress\CookiesIntegrityManager;

Class StratumWordpressCompatibiltyManager
{
    public function manageCompatibilty()
    {
        $InstallationManager = new InstallationManager;

        $InstallationManager->reRunInstallationIfIsNotComplete();

        add_action('after_switch_theme', function() use ($InstallationManager) {
            
            $InstallationManager->performInstallation();
        });
        
        (object) $WordpressRouterHandler = new WordpressRouterHandler;
        
        $WordpressRouterHandler->chooseRouteForCurrentRequest();
        
        (object) $templateIncluder = new TemplateIncluder;
        
        $templateIncluder->setIncludePath();
        
        (object) $cookiesIntegrityManager = new CookiesIntegrityManager;
        
        $cookiesIntegrityManager->restoreOriginalCookies();
        
        add_action('switch_theme', function ($newName) {
            (object) $SetupManager = new SetupManager;
            $SetupManager->unInstall();
        });

        add_filter('widget_title', function($title){
            return '';
        });

        add_filter('the_content', function($content){
            return preg_replace('/<p>([\n\r\s]*(&nbsp;)[\n\r\s]*)+<\/p>/', '', $content);
        });
        
        
        CacheManager::registerCacheCleaningEvents();
    }
}