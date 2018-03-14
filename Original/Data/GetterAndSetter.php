<?php

namespace Stratum\Original\Data;


Abstract Class GetterAndSetter
{
    protected $data;

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public function hasGetterFor($property)
    {
        (string) $getterMethod = "get$property";

        return method_exists($this, $getterMethod);
    }

    public function get($property)
    {
        (string) $getterMethod = "get$property";

        return $this->$getterMethod();
    }

    public function hasSetterFor($property)
    {
        (string) $setterMethod = "set$property";

        return method_exists($this, $setterMethod);
    }

    public function set($property, $value)
    {
        (string) $setterMethod = "set$property";

        return $this->$setterMethod($value);
    }

    public function __get($property)
    {
        if ($this->hasGetterFor($property)) {

            return $this->get($property);
        }

        
        
        return $this->data->$property;
    }

    public function __set($property, $value)
    {
        if ($this->hasSetterFor($property)) {
            $this->set($property);
        } else {
            $this->data->$property = $value;
        }
    }
}