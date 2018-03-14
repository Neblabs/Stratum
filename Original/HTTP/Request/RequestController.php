<?php

namespace Stratum\Original\HTTP\Request;

use Stratum\Original\HTTP\Creator\ControllerCreator;
use Stratum\Original\HTTP\Creator\URLDataCreator;
use Stratum\Original\HTTP\Creator\ValidatorCreator;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Registrator\OutputRegistrator;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Router;
use Stratum\Original\HTTP\URLData;
use Stratum\Prebuilt\Controller\RouteNotFoundController;


Abstract Class RequestController
{
    protected $validators = [];

    public function __construct(Request $request, Router $router, HTML $view, Text $text, Json $json, Redirection $redirection, Dump $dump, Dispatcher $dispatcher)
    {
        $this->request = $request;
        $this->router = $router;
        $this->view = $view;
        $this->text = $text;
        $this->json = $json;
        $this->redirection = $redirection;
        $this->dump = $dump;
        $this->dispatcher = $dispatcher;

        $this->validationPassed = true;

        $this->RouteNotFoundController = new RouteNotFoundController('unexistentRoute', $request, new URLData([]), $view, $redirection, $text, $json, $dump, $dispatcher);

        $this->OutputRegistrator = new OutputRegistrator;
    }

    public function prepareResponse()
    {
        (object) $this->route = $this->router->chooseCorrectRouteForCurrentRequest();

    }

    protected function validateValidatorsAndSetAControllerIFOneHasFailed()
    {
        $this->createValidatorsIfExistInChosenRoute();

            foreach ($this->validators as $validator) {
                $validator->validate();

                if ($validator->hasFailed()) {
                    $this->createcontrollerBasedOn($validator->dispatcher()->data());

                    $this->validationPassed = false;
                    
                    break;
                }
            }
    }

    protected function createValidatorsIfExistInChosenRoute()
    {   

        foreach ($this->route->validators() as $validator) {

            (object) $validatorCreator = $this->createValidatorCreator();

            $validatorCreator->setClassName($validator['className']);
            $validatorCreator->setMethodName($validator['methodName']);

            $this->validators[] = $validatorCreator->create();
        }
    }

    protected function createValidatorCreator()
    {
        (object) $URLDataCreator = new URLDataCreator();

        $URLDataCreator->setPathDefinition($this->route->pathDefinition());
        $URLDataCreator->setRequestedPath($this->request->http->URL->path);

        return new ValidatorCreator(
            $this->request,
            $URLDataCreator->create(),
            $this->view,
            $this->redirection,
            $this->text,
            $this->json,  
            $this->dump,
            $this->dispatcher
        );
    }

    protected function createControllerBasedOnRouteIfValidationsPassed()
    {
        if ($this->validationPassed) {

            $this->createControllerBasedOn($this->route->controller());

        }
    }

    protected function createcontrollerBasedOn($controllerData)
    {
        (object) $URLDataCreator = new URLDataCreator();

        $URLDataCreator->setPathDefinition($this->route->pathDefinition());
        $URLDataCreator->setRequestedPath($this->request->http->URL->path);

        (object) $controllerCreator = new ControllerCreator(
            $this->request,
            $URLDataCreator->create(),
            $this->view,
            $this->redirection,
            $this->text,
            $this->json,  
            $this->dump,
            $this->dispatcher
        );

        $controllerCreator->setClassName($controllerData['className']);
        $controllerCreator->setMethodName($controllerData['methodName']);

        $this->controller = $controllerCreator->create();
    }

    protected function executeController()
    {
        (object) $response = $this->controller->execute();

        return $response;
    }
}