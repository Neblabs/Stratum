<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\WordpressRouteValidator;

Class WordpressRouteValidatorFactory extends RouteValidatorFactory
{
    public function createFromRoute(Route $route)
    {
        (object) $routeValidator = new WordpressRouteValidator;

        $routeValidator->setRoute($route);

        return $routeValidator;
    }
}