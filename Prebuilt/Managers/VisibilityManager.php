<?php

namespace Stratum\Prebuilt\Manager;

use Stratum\Original\Presentation\Element\Manager;

Class VisibilityManager extends Manager
{
    protected function showIf($isTrue)
    {
        if ($isTrue !== true) {
            $this->element->remove();
        }
    }
}