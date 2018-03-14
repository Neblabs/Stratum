<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Registrator\WordpressRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Registrator\Wordpress;

Class WordpressTest extends TestCase
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
        $this->assertInstanceof(Wordpress::class, Wordpress::request());
    }

        public function test_returns_itself_to_suport_method_chains()
        {
            $this->assertInstanceof(Wordpress::class, Wordpress::request()->to('/users'));
            $this->assertInstanceof(Wordpress::class, Wordpress::request()->to('/users')->validateWith('StratumTestUsersValidator.create'));
        }
        
        public function test_returns_the_RoutesRegistrator_object_that_registered_the_route()
        {
            (object) $WordpressRoutesRegistrator =  Wordpress::request()->to('/users/55712')
                                                            ->validateWith('StratumTestUsersValidator.create')
                                                            ->validateWith('StratumTestUsersValidator.exists')
                                                            ->use('StratumTestUsersController.create');
            $this->assertInstanceof(RoutesRegistrator::class, $WordpressRoutesRegistrator);
            $WordpressRoutesRegistrator->unregister();
        }
        public function test_registers_a_Wordpress_route()
        {
            (object) $WordpressRoutesRegistrator =  Wordpress::request()->to('home')
                                                            ->validateWith('StratumTestUsersValidator.create')
                                                            ->validateWith('StratumTestUsersValidator.exists')
                                                            ->use('StratumTestUsersController.create');
        
            (object) $routesRegistrator = new WordpressRoutesRegistrator;
        
            $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses///EmptyRegister.php');
       
           (array) $registeredRoutes = $routesRegistrator->registeredRoutes();
           
           $this->assertCount(1, $registeredRoutes);
           
           $this->assertEquals('home', $registeredRoutes[0]->sitePage());
       
           $this->assertEquals('StratumTestUsersValidator', $registeredRoutes[0]->validators()[0]['className']);
           $this->assertEquals('create', $registeredRoutes[0]->validators()[0]['methodName']);
           $this->assertEquals('StratumTestUsersValidator', $registeredRoutes[0]->validators()[1]['className']);
           $this->assertEquals('exists', $registeredRoutes[0]->validators()[1]['methodName']);
       
           $this->assertEquals('StratumTestUsersController', $registeredRoutes[0]->controller()['className']);
           $this->assertEquals('create', $registeredRoutes[0]->controller()['methodName']);
       
       
           $WordpressRoutesRegistrator->unregister();
       }
       
    public function test_registers_several_routes()
    {
        (object) $routesRegistrator1 = Wordpress::request()->to('home')
                                                     ->use('StratumTestUsersController.create');
    
        (object) $routesRegistrator2 = Wordpress::request()->to('post')
                                                     ->use('StratumTestUsersController.show');
    
        (object) $routesRegistrator3 = Wordpress::request()->to('page')
                                                     ->use('StratumTestUsersController.create');
    
        (object) $routesRegistrator4 = Wordpress::request()->to('search')
                                                     ->use('StratumTestUsersController.show');
    
        (object) $routesRegistrator = new WordpressRoutesRegistrator;
    
        $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses///EmptyRegister.php');
        
        (array) $registeredRoutes = $routesRegistrator->registeredRoutes();
    
        $this->assertCount(4, $registeredRoutes);
    
        $this->assertEquals('home', $registeredRoutes[0]->sitePage());
        $this->assertEquals('post', $registeredRoutes[1]->sitePage());
        $this->assertEquals('page', $registeredRoutes[2]->sitePage());
        $this->assertEquals('search', $registeredRoutes[3]->sitePage());
    
        $routesRegistrator1->unregister();
        $routesRegistrator2->unregister();
        $routesRegistrator3->unregister();
        $routesRegistrator4->unregister();
    
    
    }

    public function test_default_route_is_always_at_the_end()
    {
        (object) $routesRegistrator1 = Wordpress::request()->to('DefaultView')
                                                     ->use('StratumTestUsersController.create');
    
        (object) $routesRegistrator2 = Wordpress::request()->to('post')
                                                     ->use('StratumTestUsersController.show');
    
        (object) $routesRegistrator3 = Wordpress::request()->to('page')
                                                     ->use('StratumTestUsersController.create');
    
        (object) $routesRegistrator4 = Wordpress::request()->to('search')
                                                     ->use('StratumTestUsersController.show');

        (object) $routesRegistrator5 = Wordpress::request()->to('home')
                                                     ->use('StratumTestUsersController.create');
    
        (object) $routesRegistrator = new WordpressRoutesRegistrator;
    
        $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses///EmptyRegister.php');
        
        (array) $registeredRoutes = $routesRegistrator->registeredRoutes();
    
        $this->assertCount(5, $registeredRoutes);
    
        $this->assertEquals('post', $registeredRoutes[1]->sitePage());
        $this->assertEquals('page', $registeredRoutes[2]->sitePage());
        $this->assertEquals('search', $registeredRoutes[3]->sitePage());
        $this->assertEquals('home', $registeredRoutes[4]->sitePage());
        $this->assertEquals('defaultview', $registeredRoutes[5]->sitePage());
    
        $routesRegistrator1->unregister();
        $routesRegistrator2->unregister();
        $routesRegistrator3->unregister();
        $routesRegistrator4->unregister();
        $routesRegistrator5->unregister();
    
    
    }
























}