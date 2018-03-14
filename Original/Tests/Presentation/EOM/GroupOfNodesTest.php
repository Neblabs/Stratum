<?php

use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\EOM\Node;
use Stratum\Original\Presentation\Exception\InvalidTypeException;
use PHPUnit\Framework\TestCase;

Class GroupOfNodesTest extends TestCase
{
    public function test_throws_exception_if_array_is_not_composed_only_by_node_objects()
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage("A GroupOfNodes object can only contain " . Node::class . " objects");

        new GroupOfNodes([
            new Text, 
            5, 
            new Element(['type' => 'div', 'isVoid' => false])
        ]);
    }

    public function test_throws_exception_if_adding_an_item_tha_is_not_node_object()
    {
        $this->expectException(InvalidTypeException::class);
        $this->expectExceptionMessage("A GroupOfNodes object can only contain " . Node::class . " objects");

        (object) $nodes = new GroupOfNodes([]);

        $nodes->add('oops');
    }

    public function test_finds_elements_by_type()
    {
        (object) $div = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $mainSection = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $secondarySection = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        $div->addChild($mainSection);
        $div->addChild($secondarySection);

        $GroupOfElements = new GroupOfNodes([$div]);

        (object) $sectionElements = $GroupOfElements->select('section');

        $this->assertInstanceOf(GroupOfNodes::class, $sectionElements);
        $this->assertTrue($sectionElements->wereFound());
        $this->assertEquals(2, $sectionElements->count());
        $this->assertSame($mainSection, $sectionElements->first());
        $this->assertSame($secondarySection, $sectionElements->last());
    }

    public function test_finds_elements_by_attribute_first_level()
    {
        (object) $div = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $mainSection = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $secondarySection = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        $mainSection->addClass('main');
        $div->addClass('main');

        $div->addChild($mainSection);
        $div->addChild($secondarySection);

        $GroupOfElements = new GroupOfNodes([$div]);

        (object) $elementsWithClassMain = $GroupOfElements->withClass('main');

        $this->assertInstanceOf(GroupOfNodes::class, $elementsWithClassMain);
        $this->assertTrue($elementsWithClassMain->wereFound());
        $this->assertEquals(2, $elementsWithClassMain->count());
        $this->assertSame($mainSection, $elementsWithClassMain->first());
        $this->assertSame($div, $elementsWithClassMain->last());
    }

    public function test_finds_by_type()
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
        
        (object) $div = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

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

        $div->addChild($article);
        $div->addChild($section);
        $div->addChild($p);

        $article->addChild($articleP);
        $article->addChild($articleDiv);

        $articleDiv->addChild($articleDivP);

        $section->addChild($sectionP);

        (object) $GroupOfElements = new GroupOfNodes([
            $div
        ]);

        (object) $pElements = $GroupOfElements->select('p');

        $this->assertInstanceOf(GroupOfNodes::class, $pElements);
        $this->assertTrue($pElements->wereFound());

        $this->assertEquals(4, $pElements->count());

        $this->assertSame($articleP, $pElements->first());
        $this->assertSame($articleDivP, $pElements->atPosition(2));
        $this->assertSame($sectionP, $pElements->atPosition(3));
        $this->assertSame($p, $pElements->last());
    }









}