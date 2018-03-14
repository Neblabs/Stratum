<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\POST;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;

Class POSTTest extends TestCase
{
    public function test_registers_a_route()
    {
        (object) $routesRegistrator1 = POST::request()->to('users/new')
                                                      ->validateWith('StratumTestUsersValidator.create')
                                                      ->use('StratumTestUsersController.create');

        (object) $routesRegistrator = new HTTPRoutesRegistrator;

        $routesRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/HTTP/TestClasses/EmptyRegister.php');

        (array) $registeredRoutes = $routesRegistrator->registeredRoutes();

        $this->assertCount(1, $registeredRoutes);

        $this->assertEquals('POST', $registeredRoutes[0]->method());
        $this->assertEquals('users/new', $registeredRoutes[0]->pathDefinition());

        $this->assertEquals('StratumTestUsersValidator', $registeredRoutes[0]->validators()[0]['className']);
        $this->assertEquals('create', $registeredRoutes[0]->validators()[0]['methodName']);

        $this->assertEquals('StratumTestUsersController', $registeredRoutes[0]->controller()['className']);
        $this->assertEquals('create', $registeredRoutes[0]->controller()['methodName']);

        $routesRegistrator1->unregister();
    }
}
