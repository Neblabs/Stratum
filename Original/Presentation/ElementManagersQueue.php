<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Presentation\Element\Manager;

Class ElementManagersQueue
{
    protected static $queue = [];

    public function addManagerToQueue(Manager $manager)
    {
        static::$queue[] = $manager;
    }

    public function executeManagerTasks()
    {
        foreach (static::$queue as $manager) {
            $manager->executeTask();
        }
    }

    public function clearQueue()
    {
        static::$queue = [];
    }
}