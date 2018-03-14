<?php

namespace Stratum\Original\Installer;

Class SetupManager
{
    protected $setUpAborted = false;

    public function wordpressRootDirectory()
    {   
        if (defined('ABSPATH')) {
            
            return ABSPATH;
        }  

        return WORDPRESS_ROOT_DIRECTORY;

    }

    public function finishInstallation()
    {
        if ($this->hasNotBeeninstalled()) {

            $this->createCopyOfIndexFile();
            $this->placeStratumIndexFileInWordpressRootDirectory();

        } else {
            $this->setUpAborted = true;
        }
    }

    public function unInstall()
    {
        if (!$this->hasNotBeeninstalled()) {
            $this->putBackOriginalIndexFile();
            $this->removeCopyOfOriginalIndexFile();
        }
    }

    public function setUpWasAborted()
    {
        return $this->setUpAborted;
    }

    protected function hasNotBeeninstalled()
    {
        (boolean) $CopyOfOriginalIndexDoesNotExist = !file_exists($this->wordpressRootDirectory() . '/OriginalIndex.php');

        return $CopyOfOriginalIndexDoesNotExist;
    }

    protected function createCopyOfIndexFile()
    {
        (string) $CopyOfOriginalIndex = file_get_contents($this->wordpressRootDirectory() . '/index.php');
        
        file_put_contents($this->wordpressRootDirectory() . '/OriginalIndex.php', $CopyOfOriginalIndex);
    }

    protected function placeStratumIndexFileInWordpressRootDirectory()
    {
        (string) $themeDirectoryName = substr(substr(__DIR__, strpos(__DIR__, 'themes/') + 7), 0, strpos(substr(__DIR__, strpos(__DIR__, 'themes/') + 7), '/'));
        (string) $StratumIndex = require(STRATUM_ROOT_DIRECTORY . '/Original/Installer/Templates/Index.php');
        
        file_put_contents($this->wordpressRootDirectory() . '/index.php', $StratumIndex);
    }

    protected function putBackOriginalIndexFile()
    {
        (string) $CopyOfOriginalIndex = file_get_contents($this->wordpressRootDirectory() . '/OriginalIndex.php');
        
        file_put_contents($this->wordpressRootDirectory() . '/index.php', $CopyOfOriginalIndex);
    }

    protected function removeCopyOfOriginalIndexFile()
    {
        unlink($this->wordpressRootDirectory() . '/OriginalIndex.php');
    }




}