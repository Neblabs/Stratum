<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Abstract Class Creator 
{
    protected $className;
    protected $methodName;
    protected $request;
    protected $url;
    protected $view;
    protected $redirection;
    protected $text;
    protected $json;
    protected $dump;
    protected $use;

    public function __construct(Request $request, URLData $URLData, HTML $view, Redirection $redirection, Text $text, JSON $json, Dump $dump, Dispatcher $dispatcher)
    {

        $this->request = $request;
        $this->url = $URLData;
        $this->view = $view;
        $this->redirection = $redirection;
        $this->text = $text;
        $this->json = $json;
        $this->dump = $dump;
        $this->use = $dispatcher;

    }

    public function setClassName($className)
    {
        $this->className = $className;
    }

    public function setmethodName($methodName)
    {
        $this->methodName = $methodName;
    }

    abstract protected function fullyQualifiedClassName();

    public function create()
    {
        (string) $className = $this->fullyQualifiedClassName();
       
        return new $className(
            $this->methodName,
            $this->request,
            $this->url, 
            $this->view,
            $this->redirection,
            $this->text,
            $this->json,
            $this->dump,
            $this->use
        );
    }











}