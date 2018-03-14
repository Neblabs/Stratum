<?php

namespace Stratum\Original\CommandLine\Command;

use Stratum\CoreBox\JsManagement\JavascriptsCompiler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

Class CompileJs extends Command
{

    protected function configure()
    {
        $this->setName('compileJs');
        $this->setDescription('');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        (object) $javascriptsCompiler = new JavascriptsCompiler;

        $javascriptsCompiler->compile();       
        
    }

    


}

