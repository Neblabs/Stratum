<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\EOM\Element;

Class EOMTextWriter extends EOMNodeWriter
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
        
        return $this->node->content();
    }
}
