<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\EntityData;

Class EntityDataCreator
{
    public function createFrom(array $EntityMeta)
    {
        (object) $entityData = new EntityData;

        $entityData->entityType = $EntityMeta['entityType'];
        $entityData->numberOfEntities = $EntityMeta['numberOfEntities'];
        $entityData->isMoreThan = $EntityMeta['isMoreThan'];
        $entityData->isLessThan = $EntityMeta['isLessThan'];

        return $entityData;
    }
}