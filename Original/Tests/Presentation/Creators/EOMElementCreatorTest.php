<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Creator\EOMElementCreator;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;

Class EOMElementCreatorTest extends TestCase
{
    public function test_creates_a_void_element_if_DOMElement_tagname_is_a_known_void_element()
    {
        (object) $DOMVoidElement = new DOMElement('img');
        (object) $EOMElementCreator = new EOMElementCreator($DOMVoidElement);

        (object) $EOMVoidElement = $EOMElementCreator->create();

        $this->assertTrue($EOMVoidElement->isVoid());
    }

    public function test_creates_a_void_element_if_DOMElement_has_a_isVoid_attribute()
    {
        (object) $document = new DOMDocument;

        (object) $DOMVoidElement = $document->createElement('custom');

        $DOMVoidElement->setAttribute('isVoid', true);

        (object) $EOMElementCreator = new EOMElementCreator($DOMVoidElement);

        (object) $EOMVoidElement = $EOMElementCreator->create();

        $this->assertTrue($EOMVoidElement->isVoid());
    }

    public function test_creates_an_EOM_Element_two_children_no_grandChildren()
    {
        (object) $document = new DOMDocument;

        (object) $DOMElement = $document->createElement('custom');

        (object) $p = $document->createElement('p');
        (object) $section = $document->createElement('section');

        $p->setAttribute('id', 'header');
        $p->setAttribute('class', 'float left');

        $section->setAttribute('dataPointer', '54');

        $DOMElement->appendChild($p);
        $DOMElement->appendChild($section);

        (object) $EOMElementCreator = new EOMElementCreator($DOMElement);

        (object) $EOMElement = $EOMElementCreator->create();

        $this->assertEquals('custom', $EOMElement->type());
        $this->assertNull($EOMElement->parent());
        $this->assertFalse($EOMElement->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->nextSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->wereFound());
        $this->assertEquals(2, $EOMElement->children()->count());

        $this->assertEquals('p', $EOMElement->children()->first()->type());
        $this->assertEquals('section', $EOMElement->children()->last()->type());

        $this->assertTrue($EOMElement->children()->first()->hasId('header'));
        $this->assertTrue($EOMElement->children()->first()->hasClass('float'));
        $this->assertTrue($EOMElement->children()->first()->hasClass('left'));

        $this->assertTrue($EOMElement->children()->last()->hasDataPointer('54'));

        $this->assertFalse($EOMElement->children()->first()->children()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->children()->wereFound());

        $this->assertFalse($EOMElement->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->first()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->last(), $EOMElement->children()->first()->nextSiblings()->first());

        $this->assertTrue($EOMElement->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->first(), $EOMElement->children()->last()->previousSiblings()->first());
    }

    public function test_creates_an_EOM_Element_two_children_with_grandChildren_and_siblings()
    {
        (object) $document = new DOMDocument;

        (object) $DOMElement = $document->createElement('custom');

        (object) $p = $document->createElement('p');
        (object) $section = $document->createElement('section');

        (object) $pChild = $document->createElement('span');
        (object) $pChildsChild = $document->createElement('b');
        (object) $pSecondChild = $document->createElement('strike');

        $DOMElement->appendChild($p);
        $DOMElement->appendChild($section);

        $p->appendChild($pChild);
        $p->appendChild($pSecondChild);

        $pChild->appendChild($pChildsChild);

        (object) $EOMElementCreator = new EOMElementCreator($DOMElement);

        (object) $EOMElement = $EOMElementCreator->create();

        $this->assertEquals('custom', $EOMElement->type());
        $this->assertNull($EOMElement->parent());
        $this->assertFalse($EOMElement->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->nextSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->wereFound());
        $this->assertEquals(2, $EOMElement->children()->count());

        $this->assertEquals('p', $EOMElement->children()->first()->type());
        $this->assertEquals('section', $EOMElement->children()->last()->type());

        $this->assertTrue($EOMElement->children()->first()->children()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->children()->wereFound());

        $this->assertFalse($EOMElement->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->first()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->last(), $EOMElement->children()->first()->nextSiblings()->first());

        $this->assertTrue($EOMElement->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->first(), $EOMElement->children()->last()->previousSiblings()->first());

        $this->assertEquals(2, $EOMElement->children()->first()->children()->count());

        $this->assertEquals('span', $EOMElement->children()->first()->children()->first()->type());
        $this->assertEquals('strike', $EOMElement->children()->first()->children()->last()->type());

        $this->assertFalse($EOMElement->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->first()->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals('strike', $EOMElement->children()->first()->children()->first()->nextSiblings()->first()->type());

        $this->assertTrue($EOMElement->children()->first()->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals('span', $EOMElement->children()->first()->children()->last()->previousSiblings()->first()->type());

        $this->assertTrue($EOMElement->children()->first()->children()->first()->children()->wereFound());
        $this->assertEquals(1, $EOMElement->children()->first()->children()->first()->children()->count());
        $this->assertEquals('b', $EOMElement->children()->first()->children()->first()->children()->first()->type());
        $this->assertFalse($EOMElement->children()->first()->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->first()->children()->first()->nextSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->first()->children()->first()->children()->wereFound());
    }

    public function test_creates_an_EOM_Element_two_children_with_grandChildren_and_siblings_with_text_nodes()
    {
        (object) $document = new DOMDocument;

        (object) $DOMElement = $document->createElement('custom');

        (object) $p = $document->createElement('p');
        (object) $section = $document->createElement('section');

        (object) $pText = new DOMText('The paragraph\'s text');
        (object) $pChild = $document->createElement('span');
        (object) $pChildsChild = $document->createElement('b');
        (object) $pSecondChild = $document->createElement('strike');

        $DOMElement->appendChild($p);
        $DOMElement->appendChild($section);

        $p->appendChild($pText);
        $p->appendChild($pChild);
        $p->appendChild($pSecondChild);

        $pChild->appendChild($pChildsChild);

        (object) $EOMElementCreator = new EOMElementCreator($DOMElement);

        (object) $EOMElement = $EOMElementCreator->create();

        $this->assertEquals('custom', $EOMElement->type());
        $this->assertNull($EOMElement->parent());
        $this->assertFalse($EOMElement->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->nextSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->wereFound());
        $this->assertEquals(2, $EOMElement->children()->count());

        $this->assertEquals('p', $EOMElement->children()->first()->type());
        $this->assertEquals('section', $EOMElement->children()->last()->type());

        $this->assertTrue($EOMElement->children()->first()->children()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->children()->wereFound());

        $this->assertFalse($EOMElement->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->first()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->last(), $EOMElement->children()->first()->nextSiblings()->first());

        $this->assertTrue($EOMElement->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->last()->nextSiblings()->wereFound());
        $this->assertSame($EOMElement->children()->first(), $EOMElement->children()->last()->previousSiblings()->first());

        $this->assertEquals(3, $EOMElement->children()->first()->children()->count());

        $this->assertInstanceOf(Text::class, $EOMElement->children()->first()->children()->first());
        $this->assertEquals('span', $EOMElement->children()->first()->children()->atPosition(2)->type());
        $this->assertEquals('strike', $EOMElement->children()->first()->children()->last()->type());

        $this->assertEquals('The paragraph\'s text', $EOMElement->children()->first()->children()->first()->content());

        $this->assertFalse($EOMElement->children()->first()->children()->first()->previousSiblings()->wereFound());
        $this->assertTrue($EOMElement->children()->first()->children()->first()->nextSiblings()->wereFound());
        $this->assertEquals(2, $EOMElement->children()->first()->children()->first()->nextSiblings()->count());
        $this->assertEquals('span', $EOMElement->children()->first()->children()->first()->nextSiblings()->first()->type());
        $this->assertEquals('strike', $EOMElement->children()->first()->children()->first()->nextSiblings()->last()->type());


        $this->assertTrue($EOMElement->children()->first()->children()->atPosition(2)->previousSiblings()->wereFound());
        $this->assertEquals(1, $EOMElement->children()->first()->children()->atPosition(2)->previousSiblings()->count());
        $this->assertInstanceOf(Text::class, $EOMElement->children()->first()->children()->atPosition(2)->previousSiblings()->first());
        $this->assertTrue($EOMElement->children()->first()->children()->atPosition(2)->nextSiblings()->wereFound());
        $this->assertEquals(1, $EOMElement->children()->first()->children()->atPosition(2)->nextSiblings()->count());
        $this->assertEquals('strike', $EOMElement->children()->first()->children()->atPosition(2)->nextSiblings()->first()->type());

        $this->assertTrue($EOMElement->children()->first()->children()->last()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->last()->nextSiblings()->wereFound());
        $this->assertEquals(2, $EOMElement->children()->first()->children()->last()->previousSiblings()->count());
        $this->assertInstanceOf(Text::class, $EOMElement->children()->first()->children()->last()->previousSiblings()->first());
        $this->assertEquals('span', $EOMElement->children()->first()->children()->last()->previousSiblings()->last()->type());
        $this->assertFalse($EOMElement->children()->first()->children()->last()->children()->wereFound());

        $this->assertTrue($EOMElement->children()->first()->children()->atPosition(2)->children()->wereFound());
        $this->assertEquals(1, $EOMElement->children()->first()->children()->atPosition(2)->children()->count());
        $this->assertEquals('b', $EOMElement->children()->first()->children()->atPosition(2)->children()->first()->type());
        $this->assertFalse($EOMElement->children()->first()->children()->atPosition(2)->children()->first()->previousSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->atPosition(2)->children()->first()->nextSiblings()->wereFound());
        $this->assertFalse($EOMElement->children()->first()->children()->atPosition(2)->children()->first()->children()->wereFound());

    }

    //todo: verify whitespaces empty text nodes dont get created
    
    public function test_creates_an_element_with_3_word_attribute_name()
    {
        (object) $document = new DOMDocument;

        (object) $DOMElement = $document->createElement('custom');

        $DOMElement->setAttribute('one-two-three', 'true');

        (object) $EOMElementCreator = new EOMElementCreator($DOMElement);

        (object) $EOMElement = $EOMElementCreator->create();

        $this->assertTrue($EOMElement->hasOneTwoThree('true'));
        $this->assertEquals('true', $EOMElement->oneTwoThree);

    }
    







}