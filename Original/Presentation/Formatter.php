<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Formatter
{
    use ClassName;
    
    protected $text;
    protected $formatterMethod;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function setFormatterMethod($formatterMethod)
    {
        $this->formatterMethod = $formatterMethod;
    }

    public function formattedText()
    {
        return $this->{$this->formatterMethod}();
    }
}