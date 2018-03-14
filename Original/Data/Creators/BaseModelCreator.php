<?php

namespace Stratum\Original\Data\Creator;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Original\Data\Exception\UnexistentModelClassException;
use Stratum\Original\Data\Model;

Abstract Class BaseModelCreator
{
    protected $entityType;

    abstract public function create();

    abstract public function setEntityType($fullyQualifiedEntityClassName);

    protected function generateCustomModelClassName()
    {
        (array) $finderSubnamespaces = explode('\\', 
                        substr($this->entityType, strpos($this->entityType, 'Finder\\') + strlen('Finder\\'))
        );

        (integer) $lastElementKey = count($finderSubnamespaces) - 1;

        $finderSubnamespaces[$lastElementKey] = $this->singularize($finderSubnamespaces[$lastElementKey]);

        (string) $modelClassName = 'Stratum\\Custom\\Model\\' . implode('\\', $finderSubnamespaces);

        $this->throwExceptionIfNoModelExistWith($modelClassName);

        return $modelClassName;

    }

    protected function singularize($finderName)
    {
        (boolean) $finderIsNotMeta = strpos(strtolower($finderName), 'meta') === false;

        if ($finderIsNotMeta) {
            return Inflector::singularize($finderName);
        }

        return $finderName;
        
    }

    protected function throwExceptionIfNoModelExistWith($modelClassName)
    {
        if (!class_exists($modelClassName)) {
            throw new UnexistentModelClassException(
                "Entity: {$this->entityType} requires a model class: $modelClassName"
            );
        }

        if (!is_subclass_of($modelClassName, Model::className())) {
            throw new UnexistentModelClassException(
                "Model Class: $modelClassName must extend: " . Model::className()
            );
        }
    }
}