<?php

namespace Stratum\Original\Utility\ClassUtility;

Trait ClassName
{
    public static function className() 
    {
        return __CLASS__;
    }

    public function singleClassName()
    {
        (string) $fullyQualifiedClassName = get_class($this);
        (array) $classNamespacesAndName = explode('\\', $fullyQualifiedClassName);
        (string) $singleClassName = $classNamespacesAndName[count($classNamespacesAndName) - 1];

        return $singleClassName;
    }

    public function fullyQualifiedClassName()
    {
        return get_class($this);
    }
}