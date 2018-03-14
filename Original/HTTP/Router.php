<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Creator\RouteValidatorFactory;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;

Class Router
{
	protected $request;
	protected $routeValidatorFactory;
	protected $routesRegistrator;
    protected $routeValidators = [];
    protected $hasItFoundTheCorrectRouteForTheRequest;

	public function setRouteValidatorFactory(RouteValidatorFactory $routeValidatorFactory)
	{
		$this->routeValidatorFactory = $routeValidatorFactory;
	}

	public function setRoutesRegistrator(RoutesRegistrator $routesRegistrator)
    {
        $this->routesRegistrator = $routesRegistrator;
    }

    public function chooseCorrectRouteForCurrentRequest()
    {
        $this->createRouteValidators();
        
        foreach ($this->routeValidators as $routeValidator) {

            $routeValidator->validate();

            if ($routeValidator->hasPassed()) {

                $this->hasItFoundTheCorrectRouteForTheRequest = true;

                return $routeValidator->route();

            }

        }

        $this->hasItFoundTheCorrectRouteForTheRequest = false;
    }

    protected function createRouteValidators()
    {

        (array) $routes = $this->routesRegistrator->registeredRoutes();

        foreach ($routes as $route) {

            $this->routeValidators[] = $this->routeValidatorFactory->createFromRoute($route);
        }
    }

    public function foundCorrectRouteForRequest()
    {
        return $this->hasItFoundTheCorrectRouteForTheRequest;
    }

	


















}