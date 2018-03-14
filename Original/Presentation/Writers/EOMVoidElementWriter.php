<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\EOM\Element;

Class EOMVoidElementWriter extends EOMElementWriter
{
    public function write()
    {
        print $this->get();

    }

    public function get()
    {
        if ($this->isNodeFromComponent() and $this->isCachingView) {
            return $this->writtenComponent();
        }

        return "<{$this->node->type()}{$this->elementAttributes()} />" . PHP_EOL;
    }


}