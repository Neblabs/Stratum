<?php

namespace Stratum\Original\HTTP\Request;


use Stratum\Original\HTTP\Caller;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\MissingActionException;
use Stratum\Original\HTTP\Exception\UnsupportedResponseTypeException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;
use Stratum\Original\HTTP\Validator as CoreValidator;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Validator extends CoreValidator
{
    use ClassName;

    protected $templateMethod;
    protected $request;
    protected $view;
    protected $redirection;
    protected $text;
    protected $json;
    protected $dump;
    protected $fullyQualifiedControllerClassName;

    public function __construct($validatorMethodName, Request $request, URLData $URLData, HTML $view, Redirection $redirection, Text $text, JSON $json, Dump $dump, Dispatcher $dispatcher)
    {
        $this->validatorMethodName = $validatorMethodName;
        $this->request = $request;
        $this->url = $URLData;
        $this->view = $view;
        $this->redirection = $redirection;
        $this->text = $text;
        $this->json = $json;
        $this->dump = $dump;
        $this->use = $dispatcher;
        $this->dispatch = $this->use;
        $this->fullyQualifiedValidatorClassName = get_class($this);

        $this->caller = new Caller;

        $this->caller->setObject($this);
        $this->caller->setMethodName($this->validatorMethodName);

        $this->caller->setRequest($this->request);
        $this->caller->setView($this->view);
        $this->caller->setText($this->text);
        $this->caller->setJson($this->json);
        $this->caller->setRedirection($this->redirection);
        $this->caller->setDump($this->dump);
        $this->caller->setDispatcher($this->use);
        $this->caller->setURLData($this->url);
    }

    public function callMethod()
    {   
        (object) $dispatcher = $this->caller->callMethod();

        if ($this->hasPassed()) {

            $this->passed();

        } elseif ($this->hasFailed()) {

            $this->throwExceptionIfReturnValueIsNotADispatcherObject($dispatcher);

            $this->dispatcher = $dispatcher;

        } else {
            throw new MissingActionException('A validator must either pass or fail.');
        }

    }

    public function validate()
    {
        $this->callMethod();
    }

    public function dispatcher()
    {
        return $this->dispatcher;
    }

    protected function throwExceptionIfReturnValueIsNotADispatcherObject($dispatcher)
    {
        (string) $dispatcherClass = dispatcher::ClassName();

        (boolean) $dispatcherIsNotAnInstanceOfdispatcher = !($dispatcher instanceof $dispatcherClass);

        if ($dispatcherIsNotAnInstanceOfdispatcher) {
            throw new UnsupportedResponseTypeException(
                'A validator can only return a ' . dispatcher::ClassName() . ' instance.'
            );
        }
    }




























}