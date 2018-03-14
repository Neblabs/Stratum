<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Exception\UnexistentClassException;
use Stratum\Original\HTTP\Exception\UnexistentMethodException;
use Stratum\Original\HTTP\Exception\UnsupportedMethodException;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Route;

Class RouteTest extends TestCase
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

    public function setUp()
    {
        $this->route = new HTTPRoute;
    }

    public function test_sets_and_reterives_route_data()
    {
        $this->route->setMethod('POST');
        $this->route->setPathDefinition('posts/(id | lenght: 10)/new');
        $this->route->addValidator('StratumTestUsersValidator.create');
        $this->route->addValidator('StratumTestUsersValidator.exists');
        $this->route->setController('StratumTestUsersController.create');

        $this->assertEquals('POST', $this->route->method());
        $this->assertEquals('posts/(id | lenght: 10)/new', $this->route->pathDefinition());

        $this->assertCount(2, $this->route->validators());

        $this->assertEquals('StratumTestUsersValidator', $this->route->validators()[0]['className']);
        $this->assertEquals('create', $this->route->validators()[0]['methodName']);

        $this->assertEquals('StratumTestUsersValidator', $this->route->validators()[1]['className']);
        $this->assertEquals('exists', $this->route->validators()[1]['methodName']);

        $this->assertEquals('StratumTestUsersController', $this->route->Controller()['className']);
        $this->assertEquals('create', $this->route->Controller()['methodName']);



    }

    public function test_path_definition_has_no_slashes_at_the_beginnig_and_at_the_end()
    {
        $this->route->setPathDefinition('/users/new/');

        $this->assertEquals('users/new', $this->route->pathDefinition());
    }

    public function test_always_returns_case_sensitive_path_with_no_whitespace()
    {
        $this->route->setPathDefinition('  /Users/RafA/CreaTe/    ');

        $this->assertEquals('Users/RafA/CreaTe', $this->route->pathDefinition());
    }

    public function test_leaves_the_single_slash_as_the_path_if_path_to_set_is_a_single_slash()
    {
        $this->route->setPathDefinition('/');

        $this->assertEquals('/', $this->route->pathDefinition());
    }

    public function test_throws_exception_if_no_controller_class_exists()
    {
        $this->expectException(UnexistentClassException::class);
        $this->expectExceptionMessage('No controller class was found for Stratum\Custom\Controller\StratumUnexistentController5572');

        $this->route->setController('StratumUnexistentController5572.list');
    }

    public function test_throws_exception_when_controller_class_exists_but_its_method_does_not()
    {
        $this->expectException(UnexistentMethodException::class);
        $this->expectExceptionMessage('The method none() was not found in Stratum\Custom\Controller\StratumTestUsersController');

        $this->route->setController('StratumTestUsersController.none');
    }

    public function test_throws_exception_if_no_validator_class_exists()
    {
        $this->expectException(UnexistentClassException::class);
        $this->expectExceptionMessage('No validator class was found for Stratum\Custom\Validator\StratumTestUnexistentValidator');

        $this->route->addValidator('StratumTestUnexistentValidator.create');
    }

    public function test_throws_exception_when_validator_class_exists_but_its_method_does_not()
    {
        $this->expectException(UnexistentMethodException::class);
        $this->expectExceptionMessage('The method oops() was not found in Stratum\Custom\Validator\StratumTestUsersValidator');

        $this->route->addValidator('StratumTestUsersValidator.oops');
    }

    public function test_throws_exception_if_trying_to_set_an_unsuported_http_method_which_is_any_method_other_than_GET_or_POST()
    {
        $this->expectException(UnsupportedMethodException::class);
        $this->expectExceptionMessage('Only GET and POST are the supported HTTP methods');

        $this->route->setMethod('PATCH');
    }















}