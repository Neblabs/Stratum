<?php

namespace Stratum\Original\HTTP\Request;

use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\URLData;

Abstract Class Template
{
    protected $templateMethod;
    protected $request;
    protected $view;
    protected $redirection;
    protected $text;
    protected $json;
    protected $dump;

    public function __construct($templateMethod, Route $route, Request $request, URLData $URLData, HTML $view, Redirection $redirection, Text $text, JSON $json, Dump $dump)
    {
        $this->templateMethod = $templateMethod;
        $this->route = $route;
        $this->request = $request;
        $this->url = $URLData;
        $this->view = $view;
        $this->redirection = $redirection;
        $this->text = $text;
        $this->json = $json;
        $this->dump = $dump;
    }

    public function callMethod()
    {
        (string) $method = $this->templateMethod;

        $this->$method();

        $this->calledMethod = $method;
       
    }

    public function calledMethod()
    {
        return $this->calledMethod;
    }
}