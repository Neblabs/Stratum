<?php

namespace Stratum\Extend\Saver\Cache;

use Stratum\Original\Data\Saver;

Abstract Class Cache extends Saver
{

    protected function cachedArray()
    {
        return unserialize(file_get_contents($this->cachedArrayFileLocation()));
    }

    protected function saveCachedArray(array $cachedArray)
    {
        file_put_contents($this->cachedArrayFileLocation(), serialize($cachedArray));
    }

    protected function cachedArrayFileLocation()
    {
        return STRATUM_ROOT_DIRECTORY . "/Storage/Cache/{$this->cachedArrayFileLocation}.php";
    }
}