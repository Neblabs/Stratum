<?php

namespace Stratum\Original\HTTP\Wordpress;

use Stratum\Original\HTTP\Router;

Class WordpressRouterHandler
{
    protected static $router;
    protected static $route;

    public function setRouter(Router $router)
    {
        static::$router = $router;
    }

    public function chooseRouteForCurrentRequest()
    {
        add_action('template_redirect', [$this, 'prepareRoute']);
    }

    public function prepareRoute()
    {
        static::$route = static::$router->chooseCorrectRouteForCurrentRequest();
    }

    public function route()
    {
        return static::$route;
    }
}