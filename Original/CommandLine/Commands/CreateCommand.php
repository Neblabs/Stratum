<?php

namespace Stratum\Original\CommandLine\Command;

use Doctrine\Common\Inflector\Inflector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

Class CreateCommand extends Command
{

    protected function configure()
    {
        $this->setName('create');
        $this->setDescription('');

        $this->addArgument('type', InputArgument::IS_ARRAY);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->setTypeName($input);

        $this->input = $input;
        $this->output = $output;

        $this->createBasedOn($input->getArgument('type')[0]);
    }

    protected function setTypeName(InputInterface $input)
    {
        $this->typeName = isset($input->getArgument('type')[1]) ? $input->getArgument('type')[1] : '';
    }

    protected function createBasedOn($type)
    {
        switch ($type) {
            case 'controller':
                $this->create('Controller', 'Design/Control/Controllers');
                break;
            case 'filter':
                $this->create('Filter', 'Design/Control/Filters');
                break;
            case 'validator':
                $this->create('Validator', 'Design/Control/Validators');
                break;
            case 'manager':
                $this->create('Manager', 'Design/Present/Managers');
                break;
            case 'formatter':
                $this->create('Formatter', 'Design/Present/Formatters');
                break;
            case 'component':
                $this->create('Component', 'Design/Present/Components');
                $this->createView();
                break;
            
            default:
                $this->output->writeln("<bg=red>Unsupported Type: $type</>");
                break;
        }
    }

    protected function create($type, $typeDirectory)
    {
        (string) $newFileDirectory = "/{$typeDirectory}/{$this->typeName}.php";

        (string) $controllerFile = include STRATUM_ROOT_DIRECTORY . "/Original/CommandLine/TemplateClasses/{$type}.php";

        if (!$this->fileExists($newFileDirectory) or $this->userDecidedToProceed($newFileDirectory)) {
            file_put_contents(STRATUM_ROOT_DIRECTORY .  $newFileDirectory, $controllerFile);

            $this->output->writeln("<bg=green>$type {$this->typeName} created at: $newFileDirectory</>");
        } else {
            $this->output->writeln("<bg=red>creation of $type {$this->typeName} has been aborted.</>");
        }

        
    }

    protected function fileExists($newFileDirectory)
    {
        return file_exists(STRATUM_ROOT_DIRECTORY .  $newFileDirectory);
    }

    protected function userDecidedToProceed($newFileDirectory)
    {
        if ($this->fileExists($newFileDirectory)) {
            $helper = $this->getHelper('question');
            $this->output->writeln("<bg=red>A file with the same name as: {$this->typeName} already exists</>");
            $question = new ConfirmationQuestion("Do you want to continue and override it with a new file? Answers are y for true, n for false.   ", false);

            return $helper->ask($this->input, $this->output, $question);
        }
        return true;
    }

    protected function createView()
    {
        file_put_contents(STRATUM_ROOT_DIRECTORY . "/Design/Present/Views/{$this->typeName}.html", '');
    }





}
