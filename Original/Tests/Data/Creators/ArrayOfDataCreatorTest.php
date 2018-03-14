<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\Creator\ArrayOfDataCreator;
use Stratum\Original\Data\Data;

Class ArrayOfDataCreatorTest extends TestCase
{
    public function test_creates_3_data_objects_from_array()
    {
        (array) $sets = [
            [
                'id' => 1, 
                'title' => 'First Title',
                'slug' => 'first-title'
            ],
            [
                'id' => 2, 
                'title' => 'Second Title',
                'slug' => 'second-title'
            ],
            [
                'id' => 3, 
                'title' => 'Third Title',
                'slug' => 'third-title'
            ]
        ];

        (object) $dataCreator = new ArrayOfDataCreator($sets);

        (array) $dataObjects = $dataCreator->create();

        $this->assertCount(3, $dataObjects);


        $this->assertInstanceOf(Data::class, $dataObjects[0]);
        $this->assertEquals(1, $dataObjects[0]->id);
        $this->assertEquals('First Title', $dataObjects[0]->title);
        $this->assertEquals('first-title', $dataObjects[0]->slug);

        $this->assertInstanceOf(Data::class, $dataObjects[1]);
        $this->assertEquals(2, $dataObjects[1]->id);
        $this->assertEquals('Second Title', $dataObjects[1]->title);
        $this->assertEquals('second-title', $dataObjects[1]->slug);

        $this->assertInstanceOf(Data::class, $dataObjects[2]);
        $this->assertEquals(3, $dataObjects[2]->id);
        $this->assertEquals('Third Title', $dataObjects[2]->title);
        $this->assertEquals('third-title', $dataObjects[2]->slug);
    }

    public function test_creates_nothing_returns_empty_array()
    {
        (array) $sets = [];

        (object) $dataCreator = new ArrayOfDataCreator($sets);

        (array) $dataObjects = $dataCreator->create();

        $this->assertCount(0, $dataObjects);

    }












}