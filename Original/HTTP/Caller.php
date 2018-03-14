<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\ForbiddenOutputException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class Caller
{
    protected $object;
    protected $methodName;
    protected $request;
    protected $view;
    protected $redirection;
    protected $text;
    protected $json;
    protected $dump;
    protected $dispatcher;
    protected $url;
    protected $methodArguments = [];

    protected function fullyQualifiedClassName()
    {
        return get_class($this->object);
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function setMethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    public function setClassType($type)
    {
        $this->type = $type;
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setView(HTML $view)
    {
        $this->view = $view;
    }

    public function setRedirection(Redirection $redirection)
    {
        $this->redirection = $redirection;
    }

    public function setText(Text $text)
    {
        $this->text = $text;
    }

    public function setJson(Json $json)
    {
        $this->json = $json;
    }

    public function setDump(Dump $dump)
    {
        $this->dump = $dump;
    }

    public function setDispatcher(dispatcher $dispatcher)
    {
        $this->use = $dispatcher;
    }

    public function setURLData(URLData $URLData)
    {
        $this->url = $URLData;
    }

    public function callMethod()
    {
        (array) $requestedArguments = $this->ArrayOfReflectionParameters();

        $this->setMethodArgumentsArrayFrom($requestedArguments);

        ob_start();

        (object) $response = call_user_func_array([$this->object, $this->methodName], $this->methodArguments);

        (string) $output = ob_get_contents();

        ob_end_clean();

        $this->throwExceptionIfOutputWasRegisteredWhenCallingTheMethod($output);
        
        $this->calledMethod = $this->methodName;
        
        return $response;

        
       
    }

    protected function ArrayOfReflectionParameters()
    {
        (object) $ObjectReflectionClass = new \ReflectionClass($this->fullyQualifiedClassName());
        
        (object) $ObjectReflectionMethod = $ObjectReflectionClass->getMethod($this->methodName);
        (array) $controlerMethodReflectionParameters = $ObjectReflectionMethod->getParameters();
        
        return $controlerMethodReflectionParameters;
    }

    protected function setMethodArgumentsArrayFrom($requestedArguments)
    {
        foreach ($requestedArguments as $argument) {
            (string) $argumentName = $argument->getName();
            (object) $argumentClassType = $argument->getClass();
            
            (boolean) $argumentIsTypeHinted = !is_null($argumentClassType);

            if ($argumentIsTypeHinted) {
                $this->addSupportedObjectToTheArgumentsArrayOrThrowException($argumentClassType);
            } else {
                $this->addTheRequestedUrlValueToTheArgumentsArrayOrThrowException($argumentName);
            }
        }
    }

    protected function addSupportedObjectToTheArgumentsArrayOrThrowException($argumentClassType)
    {
        (string) $argumentClassName = $argumentClassType->getName();
        
        switch ($argumentClassName) {
            case Request::ClassName():
                $this->methodArguments[] = $this->request;
                break;
            case HTML::ClassName():
                $this->methodArguments[] = $this->view;
                break;
            case Text::ClassName():
                $this->methodArguments[] = $this->text;
                break;
            case JSON::ClassName():
                $this->methodArguments[] = $this->json;
                break;
            case Redirection::ClassName():
                $this->methodArguments[] = $this->redirection;
                break;
            case Dump::ClassName():
                $this->methodArguments[] = $this->dump;
                break;
            case Dispatcher::ClassName():
                $this->methodArguments[] = $this->use;
                break;
            default:
                throw new \InvalidArgumentException('Unsupported Object');
            
        }

    }

    protected function addTheRequestedUrlValueToTheArgumentsArrayOrThrowException($argumentName)
    {
        (boolean) $URLDataValueExistsWithTheSameNameAsTheOneInTheArgument = !is_null($this->url->$argumentName);

        if ($URLDataValueExistsWithTheSameNameAsTheOneInTheArgument) {
            $this->methodArguments[] = $this->url->$argumentName;
        } else {
            throw new \InvalidArgumentException('Non-object arguments must be the name of a wildcard');
        }
    }

    protected function throwExceptionIfOutputWasRegisteredWhenCallingTheMethod($output)
    {
        (boolean) $outputContentExists = !empty($output);

        //if ($outputContentExists) {
        //    throw new ForbiddenOutputException('Outputting content directly from a controller or validator is not //allowed.');
        //}
    }
































}