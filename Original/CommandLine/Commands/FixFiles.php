<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\CoreBox\JsManagement\JavascriptsCompiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

Class FixFiles extends Command
{

    protected function configure()
    {
        $this->setName('fixfiles');
        $this->setDescription('Capitalizes the first letter of every file and directory name except those from root and /vendor directories.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        (object) $finder = new Finder;

        $finder->directories()->in(STRATUM_ROOT_DIRECTORY)->exclude('vendor');

        foreach ($finder as $directory) {

            (string) $capitalizedDirectory = STRATUM_ROOT_DIRECTORY.'/'.ucfirst($directory->getRelativePathName());
        
            rename($directory->getRealPath(), $capitalizedDirectory);



            (object) $files = new Finder;

            $files->files()->in($directory->getRealPath())->exclude('vendor');

            foreach ($files as $file) {
                (string) $originalAbsoluteFileName = $file->getRealPath();
                
                rename($originalAbsoluteFileName, $this->upperCasedFileName($originalAbsoluteFileName));
            }

        }
   
        
    }

    protected function upperCasedFileName($absoluteFileName)
    {
        (array) $dirsAndFiles = explode('/', $absoluteFileName);
        (string) $file = count($dirsAndFiles) - 1;

        $dirsAndFiles[$file] = ucfirst($dirsAndFiles[$file]);

        return implode('/', $dirsAndFiles);
    }


}

