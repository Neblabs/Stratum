<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Manager\StratumTestManager;
use Stratum\Original\Presentation\Element\Manager;
use Stratum\Original\Presentation\Exception\ForbiddenRegistrationException;
use Stratum\Original\Presentation\Exception\ForbiddenUnregistrationException;
use Stratum\Original\Presentation\Exception\UnexistentElementManagerClassException;
use Stratum\Original\Presentation\Exception\UnexistentManagerTaskMethodException;
use Stratum\Original\Presentation\Exception\UnexistentMethodException;
use Stratum\Original\Presentation\Registrator\ManagerTaskRegistrator;

Class ManagerTaskRegistartorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestManager.php');
        file_put_contents('Design/Present/Managers/StratumTestManager.php', $TestFinder);
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestFakeManager.php');
        file_put_contents('Design/Present/Managers/StratumTestFakeManager.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/StratumTestManager.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/StratumTestFakeManager.php');
    }

    public function setUp()
    {
        $this->registrationFilePath = STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/emptyRegister.php';
    }

    public function tearDown()
    {
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        //clear the globally registered task for next tests
        $ManagerTaskRegistrator->remove('testtask');
    }

    public function test_thorws_exception_if_manager_class_does_not_exist()
    {
        $this->expectException(UnexistentElementManagerClassException::className());
        $this->expectExceptionMessage("Unexistent class: Stratum\Custom\Manager\UnexistentManager");

        (object) $ManagerTaskRegistartor = new ManagerTaskRegistrator('UnexistentManager');
    }

    public function test_thorws_exception_if_manager_class_does_not_extend_Manager()
    {
        $this->expectException(UnexistentElementManagerClassException::className());
        $this->expectExceptionMessage(
            "Class: Stratum\Custom\Manager\StratumTestFakeManager must extend " . Manager::class
        );

        (object) $ManagerTaskRegistartor = new ManagerTaskRegistrator('StratumTestFakeManager');
    }

    public function test_registration_file_gets_included()
    {
        $this->expectOutPutString('All Element Manager Tasks Registered!');

        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->setRegistrationFilePath(STRATUM_ROOT_DIRECTORY . '/Original/Tests/Presentation/TestClasses/ShouterRegistrationFile.php');

        $ManagerTaskRegistrator->registeredTasks();

    }

    public function test_gets_registered_manager_tasks()
    {
        (array) $expectedArrayOfRegisteredTasks = [
            'showif' => 'Stratum\\Prebuilt\\Manager\\VisibilityManager'
        ];
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $this->assertEquals($expectedArrayOfRegisteredTasks, $ManagerTaskRegistrator->registeredTasks());
    }

    public function test_throws_exception_if_manager_task_method_does_not_exist()
    {
        $this->expectException(UnexistentMethodException::class);

        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');

        $ManagerTaskRegistrator->setTask('Unexistent-method-name');

    }

    public function test_registers_and_gets_registered_manager_tasks()
    {
        (array) $expectedArrayOfRegisteredTasks = [
            'showif' => 'Stratum\\Prebuilt\\Manager\\VisibilityManager',
            'testtask' => StratumTestManager::class
        ];
        
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');

        $ManagerTaskRegistrator->setTask('test-task');
    
        $ManagerTaskRegistrator->register();

        $tasks = new ManagerTaskRegistrator;

        $tasks->setRegistrationFilePath($this->registrationFilePath);

        $this->assertEquals($expectedArrayOfRegisteredTasks, $tasks->registeredTasks());

    }

    public function test_gets_registered_task_manager_class_name_prebuil_manager_task()
    {
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $this->assertEquals('Stratum\\Prebuilt\\Manager\\VisibilityManager', $ManagerTaskRegistrator->managerClassFor('show-if'));
    }

    public function test_gets_registered_task_manager_class_name_custom_manager_task()
    {
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');

        $ManagerTaskRegistrator->setTask('test-task');
    
        $ManagerTaskRegistrator->register();

        (object) $anotherManagerTaskRegistrator = new ManagerTaskRegistrator;

        $anotherManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $this->assertEquals('Stratum\\Custom\\Manager\\StratumTestManager', $anotherManagerTaskRegistrator->managerClassFor('test-task'));
    }

    public function test_returns_true_when_checking_for_a_registered_task()
    {
        
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);

        $this->assertTrue($ManagerTaskRegistrator->taskIsRegistered('show-if'));
        $this->assertFalse($ManagerTaskRegistrator->taskIsRegistered('unexistent-task'));
    }

    public function test_throws_exception_if_attempting_to_register_an_exisiting_task__prebuilt_task()
    {
        $this->expectException(ForbiddenRegistrationException::class);
        $this->expectExceptionMessage(
            "Cannot Register showif, an Element Manager Task with the same name has already been registered"
        );
    
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');
    
        $ManagerTaskRegistrator->setTask('show-if');
    
    }

    public function test_throws_exception_if_attempting_to_register_an_exisiting_task__custom_task()
    {
        $this->expectException(ForbiddenRegistrationException::class);
        $this->expectExceptionMessage(
            "Cannot Register testtask, an Element Manager Task with the same name has already been registered"
        );
    
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');

        $ManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);
    
        $ManagerTaskRegistrator->setTask('test-task');

        $ManagerTaskRegistrator->register();

        (object) $anotherManagerTaskRegistrator = new ManagerTaskRegistrator('StratumTestManager');

        $anotherManagerTaskRegistrator->setRegistrationFilePath($this->registrationFilePath);
        
        $anotherManagerTaskRegistrator->setTask('test-task');
        
        $anotherManagerTaskRegistrator->register();
    
    }

    public function test_throws_exception_if_attempting_to_unregister_a_default_manager_task()
    {
        $this->expectException(ForbiddenUnregistrationException::class);
        $this->expectExceptionMessage(
            "Cannot Unregister showif because it is a default Element Manager Task"
        );
    
        (object) $ManagerTaskRegistrator = new ManagerTaskRegistrator;

        $ManagerTaskRegistrator->remove('showif');
    
    }









}