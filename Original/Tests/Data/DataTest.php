<?php

use Stratum\Original\Data\Data;
use PHPUnit\Framework\TestCase;

Class DataTest extends TestCase
{
    public function setUp()
    {
        $this->data = new Data;

        $this->data->name = 'Rafa Serna';
        $this->data->date = 2012;

    }

    public function test_returns_correct_dynamically_set_properties()
    {
        $this->assertEquals('Rafa Serna', $this->data->name);
        $this->assertEquals(2012, $this->data->date);
    }

    public function test_returns_true_when_checking_for_an_existing_property()
    {
        $this->assertTrue($this->data->hasProperty('name'));
        $this->assertTrue($this->data->hasProperty('date'));
    }

    public function test_returns_false_when_checking_for_an_unexisting_property()
    {
        $this->assertFalse($this->data->hasProperty('age'));
        $this->assertFalse($this->data->hasProperty('residence'));
    }

    public function test_returns_the_total_number_of_properties()
    {
        $this->assertEquals(2, $this->data->count());
    }

    public function test_returns_aliased_property()
    {
        $this->data->setAliases([
            'id' => 'post_id'
        ]);


        $this->data->post_id = 50;

        $this->assertEquals(50, $this->data->id);
    }

    public function test_returns_null_in_unexistent_property()
    {
        $this->assertNull($this->data->IDoNotExist);
    }

    public function test_returns_true_if_empty()
    {
        (object) $data = new Data;

        $this->assertTrue($data->isEmpty());
    }

    public function test_sets_aliased_property()
    {
        $this->data->setAliases([
            'id' => 'post_id'
        ]);

        $this->data->id = 7;

        $this->assertEquals(7, $this->data->post_id);
    }









}