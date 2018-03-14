<?php

namespace Stratum\Original\Presentation\Element\Adder;

use Stratum\Original\Presentation\EOM\Element;

Class FullDOMElementContentAdder extends ElementContentAdder
{
    public function addComponentContentToElement()
    {
        (string) $EOMElement = Element::className();

        if ($this->elementToAddContentTo instanceof $EOMElement) {
            $this->elementToAddContentTo->addChildren($this->component->elements());
        } else {
            $this->elementToAddContentTo->addNodes($this->component->elements());
        }
    }
}
