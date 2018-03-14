<?php

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Test\Presentation\TestClass\ConcreteManager;
use PHPUnit\Framework\TestCase;

Class ConcreteManagerTest extends TestCase
{
    public function test_calls_task_name_composed_by_1_word_with_specified_arguments()
    {
        (object) $element = $this->createMock(Element::class);

        (object) $ConcreteManager = $this->getMockBuilder(ConcreteManager::class)
                                         ->setMethods(['task'])
                                         ->setConstructorArgs([$element])
                                         ->getMock();

        (string) $argument = 'argument';

        $ConcreteManager->expects($this->once())->method('task')->with($argument);

        $ConcreteManager->setTask('task');
        $ConcreteManager->setTaskArgument($argument);
        $ConcreteManager->executeTask();

    } 

    public function test_calls_task_name_composed_by_2_words_with_specified_arguments()
    {
        (object) $element = $this->createMock(Element::class);

        (object) $ConcreteManager = $this->getMockBuilder(ConcreteManager::class)
                                         ->setMethods(['concreteTask'])
                                         ->setConstructorArgs([$element])
                                         ->getMock();

        (string) $argument = 'argument';

        $ConcreteManager->expects($this->once())->method('concreteTask')->with($argument);

        $ConcreteManager->setTask('concrete-task');
        $ConcreteManager->setTaskArgument($argument);
        $ConcreteManager->executeTask();

    } 

    public function test_calls_task_name_composed_by_3_words_with_specified_arguments()
    {
        (object) $element = $this->createMock(Element::class);

        (object) $ConcreteManager = $this->getMockBuilder(ConcreteManager::class)
                                         ->setMethods(['concreteManagerTask'])
                                         ->setConstructorArgs([$element])
                                         ->getMock();

        (string) $argument = 'argument';

        $ConcreteManager->expects($this->once())->method('concreteManagerTask')->with($argument);

        $ConcreteManager->setTask('concrete-manager-task');
        $ConcreteManager->setTaskArgument($argument);
        $ConcreteManager->executeTask();

    } 

     public function test_calls_task_with_no_arguments()
    {
        (object) $element = $this->createMock(Element::class);

        (object) $ConcreteManager = $this->getMockBuilder(ConcreteManager::class)
                                         ->setMethods(['taskNoArguments'])
                                         ->setConstructorArgs([$element])
                                         ->getMock();


        $ConcreteManager->expects($this->once())->method('taskNoArguments');

        $ConcreteManager->setTask('task-no-arguments');
        $ConcreteManager->executeTask();

    } 

    public function test_verifies_element_is_set_correctly()
    {
        (object) $element = $this->createMock(Element::class);

        $element->expects($this->once())->method('content');

        (object) $ConcreteManager = new ConcreteManager($element);

        $ConcreteManager->setTask('concrete-task');
        $ConcreteManager->setTaskArgument('argument');
        $ConcreteManager->executeTask();



    }   
}