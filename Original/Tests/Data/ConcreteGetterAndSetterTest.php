<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Test\Data\TestClass\ConcreteGetterAndSetter;

Class ConcreteGetterAndSetterTest extends TestCase
{
    public function setUp()
    {
        $this->getter = new ConcreteGetterAndSetter;
    }
    public function test_returns_true()
    {
        $this->assertTrue($this->getter->hasGetterFor('title'));
    }

    public function test_returns_false()
    {
        $this->assertFalse($this->getter->hasGetterFor('Unexistent'));
    }

    public function test_gets_value_from_getter_method()
    {
        $this->assertEquals('filtered title', $this->getter->get('title'));
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