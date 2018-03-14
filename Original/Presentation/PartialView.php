<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Files\FilePathManager;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class PartialView
{
    use ClassName;
    
    protected $htmlPath;
    protected $compiledPath;
    protected $variables = [];

    public function __construct(array $inheretedVariables = [])
    {
        $this->variables = $inheretedVariables;
    }

    public function from($htmlPath)
    {
        (object) $pathManager = new FilePathManager($htmlPath);
        $this->htmlPath = $pathManager->pathFullyCapitalized();

        return $this;
    }

    public function with(Array $variables)
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this;
    }

    public function elements()
    {
        return $this->requireCompiledFile();
    }

    protected function requireCompiledFile()
    {
        if ($this->hasVariables()) {
            
            extract($this->variables);
        }

        return require $this->compiledVersionOfHTMLFile();
    }

    protected function compiledVersionOfHTMLFile()
    {
        return STRATUM_ROOT_DIRECTORY . "/Storage/CompiledEOM/{$this->pathWithPHPExtension()}";
    }

    protected function pathWithPHPExtension()
    {
        return ucfirst(substr_replace($this->htmlPath, '.php', strpos($this->htmlPath, '.html')));
    }

    protected function hasVariables()
    {
        return $this->variables !== null;
    }





}