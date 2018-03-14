<?php

use Stratum\Original\Data\QueryBuilderDynamicFieldResolver;


Class QueryBuilderDynamicFieldResolverTest extends DynamicFieldResolverTest
{
    public function setUp()
    {
        $this->withFieldResolver = new QueryBuilderDynamicFieldResolver('withTitle');
        $this->inFieldResolver = new QueryBuilderDynamicFieldResolver('inTitle');
        $this->byFieldResolver = new QueryBuilderDynamicFieldResolver('byTitle');
        $this->ableToFieldResolver = new QueryBuilderDynamicFieldResolver('ableToTitle');
        $this->FieldResolver = new QueryBuilderDynamicFieldResolver('Title');

        $this->andWithFieldResolver = new QueryBuilderDynamicFieldResolver('andWithTitle');
        $this->andInFieldResolver = new QueryBuilderDynamicFieldResolver('andInTitle');
        $this->andByFieldResolver = new QueryBuilderDynamicFieldResolver('andByTitle');
        $this->andAbleToFieldResolver = new QueryBuilderDynamicFieldResolver('andAbleToTitle');

        $this->orWithFieldResolver = new QueryBuilderDynamicFieldResolver('orWithTitle');
        $this->orInFieldResolver = new QueryBuilderDynamicFieldResolver('orInTitle');
        $this->orByFieldResolver = new QueryBuilderDynamicFieldResolver('orByTitle');
        $this->orAbleToFieldResolver = new QueryBuilderDynamicFieldResolver('orAbleToTitle');

        $this->orMoreFieldResolver = new QueryBuilderDynamicFieldResolver('orMorePosts');
        $this->orLessFieldResolver = new QueryBuilderDynamicFieldResolver('orLessPosts');

        $this->andFieldResolver = new QueryBuilderDynamicFieldResolver('andPosts');
        $this->orFieldResolver = new QueryBuilderDynamicFieldResolver('orPosts');
    }

    public function test_returns_only_the_field_name_when_prefixed_with_andwith_or_andin_or_andby_or_andableTo()
    {
        $this->assertEquals('title', $this->andWithFieldResolver->fieldName());
        $this->assertEquals('title', $this->andInFieldResolver->fieldName());
        $this->assertEquals('title', $this->andByFieldResolver->fieldName());
        $this->assertEquals('title', $this->andAbleToFieldResolver->fieldName());
    }

    public function test_returns_only_the_field_name_when_prefixed_with_orwith_or_orin_or_orby_or_orableTo()
    {
        $this->assertEquals('title', $this->orWithFieldResolver->fieldName());
        $this->assertEquals('title', $this->orInFieldResolver->fieldName());
        $this->assertEquals('title', $this->orByFieldResolver->fieldName());
        $this->assertEquals('title', $this->orAbleToFieldResolver->fieldName());
    }

    public function test_returns_only_the_field_name_when_prefixed_with_orMore_or_orLess()
    {
        $this->assertEquals('posts', $this->orMoreFieldResolver->fieldName());
        $this->assertEquals('posts', $this->orLessFieldResolver->fieldName());

    }

    public function test_returns_only_the_field_name_when_prefixed_with_and_or_or()
    {
        $this->assertEquals('posts', $this->andFieldResolver->fieldName());
        $this->assertEquals('posts', $this->orFieldResolver->fieldName());
    }

    public function test_is_correct_conditional_type()
    {
        $this->assertTrue($this->withFieldResolver->isANDCondition());
        $this->assertTrue($this->inFieldResolver->isANDCondition());
        $this->assertTrue($this->byFieldResolver->isANDCondition());
        $this->assertTrue($this->ableToFieldResolver->isANDCondition());

        $this->assertFalse($this->withFieldResolver->isORCondition());
        $this->assertFalse($this->inFieldResolver->isORCondition());
        $this->assertFalse($this->byFieldResolver->isORCondition());
        $this->assertFalse($this->ableToFieldResolver->isORCondition());

        $this->assertTrue($this->andWithFieldResolver->isANDCondition());
        $this->assertTrue($this->andInFieldResolver->isANDCondition());
        $this->assertTrue($this->andByFieldResolver->isANDCondition());
        $this->assertTrue($this->andAbleToFieldResolver->isANDCondition());

        $this->assertFalse($this->andWithFieldResolver->isORCondition());
        $this->assertFalse($this->andInFieldResolver->isORCondition());
        $this->assertFalse($this->andByFieldResolver->isORCondition());
        $this->assertFalse($this->andAbleToFieldResolver->isORCondition());

        $this->assertFalse($this->orWithFieldResolver->isANDCondition());
        $this->assertFalse($this->orInFieldResolver->isANDCondition());
        $this->assertFalse($this->orByFieldResolver->isANDCondition());
        $this->assertFalse($this->orAbleToFieldResolver->isANDCondition());

        $this->assertTrue($this->orWithFieldResolver->isORCondition());
        $this->assertTrue($this->orInFieldResolver->isORCondition());
        $this->assertTrue($this->orByFieldResolver->isORCondition());
        $this->assertTrue($this->orAbleToFieldResolver->isORCondition());
    }

    public function test_returns_true_if_method_starts_with_orMore_or_orLess()
    {
        $this->assertFalse($this->withFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->withFieldResolver->isOrLessMethod());

        $this->assertFalse($this->inFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->inFieldResolver->isOrLessMethod());

        $this->assertFalse($this->byFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->byFieldResolver->isOrLessMethod());

        $this->assertFalse($this->ableToFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->ableToFieldResolver->isOrLessMethod());

        $this->assertFalse($this->andWithFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->andWithFieldResolver->isOrLessMethod());

        $this->assertFalse($this->andInFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->andInFieldResolver->isOrLessMethod());

        $this->assertFalse($this->andByFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->andByFieldResolver->isOrLessMethod());

        $this->assertFalse($this->andAbleToFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->andAbleToFieldResolver->isOrLessMethod());

        $this->assertFalse($this->orWithFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orWithFieldResolver->isOrLessMethod());

        $this->assertFalse($this->orInFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orInFieldResolver->isOrLessMethod());

        $this->assertFalse($this->orByFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orByFieldResolver->isOrLessMethod());

        $this->assertFalse($this->orAbleToFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orAbleToFieldResolver->isOrLessMethod());

        $this->assertTrue($this->orMoreFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orMoreFieldResolver->isOrLessMethod());

        $this->assertFalse($this->orLessFieldResolver->isOrMoreMethod());
        $this->assertTrue($this->orLessFieldResolver->isOrLessMethod());

        $this->assertFalse($this->andFieldResolver->isOrMoreMethod());
        $this->assertFalse($this->orFieldResolver->isOrLessMethod());
    }

    public function test_returns_true_if_is_setter_for_the_same_entity_only()
    {
        $this->assertTrue($this->withFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->inFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->byFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->ableToFieldResolver->isSetterForSameEntity());
        $this->assertFalse($this->FieldResolver->isSetterForSameEntity());

        $this->assertTrue($this->andWithFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->andInFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->andByFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->andAbleToFieldResolver->isSetterForSameEntity());

        $this->assertTrue($this->orWithFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->orInFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->orByFieldResolver->isSetterForSameEntity());
        $this->assertTrue($this->orAbleToFieldResolver->isSetterForSameEntity());

        $this->assertFalse($this->orMoreFieldResolver->isSetterForSameEntity());
        $this->assertFalse($this->orLessFieldResolver->isSetterForSameEntity());

        $this->assertFalse($this->andFieldResolver->isSetterForSameEntity());
        $this->assertFalse($this->orFieldResolver->isSetterForSameEntity());
    }

    public function test_returns_true_if_starts_only_with_an_and()
    {
        $this->assertFalse($this->withFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->inFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->byFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->ableToFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->FieldResolver->methodStartsOnlyWithAnd());

        $this->assertFalse($this->andWithFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->andInFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->andByFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->andAbleToFieldResolver->methodStartsOnlyWithAnd());

        $this->assertFalse($this->orWithFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->orInFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->orByFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->orAbleToFieldResolver->methodStartsOnlyWithAnd());

        $this->assertFalse($this->orMoreFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->orLessFieldResolver->methodStartsOnlyWithAnd());

        $this->assertTrue($this->andFieldResolver->methodStartsOnlyWithAnd());
        $this->assertFalse($this->orFieldResolver->methodStartsOnlyWithAnd());
    }

    public function test_returns_true_if_starts_only_with_an_Or()
    {
        $this->assertFalse($this->withFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->inFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->byFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->ableToFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->FieldResolver->methodStartsOnlyWithOr());

        $this->assertFalse($this->andWithFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->andInFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->andByFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->andAbleToFieldResolver->methodStartsOnlyWithOr());

        $this->assertFalse($this->orWithFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->orInFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->orByFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->orAbleToFieldResolver->methodStartsOnlyWithOr());

        $this->assertFalse($this->orMoreFieldResolver->methodStartsOnlyWithOr());
        $this->assertFalse($this->orLessFieldResolver->methodStartsOnlyWithOr());

        $this->assertFalse($this->andFieldResolver->methodStartsOnlyWithOr());
        $this->assertTrue($this->orFieldResolver->methodStartsOnlyWithOr());
    }
















}
