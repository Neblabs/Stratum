<?php

namespace Stratum\Original\HTTP\Request;

use Stratum\Original\HTTP\Cleaner\BufferCleaner;
use Stratum\Original\HTTP\Creator\ControllerCreator;
use Stratum\Original\HTTP\Creator\URLDataCreator;
use Stratum\Original\HTTP\Creator\ValidatorCreator;
use Stratum\Original\HTTP\Creator\WordpressRouteValidatorFactory;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Registrator\WordpressRoutesRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Router;


Class ApplicationController extends RequestController 
{
    protected $request;
    protected $router;
    protected $route;
    protected $routesRegistrator;
    protected $view;
    protected $text;
    protected $json;
    protected $redirection;
    protected $dump;
    protected $dispatcher;

    protected static $closeHandlers = [];

    public static function addCloseHandler(Callable $handler)
    {
        static::$closeHandlers[] = $handler;
    }

    public function __construct(Request $request, Router $router, HTML $view, Text $text, Json $json, Redirection $redirection, Dump $dump, Dispatcher $dispatcher)
    {
        (object) $wordpressRouter = new Router;

        $wordpressRouter->setRouteValidatorFactory(new WordpressRouteValidatorFactory);
        $wordpressRouter->setRoutesRegistrator(new WordpressRoutesRegistrator);

        $this->WordpressController = new WordpressController($request, $wordpressRouter, $view, $text, $json, $redirection, $dump, $dispatcher);

        parent::__construct($request, $router, $view, $text, $json, $redirection, $dump, $dispatcher);
    }

    public function sendResponse()
    {
        if ($this->HTTPRouterFoundTheCorrectRoute()) {

            $this->validateValidatorsAndSetAControllerIFOneHasFailed();

            $this->createControllerBasedOnRouteIfValidationsPassed();

            (object) $response = $this->executeController();

        } else {

            $this->WordpressController->setResponse();
            
            (object) $response = $this->WordpressController->response();

        }

        $response->send();

    }   

    public function HTTPRouterFoundTheCorrectRoute()
    {
        return $this->router->foundCorrectRouteForRequest();
    }

    public function close()
    {
        register_shutdown_function(function(){
            (object) $bufferCleaner = new BufferCleaner;

            $bufferCleaner->cleanBuffersIfFull();
        });

        foreach (static::$closeHandlers as $closeHandler) {
            $closeHandler();
        }
    } 

}

