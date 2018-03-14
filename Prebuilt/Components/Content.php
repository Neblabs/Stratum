<?php

namespace Stratum\Prebuilt\Component;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;

Class Content extends Component
{
    public function load(PartialView $view)
    {
        return $this->bindedData;
    }
}