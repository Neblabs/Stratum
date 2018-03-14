<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Formatter\StratumTestFormatter;
use Stratum\Original\Presentation\FormattersHandler;
use Stratum\Original\Presentation\Registrator\FormatterRegistrator;

Class FormattersHandlerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestFormatter.php');
        file_put_contents('Design/Present/Formatters/StratumTestFormatter.php', $TestFinder);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Formatters/StratumTestFormatter.php');
    }

    public function setUp()
    {
        $this->registrationFilePath = STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/emptyRegister.php';
    }

    public function test_runs_formatters()
    {
        $this->registerFormatterNames();                                                                         

        (object) $FormattersHandler = new FormattersHandler('original text');

        $FormattersHandler->setFormatterNames(['inUppercase', 'noWhiteSpace']);

        $this->assertEquals('original text modified 1 time modified 2 times', $FormattersHandler->formatText());

        $this->removeRegisteredFormatterNames();
    }

    protected function registerFormatterNames()
    {


        (object) $FormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');

        $FormatterRegistrator->setMethod('inUppercase');

        $FormatterRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $FormatterRegistrator->register();

        (object) $anotherFormatterRegistrator = new FormatterRegistrator('StratumTestFormatter');

        $anotherFormatterRegistrator->setMethod('noWhiteSpace');

        $anotherFormatterRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $anotherFormatterRegistrator->register();
    }

    protected function removeRegisteredFormatterNames()
    {
        (object) $FormatterRegistrator = new FormatterRegistrator;

        $FormatterRegistrator->remove('inUppercase');

        $FormatterRegistrator->remove('noWhiteSpace');
    }














}