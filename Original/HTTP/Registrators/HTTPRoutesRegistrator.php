<?php

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\HTTPRoute;

Class HTTPRoutesRegistrator extends RoutesRegistrator
{
    protected static $routes = [];
    protected $method;
    protected $path;
    
    public function setMethod($HTTPMethod)
    {
        $this->method = $HTTPMethod;
    }

    public function setPath($URLPath)
    {
        $this->path = $URLPath;
    }

    public function register()
    {
        $this->throwExceptionIfAMethodOrPathHaveNotBeenSet();

        parent::register();
    }

    protected function registrationFilePath()
    {
        return STRATUM_ROOT_DIRECTORY . '/Design/Control/Routes/HTTP/Routes.php';
    }

    protected function createRoute()
    {
        $this->route = new HTTPRoute;
    }

    protected function setRouteData()
    {
        $this->route->setmethod($this->method);
        $this->route->setPathDefinition($this->path);
    }

    protected function throwExceptionIfAMethodOrPathHaveNotBeenSet()
    {
        (boolean) $methodHasNotBeenSet = !isset($this->method);
        (boolean) $pathHasNotBeenSet = !isset($this->path);

        if ($methodHasNotBeenSet) {

            throw new MissingRequiredPropertyException("An HTTP method must be set in order to register a route");

        } elseif ($pathHasNotBeenSet) {

            throw new MissingRequiredPropertyException("A URL path must be set in order to register a route");

        }
    }
    



}