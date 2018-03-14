<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Exception\UnexistentFinderClassException;
use Stratum\Original\Files\FilePathManager;

Class FinderCreator
{
    protected $finderClass;

    public function setEntityType($finderFullyQualifiedClass)
    {
        $this->finderClass = (new FilePathManager($finderFullyQualifiedClass))->pathFullyCapitalized(true);
    }

    public function create()
    {
        $this->throwExceptionIfNoFinderClassExists();

        return new $this->finderClass;
    }

    protected function throwExceptionIfNoFinderClassExists()
    {
        if (!class_exists($this->finderClass)) {
            throw new UnexistentFinderClassException($this->finderClass);
        }
    }
}