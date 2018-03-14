<?php

namespace Stratum\Original\Presentation\Registrator;

use Stratum\Original\Presentation\Element\Manager;
use Stratum\Original\Presentation\Element\ManagerTaskType;
use Stratum\Original\Presentation\Element\To;
use Stratum\Original\Presentation\Exception\UnexistentElementManagerClassException;
use Stratum\Original\Utility\StringConverter;

Class ManagerTaskRegistrator extends Registrator
{
    protected static $registeredEntities = [];
    protected static $defaultEntities = [
        'showif' => 'Stratum\\Prebuilt\\Manager\\VisibilityManager'
    ];
    protected $managerTaskType;

    public function setTask($taskName)
    {
        (object) $taskName = new StringConverter($taskName);

        $this->setMethod($taskName->removeDashes());
    }

    public function usesEOM()
    {
        $this->managerTaskType->setUsesEOM(true);

        return $this;
    }

    public function toAccessParent(To $accessType)
    {
        $this->managerTaskType->setUsesParent($accessType);

        return $this;
    }

    public function toAccessPreviousSiblings(To $accessType)
    {
        $this->managerTaskType->setUsesPreviousSiblings($accessType);

        return $this;
    }

    public function toAccessNextSiblings(To $accessType)
    {
        $this->managerTaskType->setUsesNextSiblings($accessType);

        return $this;
    }

    public function toAccessChildren(To $accessType)
    {
        $this->managerTaskType->setUsesChildren($accessType);

        return $this;
    }

    public function toAccessDescendants(To $accessType)
    {
        $this->managerTaskType->setUsesDescendants($accessType);

        return $this;
    }

    public function registeredTasks()
    {
        return $this->registeredEntities();
    }

    public function managerTypeFor($taskName)
    {
        return $this->entityClassFor((new StringConverter($taskName))->removeDashes());
    }

    public function managerTaskType()
    {
        return $this->managerTaskType;
    }

    public function taskIsRegistered($taskName)
    {
        return $this->entityExists((new StringConverter($taskName))->removeDashes());
    }

    public function register()
    {
        $this->managerTaskType = new ManagerTaskType($this->fullyQualifiedClassName());

        static::$registeredEntities[$this->methodName] = $this->managerTaskType;
    }

    protected function setDefaultRegistrationFilePath()
    {
        $this->registrationFilePath = STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/Register.php';
    }

    protected function classNameSpace()
    {
        return 'Stratum\Custom\Manager';
    }

    protected function UnexistentClassExceptionName()
    {
        return UnexistentElementManagerClassException::className();
    }

    protected function requiredParentClassType()
    {
        return Manager::className();
    }

    protected function entityType()
    {
        return 'n Element Manager Task';
    }

    protected function includeRegistrationFile()
    {
        require_once($this->registrationFilePath);
    }



}