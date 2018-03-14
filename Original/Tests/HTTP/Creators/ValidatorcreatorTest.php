<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Validator\ConcreteValidatorTest55;
use Stratum\Original\HTTP\Creator\ValidatorCreator;
use Stratum\Original\HTTP\Dispatcher;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Response\Dump;
use Stratum\Original\HTTP\Response\HTML;
use Stratum\Original\HTTP\Response\JSON;
use Stratum\Original\HTTP\Response\Redirection;
use Stratum\Original\HTTP\Response\Text;
use Stratum\Original\HTTP\URLData;

Class ValidatorCreatorTest extends TestCase
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
    
    public function setUp()
    {
        $this->ValidatorCreator = new ValidatorCreator(
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

    public function test_creates_an_instance_of_a_custom_Validator()
    {
        $this->ValidatorCreator->setClassName('ConcreteValidatorTest55');
        $this->ValidatorCreator->setMethodName('passingValidator');

        (object) $createdValidator = $this->ValidatorCreator->create();

        $this->assertInstanceOf(ConcreteValidatorTest55::class, $createdValidator);

        $createdValidator->validate();

        $this->assertTrue($createdValidator->hasPassed());
    }
















}