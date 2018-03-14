<?php

use Stratum\Original\Data\DynamicFieldResolver;
use PHPUnit\Framework\TestCase;

Class DynamicFieldResolverTest extends TestCase
{
    protected $highestFieldResolver;
    public function setUp()
    {
        $this->withFieldResolver = new DynamicFieldResolver('withTitle');
        $this->inFieldResolver = new DynamicFieldResolver('inTitle');
        $this->byFieldResolver = new DynamicFieldResolver('byTitle');
        $this->ableToFieldResolver = new DynamicFieldResolver('ableToTitle');
        $this->FieldResolver = new DynamicFieldResolver('Title');
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        
    }

    public function test_returns_only_the_field_name()
    {   
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        $this->assertEquals('title', $this->withFieldResolver->fieldName());
        $this->assertEquals('title', $this->inFieldResolver->fieldName());
        $this->assertEquals('title', $this->byFieldResolver->fieldName());
        $this->assertEquals('title', $this->ableToFieldResolver->fieldName());
        $this->assertEquals('title', $this->FieldResolver->fieldName());
        $this->assertEquals('title', $this->highestFieldResolver->fieldName());
        $this->assertEquals('title', $this->lowestFieldResolver->fieldName());
    }

    public function test_returns_true_if_only_the_field_name_was_passed()
    {
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        $this->assertTrue($this->withFieldResolver->isNotFieldNameOnly());
        $this->assertTrue($this->inFieldResolver->isNotFieldNameOnly());
        $this->assertTrue($this->byFieldResolver->isNotFieldNameOnly());
        $this->assertTrue($this->ableToFieldResolver->isNotFieldNameOnly());
        $this->assertFalse($this->FieldResolver->isNotFieldNameOnly());
        $this->assertTrue($this->highestFieldResolver->isNotFieldNameOnly());
        $this->assertTrue($this->lowestFieldResolver->isNotFieldNameOnly());
    }

    public function test_returns_true_if_is_setter_for_the_same_entity_only()
    {
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        $this->assertTrue($this->withFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->inFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->byFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->ableToFieldResolver->isSetterForSameEntity());
        $this->assertFalse($this->FieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->highestFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->lowestFieldResolver->isSetterForSameEntity());
    }

    public function test_returns_true_if_is_order_by_Descending()
    {
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        $this->assertFalse($this->withFieldResolver->isOrderByDescending());
        $this->assertFalse($this->inFieldResolver->isOrderByDescending());
        $this->assertFalse($this->byFieldResolver->isOrderByDescending());
        $this->assertFalse($this->ableToFieldResolver->isOrderByDescending());
        $this->assertFalse($this->FieldResolver->isOrderByDescending());
        $this->assertTrue($this->highestFieldResolver->isOrderByDescending());
        $this->assertFalse($this->lowestFieldResolver->isOrderByDescending());
    }

    public function test_returns_true_if_is_order_by_Ascending()
    {
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');
        $this->assertFalse($this->withFieldResolver->isOrderByAscending());
        $this->assertFalse($this->inFieldResolver->isOrderByAscending());
        $this->assertFalse($this->byFieldResolver->isOrderByAscending());
        $this->assertFalse($this->ableToFieldResolver->isOrderByAscending());
        $this->assertFalse($this->FieldResolver->isOrderByAscending());
        $this->assertFalse($this->highestFieldResolver->isOrderByAscending());
        $this->assertTrue($this->lowestFieldResolver->isOrderByAscending());
    }

    public function test_uses_field_aliases()
    {
        $this->highestFieldResolver = new DynamicFieldResolver('highestTitleFirst');
        $this->lowestFieldResolver = new DynamicFieldResolver('lowestTitleFirst');

        (array) $aliases = [
            'title' => 'post_title'
        ];

        $this->withFieldResolver->setFieldAliases($aliases);
        $this->inFieldResolver->setFieldAliases($aliases);
        $this->byFieldResolver->setFieldAliases($aliases);
        $this->ableToFieldResolver->setFieldAliases($aliases);
        $this->FieldResolver->setFieldAliases($aliases);
        $this->highestFieldResolver->setFieldAliases($aliases);
        $this->lowestFieldResolver->setFieldAliases($aliases);

        $this->assertEquals('post_title', $this->withFieldResolver->fieldName());
        $this->assertEquals('post_title', $this->inFieldResolver->fieldName());
        $this->assertEquals('post_title', $this->byFieldResolver->fieldName());
        $this->assertEquals('post_title', $this->ableToFieldResolver->fieldName());
        $this->assertEquals('post_title', $this->FieldResolver->fieldName());
        $this->assertEquals('post_title', $this->highestFieldResolver->fieldName());
        $this->assertEquals('post_title', $this->lowestFieldResolver->fieldName());
    }













}