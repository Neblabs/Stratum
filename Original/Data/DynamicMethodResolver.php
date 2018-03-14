<?php

namespace Stratum\Original\Data;

Abstract Class DynamicMethodResolver
{
    protected $methodName;

    abstract protected function generateFieldName();

    public function __construct($methodName)
    {
        $this->methodName = $methodName;

        $this->generateFieldName();
    }

    protected function methodNameStartsWith($word)
    {
        (boolean) $doesTheMethodNameStartsWithRequestedWord = strpos($this->methodName, $word) === 0;

        return $doesTheMethodNameStartsWithRequestedWord;
    }

    protected function removeWordFromMethodName($word)
    {
        (string) $methodNameWithoutRequestedWord = substr($this->methodName, strlen($word));

        return $methodNameWithoutRequestedWord;
    }
}