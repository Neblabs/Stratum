<?php

namespace Stratum\Prebuilt\Domain;

use Stratum\Original\Data\Data;
use Stratum\Original\Data\Domain;
use Stratum\Original\Data\GroupOf;

Class MetaGroup extends Domain
{
    public static function from(GroupOf $metaObjects)
    {
        (object) $metaData = new Data;

        foreach ($metaObjects as $meta) {
            $metaData->{$meta->key} = $meta->value;
        }

        return new Static($metaData);
    }
}