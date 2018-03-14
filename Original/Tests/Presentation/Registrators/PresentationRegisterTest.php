<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Formatter\StratumTestFormatter;
use Stratum\Custom\Manager\StratumTestManager;
use Stratum\Original\Presentation\Registrator\FormatterRegistrator;
use Stratum\Original\Presentation\Registrator\ManagerTaskRegistrator;
use Stratum\Original\Presentation\Registrator\Register;

Class PresentationRegisterTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestManager.php');
        file_put_contents('Design/Present/Managers/StratumTestManager.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/StratumTestManager.php');
    }

    public function test_registers_a_new_manager_task()
    {
        (array) $expectedArrayOfRegisteredTasks = [
            'showif' => 'Stratum\\Prebuilt\\Manager\\VisibilityManager',
            'testtask' => StratumTestManager::class
        ];

        Register::manager('StratumTestManager')->task('test-task');

        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/emptyRegister.php');
    
        $this->assertEquals($expectedArrayOfRegisteredTasks, $ManagerTaskRegistrator->registeredTasks());

        $ManagerTaskRegistrator->remove('testtask');
    }

    public function test_registers_a_new_formatter()
    {
        (array) $expectedArrayOfRegisteredFormatters = [
            'inuppercase' => StratumTestFormatter::class
        ];

        Register::formatter('StratumTestFormatter')->name('inUpperCase');

        (object) $FormatterRegistrator = new FormatterRegistrator;

        $FormatterRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/emptyRegister.php');
    
        $this->assertEquals($expectedArrayOfRegisteredFormatters, $FormatterRegistrator->registeredFormatters());

        $FormatterRegistrator->remove('inuppercase');
    }






}