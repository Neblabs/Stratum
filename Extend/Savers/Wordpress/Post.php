<?php

namespace Stratum\Extend\Saver\Wordpress;

use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\Saver;

Class Post extends Saver
{
    protected $querier;
    protected $sqlParameters;
    protected $databaseQuerier;

    public function save()
    {
        throw new Exception("Wordpress Saver Unsuported");
        
    }
}