<?php

namespace Stratum\Original\Presentation\Registrator;

use Stratum\Original\Presentation\Exception\UnexistentFormatterClassException;
use Stratum\Original\Presentation\Exception\UnregisteredFormatterNameException;
use Stratum\Original\Presentation\Formatter;


Class FormatterRegistrator extends Registrator
{
    protected static $registeredEntities = [];
    protected static $defaultEntities = [];

    public function registeredFormatters()
    {
        return $this->registeredEntities();
    }

    public function formatterClassFor($formatterMethodName)
    {
        $this->includeRegistrationFile();
        
        $this->throwExceptionIfNoFormatterWithRequestedNameHasBeenRegistered($formatterMethodName);

        return $this->entityClassFor($formatterMethodName);
    }

    public function formatterNameHasBeenRegistered($formatterMethodName)
    {
        return $this->entityExists($formatterMethodName);
    }


    protected function setDefaultRegistrationFilePath()
    {
        $this->registrationFilePath = STRATUM_ROOT_DIRECTORY . '/Design/Present/Formatters/Register.php';
    }

    protected function classNameSpace()
    {
        return 'Stratum\Custom\Formatter';
    }

    protected function UnexistentClassExceptionName()
    {
        return UnexistentFormatterClassException::className();
    }

    protected function requiredParentClassType()
    {
        return Formatter::className();
    }

    protected function entityType()
    {
        return ' Formatter';
    }

    protected function includeRegistrationFile()
    {
        require_once($this->registrationFilePath);
    }

    protected function throwExceptionIfNoFormatterWithRequestedNameHasBeenRegistered($formatterMethodName)
    {
        if (!$this->formatterNameHasBeenRegistered($formatterMethodName)) {
            throw new UnregisteredFormatterNameException("Unexistent formatter with name: $formatterMethodName");
        }
    }



}