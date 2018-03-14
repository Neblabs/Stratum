<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Presentation\Registrator\FormatterRegistrator;

Class FormattersHandler
{
    protected $text;
    protected $formatterNames;

    public function __construct($text)
    {
        $this->text = $text;
        $this->formatterRegistrator = new FormatterRegistrator;
    }

    public function setFormatterNames(array $formatterNames)
    {
        $this->formatterNames = $formatterNames;
    }

    public function formatText()
    {

        foreach ($this->formatterNames as $formatterName) {
            (string) $customFormatter = $this->formatterRegistrator->formatterClassFor($formatterName);
            (object) $formatter = new $customFormatter($this->text);

            $formatter->setFormatterMethod($formatterName);

            $this->text = $formatter->formattedText();            
        }

        return $this->text;
    }
}