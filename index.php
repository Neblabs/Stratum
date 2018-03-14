<?php

use Stratum\Original\Autoloader\Autoloader;
use Stratum\Original\HTTP\Creator\HTTPRouteValidatorFactory;
use Stratum\Original\HTTP\Creator\RouteValidatorFactory;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Request\ApplicationController;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Router;
use Stratum\original\HTTP\Registrator\FiltersRegistrator;

require_once 'Bootstrap.php';

(object) $request = Request::createBasedOn(\Symfony\Component\HttpFoundation\Request::createFromGlobals());

(object) $routeValidatorFactory = new HTTPRouteValidatorFactory;
(object) $filtersRegistrator = new FiltersRegistrator;
(object) $routesRegistrator = new HTTPRoutesRegistrator;

$routeValidatorFactory->setFiltersRegistrator(new FiltersRegistrator);
$routeValidatorFactory->setRequest($request);

(object) $router = new Router;

(object) $SymfonyResponse = new \Symfony\Component\HttpFoundation\Response;

(object) $view = new HTML($SymfonyResponse);
(object) $text = new Text($SymfonyResponse);
(object) $json = new JSON($SymfonyResponse);
(object) $redirection = new Redirection($SymfonyResponse);
(object) $dump = new Dump($SymfonyResponse);
(object) $dispatcher = new Dispatcher($SymfonyResponse);

$router->setRouteValidatorFactory($routeValidatorFactory);
$router->setRoutesRegistrator($routesRegistrator);

(object) $ApplicationController = new ApplicationController(
    $request,
    $router,
    $view,
    $text,
    $json,
    $redirection,
    $dump,
    $dispatcher
);

$ApplicationController->prepareResponse();

#A dirty hack. It has to be done in the global scope
require STRATUM_ROOT_DIRECTORY . '/Original/HTTP/Wordpress/LoadWordpressIfApplicable.php';

$ApplicationController->sendResponse();
$ApplicationController->close();










