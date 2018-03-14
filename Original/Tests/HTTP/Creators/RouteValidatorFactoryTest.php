<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Creator\HTTPRouteValidatorFactory;
use Stratum\Original\HTTP\Creator\RouteValidatorFactory;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\RouteValidator;

Class RouteValidatorFactoryTest extends TestCase
{
	public function test_returns_a_RouteValidator_object_with_all_dependencies_set_up()
	{
		(object) $request = $this->createMock(Request::class);
		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);
		(object) $route = $this->createMock(HTTPRoute::class);

		(object) $routeValidatorFactory = new HTTPRouteValidatorFactory;

		$routeValidatorFactory->setRequest($request);
		$routeValidatorFactory->setFiltersRegistrator($filtersRegistrator);

		(object) $routeValidator = $routeValidatorFactory->createFromRoute($route);

		$this->assertInstanceOf(RouteValidator::class, $routeValidator);

	}
}