<?php 

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;

Abstract class RoutesRegistratorFacade
{
    protected $routesRegistrator;

    abstract protected function createRoutesRegistrator();

    public function __construct()
    {
        $this->createRoutesRegistrator();
    }

    public static function request()
    {
        return new static;
    }

    public function validateWith($fullyQualifiedValidatorName)
    {
        $this->routesRegistrator->addValidator($fullyQualifiedValidatorName);

        return $this;
    }

    protected function __use($fullyQualifiedControllerName)
    {
        $this->routesRegistrator->setController($fullyQualifiedControllerName);

        $this->routesRegistrator->register();

        return $this->routesRegistrator;
    }

    public function __call($method, $arguments)
    {
        $method = "__$method";
        return $this->$method($arguments[0]);
    }












}