<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Creator\SegmentsValidatorCreator;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\HTTPRouteValidator;
use Stratum\Original\HTTP\Validator\RouteValidator;

Abstract Class RouteValidatorFactory
{
	abstract public function createFromRoute(Route $route);
}