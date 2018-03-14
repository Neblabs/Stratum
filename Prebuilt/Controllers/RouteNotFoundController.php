<?php

namespace Stratum\Prebuilt\Controller;

use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Request\Controller;
use Stratum\Original\HTTP\Response\HTML;

Class RouteNotFoundController extends Controller
{
    public function unexistentRoute(Request $requested, HTML $view)
    {
        (array) $variables = [
            'title' => 'Route Not Found',
            'path' => $requested->http->URL->path
        ];

        return $view->from('Original/route-not-found.html')->with($variables)->useMasterPage('Original/Master.html');
    }
}