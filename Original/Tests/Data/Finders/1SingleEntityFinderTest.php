<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\Creator\FieldCreator;
use Stratum\Original\Data\Exception\MissingActionException;
use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder\SingleEntityFinder;
use Stratum\Original\Test\Data\TestClass\FinderWithSecondaryKeys;

Class SingleEntityFinderTest extends TestCase
{
    protected $actualEventTrace = [];

    public function setUp()
    {

        (object) $this->finder = $this->getMockForAbstractClass(SingleEntityFinder::class);
    }
    public function test_single_onEqualityField_event_is_called()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'id',
            'fieldValue' => 98
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);

        (object) $selfReturned = $this->finder->withId(98);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_single_onEqualityField_event_is_called_with_aletrnative_inField_method()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);



        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');


        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'id',
            'fieldValue' => 98
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);

        (object) $selfReturned = $this->finder->inId(98);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_single_onEqualityField_event_is_called_with_aletrnative_byField_method()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'id',
            'fieldValue' => 98
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->byId(98);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_single_onEqualityField_event_is_called_with_aletrnative_ableToField_method()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'id',
            'fieldValue' => 98
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);                           

        (object) $selfReturned = $this->finder->ableToId(98);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_onConditionalAND_is_called_three_times_asumed_AND_as_default()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onConditionalAND',
            3 => 'onEqualityField',
            4 => 'onConditionalAND',
            5 => 'onEqualityField',
            6 => 'onConditionalAND',
            7 => 'onEqualityField',
            8 => 'onBuilderEnd',
            9 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(4))->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->exactly(3))->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(4))->method('createFrom')->withConsecutive(
            [[
                'fieldName' => 'type',
                'fieldValue' => 'type'
            ]],[[
                'fieldName' => 'author',
                'fieldValue' => 556
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2012
            ]],[[
                'fieldName' => 'receiveComments',
                'fieldValue' => true
            ]]
        )->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);                           

        (object) $selfReturned = $this->finder->withType('type');

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned2 = $this->finder->byAuthor(556);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned3 = $this->finder->inDate(2012);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned4 = $this->finder->ableToReceiveComments(true);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
        $this->assertSame($this->finder, $selfReturned2);
        $this->assertSame($this->finder, $selfReturned3);
        $this->assertSame($this->finder, $selfReturned4);
    }

    public function test_onConditional_AND_is_called_three_times_when_dynamic_method_is_prefixed_with_and()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onConditionalAND',
            3 => 'onEqualityField',
            4 => 'onConditionalAND',
            5 => 'onEqualityField',
            6 => 'onConditionalAND',
            7 => 'onEqualityField',
            8 => 'onBuilderEnd',
            9 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(4))->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->exactly(3))->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(4))->method('createFrom')->withConsecutive(
            [[
                'fieldName' => 'type',
                'fieldValue' => 'type'
            ]],[[
                'fieldName' => 'author',
                'fieldValue' => 556
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2012
            ]],[[
                'fieldName' => 'receiveComments',
                'fieldValue' => true
            ]]
        )->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->withType('type');

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned2 = $this->finder->andByAuthor(556);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned3 = $this->finder->andInDate(2012);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned4 = $this->finder->andAbleToReceiveComments(true);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
        $this->assertSame($this->finder, $selfReturned2);
        $this->assertSame($this->finder, $selfReturned3);
        $this->assertSame($this->finder, $selfReturned4);
    }

    public function test_onConditionalOR_is_called_three_times_when_dynamic_method_is_prefixed_with_or()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onConditionalOR',
            3 => 'onEqualityField',
            4 => 'onConditionalOR',
            5 => 'onEqualityField',
            6 => 'onConditionalOR',
            7 => 'onEqualityField',
            8 => 'onBuilderEnd',
            9 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(4))->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->exactly(3))->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(4))->method('createFrom')->withConsecutive(
            [[
                'fieldName' => 'type',
                'fieldValue' => 'type'
            ]],[[
                'fieldName' => 'author',
                'fieldValue' => 556
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2012
            ]],[[
                'fieldName' => 'receiveComments',
                'fieldValue' => true
            ]]
        )->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);


        (object) $selfReturned = $this->finder->withType('type');

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned2 = $this->finder->orByAuthor(556);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned3 = $this->finder->orInDate(2012);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned4 = $this->finder->orAbleToReceiveComments(true);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
        $this->assertSame($this->finder, $selfReturned2);
        $this->assertSame($this->finder, $selfReturned3);
        $this->assertSame($this->finder, $selfReturned4);
    }

    public function test_onConditionalOR_and_onConditionalAND_are_both_called()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onConditionalAND',
            3 => 'onEqualityField',
            4 => 'onConditionalOR',
            5 => 'onEqualityField',
            6 => 'onConditionalAND',
            7 => 'onEqualityField',
            8 => 'onBuilderEnd',
            9 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(4))->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->exactly(2))->method('onConditionalAND');

        $this->finder->expects($this->exactly(1))->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(4))->method('createFrom')->withConsecutive(
            [[
                'fieldName' => 'type',
                'fieldValue' => 'type'
            ]],[[
                'fieldName' => 'author',
                'fieldValue' => 556
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2012
            ]],[[
                'fieldName' => 'receiveComments',
                'fieldValue' => true
            ]]
        )->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->withType('type');

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned2 = $this->finder->byAuthor(556);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned3 = $this->finder->orInDate(2012);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        (object) $selfReturned4 = $this->finder->andAbleToReceiveComments(true);

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
        $this->assertSame($this->finder, $selfReturned2);
        $this->assertSame($this->finder, $selfReturned3);
        $this->assertSame($this->finder, $selfReturned4);
    }

    public function test_sets_current_state_to_moreOrLessThan()
    {

        
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');

        $this->finder->expects($this->never())->method('onBuilderEnd');

        $this->finder->expects($this->never())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');


        (object) $selfReturned = $this->finder->withDate();

        $this->assertSame($this->finder, $selfReturned);

    }

    public function test_throws_exception_if_called_on_a_state_other_than_directEntity()
    {
        $this->expectException(MissingActionException::class);


        
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');

        $this->finder->expects($this->never())->method('onBuilderEnd');

        $this->finder->expects($this->never())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->once())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');


        (object) $selfReturned = $this->finder->withDate()->withDate();


    }

    public function test_throws_exception_if_called_on_a_state_other_than_moreOrLessThan()
    {
        $this->expectException(MissingActionException::class);

        (object) $selfReturned = $this->finder->HigherThan(4);


    }

    public function test_onMoreThanField_event_gets_called()
    {


        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onMoreThanField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->once())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'date',
            'fieldValue' => 2012
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->withDate()->HigherThan(2012); 

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertSame($this->finder, $selfReturned);
        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);


    }

    public function test_onLessThanField_event_gets_called()
    {


        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onLessThanField',
            2 => 'onBuilderEnd',
            3 => 'onQuery'
        ];

        
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->once())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->once())->method('createFrom')->with([
            'fieldName' => 'date',
            'fieldValue' => 2012
        ])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->withDate()->lowerThan(2012); 

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertSame($this->finder, $selfReturned);
        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);


    }

    

    public function test_all_field_options_combined()
    {


        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onConditionalAND',
            3 => 'onMoreThanField',
            4 => 'onConditionalOR',
            5 => 'onMoreThanField',
            6 => 'onConditionalAND',
            7 => 'onEqualityField',
            8 => 'onConditionalAND',
            9 => 'onLessThanField',
            10 => 'onConditionalAND',
            11 => 'onLessThanField',
            12 => 'onConditionalAND',
            13 => 'onEqualityField',
            14 => 'onConditionalOR',
            15 => 'onEqualityField',
            16 => 'onBuilderEnd',
            17 => 'onQuery'
        ];

        
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(4))->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->exactly(2))->method('onMoreThanField');

        $this->finder->expects($this->exactly(2))->method('onLessThanField');

        $this->finder->expects($this->exactly(5))->method('onConditionalAND');

        $this->finder->expects($this->exactly(2))->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(8))->method('createFrom')->withConsecutive(
            [[
                'fieldName' => 'id',
                'fieldValue' => '56'
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2020
            ]],[[
                'fieldName' => 'date',
                'fieldValue' => 2050
            ]],[[
                'fieldName' => 'title',
                'fieldValue' => 'title'
            ]],[[
                'fieldName' => 'authorId',
                'fieldValue' => 50
            ]],
            [[
                'fieldName' => 'updatedDate',
                'fieldValue' => 2035
            ]],
            [[
                'fieldName' => 'content',
                'fieldValue' => 'content'
            ]],[[
                'fieldName' => 'excerpt',
                'fieldValue' => 'excerpt'
            ]]

        )->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);
        (object) $selfReturned = $this->finder->withId(56) ->andInDate()->HigherThan(2020) ->orInDate()->HigherThan(2050) ->withTitle('title') ->byAuthorId()->LowerThan(50) ->withUpdatedDate()->LowerThan(2035) ->andWithContent('content') ->orWithExcerpt('excerpt') ->find();

        $this->assertTrue($this->finder->stateIs('DirectEntity'));
        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);


    }

    public function test_single_onOrderByAscending_event_is_called()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onOrderByAscending',
            3 => 'onBuilderEnd',
            4 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(2))->method('createFrom')->withConsecutive([[
            'fieldName' => 'id',
            'fieldValue' => 98
        ]],[[
            'fieldName' => 'date',
            'fieldValue' => null
        ]])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);

        (object) $selfReturned = $this->finder->withId(98)->lowestDateFirst();

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_single_onOrderByDescending_event_is_called()
    {

        (object) $fieldCreator = $this->createMock(FieldCreator::class);

        (array) $expectedEventTrace = [
            0 => 'onBuilderStart',
            1 => 'onEqualityField',
            2 => 'onOrderByDescending',
            3 => 'onBuilderEnd',
            4 => 'onQuery'
        ];

        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');

        $this->finder->expects($this->once())->method('onBuilderEnd');

        $this->finder->expects($this->once())->method('onQuery');

        $this->finder->expects($this->never())->method('onMoreThanField');

        $this->finder->expects($this->never())->method('onLessThanField');

        $this->finder->expects($this->never())->method('onConditionalAND');

        $this->finder->expects($this->never())->method('onConditionalOR');

        $fieldCreator->expects($this->exactly(2))->method('createFrom')->withConsecutive([[
            'fieldName' => 'id',
            'fieldValue' => 98
        ]],[[
            'fieldName' => 'date',
            'fieldValue' => null
        ]])->willReturn(new Field);

        $this->finder->setFieldCreator($fieldCreator);

        (object) $selfReturned = $this->finder->withId(98)->highestDateFirst();

        $this->assertTrue($this->finder->stateIs('DirectEntity'));

        $this->finder->find();

        $this->assertEquals($expectedEventTrace, $this->finder->eventTraces()[0]);
        $this->assertSame($this->finder, $selfReturned);
    }

    public function test_returns_true_when_only_the_field_with_a_primary_key_has_been_requested()
    {
        (object) $finder = $this->getMockForAbstractClass(SingleEntityFinder::class);

        $finder->withId(8);

        $this->assertTrue($finder->hasOneSingleEntityBeenRequested()); 
    }

    public function test_returns_false_when_more_than_one_field_has_been_requested()
    {
        (object) $finder = $this->getMockForAbstractClass(SingleEntityFinder::class);

        $finder->withId(8)->orWithType('post');

        $this->assertFalse($finder->hasOneSingleEntityBeenRequested()); 
    }

    public function test_returns_false_when_more_than_one_field_has_been_requested_AND()
    {
        (object) $finder = $this->getMockForAbstractClass(SingleEntityFinder::class);

        $finder->withId(8)->andWithId(6);

        $this->assertFalse($finder->hasOneSingleEntityBeenRequested()); 
    }

    public function test_returns_false_only_primary_key()
    {
        (object) $finder = $this->getMockForAbstractClass(SingleEntityFinder::class);

        $finder->withName('post');

        $this->assertFalse($finder->hasOneSingleEntityBeenRequested()); 
    }

    public function test_returns_true_for_secondary_key()
    {
        (object) $finder = $this->getMockForAbstractClass(FinderWithSecondaryKeys::class);

        $finder->withName('post-slug');

        $this->assertTrue($finder->hasOneSingleEntityBeenRequested()); 
    }








    
} //repeat previous method but with in by adn ableto