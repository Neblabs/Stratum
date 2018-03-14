<?php

namespace Stratum\Prebuilt\Model\Wordpress;

use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Domain;
use Stratum\Original\Data\Model;
use Stratum\Original\Data\Saver;

Class Post extends Model
{
    public function __construct(Data $data = null, Domain $domain = null, Saver $saver = null)
    {
        (object) $posts = new Posts;
        
        parent::__construct($data, $domain, $saver);

        $data->setAliases($posts->fieldAliases());
    }
}