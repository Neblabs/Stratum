<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Controller\ConcreteControllerTest55;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\UnsupportedResponseTypeException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\Json;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Class ConcreteControllerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/ConcreteController.php');
        file_put_contents('Design/Control/Controllers/ConcreteControllerTest55.php', $TestController);
    }

     public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Controllers/ConcreteControllerTest55.php');
    }

    public function test_method_returns_a_view_as_a_response()
    {
        (object) $view = $this->createMock(HTML::class);
        (object) $Request = $this->createMock(Request::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);

        (object) $concreteController = new ConcreteControllerTest55(
            'controllerMethod',
            $Request,
            new URLData(['id' => 5534]),    
            $view,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $Dispatcher
        );

        $Request->expects($this->once())
                ->method('__get');

        $Dispatcher->expects($this->once())
                    ->method('controller');

        $view->expects($this->once())
            ->method('from')
            ->will($this->returnSelf());



        (object) $response = $concreteController->execute();

        $this->assertSame($view, $response);


    }

    public function test_throws_exception_if_the_return_type_is_not_a_response_object()
    {

        $this->expectException(UnsupportedResponseTypeException::class);

        (object) $view = $this->createMock(HTML::class);
        (object) $Request = $this->createMock(Request::class);
        (object) $Dispatcher = $this->createMock(Dispatcher::class);

        (object) $concreteController = new ConcreteControllerTest55(
            'fails',
            $Request,
            new URLData(['id' => 5534]),    
            $view,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $Dispatcher
        );


        (object) $response = $concreteController->execute();



    }















}