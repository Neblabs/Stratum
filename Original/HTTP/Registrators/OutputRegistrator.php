<?php

namespace Stratum\Original\HTTP\Registrator;

Class OutputRegistrator
{

    protected static $output;
    protected $outputContent;

    public function setOutput($outputContent)
    {
        $this->outputContent = trim($outputContent);
    }

    public function register()
    {
        static::$output = $this->outputContent;
    }

    public function unregister()
    {
        static::$output = '';
    }


    public function registeredOutput()
    {   
        return static::$output;
    }

    public function outputExists()
    {
        return !empty(static::$output);
    }



}
