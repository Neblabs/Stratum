<?php

namespace Stratum\Original\HTTP\Request;

use Stratum\Original\Establish\Environment;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Wordpress\WordpressRouterHandler;

Class WordpressController extends RequestController
{
    protected $outputContent;

    public function setOutputContent($outputContent)
    {
        $this->outputContent = $outputContent;
    }

    public function setResponse()
    {
        $this->setWordpressRouteHandler();

        $this->prepareResponse();

        $this->OutputRegistrator->setOutput($this->outputContent);
        $this->OutputRegistrator->register();
        
    }

    public function prepareResponse()
    {
        $this->route = $this->WordpressRouterHandler->route();
    }

    public function response()
    {
        if ($this->router->foundCorrectRouteForRequest()) {

            $this->validateValidatorsAndSetAControllerIFOneHasFailed();

            $this->createControllerBasedOnRouteIfValidationsPassed();

            (object) $response = $this->executeController();

        } else {

            (object) $response = $this->RouteNotFoundController->execute();
        }


        return $response;

    }   

    protected function requireWordpress()
    {
        if (!Environment::is()->testing()) {
            require_once ABSPATH . 'OriginalIndex.php';
        }
    }

    public function setWordpressRouteHandler()
    {
        $this->WordpressRouterHandler = new WordpressRouterHandler;
        $this->WordpressRouterHandler->setRouter($this->router);
    }














}