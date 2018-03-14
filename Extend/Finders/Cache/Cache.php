<?php

namespace Stratum\Extend\Finder\Cache;

use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder\SingleEntityFinder;

Abstract Class Cache extends SingleEntityFinder
{

    protected function cachedArray()
    {
        (boolean) $cachedArrayIsEmpty = empty(static::$cachedArray);

        if ($cachedArrayIsEmpty) {
            static::$cachedArray = unserialize(file_get_contents($this->cachedArrayFileLocation()));;
        }

        return static::$cachedArray;
    }

    protected function cachedArrayFileLocation()
    {
        return STRATUM_ROOT_DIRECTORY . "/Storage/Cache/{$this->cachedArrayFileLocation}.php";
    }

    protected function onBuilderStart()
    {

    }
    
    protected function onBuilderEnd()
    {

    }

    protected function onEqualityField(Field $field)
    {

    } 

    protected function onMoreThanField(Field $field)
    {

    }

    protected function onLessThanField(Field $field)
    {

    }

    protected function onConditionalAND()
    {

    }

    protected function onConditionalOR()
    {

    }

    protected function onOrderByAscending(Field $field)
    {

    }

    protected function onOrderByDescending(Field $field)
    {

    }
}