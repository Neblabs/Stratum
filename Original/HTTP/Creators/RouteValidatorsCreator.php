<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Validator\HTTPRouteValidator;
use Stratum\Original\HTTP\Validator\RouteValidator;

Class RouteValidatorsCreator
{
	protected $routes;
	protected $request;
	protected $routeValidators;

	public function setRoutes(array $routes)
	{
		$this->routes = $routes;
	}

	public function setRequest(Request $request)
	{
		$this->request = $request;
	}

	public function create()
	{
		return $this->RouteValidators();
	}

	protected function RouteValidators()
	{
		$this->throwExceptionIfNoRoutesNorRequestHaveBeenSet();

		foreach ($this->routes as $route) {
			$routeValidator = new HTTPRouteValidator;

			$routeValidator->setRoute($route);
			$routeValidator->setRequest($this->request);

			$this->routeValidators[] = $routeValidator;
		}

		return $this->routeValidators;
	}

	protected function throwExceptionIfNoRoutesNorRequestHaveBeenSet()
	{
		(boolean) $oneValueIsMissing = (empty($this->routes) or empty($this->request));

		if ($oneValueIsMissing) {
			throw new MissingRequiredPropertyException(
				"An array of Route objects and a Request object must be set before calling RouteValidatorsCreator::create()"
			);
		}
	}

}







