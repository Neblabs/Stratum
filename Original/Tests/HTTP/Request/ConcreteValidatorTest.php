<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Validator\ConcreteValidatorTest55;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Exception\MissingActionException;
use Stratum\Original\HTTP\Exception\UnsupportedResponseTypeException;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\Json;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Class ConcreteValidatorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestValidator = file_get_contents('Original/Tests/HTTP/TestClasses/ConcreteValidator.php');
        file_put_contents('Design/Control/Validators/ConcreteValidatorTest55.php', $TestValidator);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Control/Validators/ConcreteValidatorTest55.php');
    }

    public function test_validator_passes_when_it_should()
    {
        (object) $concreteValidator = new ConcreteValidatorTest55(
            'passingValidator',
            $this->createMock(Request::class),
            new URLData(['id' => 5534]),    
            $this->createMock(HTML::class) ,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );

        $concreteValidator->validate();

        $this->assertTrue($concreteValidator->hasPassed());
    }

    public function test_validator_fails_when_it_should_and_returns_a_dispatcher_object()
    {
        (object) $Dispatcher = $this->createMock(Dispatcher::class);

        (object) $concreteValidator = new ConcreteValidatorTest55(
            'failingValidator',
            $this->createMock(Request::class),
            new URLData(['id' => 5534]),    
            $this->createMock(HTML::class) ,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $Dispatcher
        );

        $Dispatcher->expects($this->once())
                    ->method('controller')
                    ->will($this->returnSelf());
        
        $concreteValidator->validate();
        
        (object) $returnedDispatcher  = $concreteValidator->dispatcher();

        $this->assertFalse($concreteValidator->hasPassed());
        $this->assertTrue($concreteValidator->hasFailed());

        $this->assertSame($Dispatcher, $returnedDispatcher);
    }

    public function test_throws_exception_when_a_validator_doesnt_call_passed_or_failed()
    {
        $this->expectException(MissingActionException::class);

        (object) $concreteValidator = new ConcreteValidatorTest55(
            'nullValidator',
            $this->createMock(Request::class),
            new URLData(['id' => 5534]),    
            $this->createMock(HTML::class) ,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );

        $concreteValidator->validate();

    }

    public function test_throws_exception_when_validators_fails_but_returns_no_dispatcher_object()
    {
        $this->expectException(UnsupportedResponseTypeException::class);

        (object) $concreteValidator = new ConcreteValidatorTest55(
            'wrongFailingValidator',
            $this->createMock(Request::class),
            new URLData(['id' => 5534]),    
            $this->createMock(HTML::class) ,
            $this->createMock(Redirection::class),
            $this->createMock(Text::class),
            $this->createMock(Json::class),  
            $this->createMock(Dump::class),
            $this->createMock(Dispatcher::class)
        );


        $concreteValidator->validate();

    }
    













}