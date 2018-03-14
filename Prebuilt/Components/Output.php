<?php

namespace Stratum\Prebuilt\Component;

use Stratum\Original\HTTP\Registrator\OutputRegistrator;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\PartialView;

Class Output extends Component
{
    public function load(PartialView $view)
    {
        (object) $OutputRegistrator = new OutputRegistrator;

        return $view->from('Original/output.html')->with(['output' => $OutputRegistrator->registeredOutput()]);
    }
}