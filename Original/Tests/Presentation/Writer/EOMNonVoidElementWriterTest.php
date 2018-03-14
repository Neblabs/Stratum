<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Writer\EOMNonVoidElementWriter;

Class EOMNonVoidElementWriterTest extends TestCase
{
    public function setUp()
    {
        $this->element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $this->EOMNonVoidElementWriter = new EOMNonVoidElementWriter($this->element);
    }
    public function test_writes_1_element_no_attributes()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_attribute()
    {
        (string) $expectedElement = '<div class="container">' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        $this->element->addClass('container');

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_attribute_escaped()
    {
        (string) $expectedElement = '<div class="container &quot; &gt;&lt;script&gt;gotYa&lt;/script&gt;">' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        $this->element->addClass('container " ><script>gotYa</script>');

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_2_attributes()
    {
        (string) $expectedElement = '<div id="main" class="container">' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        $this->element->addId('main');
        $this->element->addClass('container');

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_3_attributes_1_attribute_name_composed_by_3_words()
    {
        (string) $expectedElement = '<div id="main" class="container" one-two-three="one">' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        $this->element->addId('main');
        $this->element->addClass('container');
        $this->element->addOneTwoThree('one');

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_child_element_non_void()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_child_element_void()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<img />' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_child_text()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= 'A div!';
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Text;
        $childElement->addContent('A div!');

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_3_children_each_a_supported_node_type()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement.= '<img />' . PHP_EOL;
        $expectedElement.= 'A div!';
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $secondChildElement = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        (object) $text = new Text;

        $text->addContent('A div!');

        $this->element->addChild($childElement);
        $this->element->addChild($secondChildElement);
        $this->element->addChild($text);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_child_element_and_1_grand_child()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '<b>' . PHP_EOL;
        $expectedElement.= '</b>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $grandchildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $childElement->addChild($grandchildElement);

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_descendant_3_levels()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '<b>' . PHP_EOL;
        $expectedElement.= '<span>' . PHP_EOL;
        $expectedElement.= '</span>' . PHP_EOL;
        $expectedElement.= '</b>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $grandchildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $grandchildElementChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        $childElement->addChild($grandchildElement);
        $grandchildElement->addChild($grandchildElementChild);

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_1_descendant_3_levels_2()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '<b>' . PHP_EOL;
        $expectedElement.= '<span>' . PHP_EOL;
        $expectedElement.= '</span>' . PHP_EOL;
        $expectedElement.= '<i>' . PHP_EOL;
        $expectedElement.= '</i>' . PHP_EOL;
        $expectedElement.= '</b>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $grandchildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $grandchildElementChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        (object) $grandchildElementSecondChild = new Element([
            'type' => 'i',
            'isVoid' => false
        ]);

        $childElement->addChild($grandchildElement);
        $grandchildElement->addChild($grandchildElementChild);
        $grandchildElement->addChild($grandchildElementSecondChild);

        $this->element->addChild($childElement);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_2_descendants_3_levels_2()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= '<b>' . PHP_EOL;
        $expectedElement.= '<span>' . PHP_EOL;
        $expectedElement.= '</span>' . PHP_EOL;
        $expectedElement.= '<i>' . PHP_EOL;
        $expectedElement.= '</i>' . PHP_EOL;
        $expectedElement.= '</b>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement.= '<section>' . PHP_EOL;
        $expectedElement.= '<div>' . PHP_EOL;
        $expectedElement.= '<article>' . PHP_EOL;
        $expectedElement.= '</article>' . PHP_EOL;
        $expectedElement.= '<footer>' . PHP_EOL;
        $expectedElement.= '</footer>' . PHP_EOL;
        $expectedElement.= '</div>' . PHP_EOL;
        $expectedElement.= '</section>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);
        
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $grandchildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $grandchildElementChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        (object) $grandchildElementSecondChild = new Element([
            'type' => 'i',
            'isVoid' => false
        ]);

        $childElement->addChild($grandchildElement);
        $grandchildElement->addChild($grandchildElementChild);
        $grandchildElement->addChild($grandchildElementSecondChild);
        

        (object) $childElementB = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $grandchildElementB = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $grandchildElementChildB = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        (object) $grandchildElementSecondChildB = new Element([
            'type' => 'footer',
            'isVoid' => false
        ]);

        $childElementB->addChild($grandchildElementB);
        $grandchildElementB->addChild($grandchildElementChildB);
        $grandchildElementB->addChild($grandchildElementSecondChildB);

        $this->element->addChild($childElement);
        $this->element->addChild($childElementB);

        $this->EOMNonVoidElementWriter->write();
    }

    public function test_writes_1_element_2_descendants_3_levels_2_with_text()
    {
        (string) $expectedElement = '<div>' . PHP_EOL;
        $expectedElement.= 'div Text!';
        $expectedElement.= '<p>' . PHP_EOL;
        $expectedElement.= 'p Text!';
        $expectedElement.= '<b>' . PHP_EOL;
        $expectedElement.= '<span>' . PHP_EOL;
        $expectedElement.= 'span Text!';
        $expectedElement.= '</span>' . PHP_EOL;
        $expectedElement.= '<i>' . PHP_EOL;
        $expectedElement.= '</i>' . PHP_EOL;
        $expectedElement.= '</b>' . PHP_EOL;
        $expectedElement.= '</p>' . PHP_EOL;
        $expectedElement.= '<section>' . PHP_EOL;
        $expectedElement.= 'section Text!';
        $expectedElement.= '<div>' . PHP_EOL;
        $expectedElement.= '<article>' . PHP_EOL;
        $expectedElement.= '</article>' . PHP_EOL;
        $expectedElement.= '<footer>' . PHP_EOL;
        $expectedElement.= 'footer Text!';
        $expectedElement.= '</footer>' . PHP_EOL;
        $expectedElement.= '</div>' . PHP_EOL;
        $expectedElement.= '</section>' . PHP_EOL;
        $expectedElement .= '</div>' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $divText = new Text;
        $divText->addContent('div Text!');

        $this->element->addChild($divText);
        
        (object) $childElement = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $pText = new Text;
        $pText->addContent('p Text!');
        
        $childElement->addChild($pText);

        (object) $grandchildElement = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $grandchildElementChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        (object) $spanText = new Text;
        $spanText->addContent('span Text!');
        
        $grandchildElementChild->addChild($spanText);

        (object) $grandchildElementSecondChild = new Element([
            'type' => 'i',
            'isVoid' => false
        ]);

        $childElement->addChild($grandchildElement);
        $grandchildElement->addChild($grandchildElementChild);
        $grandchildElement->addChild($grandchildElementSecondChild);
        

        (object) $childElementB = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $sectionText = new Text;
        $sectionText->addContent('section Text!');
        
        $childElementB->addChild($sectionText);

        (object) $grandchildElementB = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $grandchildElementChildB = new Element([
            'type' => 'article',
            'isVoid' => false
        ]);

        (object) $grandchildElementSecondChildB = new Element([
            'type' => 'footer',
            'isVoid' => false
        ]);

        (object) $footerText = new Text;
        $footerText->addContent('footer Text!');
        
        $grandchildElementSecondChildB->addChild($footerText);

        $childElementB->addChild($grandchildElementB);
        $grandchildElementB->addChild($grandchildElementChildB);
        $grandchildElementB->addChild($grandchildElementSecondChildB);

        $this->element->addChild($childElement);
        $this->element->addChild($childElementB);

        $this->EOMNonVoidElementWriter->write();
    }
}

