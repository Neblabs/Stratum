<?php

namespace Stratum\Original\Presentation\Compiler;

Abstract Class Compiler
{
    protected $elementIsInsideForeach = false;
    protected $ancestorElementIsInsideForeach = false;
    protected $aliasedVariableForForeach;
    protected $parentCompiler;

    abstract public function compiledType();

    public function setParentCompiler(Compiler $parentCompiler)
    {
        $this->parentCompiler = $parentCompiler;
    }

    public function elementIsInsideForeach()
    {
        return $this->elementIsInsideForeach;
    }

    public function aliasedVariableForForeach()
    {
        return $this->aliasedVariableForForeach;
    }
}