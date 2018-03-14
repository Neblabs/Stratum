<?php

namespace Stratum\Custom\Controller;

use Stratum\Original\HTTP\Caller;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Request\Controller;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\Json;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;

Class ConcreteControllerTest55 extends Controller
{
    public function controllerMethod(Request $request, HTML $view)
    {
        $request->id;

        $this->use->controller('controller.name');

        return $view->from('file.html');
    }

    public function fails()
    {
        
        return new \stdClass;
    }

    public function forbiddenAccess(Text $text)
    {
        return $text->containing('you don\'t have access to view this page.');
    }

}