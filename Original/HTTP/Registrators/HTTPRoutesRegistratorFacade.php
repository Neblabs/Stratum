<?php 

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;

Abstract class HTTPRoutesRegistratorFacade extends RoutesRegistratorFacade
{

    abstract protected function setMethod();

    public function __construct()
    {
        parent::__construct();
        
        $this->setMethod();
    }

    protected function createRoutesRegistrator()
    {
        $this->routesRegistrator = new HTTPRoutesRegistrator;
    }

    public function to($pathDefiniton)
    {
        $this->routesRegistrator->setPath($pathDefiniton);

        return $this;
    }


}