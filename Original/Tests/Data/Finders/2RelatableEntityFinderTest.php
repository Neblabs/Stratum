<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\Creator\EntityDataCreator;
use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Exception\MissingActionException;
use Stratum\Original\Data\Exception\UnsupportedRelatableEntityType;
use Stratum\Original\Data\Finder\RelatableEntityFinder;

Class RelatableEntityFinderTest extends SingleEntityFinderTest 
{
    public function setUp()
    {
        $this->finder = $this->getMockForAbstractClass(RelatableEntityFinder::class);

        $this->relatedFinder = $this->getMockForAbstractClass(RelatableEntityFinder::class);

        $this->secondRelatedFinder = $this->getMockForAbstractClass(RelatableEntityFinder::class);

        $this->thirdRelatedFinder = $this->getMockForAbstractClass(RelatableEntityFinder::class);
         
    }

    public function test_throws_exception_if_called_when_setting_a_moreOrLessThanField()
    {
        $this->expectException(MissingActionException::class);

        $this->finder->inDate() 
                      ->with(2);

    }

    public function test_throws_exception_if_called_when_setting_a_moreOrLessThanField_in_and()
    {
        $this->expectException(MissingActionException::class);
    
        $this->finder->inDate() 
                     ->andWith(2);
                     
    }
    
    public function test_throws_exception_if_called_when_setting_a_moreOrLessThanField_in_or()
    {
        $this->expectException(MissingActionException::class);
    
        $this->finder->inDate() 
                     ->orWith(2);
                     
    }
    
    public function test_sets_state_to_Relationship()
    {
        $this->assertTrue($this->finder->stateIs('DirectEntity'));
    
        $this->finder->with(1);
    
        $this->assertTrue($this->finder->stateIs('SetRelationship'));
    }
    
    public function test_sets_state_to_Relationship_with_andWith()
    {
        $this->assertTrue($this->finder->stateIs('DirectEntity'));
    
        $this->finder->andWith(1);
    
        $this->assertTrue($this->finder->stateIs('SetRelationship'));
    }
    
    public function test_sets_state_to_Relationship_with_orWith()
    {
        $this->assertTrue($this->finder->stateIs('DirectEntity'));
    
        $this->finder->orWith(1);
    
        $this->assertTrue($this->finder->stateIs('SetRelationship'));
    }
    
    public function test_with_andWith_orWith_return_the_same_instance()
    {
        (object) $finderAndWith = $this->getMockForAbstractClass(RelatableEntityFinder::class);
        (object) $finderOrWith = $this->getMockForAbstractClass(RelatableEntityFinder::class);
    
        (object) $anotherReferenceOfFinder = $this->finder->with(6);
        (object) $anotherReferenceOfFinderAndWith = $finderAndWith->andWith(6);
        (object) $anotherReferenceOfFinderOrWith = $finderOrWith->orWith(6);
    
        $this->assertSame($this->finder, $anotherReferenceOfFinder);
        $this->assertSame($finderAndWith, $anotherReferenceOfFinderAndWith);
        $this->assertSame($finderOrWith, $anotherReferenceOfFinderOrWith);
    }
    
    public function test_throws_exception_when_calling_entity_as_method_in_a_state_that_is_not_Relationship()
    { 
       $this->expectException(MissingActionException::class);
    
       $this->finder->comments();
    
    }
    public function test_throws_exception_when_calling_MoreThanentity_as_method_in_a_state_that_is_not_Relationship()
    { 
       $this->expectException(MissingActionException::class);
    
       $this->finder->orMoreComments();
    
    }
    public function test_throws_exception_when_calling_LessThanentity_as_method_in_a_state_that_is_not_Relationship()
    {
        $this->expectException(MissingActionException::class);
    
        $this->finder->orLessComments();
    }
    
    public function test_throws_exception_if_finder_does_not_support_an_entity_with_entity_Type_as_method()
    {
        $this->expectException(UnsupportedRelatableEntityType::class);
    
        $this->finder->oneToManyRelationships = [];
    
        $this->finder->with(2)->Comments();
    }
    
    public function test_throws_exception_if_finder_does_not_support_an_entity()
    {
        $this->expectException(UnsupportedRelatableEntityType::class);
    
        $this->finder->oneToManyRelationships = [];
    
        $this->finder->with(2)->orMoreComments();
    }
    
    public function test_throws_exception_if_finder_does_not_support_an_entity_with_orLessMethod()
    {
        $this->expectException(UnsupportedRelatableEntityType::class);
    
        $this->finder->oneToManyRelationships = [];
    
        $this->finder->with(2)->orLessComments();
    }
    
    
    public function test_calls_onOneToManyRelationshipStart_and_end_events_equality()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);

        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->with(2)->Comments();

        $this->assertTrue($this->finder->stateIs('Relationship'));

        $this->finder->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_end_events_moreThan()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => true,
            'isLessThan' => false
        ])->willReturn(new EntityData);

        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->with(2)->orMoreComments()->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_end_events_lessThan()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => true
        ])->willReturn(new EntityData);

        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->with(2)->orLessComments()->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_end_events_equality_in()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);

        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->in(2)->Comments()->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_end_events_equality_by()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);

        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->by(2)->Comments()->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_singleEntityFinder_events()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onEqualityField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->with(2)->Comments()
                     ->inDate(2020)
                     ->byAuthor(87)
                     ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }

    public function test_calls_onOneToManyRelationshipStart_and_singleEntityFinder_events_onAndWith()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onBuilderStart');    
        $this->finder->expects($this->exactly(1))->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onOneToManyRelationShipStart',
                4 => 'onOneToManyRelationShipEnd',
                5 => 'onBuilderEnd',
                6 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onEqualityField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->inDate(2020)
            ->andWith(2)->Comments()->byAuthor(98)->inDate(2020)
            ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_and_singleEntityFinder_events_onOrWith()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->once())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->once())->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalOR',
                3 => 'onOneToManyRelationShipStart',
                4 => 'onOneToManyRelationShipEnd',
                5 => 'onBuilderEnd',
                6 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onEqualityField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
        $this->finder->inDate(2020)
                     ->orWith(2)->Comments()->byAuthor(98)->inDate(2020)
                     ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
