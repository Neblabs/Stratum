<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\Element\Manager;

Class ElementManagersQueueTest extends TestCase
{
    public function test_executes_all_managers_from_queue()
    {
        (object) $firstManager = $this->createMock(Manager::class);
        (object) $secondManager = $this->createMock(Manager::class);
        (object) $thirdManager = $this->createMock(Manager::class);
        (object) $fourthManager = $this->createMock(Manager::class);
        (object) $fifthManager = $this->createMock(Manager::class);

        $firstManager->expects($this->once())->method('executeTask');
        $secondManager->expects($this->once())->method('executeTask');
        $thirdManager->expects($this->once())->method('executeTask');
        $fourthManager->expects($this->once())->method('executeTask');
        $fifthManager->expects($this->once())->method('executeTask');

        (object) $ElementManagersQueue = new ElementManagersQueue;

        $ElementManagersQueue->addManagerToQueue($firstManager);
        $ElementManagersQueue->addManagerToQueue($secondManager);
        $ElementManagersQueue->addManagerToQueue($thirdManager);
        $ElementManagersQueue->addManagerToQueue($fourthManager);
        $ElementManagersQueue->addManagerToQueue($fifthManager);

        (object) $anotherElementManagersQueue = new ElementManagersQueue;

        $anotherElementManagersQueue->executeManagerTasks();
    }

    public function test_clears_all_managers_from_queue()
    {
        (object) $firstManager = $this->createMock(Manager::class);
        (object) $secondManager = $this->createMock(Manager::class);
        (object) $thirdManager = $this->createMock(Manager::class);
        (object) $fourthManager = $this->createMock(Manager::class);
        (object) $fifthManager = $this->createMock(Manager::class);

        $firstManager->expects($this->never())->method('executeTask');
        $secondManager->expects($this->never())->method('executeTask');
        $thirdManager->expects($this->never())->method('executeTask');
        $fourthManager->expects($this->never())->method('executeTask');
        $fifthManager->expects($this->never())->method('executeTask');

        (object) $ElementManagersQueue = new ElementManagersQueue;

        $ElementManagersQueue->addManagerToQueue($firstManager);
        $ElementManagersQueue->addManagerToQueue($secondManager);
        $ElementManagersQueue->addManagerToQueue($thirdManager);
        $ElementManagersQueue->addManagerToQueue($fourthManager);
        $ElementManagersQueue->addManagerToQueue($fifthManager);

        $ElementManagersQueue->clearQueue();

        $ElementManagersQueue->executeManagerTasks();



    }
}