<?php

use Stratum\Original\Presentation\EOM\Attributes;
use PHPUnit\Framework\TestCase;

Class AttributesTest extends TestCase
{ 

    public function test_returns_null_on_unexistent_attribute_name()
    {
        (object) $Attributes = new Attributes;

        $this->assertNull($Attributes->get('id'));

    }

    public function test_sets_and_gets_attribute()
    {
        (object) $Attributes = new Attributes;

        $Attributes->set([
            'name' => 'id',
            'value' => 'header'
        ]); 

        $this->assertEquals('header', $Attributes->get('id'));

    }

    public function test_set_overrides_previous_values()
    {
        (object) $Attributes = new Attributes;

        $Attributes->set([
            'name' => 'id',
            'value' => 'header'
        ]); 

        $this->assertEquals('header', $Attributes->get('id'));

        $Attributes->set([
            'name' => 'id',
            'value' => 'new-header'
        ]); 

        $this->assertEquals('new-header', $Attributes->get('id'));

    }

    public function test_adds_and_gets_attribute()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $this->assertEquals('header', $Attributes->get('class'));

    }

    public function test_adds_appends_new_values_to_previous_values()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $this->assertEquals('header', $Attributes->get('class'));

        $Attributes->add([
            'name' => 'class',
            'value' => 'new-header'
        ]); 

        $this->assertEquals('header new-header', $Attributes->get('class'));

    }

    public function test_returns_false_on_unexistent_attribute_name()
    {
        (object) $Attributes = new Attributes;

        $this->assertFalse($Attributes->has([
            'name' => 'unregisteredAttribute',
            'value' => '9'
        ]));
    }

    public function test_returns_true_when_value_exists_one_value()
    {
        (object) $Attributes = new Attributes;

        $Attributes->set([
            'name' => 'id',
            'value' => 'header'
        ]); 

        $this->assertTrue($Attributes->has(['name' => 'id', 'value' => 'header']));

    }

    public function test_returns_true_when_value_exists_two_values_find_first()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'floated'
        ]); 

        $this->assertTrue($Attributes->has(['name' => 'class', 'value' => 'header']));

    }

    public function test_returns_true_when_value_exists_two_values_find_second()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'floated'
        ]); 

        $this->assertTrue($Attributes->has(['name' => 'class', 'value' => 'floated']));

    }

    public function test_returns_true_when_value_exists_three_values_find_middle()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'floated'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'home'
        ]); 

        $this->assertTrue($Attributes->has(['name' => 'class', 'value' => 'floated']));

    }


    public function test_removes_attribute_value()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $this->assertEquals('header', $Attributes->get('class'));

        $Attributes->remove(['name' => 'class', 'value' => 'header']);

        $this->assertNull($Attributes->get('class'));


    }


    public function test_removes_attribute_value_two_values_first()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'float'
        ]); 

        $this->assertEquals('header float', $Attributes->get('class'));

        $Attributes->remove(['name' => 'class', 'value' => 'header']);

        $this->assertEquals('float', $Attributes->get('class'));

    }

    public function test_removes_attribute_value_two_values_second()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'float'
        ]); 

        $this->assertEquals('header float', $Attributes->get('class'));

        $Attributes->remove(['name' => 'class', 'value' => 'float']);

        $this->assertEquals('header', $Attributes->get('class'));

    }

    public function test_removes_attribute_value_three_values_middle()
    {
        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $Attributes->add([
            'name' => 'class',
            'value' => 'float'
        ]);

        $Attributes->add([
            'name' => 'class',
            'value' => 'third'
        ]); 

        $this->assertEquals('header float third', $Attributes->get('class'));

        $Attributes->remove(['name' => 'class', 'value' => 'float']);

        $this->assertEquals('header third', $Attributes->get('class'));

    }

    public function test_gets_attributes_as_array()
    {
        (array) $expectedArrayOfAttributes = [
            'class' => 'header'
        ];

        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]); 

        $this->assertEquals($expectedArrayOfAttributes, $Attributes->asArray());
    }

    public function test_gets_attributes_as_array_two_attributes_multi_word_attribute_names()
    {
        (array) $expectedArrayOfAttributes = [
            'data-pointer' => '56',
            'id' => 'home'
        ];

        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'dataPointer',
            'value' => '56'
        ]); 

        $Attributes->set([
            'name' => 'id',
            'value' => 'home'
        ]); 

        $this->assertEquals($expectedArrayOfAttributes, $Attributes->asArray());
    }

    public function test_gets_attributes_as_array_only_attributes_with_a_value()
    {
        (array) $expectedArrayOfAttributes = [
            'class' => 'header'
        ];

        (object) $Attributes = new Attributes;

        $Attributes->add([
            'name' => 'class',
            'value' => 'header'
        ]);

        $Attributes->set([
            'name' => 'id',
            'value' => 'main'
        ]); 

        $Attributes->remove([
            'name' => 'id',
            'value' => 'main'
        ]);

        $this->assertEquals($expectedArrayOfAttributes, $Attributes->asArray());
    }



















}