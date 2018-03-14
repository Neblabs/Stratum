<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Presentation\Writer\EOMNodeWriter;

Class EOMWriter
{
    protected $node;

    public function __construct($node)
    {
        $this->node = $node;
    }

    public function render()
    {
        (object) $EOMNodeWriter = EOMNodeWriter::createFrom($this->node);

        $EOMNodeWriter->write(); 
    }
}