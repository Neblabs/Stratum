<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\EOM\Element;

Class EOMNonVoidElementWriter extends EOMElementWriter
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

        return $this->getOpeningTag() . 
               $this->getChildren()   .
               $this->getClosingTag() ; 

    }

    public function writeOpeningTag()
    {
        print $this->getOpeningTag();
    }

    public function writeClosingTag()
    {
        print $this->getClosingTag();
    }

    public function getOpeningTag()
    {
        return "<{$this->node->type()}{$this->elementAttributes()}>";
    }

    public function getClosingTag()
    {
        return "</{$this->node->type()}>"; 
    }

    protected function getChildren()
    {
        (string) $writtenChildren = '';

        foreach ($this->node->children() as $child) {

            (object) $nodeWriter = EOMNodeWriter::createFrom($child);

            $nodeWriter->setIsCachingView($this->isCachingView);

            $writtenChildren.= $nodeWriter->get();
        }

        return $writtenChildren;
    }







}
