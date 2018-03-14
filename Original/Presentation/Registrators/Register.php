<?php

namespace Stratum\Original\Presentation\Registrator;

use Stratum\Original\Presentation\Registrator\FormatterRegistrator;

Class Register
{
    protected $managerTaskRegistrator;
    protected $formatterRegistrator;

    public static function manager($managerClassName)
    {
        (object) $register =  new Static;

        $register->managerTaskRegistrator = new ManagerTaskRegistrator($managerClassName);

        return $register;
    }

    public static function formatter($formatterClassName)
    {
        (object) $register = new Static;

        $register->formatterRegistrator = new FormatterRegistrator($formatterClassName);

        return $register;
    }

    public function name($FormatterMethodName)
    {
        $this->formatterRegistrator->setMethod($FormatterMethodName);
        $this->formatterRegistrator->register();
    }

    public function task($taskName)
    {
        $this->managerTaskRegistrator->setTask($taskName);
        $this->managerTaskRegistrator->register();

        return $this->managerTaskRegistrator;
    }
}