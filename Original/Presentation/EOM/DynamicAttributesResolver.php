<?php

namespace Stratum\Original\Presentation\EOM;

use Stratum\Original\Data\DynamicMethodResolver;
use BadMethodCallException;

Class DynamicAttributesResolver extends DynamicMethodResolver
{
    protected $attributeName;
    protected $isHasMethod = false;
    protected $isSetMethod = false;
    protected $isAddMethod = false;
    protected $isRemoveMethod = false;
    protected $isWithMethod = false;

    protected function generateFieldName()
    {
        if ($this->methodNameStartsWith('has')) {
            $this->isHasMethod = true;
            $this->attributeName = $this->removeWordFromMethodName('has');
        } elseif ($this->methodNameStartsWith('set')) {
            $this->isSetMethod = true;
            $this->attributeName = $this->removeWordFromMethodName('set');
        } elseif ($this->methodNameStartsWith('add')) {
            $this->isAddMethod = true;
            $this->attributeName = $this->removeWordFromMethodName('add');
        } elseif ($this->methodNameStartsWith('remove')) {
            $this->isRemoveMethod = true;
            $this->attributeName = $this->removeWordFromMethodName('remove');
        } elseif ($this->methodNameStartsWith('with')) {
            $this->isWithMethod = true;
            $this->attributeName = $this->removeWordFromMethodName('with');
        } else {
            throw new BadMethodCallException("Call to undefinded method: {$this->methodName}()");
        }
    }

    public function attributeName()
    {
        return lcfirst($this->attributeName);
    }

    public function isHas()
    {
        return $this->isHasMethod;
    }

    public function __call($method, $arguments)
    {
        return $this->___isSet();
    }

    protected function ___isSet()
    {
        return $this->isSetMethod;
    }

    public function isAdd()
    {
        return $this->isAddMethod;
    }

    public function isRemove()
    {
        return $this->isRemoveMethod;
    }

    public function isWith()
    {
        return $this->isWithMethod;
    }











}