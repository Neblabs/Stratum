<?php

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\HTTPRoute;

Abstract Class RoutesRegistrator
{
    protected $route;
    protected $validators = [];
    protected $controller;
    protected static $routesFileHasBeenIncluded = false;
    protected $registrationFilePath;

    abstract protected function setRouteData();
    abstract protected function createRoute();
    abstract protected function registrationFilePath();

    public function __construct()
    {
        $this->createRoute();
        $this->registrationFilePath = $this->registrationFilePath();
    }

    public function addValidator($fullyQualifiedValidatorName)
    {
        $this->validators[] = $fullyQualifiedValidatorName;
    }

    public function setController($fullyQualifiedControllerName)
    {
        $this->controller = $fullyQualifiedControllerName;
    }

    public function register()
    {
        $this->throwExceptionIfAControllerHaveNotBeenSet();

        $this->setRoute();

        static::$routes[] = $this->route;
    }

    public function unregister()
    {
        (integer) $routeKey = array_search($this->route, static::$routes, true);
        (boolean) $routeExistsInRoutesArray = $routeKey !== false;
        
        if ($routeExistsInRoutesArray) {
            unset(static::$routes[$routeKey]);

            static::$routes = array_values(static::$routes);
        }
    }

    public function setRegistrationFilePath($registrationFilePath)
    {
        $this->registrationFilePath = $registrationFilePath;
    }


    protected function setRoute()
    {
        $this->setRouteData();

        foreach ($this->validators as $validator) {
            $this->route->addValidator($validator);
        }

        $this->route->setController($this->controller);
    }

    public function registeredRoutes()
    {   
        $this->includeRoutesFileIfItHasntBeenincluded();

        return static::$routes;
    }

    protected function includeRoutesFileIfItHasntBeenincluded()
    {     
        require_once $this->registrationFilePath;
    }

    protected function throwExceptionIfAControllerHaveNotBeenSet()
    {
        (boolean) $controllerHasNotBeenSet = !isset($this->controller);

        if ($controllerHasNotBeenSet) {

            throw new MissingRequiredPropertyException("A fully qualified controller name must be set in order to register a route");

        }
    }














}