<?php

namespace Stratum\Custom\Controller;

use Stratum\Original\HTTP\Request\Controller;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\Json;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;

Class WordPressController extends Controller
{
    public function home(Request $request, HTML $view)
    {
        return $view->from('Home.html');
    }

}