<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\Original\Presentation\Balance\Cache\ComponentCache;
use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;
use Stratum\Original\Presentation\Balance\Map\HighPerformantMap;
use Stratum\Original\Presentation\Compiler\EOMCompilerWriter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CompileViewsCommand extends Command
{
    protected $HTMLFiles = [];
    protected $baseDirectory;
    protected $parentDirectories = '';

    public function __construct($name = null)
    {
        //$componentCache = new ComponentCache;
        //$componentCache->clearCache();
        $this->finder = new Finder; 

        parent::__construct($name);
    }

    public function HTMLFiles()
    {
        return $this->HTMLFiles;
    }

    public function setBaseDirectory($baseDirectory)
    {
        $this->baseDirectory = $baseDirectory;
    }

    protected function configure()
    {
        $this->setName('compile');
        $this->setDescription('Compiles pseudo HTML Views into PHP objects');

        $this->addArgument('views', InputArgument::REQUIRED, 'views');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setBaseDirectoryIfUnexistent();
        $this->searchFiles();

        $output->writeln("Compiling <info>{$this->numberOfViewsFound}</info> {$this->viewsInPluralOrSingular()}...");

        $this->compileViews();

        $output->writeln('<info>Done!</info>');
    }

    protected function setBaseDirectoryIfUnexistent()
    {
        (boolean) $noBaseDirectoryHasBeenSet = $this->baseDirectory === null;

        if ($noBaseDirectoryHasBeenSet) {
            $this->baseDirectory = STRATUM_ROOT_DIRECTORY . '/Design/Present/Views';
        }
    }

    protected function searchFiles()
    {
        $this->finder->files()->name('*.html')->in($this->baseDirectory);
        $this->numberOfViewsFound = $this->finder->count();   
    }

    protected function compileViews()
    { 
        
        foreach ($this->finder as $file) {
            $this->compile($file->getRelativePathname());
        }

    }

    protected function compile($filePath)
    {

        (object) $EOMCompilerWriter = new EOMCompilerWriter($filePath);

        $EOMCompilerWriter->writeCompiledEOMObjectsToDisk();
    }

    protected function viewsInPluralOrSingular()
    {
        if ($this->numberOfViewsFound === 1) {
            return 'View';
        }

        return 'Views';
    }

    








}
