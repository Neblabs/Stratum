<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Creator\HTTPRouteValidatorFactory;
use Stratum\Original\HTTP\Creator\RouteValidatorFactory;
use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Router;
use Stratum\Original\HTTP\Validator\RouteValidator;
use Stratum\original\HTTP\Registrator\FiltersRegistrator;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

Class RouterTest extends TestCase
{
    public function test_chooses_the_correct_route_object_based_on_the_request_which_should_be_the_route_from_the_first_RouteValidator_that_passes()
    {
        (object) $request = $this->createMock(Request::class);
        (object) $routesRegistrator = $this->createMock(RoutesRegistrator::class);
        (object) $routeValidatorFactory = $this->createMock(RouteValidatorFactory::class);

        (object) $firstRoute = $this->createMock(HTTPRoute::class);
        (object) $secondRoute = $this->createMock(HTTPRoute::class);
        (object) $thirdRoute = $this->createMock(HTTPRoute::class);
        (object) $fourthRoute = $this->createMock(HTTPRoute::class);

        (object) $firstRouteValidator = $this->createMock(RouteValidator::class);
        (object) $secondRouteValidator = $this->createMock(RouteValidator::class);
        (object) $thirdRouteValidator = $this->createMock(RouteValidator::class);
        (object) $fourthRouteValidator = $this->createMock(RouteValidator::class);

        $firstRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);

        $secondRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);

        $thirdRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(true);

        $fourthRouteValidator->expects($this->never())
                            ->method('hasPassed');

        $thirdRouteValidator->expects($this->once())
                            ->method('route')
                            ->willReturn($thirdRoute);

        $routesRegistrator->expects($this->once())
                        ->method('registeredRoutes')
                        ->willReturn([
                            $firstRoute,
                            $secondRoute,
                            $thirdRoute,
                            $fourthRoute
                        ]);
        $routeValidatorFactory->expects($this->any())
                            ->method('createFromRoute')
                            ->will($this->onConsecutiveCalls(
                                    $firstRouteValidator,
                                    $secondRouteValidator,
                                    $thirdRouteValidator,
                                    $fourthRouteValidator
                                ));

        (object) $router = new Router;

        $router->setRouteValidatorFactory($routeValidatorFactory);
        $router->setRoutesRegistrator($routesRegistrator);


        (object) $correctRoute = $router->chooseCorrectRouteForCurrentRequest();


        $this->assertTrue($router->foundCorrectRouteForRequest());
        $this->assertSame($thirdRoute, $correctRoute);

    }

    public function test_chooses_correct_route_with_the_real_participants_no_mocks()
    {
        (object) $request = new GETRequest(SymfonyRequest::create('users/5572', 'GET'));
        (object) $routesRegistrator = $this->createMock(RoutesRegistrator::class);
        (object) $routeValidatorFactory = new HTTPRouteValidatorFactory;

        $routeValidatorFactory->setFiltersRegistrator(new FiltersRegistrator);
        $routeValidatorFactory->setRequest($request);

        (object) $route1 = new HTTPRoute;
        (object) $route2 = new HTTPRoute;
        (object) $route3 = new HTTPRoute;
        (object) $route4 = new HTTPRoute;

        $route1->setPathDefinition('users/(id)/new');
        $route1->setMethod('GET');

        $route2->setPathDefinition('users/(id)/remove');
        $route2->setMethod('GET');

        $route3->setPathDefinition('users/(id | integer | lenght: 4)');
        $route3->setMethod('GET');

        $route4->setPathDefinition('posts/(title)/');
        $route4->setMethod('GET');

        $routesRegistrator->method('registeredRoutes')
                        ->willReturn([
                            $route1,
                            $route2,
                            $route3,
                            $route4
                        ]);

        (object) $router = new Router;


        $router->setRouteValidatorFactory($routeValidatorFactory);
        $router->setRoutesRegistrator($routesRegistrator);


        (object) $correctRoute = $router->chooseCorrectRouteForCurrentRequest();


        $this->assertTrue($router->foundCorrectRouteForRequest());
        $this->assertSame($route3, $correctRoute);
    }

    public function test_returns_nothing_if_no_routeValidator_passed()
    {
        (object) $request = $this->createMock(Request::class);
        (object) $routesRegistrator = $this->createMock(RoutesRegistrator::class);
        (object) $routeValidatorFactory = $this->createMock(RouteValidatorFactory::class);

        (object) $firstRoute = $this->createMock(HTTPRoute::class);
        (object) $secondRoute = $this->createMock(HTTPRoute::class);
        (object) $thirdRoute = $this->createMock(HTTPRoute::class);
        (object) $fourthRoute = $this->createMock(HTTPRoute::class);

        (object) $firstRouteValidator = $this->createMock(RouteValidator::class);
        (object) $secondRouteValidator = $this->createMock(RouteValidator::class);
        (object) $thirdRouteValidator = $this->createMock(RouteValidator::class);
        (object) $fourthRouteValidator = $this->createMock(RouteValidator::class);

        $firstRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);

        $secondRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);

        $thirdRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);

        $fourthRouteValidator->expects($this->once())
                            ->method('hasPassed')
                            ->willReturn(false);


        $routesRegistrator->expects($this->once())
                        ->method('registeredRoutes')
                        ->willReturn([
                            $firstRoute,
                            $secondRoute,
                            $thirdRoute,
                            $fourthRoute
                        ]);
        $routeValidatorFactory->expects($this->any())
                            ->method('createFromRoute')
                            ->will($this->onConsecutiveCalls(
                                    $firstRouteValidator,
                                    $secondRouteValidator,
                                    $thirdRouteValidator,
                                    $fourthRouteValidator
                                ));

        (object) $router = new Router;

        $router->setRouteValidatorFactory($routeValidatorFactory);
        $router->setRoutesRegistrator($routesRegistrator);


        $routeValidator = $router->chooseCorrectRouteForCurrentRequest();


        $this->assertFalse($router->foundCorrectRouteForRequest());
        $this->assertNull($routeValidator);

    }




















}