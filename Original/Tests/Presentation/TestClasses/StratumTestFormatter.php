<?php

namespace Stratum\Custom\Formatter;

use Stratum\Original\Presentation\Formatter;


Class StratumTestFormatter extends Formatter
{
    public function inUpperCase()
    {
        return "{$this->text} modified 1 time";
    }

    public function noWhiteSpace()
    { 
         return "{$this->text} modified 2 times";
    }
}