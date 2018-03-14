<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Files\FilePathManager;

Class EOMCompilerWriter
{
    protected $path;
    protected $EOMCompiler;

    public function __construct($htmlFilePath)
    {
        (object) $filePathManager = new FilePathManager($htmlFilePath);
        $this->path = $filePathManager->pathFullyCapitalized();
        $this->EOMCompiler = new EOMCompiler($this->HTMLfileContents(), $this->isMasterView());
    }

    public function writeCompiledEOMObjectsToDisk()
    {
        $this->writeToFile($this->EOMCompiler->compiledPHPEOM());
    }

    protected function isMasterView()
    {
        return preg_match('/([a-zA-Z0-9]\/)*([Mm])aster\.html/', $this->path);
    }

    protected function HTMLfileContents()
    {
        return file_get_contents(STRATUM_ROOT_DIRECTORY . "/Design/Present/Views/{$this->path}");
    }

    protected function writeToFile()
    {
        $this->createDirectoryIfItDoesNotExist();

        file_put_contents(STRATUM_ROOT_DIRECTORY . "/Storage/CompiledEOM/{$this->pathWithPHPExtension()}", $this->EOMCompiler->compiledPHPEOM());

        if ($this->isMasterView()) {
            file_put_contents(STRATUM_ROOT_DIRECTORY . "/Storage/CompiledEOM/{$this->pathWithNoExtension()}/Master-head.php", $this->EOMCompiler->compiledPHPEOMMasterHead());
        }
    }

    protected function createDirectoryIfItDoesNotExist()
    {
        if ($this->directoryDoesNotExist()) {
            mkdir(STRATUM_ROOT_DIRECTORY . "/Storage/CompiledEOM/{$this->pathWithNoExtension()}", 0777, true);
        }
    }

    protected function pathWithPHPExtension()
    {
        return substr_replace($this->path, '.php', strpos($this->path, '.html'));
    }

    protected function pathWithNoExtension()
    {
        (array) $pathComponents = explode('/', substr($this->path, 0, strpos($this->path, '.html')));
        (boolean) $hasSubdirectories = count($pathComponents) > 1;

        if ($hasSubdirectories) {
            array_pop($pathComponents);
            return  implode('/', $pathComponents);
        }

        return '';
    }

    protected function directoryDoesNotExist()
    {
        return !file_exists(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/' . $this->pathWithNoExtension() . '/');
    }



}