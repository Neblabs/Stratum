<?php 

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Registrator\HTTPRoutesRegistrator;
use Stratum\Original\HTTP\Registrator\WordpressRoutesRegistrator;

Class Wordpress extends RoutesRegistratorFacade
{

    protected function createRoutesRegistrator()
    {
        $this->routesRegistrator = new WordpressRoutesRegistrator;
    }

    public function to($pathDefiniton)
    {
        $this->routesRegistrator->setSitePage($pathDefiniton);

        return $this;
    }

    public function withType($posType)
    {
        $this->routesRegistrator->setPostType($posType);

        return $this;
    }


}