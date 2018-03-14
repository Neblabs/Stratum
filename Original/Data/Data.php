<?php

namespace Stratum\Original\Data;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class Data
{
    use ClassName;

    protected $aliases = [];

    public function setAliases(array $aliases)
    {
        $this->aliases = $aliases;
    }

    public function aliases()
    {
        return $this->aliases;
    }
    
    public function hasProperty($property)
    {
        return isset($this->$property);
    }

    public function count()
    {
        return $this->numberOfPublicProperties();
    }

    public function isEmpty()
    {
        return $this->numberOfPublicProperties() === 0;
    }

    public function __get($property)
    {
        if ($this->aliasExistFor($property)) {
            return $this->aliased($property); 
        } 

        return null;
    }

    public function __set($property, $value)
    {
        if ($this->aliasExistFor($property)) {
            (string) $alias = $this->aliases[$property];
            $this->$alias = $value;
        } else {
            $this->$property = $value;
        }
    }

    protected function aliasExistFor($property)
    {
        (boolean) $propertyExistsInAliasesArray = isset($this->aliases[$property]);

        return $propertyExistsInAliasesArray;
    }

    protected function aliased($property)
    {
        return $this->{$this->aliases[$property]};
    }

    protected function numberOfPublicProperties()
    {
        (array) $publicProperties = get_object_vars($this);
        (integer) $protectedProperties = 1;

        return count($publicProperties) - $protectedProperties;
    }












}