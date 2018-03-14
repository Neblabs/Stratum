<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Controller\ConcreteControllerTest55;
use Stratum\Original\HTTP\Creator\ControllerCreator;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Class ControllerCreatorTest extends TestCase
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
    
    
    public function setUp()
    {
        $this->controllerCreator = new ControllerCreator(
            $this->createMock(Request::class),
            new URLData(['id' => 5534]),    
            $this->createMock(HTML::class) ,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );
    }

    public function test_creates_an_instance_of_a_custom_controller()
    {
        $this->controllerCreator->setClassName('ConcreteControllerTest55');
        $this->controllerCreator->setMethodName('controllerMethod');

        (object) $createdController = $this->controllerCreator->create();

        $this->assertInstanceOf(ConcreteControllerTest55::class, $createdController);
    }
}