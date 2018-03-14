<?php

namespace Stratum\Original\Presentation\Element;

use Stratum\Original\Presentation\EOM;
use Stratum\Original\Utility\ClassUtility\ClassName;
use Stratum\Original\Utility\StringConverter;

Abstract Class Manager
{
    use ClassName;
    
    protected $element;
    protected $taskMethod;
    protected $taskArgument = [];
    protected $taskType;
    protected $variables = [];

    public function __construct(EOM\Element $element)
    {
        $this->element = $element;
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

    public function setTask($taskMethod)
    {
        $this->taskMethod = (new StringConverter($taskMethod))->removeDashes();
    }

    public function setTaskArgument($taskArgument)
    {
        $this->taskArgument[] = $taskArgument;
    }

    public function setTaskType(ManagerTaskType $managerTaskType)
    {
        $this->taskType = $managerTaskType;
    }

    public function executeTask()
    {
        call_user_func_array([$this, $this->taskMethod], $this->taskArgument);
    }

}
