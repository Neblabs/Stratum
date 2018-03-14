<?php

namespace Stratum\Original\HTTP\Request;

use Stratum\Original\HTTP\Caller;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\UnsupportedResponseTypeException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Controller
{
    use ClassName;

    protected $request;
    protected $view;
    protected $redirection;
    protected $text;
    protected $json;
    protected $dump;
    
    private $templateMethod;
    private $controllerMethodName;
    private $fullyQualifiedControllerClassName;
    private $caller;


    public function __construct($controllerMethodName, Request $request, URLData $URLData, HTML $view, Redirection $redirection, Text $text, JSON $json, Dump $dump, Dispatcher $dispatcher)
    {
        $this->controllerMethodName = $controllerMethodName;
        $this->request = $request;
        $this->url = $URLData;
        $this->view = $view;
        $this->redirection = $redirection;
        $this->text = $text;
        $this->json = $json;
        $this->dump = $dump;
        $this->use = $dispatcher;
        $this->fullyQualifiedControllerClassName = get_class($this);

        $this->caller = new Caller;

        $this->caller->setObject($this);
        $this->caller->setMethodName($this->controllerMethodName);

        $this->caller->setRequest($this->request);
        $this->caller->setView($this->view);
        $this->caller->setText($this->text);
        $this->caller->setJson($this->json);
        $this->caller->setRedirection($this->redirection);
        $this->caller->setDump($this->dump);
        $this->caller->setDispatcher($this->use);
        $this->caller->setURLData($this->url);
    }

    final public function callMethod()
    {
        (object) $response = $this->caller->callMethod();

        $this->throwExceptionIfResponseIsNotASuportedResponseObject($response);

        return $response;
    }

    final public function execute()
    {
        return $this->callMethod();
    }

    protected function throwExceptionIfResponseIsNotASuportedResponseObject($response)
    {
        (string) $responseClass = Response::ClassName();

        (boolean) $responseIsNotAnInstanceOfResponse = !($response instanceof $responseClass);

        if ($responseIsNotAnInstanceOfResponse) {
            throw new UnsupportedResponseTypeException(
                'A controller can only return a ' . Response::ClassName() . ' instance.'
            );
        }
    }




























}