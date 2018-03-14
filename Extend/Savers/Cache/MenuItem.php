<?php

namespace Stratum\Extend\Saver\Cache;

use Stratum\CoreBox\Model\WPPPostToDataObjectConverter;
use Stratum\Custom\Domain;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Saver;
use WP_Post;

Class MenuItem extends Saver
{

    public function save()
    {
        $this->serializeDomainObjectInCacheDirectory();
    }  

    protected function serializeDomainObjectInCacheDirectory()
    {
        (string) $fileName = STRATUM_ROOT_DIRECTORY . "/Storage/Cache/Menus/Items/item{$this->ID}.php";

        file_put_contents($fileName, serialize($this));
    }










}