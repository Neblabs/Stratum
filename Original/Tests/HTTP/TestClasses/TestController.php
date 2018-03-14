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

Class StratumTestUsersController extends Controller
{
    protected function show()
    {
        //
    }

    protected function create()
    {
        //
    }

    public function list(Request $request, HTML $view, $id)
    {

        return $view->from('path.html');
    }

    public function noArguments()
    {
        //shh...
    }

    public function onlyWildcards($id, $commentId)
    {
        //
    }

    public function onlyTypeHinted(Request $request, HTML $view, Redirection $redirection, Text $text, Json $json, Dump $dump, Dispatcher $use)
    {
        $request->userName;
        $view->from('file.html');
        $redirection->to('path');
        $text->containing('content');
        $json->fromArray([]);
        $dump->variable('no var');
        $use->controller('nonexistingcontroler.no');
    }

    public function typeHintedAndWildcards($id, Request $request, HTML $view, Redirection $redirection, Text $text, Json $json, Dump $dump, Dispatcher $use, $commentId)
    {
        $request->userName;
        $view->from('file.html');
        $redirection->to('path');
        $text->containing('content');
        $json->fromArray([]);
        $dump->variable('no var');
        $use->controller('nonexistingcontroler.no');
    }

    public function unknownObject(Caller $ooops)
    {
        // will complain
    }

    public function unknownWildcard($unknownId)
    {
        // will comlain *2
    }

    public function output()
    {
        echo 'bad practice!';
    }
































}