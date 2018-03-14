<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Creator\RouteValidatorsCreator;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\RouteValidator;

Class RouteValidatorsCreatorTest extends TestCase 
{
	public function test_creates_array_of_RouteValidator_objects_from_a_request_object_and_an_array_of_route_objects()
	{
		$routeValidatorsCreator = new routeValidatorsCreator;

		$routeOne = $this->createMock(HTTPRoute::class);
		$routeTwo = $this->createMock(HTTPRoute::class);
		$routeThree = $this->createMock(HTTPRoute::class);

		$routes = [$routeOne, $routeTwo, $routeThree];

		$request = $this->createMock(Request::class);

		$routeValidatorsCreator->setRoutes($routes);
		$routeValidatorsCreator->setRequest($request);

		$routeValidators = $routeValidatorsCreator->create();

		$this->assertCount(3, $routeValidators);

		foreach ($routeValidators as $index => $routeValidator) {
			$this->assertSame($routes[$index], $routeValidator->route());
		}
	}

	public function test_throws_exception_if_no_Routes_nor_Request_have_been_set_before_calling_create()
	{
		$this->expectException(MissingRequiredPropertyException::class);

		$routeValidatorsCreator = new RouteValidatorsCreator;

		$routeValidatorsCreator->create();
	}
}