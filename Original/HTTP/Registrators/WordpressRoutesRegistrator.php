<?php

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\WordpressRoute;

Class WordpressRoutesRegistrator extends RoutesRegistrator
{
    protected static $routes = [];
    protected $sitePage;
    protected $postType;

    public function registeredRoutes()
    {   
        $this->placeDefaultRouteAtTheEndIfExists();

        return parent::registeredRoutes();
    }
    
    public function setSitePage($sitePage)
    {
        $this->sitePage = $sitePage;
    }

    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    protected function registrationFilePath()
    {
        return STRATUM_ROOT_DIRECTORY . '/Design/Control/Routes/Wordpress/Routes.php';
    }


    protected function createRoute()
    {
        $this->route = new WordpressRoute;
    }

    protected function setRouteData()
    {
        $this->route->setSitePage($this->sitePage);
        $this->route->setPostType($this->postType);
    }

    protected function placeDefaultRouteAtTheEndIfExists()
    {
        foreach (static::$routes as $route) {

            if ($route->sitePage() === 'defaultview') {

                (integer) $routeKey = array_search($route, static::$routes);

                unset(static::$routes[$routeKey]);

                static::$routes[] = $route;
            }
        }
    }














}