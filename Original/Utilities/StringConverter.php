<?php

namespace Stratum\Original\Utility;

Class StringConverter
{
    public function __construct($string)
    {
        $this->string = $string;
    }

    public function removeDashes()
    {
        return implode('', explode('-', $this->string));
    }

    public function replaceDashesWithUpperCasedLetters()
    {
        return preg_replace_callback('/-[a-zA-Z]/', function($matches){

            return ucfirst(substr($matches[0], 1));
        }, $this->string);
    }
}