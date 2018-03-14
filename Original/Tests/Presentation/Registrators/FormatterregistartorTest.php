<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Formatter\StratumTestFormatter;
use Stratum\Original\Presentation\Exception\UnexistentFormatterClassException;
use Stratum\Original\Presentation\Exception\UnexistentMethodException;
use Stratum\Original\Presentation\Exception\UnregisteredFormatterNameException;
use Stratum\Original\Presentation\Formatter;
use Stratum\Original\Presentation\Registrator\FormatterRegistrator;


Class FormatterRegistartorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestFormatter.php');
        file_put_contents('Design/Present/Formatters/StratumTestFormatter.php', $TestFinder);
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestFakeFormatter.php');
        file_put_contents('Design/Present/Formatters/StratumTestFakeFormatter.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Formatters/StratumTestFormatter.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Formatters/StratumTestFakeFormatter.php');
    }

    public function setUp()
    {
        $this->registrationFilePath = STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/emptyRegister.php';
    }

    public function test_throws_exception_if_formatter_class_does_not_exist()
    {
        $this->expectException(UnexistentFormatterClassException::className());
        $this->expectExceptionMessage("Unexistent class: Stratum\Custom\Formatter\Unexistentformatter");

        (object) $FormatterRegistrator = new FormatterRegistrator('Unexistentformatter');
    }

    public function test_throws_exception_if_formatter_class_does_not_extend_formatter()
    {
        $this->expectException(UnexistentFormatterClassException::className());
        $this->expectExceptionMessage(
            "Class: Stratum\Custom\Formatter\StratumTestFakeFormatter must extend " . Formatter::class
        );

        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFakeFormatter');
    }

    
    public function test_throws_exception_if_formatter_method_does_not_exist()
    {
        $this->expectException(UnexistentMethodException::class);
    
        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');
    
        $FormatterRegistrator->setMethod('Unexistentmethodname');
    
    }
    
    public function test_registers_and_gets_registered_formatters()
    {
        (array) $expectedArrayOfRegisteredFormatters = [
            'inuppercase' => StratumTestFormatter::class
        ];
        
        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');
    
        $FormatterRegistrator->setMethod('inUpperCase');
    
        $FormatterRegistrator->register();
    
        $formatters = new FormatterRegistrator;
    
        $formatters->setRegistrationFilePath($this->registrationFilePath);
    
        $this->assertEquals($expectedArrayOfRegisteredFormatters, $formatters->registeredFormatters());

    
    }
    
    public function test_returns_true_when_checking_for_a_registered_formatter()
    {
        
        (object) $FormatterRegistrator = new FormatterRegistrator;
    
        $FormatterRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $this->assertTrue($FormatterRegistrator->formatterNameHasBeenRegistered('inUpperCase'));
        $this->assertFalse($FormatterRegistrator->formatterNameHasBeenRegistered('unexistentFormatterMethod'));

        $FormatterRegistrator->remove('inuppercase');
    }

    public function test_returns_formatter_class_name_for_a_registered_formatter_name()
    {
        
        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');
    
        $FormatterRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $FormatterRegistrator->setMethod('inUpperCase');
    
        $FormatterRegistrator->register();

        $this->assertEquals(StratumTestFormatter::class, $FormatterRegistrator->formatterClassFor('inuppercase'));

        $FormatterRegistrator->remove('inuppercase');
    }

    public function test_returns_formatter_class_name_for_a_registered_formatter_name_case_insensitive()
    {
        
        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');
    
        $FormatterRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $FormatterRegistrator->setMethod('inUpperCase');
    
        $FormatterRegistrator->register();

        $this->assertEquals(StratumTestFormatter::class, $FormatterRegistrator->formatterClassFor('INuppERCase'));

        $FormatterRegistrator->remove('inuppercase');
    }

    public function test_throws_exception_if_formatter_name_does_not_exist()
    {
        $this->expectException(UnregisteredFormatterNameException::class);
        $this->expectExceptionMessage("Unexistent formatter with name: unexistent");

        (object) $FormatterRegistrator = new FormatterRegistrator();

        $FormatterRegistrator->formatterClassFor('unexistent');
    }

    



}