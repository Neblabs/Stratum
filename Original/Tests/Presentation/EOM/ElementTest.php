<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Exception\ForbiddenAttributeWriteException;
use Stratum\Original\Presentation\Exception\InvalidChildException;

Class ElementTest extends TestCase
{
    protected $div;

    public function setUp()
    {
        (object) $this->div = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    }
    public function test_sets_correct_element_data()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $this->assertEquals('div', $element->type());
        $this->assertFalse($element->isVoid());

        $this->assertFalse($element->children()->wereFound());
        $this->assertEquals(0, $element->children()->count());

        $this->assertInstanceOf(GroupOfNodes::class, $element->previousSiblings());
        $this->assertInstanceOf(GroupOfNodes::class, $element->nextSiblings());
        $this->assertFalse($element->previousSiblings()->wereFound());
        $this->assertEquals(0, $element->previousSiblings()->count());

        $this->assertFalse($element->nextSiblings()->wereFound());
        $this->assertEquals(0, $element->nextSiblings()->count());


    }

    public function test_sets_correct_element_data_is_void()
    {
        (object) $element = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        $this->assertEquals('a', $element->type());
        $this->assertTrue($element->isVoid());

        $this->assertFalse($element->children()->wereFound());
        $this->assertEquals(0, $element->children()->count());

        $this->assertFalse($element->previousSiblings()->wereFound());
        $this->assertEquals(0, $element->previousSiblings()->count());

        $this->assertFalse($element->nextSiblings()->wereFound());
        $this->assertEquals(0, $element->nextSiblings()->count());

    }

    public function test_returns_true_when_correct_type()
    {
        $this->assertTrue($this->div->is('div'));
        $this->assertFalse($this->div->is('p'));
    }

    public function test_adds_correct_child()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(1, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());

        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertFalse($childElement->nextSiblings()->wereFound());
        $this->assertFalse($childElement->children()->wereFound());


    }

    public function test_throws_exception_when_attempting_to_add_a_children_to_a_void_eleme()
    {
        $this->expectException(InvalidChildException::class);
        $this->expectExceptionMessage('A void element cannot have children');

        (object) $voidElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $voidElement->addChild($childElement);

    }

    public function test_adds_two_children()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        $this->div->addChild($secondChildElement);

        $this->assertInstanceOf(GroupOfNodes::class, $this->div->children());
        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(2, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->last());

        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $childElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->first());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertFalse($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $secondChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->first());
        $this->assertFalse($secondChildElement->children()->wereFound());

    }

    public function test_adds_five_children()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        $this->div->addChild($secondChildElement);

        (object) $thirdChildElement = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $this->div->addChild($thirdChildElement);

        (object) $fourthChildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $this->div->addChild($fourthChildElement);

        (object) $fifthChildElement = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $this->div->addChild($fifthChildElement);


        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(5, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());
        
        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(4, $childElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->first());
        $this->assertSame($thirdChildElement, $childElement->nextSiblings()->atPosition(2));
        $this->assertSame($fourthChildElement, $childElement->nextSiblings()->atPosition(3));
        $this->assertSame($fifthChildElement, $childElement->nextSiblings()->last());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertEquals(1, $secondChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->first());
        $this->assertTrue($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(3, $secondChildElement->nextSiblings()->count());
        $this->assertSame($thirdChildElement, $secondChildElement->nextSiblings()->first());
        $this->assertSame($fourthChildElement, $secondChildElement->nextSiblings()->atPosition(2));
        $this->assertSame($fifthChildElement, $secondChildElement->nextSiblings()->last());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertSame($this->div, $thirdChildElement->parent());
        $this->assertTrue($thirdChildElement->previousSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $thirdChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $thirdChildElement->previousSiblings()->last());
        $this->assertTrue($thirdChildElement->nextSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->nextSiblings()->count());
        $this->assertSame($fourthChildElement, $thirdChildElement->nextSiblings()->first());
        $this->assertSame($fifthChildElement, $thirdChildElement->nextSiblings()->last());
        $this->assertFalse($thirdChildElement->children()->wereFound());

        $this->assertSame($this->div, $fourthChildElement->parent());
        $this->assertTrue($fourthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(3, $fourthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fourthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fourthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fourthChildElement->previousSiblings()->last());
        $this->assertTrue($fourthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $fourthChildElement->nextSiblings()->count());
        $this->assertSame($fifthChildElement, $fourthChildElement->nextSiblings()->first());
        $this->assertFalse($fourthChildElement->children()->wereFound());

        $this->assertSame($this->div, $fifthChildElement->parent());
        $this->assertTrue($fifthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(4, $fifthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fifthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fifthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fifthChildElement->previousSiblings()->atPosition(3));
        $this->assertSame($fourthChildElement, $fifthChildElement->previousSiblings()->last());
        $this->assertFalse($fifthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(0, $fifthChildElement->nextSiblings()->count());
        $this->assertFalse($fifthChildElement->children()->wereFound());


    }

    public function test_adds_five_children_at_once_from_a_GroupOfNodes()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        (object) $thirdChildElement = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        (object) $fourthChildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $fifthChildElement = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $GroupOfNodes = new GroupOfNodes([
            $childElement,
            $secondChildElement,
            $thirdChildElement,
            $fourthChildElement,
            $fifthChildElement,
        ]);

        $this->div->addChildren($GroupOfNodes);


        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(5, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());
        
        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(4, $childElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->first());
        $this->assertSame($thirdChildElement, $childElement->nextSiblings()->atPosition(2));
        $this->assertSame($fourthChildElement, $childElement->nextSiblings()->atPosition(3));
        $this->assertSame($fifthChildElement, $childElement->nextSiblings()->last());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertEquals(1, $secondChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->first());
        $this->assertTrue($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(3, $secondChildElement->nextSiblings()->count());
        $this->assertSame($thirdChildElement, $secondChildElement->nextSiblings()->first());
        $this->assertSame($fourthChildElement, $secondChildElement->nextSiblings()->atPosition(2));
        $this->assertSame($fifthChildElement, $secondChildElement->nextSiblings()->last());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertSame($this->div, $thirdChildElement->parent());
        $this->assertTrue($thirdChildElement->previousSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $thirdChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $thirdChildElement->previousSiblings()->last());
        $this->assertTrue($thirdChildElement->nextSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->nextSiblings()->count());
        $this->assertSame($fourthChildElement, $thirdChildElement->nextSiblings()->first());
        $this->assertSame($fifthChildElement, $thirdChildElement->nextSiblings()->last());
        $this->assertFalse($thirdChildElement->children()->wereFound());

        $this->assertSame($this->div, $fourthChildElement->parent());
        $this->assertTrue($fourthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(3, $fourthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fourthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fourthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fourthChildElement->previousSiblings()->last());
        $this->assertTrue($fourthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $fourthChildElement->nextSiblings()->count());
        $this->assertSame($fifthChildElement, $fourthChildElement->nextSiblings()->first());
        $this->assertFalse($fourthChildElement->children()->wereFound());

        $this->assertSame($this->div, $fifthChildElement->parent());
        $this->assertTrue($fifthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(4, $fifthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fifthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fifthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fifthChildElement->previousSiblings()->atPosition(3));
        $this->assertSame($fourthChildElement, $fifthChildElement->previousSiblings()->last());
        $this->assertFalse($fifthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(0, $fifthChildElement->nextSiblings()->count());
        $this->assertFalse($fifthChildElement->children()->wereFound());


    }

    public function test_adds_five_children_four_at_once_from_a_GroupOfNodes_and_one_individually()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        (object) $thirdChildElement = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        (object) $fourthChildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $fifthChildElement = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);
        
        $GroupOfNodes = new GroupOfNodes([
            $secondChildElement,
            $thirdChildElement,
            $fourthChildElement,
            $fifthChildElement,
        ]);

        $this->div->addChildren($GroupOfNodes);


        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(5, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());
        
        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(4, $childElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->first());
        $this->assertSame($thirdChildElement, $childElement->nextSiblings()->atPosition(2));
        $this->assertSame($fourthChildElement, $childElement->nextSiblings()->atPosition(3));
        $this->assertSame($fifthChildElement, $childElement->nextSiblings()->last());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertEquals(1, $secondChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->first());
        $this->assertTrue($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(3, $secondChildElement->nextSiblings()->count());
        $this->assertSame($thirdChildElement, $secondChildElement->nextSiblings()->first());
        $this->assertSame($fourthChildElement, $secondChildElement->nextSiblings()->atPosition(2));
        $this->assertSame($fifthChildElement, $secondChildElement->nextSiblings()->last());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertSame($this->div, $thirdChildElement->parent());
        $this->assertTrue($thirdChildElement->previousSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $thirdChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $thirdChildElement->previousSiblings()->last());
        $this->assertTrue($thirdChildElement->nextSiblings()->wereFound());
        $this->assertEquals(2, $thirdChildElement->nextSiblings()->count());
        $this->assertSame($fourthChildElement, $thirdChildElement->nextSiblings()->first());
        $this->assertSame($fifthChildElement, $thirdChildElement->nextSiblings()->last());
        $this->assertFalse($thirdChildElement->children()->wereFound());

        $this->assertSame($this->div, $fourthChildElement->parent());
        $this->assertTrue($fourthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(3, $fourthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fourthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fourthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fourthChildElement->previousSiblings()->last());
        $this->assertTrue($fourthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $fourthChildElement->nextSiblings()->count());
        $this->assertSame($fifthChildElement, $fourthChildElement->nextSiblings()->first());
        $this->assertFalse($fourthChildElement->children()->wereFound());

        $this->assertSame($this->div, $fifthChildElement->parent());
        $this->assertTrue($fifthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(4, $fifthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fifthChildElement->previousSiblings()->first());
        $this->assertSame($secondChildElement, $fifthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fifthChildElement->previousSiblings()->atPosition(3));
        $this->assertSame($fourthChildElement, $fifthChildElement->previousSiblings()->last());
        $this->assertFalse($fifthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(0, $fifthChildElement->nextSiblings()->count());
        $this->assertFalse($fifthChildElement->children()->wereFound());


    }

    public function test_moves_element_with_same_parent()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        $this->div->addChild($secondChildElement);

        (object) $thirdChildElement = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $this->div->addChild($thirdChildElement);

        (object) $fourthChildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $this->div->addChild($fourthChildElement);

        (object) $fifthChildElement = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $this->div->addChild($fifthChildElement);


        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(5, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());
        
        $fourthChildElement->moveBefore($childElement);

        $this->assertEquals(5, $childElement->parent()->children()->count());

        $this->assertSame($fourthChildElement, $this->div->children()->first());
        $this->assertSame($childElement, $this->div->children()->atPosition(2));
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());

        $this->assertSame($this->div, $fourthChildElement->parent());
        $this->assertFalse($fourthChildElement->previousSiblings()->wereFound());
        $this->assertTrue($fourthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(4, $fourthChildElement->nextSiblings()->count());
        $this->assertSame($childElement, $fourthChildElement->nextSiblings()->first());
        $this->assertSame($secondChildElement, $fourthChildElement->nextSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $fourthChildElement->nextSiblings()->atPosition(3));
        $this->assertSame($fifthChildElement, $fourthChildElement->nextSiblings()->last());
        $this->assertFalse($fourthChildElement->children()->wereFound());

        $this->assertSame($this->div, $childElement->parent());
        $this->assertTrue($childElement->previousSiblings()->wereFound());
        $this->assertEquals(1, $childElement->previousSiblings()->count());
        $this->assertSame($fourthChildElement, $childElement->previousSiblings()->first());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(3, $childElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->first());
        $this->assertSame($thirdChildElement, $childElement->nextSiblings()->atPosition(2));
        $this->assertSame($fifthChildElement, $childElement->nextSiblings()->last());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertEquals(2, $secondChildElement->previousSiblings()->count());
        $this->assertSame($fourthChildElement, $secondChildElement->previousSiblings()->first());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->last());
        $this->assertTrue($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(2, $secondChildElement->nextSiblings()->count());
        $this->assertSame($thirdChildElement, $secondChildElement->nextSiblings()->first());
        $this->assertSame($fifthChildElement, $secondChildElement->nextSiblings()->last());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertSame($this->div, $thirdChildElement->parent());
        $this->assertTrue($thirdChildElement->previousSiblings()->wereFound());
        $this->assertEquals(3, $thirdChildElement->previousSiblings()->count());
        $this->assertSame($fourthChildElement, $thirdChildElement->previousSiblings()->first());
        $this->assertSame($childElement, $thirdChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($secondChildElement, $thirdChildElement->previousSiblings()->last());
        $this->assertTrue($thirdChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $thirdChildElement->nextSiblings()->count());
        $this->assertSame($fifthChildElement, $thirdChildElement->nextSiblings()->first());
        $this->assertFalse($thirdChildElement->children()->wereFound());

        $this->assertSame($this->div, $fifthChildElement->parent());
        $this->assertTrue($fifthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(4, $fifthChildElement->previousSiblings()->count());
        $this->assertSame($fourthChildElement, $fifthChildElement->previousSiblings()->first());
        $this->assertSame($childElement, $fifthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($secondChildElement, $fifthChildElement->previousSiblings()->atPosition(3));
        $this->assertSame($thirdChildElement, $fifthChildElement->previousSiblings()->last());
        $this->assertFalse($fifthChildElement->nextSiblings()->wereFound());
        $this->assertFalse($fifthChildElement->children()->wereFound());


    }

    public function test_adds_moves_element_from_different_parents()
    {
        (object) $firstParent = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);


        (object) $secondParent = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);


        (object) $firstParentsFirstChild = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $firstParent->addChild($firstParentsFirstChild);

        (object) $firstParentsSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $firstParent->addChild($firstParentsSecondChild);

        (object) $secondParentsFirstChild = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $secondParent->addChild($secondParentsFirstChild);

        (object) $secondParentsSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $secondParent->addChild($secondParentsSecondChild);



        $this->assertTrue($firstParent->children()->wereFound());
        $this->assertEquals(2, $firstParent->children()->count());
        $this->assertNull($firstParent->parent());
        $this->assertFalse($firstParent->previousSiblings()->wereFound());
        $this->assertFalse($firstParent->nextSiblings()->wereFound());

        $this->assertTrue($secondParent->children()->wereFound());
        $this->assertEquals(2, $secondParent->children()->count());
        $this->assertNull($secondParent->parent());
        $this->assertFalse($secondParent->previousSiblings()->wereFound());
        $this->assertFalse($secondParent->nextSiblings()->wereFound());


        $this->assertSame($firstParentsFirstChild, $firstParent->children()->first());
        $this->assertSame($firstParentsSecondChild, $firstParent->children()->last());

        $this->assertSame($secondParentsFirstChild, $secondParent->children()->first());
        $this->assertSame($secondParentsSecondChild, $secondParent->children()->last());

        $secondParentsFirstChild->moveBefore($firstParentsSecondChild);

        $this->assertTrue($firstParent->children()->wereFound());
        $this->assertEquals(3, $firstParent->children()->count());
        $this->assertNull($firstParent->parent());
        $this->assertFalse($firstParent->previousSiblings()->wereFound());
        $this->assertFalse($firstParent->nextSiblings()->wereFound());

        $this->assertTrue($secondParent->children()->wereFound());
        $this->assertEquals(1, $secondParent->children()->count());
        $this->assertNull($secondParent->parent());
        $this->assertFalse($secondParent->previousSiblings()->wereFound());
        $this->assertFalse($secondParent->nextSiblings()->wereFound());

        $this->assertSame($firstParentsFirstChild, $firstParent->children()->first());
        $this->assertSame($secondParentsFirstChild, $firstParent->children()->atPosition(2));
        $this->assertSame($firstParentsSecondChild, $firstParent->children()->last());

        $this->assertSame($secondParentsSecondChild, $secondParent->children()->first());

        $this->assertSame($firstParent, $firstParentsFirstChild->parent());
        $this->assertFalse($firstParentsFirstChild->previousSiblings()->wereFound());
        $this->assertTrue($firstParentsFirstChild->nextSiblings()->wereFound());
        $this->assertEquals(2, $firstParentsFirstChild->nextSiblings()->count());
        $this->assertSame($secondParentsFirstChild, $firstParentsFirstChild->nextSiblings()->first());
        $this->assertSame($firstParentsSecondChild, $firstParentsFirstChild->nextSiblings()->last());
        $this->assertFalse($firstParentsFirstChild->children()->wereFound());

        $this->assertSame($firstParent, $secondParentsFirstChild->parent());
        $this->assertTrue($secondParentsFirstChild->previousSiblings()->wereFound());
        $this->assertEquals(1, $secondParentsFirstChild->previousSiblings()->count());
        $this->assertSame($firstParentsFirstChild, $secondParentsFirstChild->previousSiblings()->first());
        $this->assertTrue($secondParentsFirstChild->nextSiblings()->wereFound());
        $this->assertEquals(1, $secondParentsFirstChild->nextSiblings()->count());
        $this->assertSame($firstParentsSecondChild, $secondParentsFirstChild->nextSiblings()->first());
        $this->assertFalse($secondParentsFirstChild->children()->wereFound());

        $this->assertSame($firstParent, $firstParentsSecondChild->parent());
        $this->assertTrue($firstParentsSecondChild->previousSiblings()->wereFound());
        $this->assertEquals(2, $firstParentsSecondChild->previousSiblings()->count());
        $this->assertSame($firstParentsFirstChild, $firstParentsSecondChild->previousSiblings()->first());
        $this->assertSame($secondParentsFirstChild, $firstParentsSecondChild->previousSiblings()->last());
        $this->assertFalse($firstParentsSecondChild->nextSiblings()->wereFound());
        $this->assertFalse($firstParentsSecondChild->children()->wereFound());

        $this->assertSame($secondParent, $secondParentsSecondChild->parent());
        $this->assertFalse($secondParentsSecondChild->previousSiblings()->wereFound());
        $this->assertFalse($secondParentsSecondChild->nextSiblings()->wereFound());
        $this->assertFalse($secondParentsSecondChild->children()->wereFound());

    }

    public function test_moves_after_element_with_same_parent()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($childElement);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);

        $this->div->addChild($secondChildElement);

        (object) $thirdChildElement = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $this->div->addChild($thirdChildElement);

        (object) $fourthChildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $this->div->addChild($fourthChildElement);

        (object) $fifthChildElement = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $this->div->addChild($fifthChildElement);


        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(5, $this->div->children()->count());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());


        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());
        
        $fourthChildElement->moveAfter($childElement);

        $this->assertEquals(5, $childElement->parent()->children()->count());

        $this->assertSame($childElement, $this->div->children()->first());
        $this->assertSame($fourthChildElement, $this->div->children()->atPosition(2));
        $this->assertSame($secondChildElement, $this->div->children()->atPosition(3));
        $this->assertSame($thirdChildElement, $this->div->children()->atPosition(4));
        $this->assertSame($fifthChildElement, $this->div->children()->last());

        $this->assertSame($this->div, $childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertTrue($childElement->nextSiblings()->wereFound());
        $this->assertEquals(4, $childElement->nextSiblings()->count());
        $this->assertSame($fourthChildElement, $childElement->nextSiblings()->first());
        $this->assertSame($secondChildElement, $childElement->nextSiblings()->atPosition(2));
        $this->assertSame($thirdChildElement, $childElement->nextSiblings()->atPosition(3));
        $this->assertSame($fifthChildElement, $childElement->nextSiblings()->last());
        $this->assertFalse($childElement->children()->wereFound());

        $this->assertSame($this->div, $fourthChildElement->parent());
        $this->assertTrue($fourthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(1, $fourthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fourthChildElement->previousSiblings()->first());
        $this->assertTrue($fourthChildElement->nextSiblings()->wereFound());
        $this->assertEquals(3, $fourthChildElement->nextSiblings()->count());
        $this->assertSame($secondChildElement, $fourthChildElement->nextSiblings()->first());
        $this->assertSame($thirdChildElement, $fourthChildElement->nextSiblings()->atPosition(2));
        $this->assertSame($fifthChildElement, $fourthChildElement->nextSiblings()->last());
        $this->assertFalse($fourthChildElement->children()->wereFound());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertTrue($secondChildElement->previousSiblings()->wereFound());
        $this->assertEquals(2, $secondChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $secondChildElement->previousSiblings()->first());
        $this->assertSame($fourthChildElement, $secondChildElement->previousSiblings()->last());
        $this->assertTrue($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(2, $secondChildElement->nextSiblings()->count());
        $this->assertSame($thirdChildElement, $secondChildElement->nextSiblings()->first());
        $this->assertSame($fifthChildElement, $secondChildElement->nextSiblings()->last());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertSame($this->div, $thirdChildElement->parent());
        $this->assertTrue($thirdChildElement->previousSiblings()->wereFound());
        $this->assertEquals(3, $thirdChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $thirdChildElement->previousSiblings()->first());
        $this->assertSame($fourthChildElement, $thirdChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($secondChildElement, $thirdChildElement->previousSiblings()->last());
        $this->assertTrue($thirdChildElement->nextSiblings()->wereFound());
        $this->assertEquals(1, $thirdChildElement->nextSiblings()->count());
        $this->assertSame($fifthChildElement, $thirdChildElement->nextSiblings()->first());
        $this->assertFalse($thirdChildElement->children()->wereFound());

        $this->assertSame($this->div, $fifthChildElement->parent());
        $this->assertTrue($fifthChildElement->previousSiblings()->wereFound());
        $this->assertEquals(4, $fifthChildElement->previousSiblings()->count());
        $this->assertSame($childElement, $fifthChildElement->previousSiblings()->first());
        $this->assertSame($fourthChildElement, $fifthChildElement->previousSiblings()->atPosition(2));
        $this->assertSame($secondChildElement, $fifthChildElement->previousSiblings()->atPosition(3));
        $this->assertSame($thirdChildElement, $fifthChildElement->previousSiblings()->last());
        $this->assertFalse($fifthChildElement->nextSiblings()->wereFound());
        $this->assertFalse($fifthChildElement->children()->wereFound());


    }

    public function test_moves_after_element_from_different_parents()
    {
        (object) $firstParent = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);


        (object) $secondParent = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);


        (object) $firstParentsFirstChild = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $firstParent->addChild($firstParentsFirstChild);

        (object) $firstParentsSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $firstParent->addChild($firstParentsSecondChild);

        (object) $secondParentsFirstChild = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        $secondParent->addChild($secondParentsFirstChild);

        (object) $secondParentsSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $secondParent->addChild($secondParentsSecondChild);



        $this->assertTrue($firstParent->children()->wereFound());
        $this->assertEquals(2, $firstParent->children()->count());
        $this->assertNull($firstParent->parent());
        $this->assertFalse($firstParent->previousSiblings()->wereFound());
        $this->assertFalse($firstParent->nextSiblings()->wereFound());

        $this->assertTrue($secondParent->children()->wereFound());
        $this->assertEquals(2, $secondParent->children()->count());
        $this->assertNull($secondParent->parent());
        $this->assertFalse($secondParent->previousSiblings()->wereFound());
        $this->assertFalse($secondParent->nextSiblings()->wereFound());


        $this->assertSame($firstParentsFirstChild, $firstParent->children()->first());
        $this->assertSame($firstParentsSecondChild, $firstParent->children()->last());

        $this->assertSame($secondParentsFirstChild, $secondParent->children()->first());
        $this->assertSame($secondParentsSecondChild, $secondParent->children()->last());

        $secondParentsFirstChild->moveAfter($firstParentsSecondChild);

        $this->assertTrue($firstParent->children()->wereFound());
        $this->assertEquals(3, $firstParent->children()->count());
        $this->assertNull($firstParent->parent());
        $this->assertFalse($firstParent->previousSiblings()->wereFound());
        $this->assertFalse($firstParent->nextSiblings()->wereFound());

        $this->assertTrue($secondParent->children()->wereFound());
        $this->assertEquals(1, $secondParent->children()->count());
        $this->assertNull($secondParent->parent());
        $this->assertFalse($secondParent->previousSiblings()->wereFound());
        $this->assertFalse($secondParent->nextSiblings()->wereFound());

        $this->assertSame($firstParentsFirstChild, $firstParent->children()->first());
        $this->assertSame($firstParentsSecondChild, $firstParent->children()->atPosition(2));
        $this->assertSame($secondParentsFirstChild, $firstParent->children()->last());

        $this->assertSame($secondParentsSecondChild, $secondParent->children()->first());

        $this->assertSame($firstParent, $firstParentsFirstChild->parent());
        $this->assertFalse($firstParentsFirstChild->previousSiblings()->wereFound());
        $this->assertTrue($firstParentsFirstChild->nextSiblings()->wereFound());
        $this->assertEquals(2, $firstParentsFirstChild->nextSiblings()->count());
        $this->assertSame($firstParentsSecondChild, $firstParentsFirstChild->nextSiblings()->first());
        $this->assertSame($secondParentsFirstChild, $firstParentsFirstChild->nextSiblings()->last());
        $this->assertFalse($firstParentsFirstChild->children()->wereFound());

        $this->assertSame($firstParent, $firstParentsSecondChild->parent());
        $this->assertTrue($firstParentsSecondChild->previousSiblings()->wereFound());
        $this->assertEquals(1, $firstParentsSecondChild->previousSiblings()->count());
        $this->assertSame($firstParentsFirstChild, $firstParentsSecondChild->previousSiblings()->first());
        $this->assertTrue($firstParentsSecondChild->nextSiblings()->wereFound());
        $this->assertEquals(1, $firstParentsSecondChild->nextSiblings()->count());
        $this->assertSame($secondParentsFirstChild, $firstParentsSecondChild->nextSiblings()->first());
        $this->assertFalse($firstParentsSecondChild->children()->wereFound());

        $this->assertSame($firstParent, $secondParentsFirstChild->parent());
        $this->assertTrue($secondParentsFirstChild->previousSiblings()->wereFound());
        $this->assertEquals(2, $secondParentsFirstChild->previousSiblings()->count());
        $this->assertSame($firstParentsFirstChild, $secondParentsFirstChild->previousSiblings()->first());
        $this->assertSame($firstParentsSecondChild, $secondParentsFirstChild->previousSiblings()->last());
        $this->assertFalse($secondParentsFirstChild->nextSiblings()->wereFound());
        $this->assertFalse($secondParentsFirstChild->children()->wereFound());

        $this->assertSame($secondParent, $secondParentsSecondChild->parent());
        $this->assertFalse($secondParentsSecondChild->previousSiblings()->wereFound());
        $this->assertFalse($secondParentsSecondChild->nextSiblings()->wereFound());
        $this->assertFalse($secondParentsSecondChild->children()->wereFound());

    }

    public function test_clone()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);


        (object) $firstChild = new Element([
            'type' => 'br',
            'isVoid' => true
        ]);

        $element->addChild($firstChild);

        (object) $secondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $element->addChild($secondChild);

        $this->assertTrue($element->children()->wereFound());
        $this->assertEquals(2, $element->children()->count());
        $this->assertNull($element->parent());
        $this->assertFalse($element->previousSiblings()->wereFound());
        $this->assertFalse($element->nextSiblings()->wereFound());

        $this->assertSame($firstChild, $element->children()->first());
        $this->assertSame($secondChild, $element->children()->last());

        (object) $clonedElement = clone $element;

        $this->assertTrue($clonedElement->children()->wereFound());
        $this->assertEquals(2, $clonedElement->children()->count());
        $this->assertNull($clonedElement->parent());
        $this->assertFalse($clonedElement->previousSiblings()->wereFound());
        $this->assertFalse($clonedElement->nextSiblings()->wereFound());

        $this->assertNotSame($firstChild, $clonedElement->children()->first());
        $this->assertNotSame($secondChild, $clonedElement->children()->last());
        
    }

    public function test_throws_exception_when_attempting_to_write_an_attribute_from_property()
    {
        $this->expectException(ForbiddenAttributeWriteException::class);
        $this->expectExceptionMessage('Attributes via properties are read-only');

        $this->div->id = 'wrong';

    }  

    public function test_returns_null_undefinded_attributes()
    {
        $this->assertEquals(null, $this->div->id);
    }  

    public function test_sets_checks_and_reads_attribute()
    {
        $this->div->setId('main');

        $this->assertTrue($this->div->hasId('main'));

        $this->assertEquals('main', $this->div->id);
    }

    public function test_adds_checks_and_reads_attributes()
    {
        $this->div->addClass('main');
        $this->div->addClass('secondary');

        $this->assertTrue($this->div->hasClass('main'));
        $this->assertTrue($this->div->hasClass('secondary'));

        $this->assertEquals('main secondary', $this->div->class);
    }

    public function test_sets_checks_and_reads_multiword_attributes()
    {
        $this->div->setDataPointer('56');

        $this->assertTrue($this->div->hasDataPointer('56'));

        $this->assertEquals('56', $this->div->dataPointer);
    }

    public function test_removes_single_attribute()
    {
        $this->div->setId('main');

        $this->assertTrue($this->div->hasId('main'));

        $this->assertEquals('main', $this->div->id);

        $this->div->removeId('main');

        $this->assertNull($this->div->id);
    }

    public function test_gets_attributes_array()
    {
        (array) $expectedAttributesArray = [
            'data-pointer' => '56',
            'class' => 'float left'
        ];
        
        $this->div->setDataPointer('56');
        $this->div->addClass('float');
        $this->div->addClass('left');

        $this->assertEquals($expectedAttributesArray, $this->div->attributes());
    }

    public function test_directly_Creates_a_Text_object_and_does_no_parsing_with_DOMDocument_loadHTML_if_content_has_no_html_tags()
    {
        (string) $plainText = "Plain and boring content";

        (object) $DOMDocument = $this->createMock(DOMDocument::class);

        $DOMDocument->expects($this->never())->method('loadHTML');

        $this->div->setDOMDocument($DOMDocument);

        $this->div->addContent($plainText);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(1, $this->div->children()->count());
        $this->assertInstanceOf(Text::class, $this->div->children()->first());
        $this->assertEquals('Plain and boring content', $this->div->children()->first()->content());
    }


    public function test_uses_DOMDocument_loadHTML_with_a_wrapper_div_when_content_has_html_tags()
    {
        (object) $document = new DOMDocument;

        (object) $bodyNode = $document->createElement('body');
        (object) $wrapperDiv = $document->createElement('div');

        $bodyNode->appendChild($wrapperDiv);

        (array) $body = [$bodyNode];
        (string) $htmlString = "Smart <b>text</b> content";

        (object) $DOMDocument = $this->createMock(DOMDocument::class);

        $DOMDocument->expects($this->once())->method('loadHTML')->with("<div>$htmlString</div>");
        $DOMDocument->expects($this->once())->method('getElementsByTagName')->with('body')->willReturn($body);

        $this->div->setDOMDocument($DOMDocument);

        $this->div->addContent($htmlString);

    }

    public function test_adds_elements_from_html_string_without_empty_text_nodes_from_whitespaces()
    {
        (string) $htmlString = "

            <p></p>

            <b>bold!</b>

        ";

        $this->div->addContent($htmlString);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(2, $this->div->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->first());
        $this->assertInstanceOf(Element::class, $this->div->children()->last());

        $this->assertEquals('p', $this->div->children()->first()->type());
        $this->assertEquals('b', $this->div->children()->last()->type());

        $this->assertFalse($this->div->children()->first()->children()->wereFound());
        $this->assertTrue($this->div->children()->last()->children()->wereFound());

        $this->assertEquals(1, $this->div->children()->last()->children()->count());

        $this->assertInstanceOf(Text::class, $this->div->children()->last()->children()->first());
        $this->assertEquals('bold!', $this->div->children()->last()->children()->first()->content());

    }

    public function test_adds_elements_from_html_string_without_empty_text_nodes_from_whitespaces_with_attributes()
    {
        (string) $htmlString = ' 
            <p id="main-content"></p>

            <b class="strong-sentence">bold!</b>
        ';

        $this->div->addContent($htmlString);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(2, $this->div->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->first());
        $this->assertInstanceOf(Element::class, $this->div->children()->last());

        $this->assertEquals('p', $this->div->children()->first()->type());
        $this->assertEquals('b', $this->div->children()->last()->type());

        $this->assertTrue($this->div->children()->first()->hasId('main-content'));
        $this->assertTrue($this->div->children()->last()->hasClass('strong-sentence'));

        $this->assertEquals('main-content', $this->div->children()->first()->id);
        $this->assertEquals('strong-sentence', $this->div->children()->last()->class);

        $this->assertFalse($this->div->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($this->div->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->nextSiblings()->count());
        $this->assertSame($this->div->children()->last(), $this->div->children()->first()->nextSiblings()->first());

        $this->assertTrue($this->div->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->last()->previousSiblings()->count());
        $this->assertSame($this->div->children()->first(), $this->div->children()->last()->previousSiblings()->first());

        $this->assertFalse($this->div->children()->first()->children()->wereFound());
        $this->assertTrue($this->div->children()->last()->children()->wereFound());

        $this->assertEquals(1, $this->div->children()->last()->children()->count());

        $this->assertInstanceOf(Text::class, $this->div->children()->last()->children()->first());
        $this->assertEquals('bold!', $this->div->children()->last()->children()->first()->content());

    }

    public function test_adds_elements_from_html_string_text_node_first()
    {
        (string) $htmlString = ' 
            An introductory paragraph <b>about us</b>
            <a href="#" > check it out!</a> and tell us your thoughts.
        ';

        $this->div->addContent($htmlString);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(4, $this->div->children()->count());

        $this->assertInstanceOf(Text::class, $this->div->children()->first());
        $this->assertInstanceOf(Element::class, $this->div->children()->atPosition(2));
        $this->assertInstanceOf(Element::class, $this->div->children()->atPosition(3));
        $this->assertInstanceOf(Text::class, $this->div->children()->last());
        
        $this->assertTrue(strpos($this->div->children()->first()->content(), 'An introductory paragraph ') !== false);
        $this->assertEquals('b', $this->div->children()->atPosition(2)->type());
        $this->assertEquals('a', $this->div->children()->atPosition(3)->type());
        $this->assertTrue(strpos($this->div->children()->last()->content(), ' and tell us your thoughts.') !== false);
        

        $this->assertTrue($this->div->children()->atPosition(3)->hasHref('#'));
        
        $this->assertEquals('#', $this->div->children()->atPosition(3)->href);

        $this->assertFalse($this->div->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($this->div->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals(3, $this->div->children()->first()->nextSiblings()->count());
        $this->assertSame($this->div->children()->atPosition(2), $this->div->children()->first()->nextSiblings()->first());
        $this->assertSame($this->div->children()->atPosition(3), $this->div->children()->first()->nextSiblings()->atPosition(2));
        $this->assertSame($this->div->children()->last(), $this->div->children()->first()->nextSiblings()->last());

        $this->assertTrue($this->div->children()->atPosition(2)->previousSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->atPosition(2)->previousSiblings()->count());
        $this->assertSame($this->div->children()->first(), $this->div->children()->atPosition(2)->previousSiblings()->first());
        $this->assertTrue($this->div->children()->atPosition(2)->nextSiblings()->wereFound());
        $this->assertEquals(2, $this->div->children()->atPosition(2)->nextSiblings()->count());
        $this->assertSame($this->div->children()->atPosition(3), $this->div->children()->atPosition(2)->nextSiblings()->first());
        $this->assertSame($this->div->children()->last(), $this->div->children()->atPosition(2)->nextSiblings()->last());

        $this->assertTrue($this->div->children()->atPosition(3)->previousSiblings()->wereFound());
        $this->assertEquals(2, $this->div->children()->atPosition(3)->previousSiblings()->count());
        $this->assertSame($this->div->children()->first(), $this->div->children()->atPosition(3)->previousSiblings()->first());
        $this->assertSame($this->div->children()->atPosition(2), $this->div->children()->atPosition(3)->previousSiblings()->last());
        $this->assertTrue($this->div->children()->atPosition(3)->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->atPosition(3)->nextSiblings()->count());
        $this->assertSame($this->div->children()->last(), $this->div->children()->atPosition(3)->nextSiblings()->last());
        
        $this->assertTrue($this->div->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals(3, $this->div->children()->last()->previousSiblings()->count());
        $this->assertSame($this->div->children()->first(), $this->div->children()->last()->previousSiblings()->first());
        $this->assertSame($this->div->children()->atPosition(2), $this->div->children()->last()->previousSiblings()->atPosition(2));
        $this->assertSame($this->div->children()->atPosition(3), $this->div->children()->last()->previousSiblings()->last());

        $this->assertTrue($this->div->children()->atPosition(2)->children()->wereFound());
        $this->assertTrue($this->div->children()->atPosition(3)->children()->wereFound());
        
        $this->assertEquals(1, $this->div->children()->atPosition(2)->children()->count());
        $this->assertEquals(1, $this->div->children()->atPosition(3)->children()->count());
        
        $this->assertInstanceOf(Text::class, $this->div->children()->atPosition(2)->children()->first());
        $this->assertEquals('about us', $this->div->children()->atPosition(2)->children()->first()->content());

        $this->assertInstanceOf(Text::class, $this->div->children()->atPosition(3)->children()->first());
        $this->assertEquals(' check it out!', $this->div->children()->atPosition(3)->children()->first()->content());

    }

    public function test_no_nested_empty_text_nodes_from_whitespace()
    {
        (string) $htmlString = ' 
            <section id="main-content">

                <p>
                    <b class="imp-rd">

                        <span>

                        a span inside a b!</span>

                    </b>
                </p>


                direct text

            </section>

            <b class="strong-sentence">
                <img source="#" note="ilegal.Image" />

            

            </b>

        ';

        $this->div->addContent($htmlString);

        $this->assertTrue($this->div->children()->wereFound());
        $this->assertEquals(2, $this->div->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->first());
        $this->assertInstanceOf(Element::class, $this->div->children()->last());
        
        $this->assertEquals('section', $this->div->children()->first()->type());
        $this->assertEquals('b', $this->div->children()->last()->type());
        
        $this->assertTrue($this->div->children()->first()->hasId('main-content'));
        $this->assertTrue($this->div->children()->last()->hasClass('strong-sentence'));
        
        $this->assertEquals('main-content', $this->div->children()->first()->id);
        $this->assertEquals('strong-sentence', $this->div->children()->last()->class);
        
        $this->assertFalse($this->div->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($this->div->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->nextSiblings()->count());
        $this->assertSame($this->div->children()->last(), $this->div->children()->first()->nextSiblings()->first());
        
        $this->assertTrue($this->div->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->last()->previousSiblings()->count());
        $this->assertSame($this->div->children()->first(), $this->div->children()->last()->previousSiblings()->first());
        
        $this->assertTrue($this->div->children()->first()->children()->wereFound());
        $this->assertTrue($this->div->children()->last()->children()->wereFound());
        
        $this->assertEquals(2, $this->div->children()->first()->children()->count());
        //1
        
        $this->assertInstanceOf(Element::class, $this->div->children()->first()->children()->first());
        $this->assertEquals('p', $this->div->children()->first()->children()->first()->type());
        $this->assertInstanceOf(Text::class, $this->div->children()->first()->children()->last());
        $this->assertTrue(strpos($this->div->children()->first()->children()->last()->content(), 'direct text') !== false);

        $this->assertFalse($this->div->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($this->div->children()->first()->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->children()->first()->nextSiblings()->count());
        $this->assertSame($this->div->children()->first()->children()->last(), $this->div->children()->first()->children()->first()->nextSiblings()->first());

        $this->assertTrue($this->div->children()->first()->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->first()->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->children()->last()->previousSiblings()->count());
        $this->assertSame($this->div->children()->first()->children()->first(), $this->div->children()->first()->children()->last()->previousSiblings()->first());

        $this->assertTrue($this->div->children()->first()->children()->first()->children()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->children()->first()->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->first()->children()->first()->children()->first());
        $this->assertEquals('b', $this->div->children()->first()->children()->first()->children()->first()->type());

        $this->assertFalse($this->div->children()->first()->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->first()->children()->first()->children()->first()->nextSiblings()->wereFound());

        $this->assertTrue($this->div->children()->first()->children()->first()->children()->first()->hasClass('imp-rd'));
        $this->assertEquals('imp-rd', $this->div->children()->first()->children()->first()->children()->first()->class);

        $this->assertTrue($this->div->children()->first()->children()->first()->children()->first()->children()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->children()->first()->children()->first()->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->first()->children()->first()->children()->first()->children()->first());
        $this->assertEquals('span', $this->div->children()->first()->children()->first()->children()->first()->children()->first()->type());

        $this->assertFalse($this->div->children()->first()->children()->first()->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->first()->children()->first()->children()->first()->children()->first()->nextSiblings()->wereFound());

        $this->assertTrue($this->div->children()->first()->children()->first()->children()->first()->children()->first()->children()->wereFound());
        $this->assertEquals(1, $this->div->children()->first()->children()->first()->children()->first()->children()->first()->children()->count());

        $this->assertInstanceOf(Text::class, $this->div->children()->first()->children()->first()->children()->first()->children()->first()->children()->first());
        $this->assertTrue(strpos($this->div->children()->first()->children()->first()->children()->first()->children()->first()->children()->first()->content(), 'a span inside a b!') !== false);
        
        
        $this->assertEquals(1, $this->div->children()->last()->children()->count());

        $this->assertInstanceOf(Element::class, $this->div->children()->last()->children()->first());
        
        $this->assertEquals('img', $this->div->children()->last()->children()->first()->type());

        $this->assertFalse($this->div->children()->last()->children()->first()->previousSiblings()->wereFound());
        $this->assertFalse($this->div->children()->last()->children()->first()->nextSiblings()->wereFound());

        $this->assertTrue($this->div->children()->last()->children()->first()->hasSource('#'));
        $this->assertTrue($this->div->children()->last()->children()->first()->hasNote('ilegal.Image'));
        
        $this->assertEquals('#', $this->div->children()->last()->children()->first()->source);
        $this->assertEquals('ilegal.Image', $this->div->children()->last()->children()->first()->note);

    }

    public function test_finds_by_type_children_and_its_descendants()
    {
        /*
        
            HTML Representation

            <div>
                <article>
                    <p>
                    </p>
                    <div>
                        <p>
                        </p>
                    </div>
                </article>
                <section>
                    <p>
                    </p>
                </section>
                <p>
                </p>
            </div>

    

         */
        (object) $article = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        (object) $section = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $p = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        # Descendants:

        (object) $articleP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $articleDiv = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $articleDivP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $sectionP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->div->addChild($article);
        $this->div->addChild($section);
        $this->div->addChild($p);

        $article->addChild($articleP);
        $article->addChild($articleDiv);

        $articleDiv->addChild($articleDivP);

        $section->addChild($sectionP);

        (object) $pElements = $this->div->select('p');

        $this->assertInstanceOf(GroupOfNodes::class, $pElements);
        $this->assertTrue($pElements->wereFound());

        $this->assertEquals(4, $pElements->count());

        $this->assertSame($articleP, $pElements->first());
        $this->assertSame($articleDivP, $pElements->atPosition(2));
        $this->assertSame($sectionP, $pElements->atPosition(3));
        $this->assertSame($p, $pElements->last());


    }

    public function test_finds_by_attribute_children_and_its_descendants()
    {
        /*
        
            HTML Representation

            <div>
                <article>
                    <p>
                    </p>
                    <div>
                        <p>
                        </p>
                    </div>
                </article>
                <section>
                    <p>
                    </p>
                </section>
                <p>
                </p>
            </div>

    

         */
        (object) $article = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        (object) $section = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $p = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        # Descendants:

        (object) $articleP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $articleDiv = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $articleDivP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $sectionP = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $articleDiv->addClass('content');
        $section->addClass('content');

        $this->div->addChild($article);
        $this->div->addChild($section);
        $this->div->addChild($p);

        $article->addChild($articleP);
        $article->addChild($articleDiv);

        $articleDiv->addChild($articleDivP);

        $section->addChild($sectionP);

        (object) $contentElements = $this->div->withClass('content');

        $this->assertInstanceOf(GroupOfNodes::class, $contentElements);
        $this->assertTrue($contentElements->wereFound());

        $this->assertEquals(2, $contentElements->count());

        $this->assertSame($articleDiv, $contentElements->first());
        $this->assertSame($section, $contentElements->last());


    }


    public function test_removes_element()
    {
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $secondChildElement = new Element([
            'type' => 'a',
            'isVoid' => true
        ]);
        
        $this->div->addChild($childElement);
        $this->div->addChild($secondChildElement);

        $childElement->remove();

        $this->assertEquals(1, $this->div->children()->count());

        $this->assertInstanceOf(GroupOfNodes::class, $this->div->children());
        $this->assertTrue($this->div->children()->wereFound());
        $this->assertNull($this->div->parent());
        $this->assertFalse($this->div->previousSiblings()->wereFound());
        $this->assertFalse($this->div->nextSiblings()->wereFound());

        $this->assertSame($secondChildElement, $this->div->children()->first());

        $this->assertSame($this->div, $secondChildElement->parent());
        $this->assertFalse($secondChildElement->previousSiblings()->wereFound());
        $this->assertFalse($secondChildElement->nextSiblings()->wereFound());
        $this->assertEquals(0, $secondChildElement->nextSiblings()->count());
        $this->assertFalse($secondChildElement->children()->wereFound());

        $this->assertNull($childElement->parent());
        $this->assertFalse($childElement->previousSiblings()->wereFound());
        $this->assertFalse($childElement->nextSiblings()->wereFound());
        $this->assertFalse($childElement->children()->wereFound());
    }

















}