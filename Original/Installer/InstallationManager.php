<?php

namespace Stratum\Original\Installer;

use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Original\Installer\SetupManager;
use Symfony\Component\HttpFoundation\RedirectResponse;

Class InstallationManager
{
    protected static $installationJobs = [];

    public static function addInstallationJob(Callable $job)
    {
        static::$installationJobs[] = $job;
    }

    public function reRunInstallationIfIsNotComplete()
    {
        if ($this->installationsIsNotComplete()) {
         
            $this->performInstallation();

        }
    }

    public function performInstallation()
    {
        $this->reRunInstallation();
        $this->runInstallationJobs();
        $this->redirectToDashBoard();
    }

    protected function installationsIsNotComplete()
    {
        return !file_exists(ABSPATH . 'OriginalIndex.php');
    }

    protected function reRunInstallation()
    {
        (object) $installer = new SetupManager;

        $installer->finishInstallation();
    }

    protected function runInstallationJobs()
    {
        foreach (static::$installationJobs as $job) {
            $job();
        }
    }

    protected function redirectToDashBoard()
    {
        (string) $url = Options::withName('siteurl')->find()->first()->value;

        (object) $RedirectResponse = new RedirectResponse("$url/wp-admin/themes.php");
        $RedirectResponse->send(); 
    }
}