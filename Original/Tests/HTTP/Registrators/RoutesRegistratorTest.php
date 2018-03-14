<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;
use Stratum\Original\HTTP\Route;

Class RoutesRegistratorTest extends TestCase
{
    protected $routesRegistrator;

    public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/TestController.php');
        file_put_contents('Design/Control/Controllers/StratumTestUsersController.php', $TestController);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Controllers/StratumTestUsersController.php');
    }

    public function setUp()
    {
        $this->routesRegistrator = new HTTPRoutesRegistrator;
        $this->routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses/EmptyRegister.php');
    }

    public function test_throws_exception_if_no_http_method_has_been_set_before_calling_register()
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('An HTTP method must be set in order to register a route');

        $this->routesRegistrator->setPath('/welcome');
        $this->routesRegistrator->setController('defaultController.show');

        $this->routesRegistrator->register();
    }

    public function test_throws_exception_if_no_URL_path_has_been_set_before_calling_register()
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('A URL path must be set in order to register a route');

        $this->routesRegistrator->setMethod('POST');
        $this->routesRegistrator->setController('defaultController.show');

        $this->routesRegistrator->register();
    }

    public function test_throws_exception_if_no_controller_has_been_set_before_calling_register()
    {
        $this->expectException(MissingRequiredPropertyException::class);
        $this->expectExceptionMessage('A fully qualified controller name must be set in order to register a route');

        $this->routesRegistrator->setMethod('POST');
        $this->routesRegistrator->setPath('/welcome');

        $this->routesRegistrator->register();
    }

    public function test_registers_and_retreives_route()
    {
        $this->routesRegistrator->setmethod('GET');
        $this->routesRegistrator->setPath('/users/(name | alphabetic)');
        $this->routesRegistrator->setController('StratumTestUsersController.show');

        $this->routesRegistrator->register();

        (object) $registerdRoutes = $this->routesRegistrator->registeredRoutes();
        (object) $route = $registerdRoutes[0];

        $this->assertCount(1, $registerdRoutes);
        $this->assertInstanceOf(HTTPRoute::class, $route);
        $this->assertEquals('GET', $route->method());
        $this->assertEquals('users/(name | alphabetic)', $route->pathDefinition());
        $this->assertEquals('StratumTestUsersController', $route->controller()['className']);
        $this->assertEquals('show', $route->controller()['methodName']);
    }

    public function test_unregisters_the_route_that_the_RoutesRegistrator_object_registered()
    {
        (object) $secondRoutesRegistrator = $this->routesRegistrator;

        $secondRoutesRegistrator->setmethod('GET');
        $secondRoutesRegistrator->setPath('/about-us');
        $secondRoutesRegistrator->setController('StratumTestUsersController.show');

        $secondRoutesRegistrator->register();

        $thirdRoutesRegistrator = new HTTPRoutesRegistrator;

        $thirdRoutesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses/EmptyRegister.php');

        $thirdRoutesRegistrator->setmethod('GET');
        $thirdRoutesRegistrator->setPath('/posts/(id | alphabetic)');
        $thirdRoutesRegistrator->setController('StratumTestUsersController.create');

        $thirdRoutesRegistrator->register();

        (array) $registeredRoutes = $thirdRoutesRegistrator->registeredRoutes();

        $this->assertCount(3, $registeredRoutes);

        $this->assertEquals('users/(name | alphabetic)', $registeredRoutes[0]->pathDefinition());
        $this->assertEquals('about-us', $registeredRoutes[1]->pathDefinition());
        $this->assertEquals('posts/(id | alphabetic)', $registeredRoutes[2]->pathDefinition());

        $secondRoutesRegistrator->unregister();

        (array) $registeredRoutes = $thirdRoutesRegistrator->registeredRoutes();

        $this->assertEquals('users/(name | alphabetic)', $registeredRoutes[0]->pathDefinition());
        $this->assertEquals('posts/(id | alphabetic)', $registeredRoutes[1]->pathDefinition());

    }

    /**
     * @depends test_route_registers_and_retreives_route
     * 
     */
   //public function test_gets_registered_routes_from_a_fresh_RoutesRegistrator_object()
   //{
   //    (object) $anotherRoutesRegistrator = new HTTPRoutesRegistrator;

   //    (object) $registerdRoutes = $anotherRoutesRegistrator->registeredRoutes();
   //    (object) $route = $registerdRoutes[0];

   //    $this->assertCount(1, $registerdRoutes);
   //    $this->assertInstanceOf(Route::class, $route);
   //    $this->assertEquals('GET', $route->method());
   //    $this->assertEquals('users/(name | alphabetic)', $route->pathDefinition());
   //    //$this->assertEquals('UsersController.show', $route->controller());
   //}























}