<?php

namespace Stratum\Original\Test\Presentation\TestClass;

use Stratum\Original\Presentation\Element\Manager;

Class ConcreteManager extends Manager
{

    public function task($argument)
    {   
        $this->element->content();
    }

    public function taskNoArguments()
    {   
        $this->element->content();
    }

    public function concreteTask($argument)
    {
        $this->element->content();
    }

    public function concreteManagerTask($argument)
    {
        $this->element->content();
    }
}