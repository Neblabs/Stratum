<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Creator\SegmentsValidatorCreator;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\HTTPRouteValidator;
use Stratum\Original\HTTP\Validator\RouteValidator;

Class HTTPRouteValidatorFactory extends RouteValidatorFactory
{

    protected $request;
    protected $filtersRegitrator;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setFiltersRegistrator(FiltersRegistrator $filtersRegitrator)
    {
        $this->filtersRegitrator = $filtersRegitrator;
    }

    public function createFromRoute(Route $route)
    {
        (object) $routeValidator = new HTTPRouteValidator;

        $routeValidator->setRoute($route);
        $routeValidator->setRequest($this->request);
        $routeValidator->setSegmentsValidatorCreator(new SegmentsValidatorCreator);
        $routeValidator->setFiltersRegistrator($this->filtersRegitrator);

        return $routeValidator;
    }
}