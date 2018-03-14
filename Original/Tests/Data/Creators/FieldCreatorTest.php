<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\Creator\FieldCreator;
use Stratum\Original\Data\Field;

Class FieldCreatorTest extends TestCase
{
    public function setUp()
    {
        $this->fieldCreator = new FieldCreator;

        $this->field = $this->fieldCreator->createFrom([
                    'fieldName' => 'author',
                    'fieldValue' => 'Rafa Serna'
        ]);
    }
    public function test_returns_a_field_object()
    {
        

        $this->assertInstanceOf(Field::class, $this->field);
    }

    public function test_creates_a_field_object_with_the_correct_properties()
    {
        $this->assertEquals('author', $this->field->name);
        $this->assertEquals('Rafa Serna', $this->field->value);
    }
}