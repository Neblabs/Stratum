<?php

namespace Stratum\Original\Presentation\Element\Adder;

Class PartialDOMElementContentAdder extends ElementContentAdder
{
    public function addComponentContentToElement()
    {
        $this->elementToAddContentTo->addChildren($this->component->partialElements());
    }
}