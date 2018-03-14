<?php

namespace Stratum\Original\Presentation\Element\Adder;

Class TextElementContentAdder extends ElementContentAdder
{
    public function addComponentContentToElement()
    {
        $this->elementToAddContentTo->addText($this->component->asTextElements());
    }
}
