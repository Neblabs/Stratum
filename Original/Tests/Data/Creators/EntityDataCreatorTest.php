<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\Creator\EntityDataCreator;
use Stratum\Original\Data\EntityData;

Class EntityDataCreatorTest extends TestCase
{
    public function setUp()
    {
        $this->entityDataCreator = new EntityDataCreator;

        $this->entityData = $this->entityDataCreator->createFrom([
            'entityType' => 'Posts',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => true
        ]);
    }
    public function test_returns_an_EntityData_object()
    {
        
        $this->assertInstanceOf(EntityData::class, $this->entityData);
    }

    public function test_creates_an_EntityData_object_with_the_correct_properties()
    {
        $this->assertEquals('Posts', $this->entityData->entityType);
        $this->assertEquals(2, $this->entityData->numberOfEntities);
        $this->assertEquals(false, $this->entityData->isMoreThan);
        $this->assertEquals(true, $this->entityData->isLessThan);
    }
}