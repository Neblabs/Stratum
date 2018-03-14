<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Creator\SegmentsValidatorCreator;
use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator\HTTPRouteValidator;
use Stratum\Original\HTTP\Validator\RouteValidator;
use Stratum\Original\HTTP\Validator\ValidatorsValidator;
use Symfony\Component\HttpFoundation\Request;

Class RouteValidatorTest extends TestCase
{
	public function setUp()
	{
		$this->routeValidator = new HTTPRouteValidator;
		$this->route = $this->createMock(HTTPRoute::class);
		$this->request = new GETRequest(Request::create('users/5534', 'GET'));
		$this->filtersRegistrator = $this->createMock(FiltersRegistrator::class);


		$this->routeValidator->setRequest($this->request);
		$this->routeValidator->setFiltersRegistrator($this->filtersRegistrator);
	}

	public function test_passes_when_http_method_from_route_matches_the_method_for_the_request_and_SegmentsValidator_has_passed()
	{
		(object) $route = $this->createMock(HTTPRoute::class);

		$route->expects($this->once())
				->method('method')
				->willReturn('GET');

		$this->routeValidator->setRoute($route);

		(object) $segmentsValidator = $this->createMock(ValidatorsValidator::class);

		$segmentsValidator->expects($this->once())
						->method('validate');

		$segmentsValidator->expects($this->once())
						->method('hasPassed')
						->willReturn(true);

		(object) $segmentsValidatorCreator = $this->createMock(SegmentsValidatorCreator::class);
		
		$segmentsValidatorCreator->expects($this->once())
								->method('create')
								->willReturn($segmentsValidator);

		
		$this->routeValidator->setSegmentsValidatorCreator($segmentsValidatorCreator);

		$this->routeValidator->validate();

		$this->assertTrue($this->routeValidator->hasPassed());
	}

	public function test_fails_when_http_method_matches_but_SegmentsValidator_has_failed()
	{
		(object) $route = $this->createMock(HTTPRoute::class);

		$route->expects($this->once())
				->method('method')
				->willReturn('GET');

		$this->routeValidator->setRoute($route);

		(object) $segmentsValidator = $this->createMock(ValidatorsValidator::class);

		$segmentsValidator->expects($this->once())
						->method('validate');

		$segmentsValidator->expects($this->once())
						->method('hasPassed')
						->willReturn(false);

		(object) $segmentsValidatorCreator = $this->createMock(SegmentsValidatorCreator::class);
		
		$segmentsValidatorCreator->expects($this->once())
								->method('create')
								->willReturn($segmentsValidator);

		
		$this->routeValidator->setSegmentsValidatorCreator($segmentsValidatorCreator);

		$this->routeValidator->validate();

		$this->assertFalse($this->routeValidator->hasPassed());
	}

	public function test_fails_when_http_method_from_request_does_not_match_method_from_route()
	{
		(object) $route = $this->createMock(HTTPRoute::class);

		$route->expects($this->once())
				->method('method')
				->willReturn('POST');

		$this->routeValidator->setRoute($route);

		(object) $segmentsValidator = $this->createMock(ValidatorsValidator::class);

		$segmentsValidator->expects($this->never())
						->method('validate');

		$segmentsValidator->expects($this->never())
						->method('hasPassed')
						->willReturn(false);

		(object) $segmentsValidatorCreator = $this->createMock(SegmentsValidatorCreator::class);
		
		$segmentsValidatorCreator->expects($this->once())
								->method('create')
								->willReturn($segmentsValidator);

		
		$this->routeValidator->setSegmentsValidatorCreator($segmentsValidatorCreator);

		$this->routeValidator->validate();

		$this->assertFalse($this->routeValidator->hasPassed());
	}

















}