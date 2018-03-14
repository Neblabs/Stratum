<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\Original\Installer\SetupManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

Class FinishInstallationCommand extends Command
{
    protected $HTMLFiles = [];
    protected $baseDirectory;
    protected $parentDirectories = '';

    protected function configure()
    {
        $this->setName('finish');
        $this->setDescription('Replaces Wordpress\' Original Index.php file with Stratum\'s');

        $this->addArgument('Installation', InputArgument::REQUIRED, 'Installation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (object) $installer = new SetupManager;

        $installer->finishInstallation();

        if ($installer->setUpWasAborted()) {
            $output->writeln('<bg=red>Stratum has already been installed.</>');
        } else {
            $output->writeln('<bg=green>Everything ready, enjoy!</>');
        }
    }

    


}

