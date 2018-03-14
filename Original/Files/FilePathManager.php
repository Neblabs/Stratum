<?php

namespace Stratum\Original\Files;

Class FilePathManager
{
    private $originalPath;

    public function __construct($filePath)
    {
        $this->originalPath = $filePath;
    }

    public function pathFullyCapitalized($isClassName = false)
    {
        (array) $pathSeparator = $isClassName? '\\' : DIRECTORY_SEPARATOR;
        (array) $oldParts = explode($pathSeparator, $this->originalPath);
        (array) $newParts = [];

        foreach ($oldParts as $part) {
            $newParts[] = ucfirst($part);
        }

        return implode($pathSeparator, $newParts);
    }
}