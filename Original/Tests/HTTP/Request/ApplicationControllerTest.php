<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Request\ApplicationController;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Router;

class ApplicationControllerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/ConcreteController.php');
        file_put_contents('Design/Control/Controllers/ConcreteControllerTest55.php', $TestController);

        (string) $TestValidator = file_get_contents('Original/Tests/HTTP/TestClasses/ConcreteValidator.php');
        file_put_contents('Design/Control/Validators/ConcreteValidatorTest55.php', $TestValidator);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Controllers/ConcreteControllerTest55.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Validators/ConcreteValidatorTest55.php');
    }

    public function test_controller_is_called_and_its_response_is_called_as_well_no_validators()
    {
        (object) $request = new GETRequest(\Symfony\Component\HttpFoundation\request::create('users/5563'));
        (object) $router = $this->createMock(Router::class);

        (object) $route = $this->createMock(HTTPRoute::class);

        (object) $view = $this->createMock(HTML::class);

        (object) $applicationController = new ApplicationController(
            $request,
            $router,
            $view,
            $this->createMock(Text::class),
            $this->createMock(JSON::class),
            $this->createMock(Redirection::class),
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );

        $router->expects($this->once())
                ->method('chooseCorrectRouteForCurrentRequest')
                ->willReturn($route);

        $router->expects($this->once())
                ->method('foundCorrectRouteForRequest')
                ->willReturn(true);

        $route->expects($this->once())
                ->method('validators')
                ->willReturn([]);

        $route->expects($this->once())
                ->method('controller')
                ->willReturn([
                    'className' => 'ConcreteControllerTest55',
                    'methodName' => 'controllerMethod'
        ]);

        $route->expects($this->once())
            ->method('pathDefinition')
            ->willReturn('users/5563');

        $view->expects($this->once())->method('from')
            ->will($this->returnSelf());

        $view->expects($this->once())
            ->method('send');


        $applicationController->prepareResponse();


        $applicationController->sendResponse();


    }

    public function test_controller_is_called_and_its_response_is_called_when_all_validators_have_passed()
    {
        (object) $request = new GETRequest(\Symfony\Component\HttpFoundation\request::create('users/5563'));
        (object) $router = $this->createMock(Router::class);

        (object) $route = $this->createMock(HTTPRoute::class);

        (object) $view = $this->createMock(HTML::class);

        (object) $applicationController = new ApplicationController(
            $request,
            $router,
            $view,
            $this->createMock(Text::class),
            $this->createMock(JSON::class),
            $this->createMock(Redirection::class),
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );

        $router->expects($this->once())
                ->method('chooseCorrectRouteForCurrentRequest')
                ->willReturn($route);

        $router->expects($this->once())
                ->method('foundCorrectRouteForRequest')
                ->willReturn(true);

        $route->expects($this->once())
                ->method('validators')
                ->willReturn([
                    [
                        'className' => 'ConcreteValidatorTest55',
                        'methodName' => 'passingValidator'
                    ]
                ]);

        $route->expects($this->once())
                ->method('controller')
                ->willReturn([
                    'className' => 'ConcreteControllerTest55',
                    'methodName' => 'controllerMethod'
        ]);

        $route->expects($this->any())
            ->method('pathDefinition')
            ->willReturn('users/5563');

        $view->expects($this->once())
            ->method('from')
            ->will($this->returnSelf());

        $view->expects($this->once())
            ->method('send');


        $applicationController->prepareResponse();


        $applicationController->sendResponse();


    }

    public function test_controller_is_not_called_when_one_validator_have_failed()
    {
        (object) $request = new GETRequest(\Symfony\Component\HttpFoundation\request::create('users/5563'));
        (object) $router = $this->createMock(Router::class);

        (object) $route = $this->createMock(HTTPRoute::class);

        (object) $view = $this->createMock(HTML::class);

        (object) $dispatcher = $this->createMock(Dispatcher::class);

        (object) $text = $this->createMock(Text::class);

        (object) $applicationController = new ApplicationController(
            $request,
            $router,
            $view,
            $text,
            $this->createMock(JSON::class),
            $this->createMock(Redirection::class),
            $this->createMock(Dump::class),
            $dispatcher
        );

        $router->expects($this->once())
                ->method('chooseCorrectRouteForCurrentRequest')
                ->willReturn($route);

        $router->expects($this->once())
                ->method('foundCorrectRouteForRequest')
                ->willReturn(true);

        $route->expects($this->once())
                ->method('validators')
                ->willReturn([
                    [
                        'className' => 'ConcreteValidatorTest55',
                        'methodName' => 'failingValidator'
                    ],
                    [
                        'className' => 'ConcreteValidatorTest55',
                        'methodName' => 'passingValidator'
                    ]
                ]);

        $route->expects($this->never())
                ->method('controller')
                ->willReturn([
                    'className' => 'ConcreteControllerTest55',
                    'methodName' => 'controllerMethod'
        ]);

        $route->expects($this->any())
            ->method('pathDefinition')
            ->willReturn('users/5563');

        $dispatcher->expects($this->once())
                    ->method('controller')
                    ->will($this->returnSelf());

        $dispatcher->expects($this->once())
                    ->method('data')
                    ->willReturn([
                        'className' => 'ConcreteControllerTest55',
                        'methodName' => 'forbiddenAccess'
                    ]);


        $text->expects($this->once())
            ->method('containing')
            ->with($this->equalTo('you don\'t have access to view this page.'))
            ->will($this->returnSelf());

        $text->expects($this->once())
            ->method('send');


        $view->expects($this->never())
            ->method('from')
            ->will($this->returnSelf());

        $view->expects($this->never())
            ->method('send');


        $applicationController->prepareResponse();


        $applicationController->sendResponse();


    }

    public function test_404_response_is_sent_when_no_route_was_found_for_the_request()
    {

        (object) $request = new GETRequest(\Symfony\Component\HttpFoundation\request::create('users/5563'));
        (object) $router = $this->createMock(Router::class);

        (object) $route = $this->createMock(HTTPRoute::class);

        (object) $view = $this->createMock(HTML::class);

        (object) $applicationController = new ApplicationController(
            $request,
            $router,
            $view,
            $this->createMock(Text::class),
            $this->createMock(JSON::class),
            $this->createMock(Redirection::class),
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );

        $view->expects($this->once())->method('from')->with('Original/route-not-found.html')->will($this->returnSelf());
        $view->expects($this->once())->method('with')->will($this->returnSelf());
        $view->expects($this->once())->method('useMasterPage')->will($this->returnSelf());
        $view->expects($this->once())->method('send');

        $router->expects($this->once())
                ->method('chooseCorrectRouteForCurrentRequest')
                ->willReturn(null);

        $router->expects($this->once())
                ->method('foundCorrectRouteForRequest')
                ->willReturn(false);

        $route->expects($this->never())
                ->method('validators')
                ->willReturn([]);


        $route->expects($this->never())
            ->method('pathDefinition')
            ->willReturn('users/5563');



        $applicationController->prepareResponse();


        $applicationController->sendResponse();


    }















}