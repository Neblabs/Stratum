<?php

namespace Stratum\Original\Presentation\Registrator;

use Stratum\Original\Presentation\Exception\ForbiddenRegistrationException;
use Stratum\Original\Presentation\Exception\ForbiddenUnregistrationException;
use Stratum\Original\Presentation\Exception\UnexistentMethodException;

Abstract Class Registrator
{
    protected $className;
    protected $methodName;
    protected $registrationFilePath;

    abstract protected function classNameSpace();
    abstract protected function UnexistentClassExceptionName();
    abstract protected function requiredParentClassType();
    abstract protected function entityType();
    abstract protected function includeRegistrationFile();
    abstract protected function setDefaultRegistrationFilePath();

    

    public function __construct($className = null)
    {
        $this->className = !is_null($className) ? $className : null;
        $this->throwExceptionIfClassDoesNotExistOrDoesNotExtendRequiredType();

        $this->setDefaultRegistrationFilePath();
    }

    public function setRegistrationFilePath($registrationFilePath)
    {
        $this->registrationFilePath = $registrationFilePath;
    }

    public function setMethod($methodName)
    {
        $this->throwExceptionIfEntityIsAlreadyRegistered($methodName);

        $this->throwExceptionIfMethodDoesNotExist($methodName);

        $this->methodName = strtolower($methodName);
    }

    public function register()
    {
        static::$registeredEntities[$this->methodName] = $this->fullyQualifiedClassName();
    }

    public function registeredEntities()
    {
        $this->includeRegistrationFile();

        return array_merge(static::$registeredEntities, static::$defaultEntities);
    }

    public function entityClassFor($methodName)
    {
        return $this->registeredEntities()[strtolower($methodName)];
    }

    public function entityExists($methodName)
    {
        (array) $registeredEntities = array_merge(static::$registeredEntities, static::$defaultEntities);

        (boolean) $methodNameExistsInRegisteredEntitiesArray = isset($registeredEntities[strtolower($methodName)]);

        return $methodNameExistsInRegisteredEntitiesArray;
    }

    public function remove($methodName)
    {
        $methodName = strtolower($methodName);

        if ($this->entityExists($methodName)) {

            $this->throwExceptionIfAttemptingToUnregisterADefaultEntity($methodName);

            unset(static::$registeredEntities[$methodName]);
        }
    }

    public function removeAll()
    {
        static::$registeredEntities = [];
    }

    protected function throwExceptionIfClassDoesNotExistOrDoesNotExtendRequiredType()
    {
        if (!is_null($this->className)) {

            (string) $UnexistentClassException = $this->UnexistentClassExceptionName();
    
            if (!class_exists($this->fullyQualifiedClassName())) {
                throw new $UnexistentClassException("Unexistent class: {$this->fullyQualifiedClassName()}");
            } 
            if (!is_subclass_of($this->fullyQualifiedClassName(), $this->requiredParentClassType())) {
                throw new $UnexistentClassException(
                    "Class: {$this->fullyQualifiedClassName()} must extend {$this->requiredParentClassType()}"
                );
            }
        }
    }

    protected function throwExceptionIfEntityIsAlreadyRegistered($methodName)
    {
        if ($this->entityExists($methodName)) {
            throw new ForbiddenRegistrationException(
                "Cannot Register $methodName, a{$this->entityType()} with the same name has already been registered"
            );
        }
    }

    protected function throwExceptionIfMethodDoesNotExist($methodName)
    {
        if (!method_exists($this->fullyQualifiedClassName(), $methodName)) {
            throw new UnexistentMethodException(
                "Class {$this->fullyQualifiedClassName()} must containt method: $methodName"
            );
        }
    }

    protected function throwExceptionIfAttemptingToUnregisterADefaultEntity($methodName)
    {
        (boolean) $methodExistsInDefaultEntitiesArray = isset(static::$defaultEntities[$methodName]);

       if ($methodExistsInDefaultEntitiesArray) {
            throw new ForbiddenUnregistrationException(
                "Cannot Unregister $methodName because it is a default". substr($this->entityType(), 1)
            );
        } 
    }

    protected function fullyQualifiedClassName()
    {
        return "{$this->classNameSpace()}\\$this->className";
    }





}