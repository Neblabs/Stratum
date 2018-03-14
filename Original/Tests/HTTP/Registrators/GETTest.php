<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Registrator\GET;
use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;

Class GETTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/TestController.php');
        file_put_contents('Design/Control/Controllers/StratumTestUsersController.php', $TestController);

        (string) $TestValidator = file_get_contents('Original/Tests/HTTP/TestClasses/TestValidator.php');
        file_put_contents('Design/Control/Validators/StratumTestUsersValidator.php', $TestValidator);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Controllers/StratumTestUsersController.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Validators/StratumTestUsersValidator.php');
    }

    public function test_returns_a_new_instance()
    {
        $this->assertInstanceof(GET::class, GET::request());
    }

    public function test_returns_itself_to_suport_method_chains()
    {
        $this->assertInstanceof(GET::class, GET::request()->to('/users'));
        $this->assertInstanceof(GET::class, GET::request()->to('/users')->validateWith('StratumTestUsersValidator.create'));
    }

    public function test_returns_the_RoutesRegistrator_object_that_registered_the_route()
    {
        (object) $GETRoutesRegistrator =  GET::request()->to('/users/55712')
                                                        ->validateWith('StratumTestUsersValidator.create')
                                                        ->validateWith('StratumTestUsersValidator.exists')
                                                        ->use('StratumTestUsersController.create');

        $this->assertInstanceof(RoutesRegistrator::class, $GETRoutesRegistrator);

        $GETRoutesRegistrator->unregister();
    }

    public function test_registers_a_GET_route()
    {
        (object) $GETRoutesRegistrator =  GET::request()->to('/users/55712')
                                                        ->validateWith('StratumTestUsersValidator.create')
                                                        ->validateWith('StratumTestUsersValidator.exists')
                                                        ->use('StratumTestUsersController.create');

        (object) $routesRegistrator = new HTTPRoutesRegistrator;

        $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses/EmptyRegister.php');

        (array) $registeredRoutes = $routesRegistrator->registeredRoutes();
        
        $this->assertCount(1, $registeredRoutes);
        
        $this->assertEquals('GET', $registeredRoutes[0]->method());
        $this->assertEquals('users/55712', $registeredRoutes[0]->pathDefinition());

        $this->assertEquals('StratumTestUsersValidator', $registeredRoutes[0]->validators()[0]['className']);
        $this->assertEquals('create', $registeredRoutes[0]->validators()[0]['methodName']);
        $this->assertEquals('StratumTestUsersValidator', $registeredRoutes[0]->validators()[1]['className']);
        $this->assertEquals('exists', $registeredRoutes[0]->validators()[1]['methodName']);

        $this->assertEquals('StratumTestUsersController', $registeredRoutes[0]->controller()['className']);
        $this->assertEquals('create', $registeredRoutes[0]->controller()['methodName']);


        $GETRoutesRegistrator->unregister();
    }

    public function test_registers_several_routes()
    {
        (object) $routesRegistrator1 = GET::request()->to('/')
                                                     ->use('StratumTestUsersController.create');

        (object) $routesRegistrator2 = GET::request()->to('contact')
                                                     ->use('StratumTestUsersController.show');

        (object) $routesRegistrator3 = GET::request()->to('about-us')
                                                     ->use('StratumTestUsersController.create');

        (object) $routesRegistrator4 = GET::request()->to('sign-in')
                                                     ->use('StratumTestUsersController.show');

        (object) $routesRegistrator = new HTTPRoutesRegistrator;

        $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses/EmptyRegister.php');
        
        (array) $registeredRoutes = $routesRegistrator->registeredRoutes();

        $this->assertCount(4, $registeredRoutes);

        $this->assertEquals('/', $registeredRoutes[0]->pathDefinition());
        $this->assertEquals('contact', $registeredRoutes[1]->pathDefinition());
        $this->assertEquals('about-us', $registeredRoutes[2]->pathDefinition());
        $this->assertEquals('sign-in', $registeredRoutes[3]->pathDefinition());

        $routesRegistrator1->unregister();
        $routesRegistrator2->unregister();
        $routesRegistrator3->unregister();
        $routesRegistrator4->unregister();


    }
























}