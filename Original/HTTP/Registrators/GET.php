<?php

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Registrator\RoutesRegistratorFacade;
use Stratum\Original\HTTP\Registrator\RoutesRegistrator;

Class GET extends HTTPRoutesRegistratorFacade
{
    protected function setMethod()
    {
        $this->routesRegistrator->setMethod('GET');
    }

}