//
    public function test_manually_ends_the_relationship_and_calls_onAND_event()
    {
        $this->finder->oneToManyRelationships = ['comments'];

    $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->once())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 =>'onConditionalAND',
                4 =>'onEqualityField',
                5 =>'onConditionalOR',
                6 =>'onEqualityField',
                7 => 'onBuilderEnd',
                8 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . lcfirst(get_class($this->finder));
    
        $this->finder->with(2)->Comments()
                     ->inDate(2020)
                     ->$andSameEntity()
                     ->byAuthor(54)
                     ->orInDate(2030)
                     ->find();
        
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_manually_ends_the_relationship_and_calls_onAND_event_moreThan()
    {
        $this->finder->oneToManyRelationships = ['comments'];

    $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->once())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => true,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 =>'onConditionalAND',
                4 =>'onEqualityField',
                5 =>'onConditionalOR',
                6 =>'onEqualityField',
                7 => 'onBuilderEnd',
                8 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->with(2)->orMoreComments()
                     ->inDate(2020)
                     ->$andSameEntity()
                     ->byAuthor(54)
                     ->orInDate(2030)
                     ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_manually_ends_the_relationship_and_calls_onAND_event_lessThan()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->once())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => true
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 =>'onConditionalAND',
                4 =>'onEqualityField',
                5 =>'onConditionalOR',
                6 =>'onEqualityField',
                7 => 'onBuilderEnd',
                8 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->with(2)->orLessComments()
                     ->inDate(2020)
                     ->$andSameEntity()
                     ->byAuthor(54)
                     ->orInDate(2030)
                     ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_manually_ends_the_relationship_and_calls_onAND_event_moreThan_event()
    {
        $this->finder->oneToManyRelationships = ['comments'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->exactly(2))->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->exactly(2))->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 =>'onConditionalOR',
                4 =>'onEqualityField',
                5 =>'onConditionalOR',
                6 =>'onEqualityField',
                7 => 'onBuilderEnd',
                8 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $orSameEntity = 'or' . get_class($this->finder);
    
        $this->finder->with(2)->Comments()
                     ->inDate(2020)
                     ->$orSameEntity()
                     ->byAuthor(54)
                     ->orInDate(2030)
                     ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onOneToManyRelationshipStart_occurs_twice()
    {
        $this->finder->oneToManyRelationships = ['comments', 'likes'];

        $this->finder->method('relatedFinder')->will(
            $this->onConsecutiveCalls($this->relatedFinder, $this->secondRelatedFinder)
            );
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->exactly(2))->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->exactly(2))->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->exactly(1))->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');



        $this->secondRelatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->secondRelatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->secondRelatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->secondRelatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->secondRelatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->secondRelatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->secondRelatedFinder->expects($this->once())->method('onBuilderStart');
        $this->secondRelatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->secondRelatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->secondRelatedFinder->expects($this->never())->method('onQuery');
    
        $this->secondRelatedFinder->expects($this->once())->method('onMoreThanField');
    
        $this->secondRelatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->secondRelatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->secondRelatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(2))->method('createFrom')->withConsecutive([[
            'entityType' => 'comments',
            'numberOfEntities' => 2,
            'isMoreThan' => false,
            'isLessThan' => false
        ]],[[
            'entityType' => 'likes',
            'numberOfEntities' => 10,
            'isMoreThan' => true,
            'isLessThan' => false
        ]]
    
        )->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onOneToManyRelationShipStart',
                2 => 'onOneToManyRelationShipEnd',
                3 =>'onConditionalOR',
                4 => 'onOneToManyRelationShipStart',
                5 => 'onOneToManyRelationShipEnd',
                6 => 'onBuilderEnd',
                7 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ],
            [
                0 => 'onBuilderStart',
                1 => 'onMoreThanField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $orSameEntity = 'or' . get_class($this->finder);
    
        $this->finder->with(2)->Comments()->inDate(2020)
                     ->$orSameEntity()
                     ->with(10)->orMoreLikes()->inDate()->higherThan(2024)
                     ->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_onMantToOneRelationshipStartevent()
    {
        $this->finder->manyToOneRelationships = ['authors'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->once())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'authors',
            'numberOfEntities' => null,
            'isMoreThan' => null,
            'isLessThan' => null
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onManyToOneRelationShipStart',
                2 => 'onManyToOneRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onLessThanField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
            $this->finder->byAuthors()->withLastName('Doe')->inJoinDate()->lowerThan(2020)
                         ->find();
    
            $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls__ANDConditional_event_before_onMantToOneRelationshipStartevent()
    {
        $this->finder->manyToOneRelationships = ['authors'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->once())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'authors',
            'numberOfEntities' => null,
            'isMoreThan' => null,
            'isLessThan' => null
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onManyToOneRelationShipStart',
                4 => 'onManyToOneRelationShipEnd',
                5 => 'onBuilderEnd',
                6 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onLessThanField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
            $this->finder->withType('Link')
                         ->byAuthors()->withLastName('Doe')->inJoinDate()->lowerThan(2020)
                         ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls__ORConditional_event_before_onMantToOneRelationshipStartevent()
    {
        $this->finder->manyToOneRelationships = ['authors'];

    $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->exactly(1))->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->once())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'authors',
            'numberOfEntities' => null,
            'isMoreThan' => null,
            'isLessThan' => null
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalOR',
                3 => 'onManyToOneRelationShipStart',
                4 => 'onManyToOneRelationShipEnd',
                5 => 'onBuilderEnd',
                6 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onLessThanField',
                4 => 'onBuilderEnd'
            ]

        ];
    
    
            $this->finder->withType('Link')
                         ->orByAuthors()->withLastName('Doe')->inJoinDate()->lowerThan(2020)
                         ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_on_ManyToOneRelationship_when_calling_andSameEntity()
    {
        $this->finder->manyToOneRelationships = ['authors'];

    $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->never())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->once())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->once())->method('onConditionalAND');
    
        $this->finder->expects($this->once())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->once())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'authors',
            'numberOfEntities' => null,
            'isMoreThan' => null,
            'isLessThan' => null
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalOR',
                3 => 'onManyToOneRelationShipStart',
                4 => 'onManyToOneRelationShipEnd',
                5 => 'onConditionalAND',
                6 => 'onMoreThanField',
                7 => 'onBuilderEnd',
                8 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalAND',
                3 => 'onLessThanField',
                4 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->withType('Link')
                         ->orByAuthors()->withLastName('Doe')->inJoinDate()->lowerThan(2020)
                         ->$andSameEntity()
                         ->inDate()
                         ->higherThan(2019)
                         ->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_on_ManyToManyRelationship()
    {
        $this->finder->manyToManyRelationships = ['categories'];

        $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');

    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'categories',
            'numberOfEntities' => 2,
            'isMoreThan' => true,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onManyToManyRelationShipStart',
                2 => 'onManyToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->in(2)->orMoreCategories()
                         ->find();
    
        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    public function test_calls_on_ManyToManyRelationship_no_entity_number()
    {
        $this->finder->manyToManyRelationships = ['categories'];
          $this->finder->method('relatedFinder')->willReturn($this->relatedFinder);
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->never())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->never())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->never())->method('onMoreThanField');
    
        $this->finder->expects($this->never())->method('onLessThanField');
    
        $this->finder->expects($this->never())->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');

        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalOR');
    
        $entityDataCreator->expects($this->exactly(1))->method('createFrom')->with([
            'entityType' => 'categories',
            'numberOfEntities' => null,
            'isMoreThan' => false,
            'isLessThan' => false
        ])->willReturn(new EntityData);
    
        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onManyToManyRelationShipStart',
                2 => 'onManyToManyRelationShipEnd',
                3 => 'onBuilderEnd',
                4 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->in()->Categories()->withName('News')
                         ->find();

        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }

    public function test_all_cobined()
    {
        $this->finder->oneToManyRelationships = ['comments'];
        $this->finder->manyToOneRelationships = ['authors'];
        $this->finder->manyToManyRelationships = ['categories'];
        
        $this->finder->method('relatedFinder')->will(
            $this->onConsecutiveCalls($this->relatedFinder, $this->secondRelatedFinder, $this->thirdRelatedFinder)
        );
    
        (object) $entityDataCreator = $this->createMock(EntityDataCreator::class);
    
        $this->finder->setEntityDataCreator($entityDataCreator);
    
        $this->finder->expects($this->once())->method('onOneToManyRelationShipStart');
        $this->finder->expects($this->once())->method('onOneToManyRelationShipEnd');
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipStart') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipStart') ;
    
        $this->finder->expects($this->once())->method('onManyToOneRelationShipEnd') ;
        $this->finder->expects($this->once())->method('onManyToManyRelationShipEnd') ;
    
        $this->finder->expects($this->once())->method('onBuilderStart');
        $this->finder->expects($this->once())->method('onEqualityField');
    
        $this->finder->expects($this->once())->method('onBuilderEnd');
    
        $this->finder->expects($this->once())->method('onQuery');
    
        $this->finder->expects($this->once())->method('onMoreThanField');
    
        $this->finder->expects($this->once())->method('onLessThanField');
    
        $this->finder->expects($this->exactly(5))->method('onConditionalAND');
    
        $this->finder->expects($this->never())->method('onConditionalOR');


        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->relatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->relatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderStart');
        $this->relatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->relatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->relatedFinder->expects($this->never())->method('onQuery');
    
        $this->relatedFinder->expects($this->once())->method('onMoreThanField');
    
        $this->relatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->relatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->relatedFinder->expects($this->once())->method('onConditionalOR');

        $this->secondRelatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->secondRelatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->secondRelatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->secondRelatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->secondRelatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->secondRelatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->secondRelatedFinder->expects($this->once())->method('onBuilderStart');
        $this->secondRelatedFinder->expects($this->never())->method('onEqualityField');
    
        $this->secondRelatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->secondRelatedFinder->expects($this->never())->method('onQuery');
    
        $this->secondRelatedFinder->expects($this->once())->method('onMoreThanField');
    
        $this->secondRelatedFinder->expects($this->once())->method('onLessThanField');
    
        $this->secondRelatedFinder->expects($this->once())->method('onConditionalAND');
    
        $this->secondRelatedFinder->expects($this->never())->method('onConditionalOR');



        $this->thirdRelatedFinder->expects($this->never())->method('onOneToManyRelationShipStart');
        $this->thirdRelatedFinder->expects($this->never())->method('onOneToManyRelationShipEnd');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onManyToOneRelationShipStart');
        $this->thirdRelatedFinder->expects($this->never())->method('onManyToManyRelationShipStart');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onManyToOneRelationShipEnd');
        $this->thirdRelatedFinder->expects($this->never())->method('onManyToManyRelationShipEnd');
    
        $this->thirdRelatedFinder->expects($this->once())->method('onBuilderStart');
        $this->thirdRelatedFinder->expects($this->once())->method('onEqualityField');
    
        $this->thirdRelatedFinder->expects($this->once())->method('onBuilderEnd');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onQuery');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onMoreThanField');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onLessThanField');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onConditionalAND');
    
        $this->thirdRelatedFinder->expects($this->never())->method('onConditionalOR');


    
        $entityDataCreator->expects($this->exactly(3))->method('createFrom')->withConsecutive([[
            'entityType' => 'comments',
            'numberOfEntities' => 1,
            'isMoreThan' => true,
            'isLessThan' => false
        ]],[[
            'entityType' => 'categories',
            'numberOfEntities' => null,
            'isMoreThan' => false,
            'isLessThan' => false
        ]],[[
            'entityType' => 'authors',
            'numberOfEntities' => null,
            'isMoreThan' => false,
            'isLessThan' => false
        ]]
    
        )->willReturn(new EntityData);


        $expectedEventTraces = [
            [
                0 => 'onBuilderStart',
                1 => 'onLessThanField',
                2 => 'onConditionalAND',
                3 => 'onOneToManyRelationShipStart',
                4 => 'onOneToManyRelationShipEnd',
                5 => 'onConditionalAND', 
                6 => 'onManyToManyRelationShipStart',
                7 => 'onManyToManyRelationShipEnd',    
                8 => 'onConditionalAND', 
                9 => 'onEqualityField',
                10 => 'onConditionalAND', 
                11 => 'onManyToOneRelationShipStart',
                12 => 'onManyToOneRelationShipEnd', 
                13 => 'onConditionalAND', 
                14 => 'onMoreThanField',          
                15 => 'onBuilderEnd',
                16 => 'onQuery'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField',
                2 => 'onConditionalOR', 
                3 => 'onMoreThanField', 
                4 => 'onBuilderEnd'
            ] ,
            [
                0 => 'onBuilderStart',
                1 => 'onMoreThanField',
                2 => 'onConditionalAND', 
                3 => 'onLessThanField', 
                4 => 'onBuilderEnd'
            ],
            [
                0 => 'onBuilderStart',
                1 => 'onEqualityField', 
                2 => 'onBuilderEnd'
            ]

        ];
    
        (string) $andSameEntity = 'and' . get_class($this->finder);
    
        $this->finder->withId()->lowerThan(50)
                    ->with(1)->orMoreComments()->byAuthor(40)->orInDate()->higherThan(2010)
                    ->$andSameEntity()
                    ->with()->Categories()->withId()->higherThan(20)->andWithId()->lowerThan(30)
                    ->$andSameEntity()
                    ->withType('post')
                    ->andWithAuthors()->withRole('admin')
                    ->$andSameEntity()
                    ->inDate()->higherThan(2030)
                    ->find();



        $this->assertEquals($expectedEventTraces, $this->finder->eventtraces());
    }
    
    





}