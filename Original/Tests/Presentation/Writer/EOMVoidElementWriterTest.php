<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\Writer\EOMVoidElementWriter;

Class EOMVoidElementWriterTest extends TestCase
{
    public function test_writes_void_element_no_attributes()
    {
        (string) $expectedElement = '<img />' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        (object) $EOMVoidElementWriter = new EOMVoidElementWriter($element);

        $EOMVoidElementWriter->write();
    }

    public function test_writes_void_element_1_attribute()
    {
        (string) $expectedElement = '<img href="#" />' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        $element->addHref('#');

        (object) $EOMVoidElementWriter = new EOMVoidElementWriter($element);

        $EOMVoidElementWriter->write();
    }

    public function test_writes_void_element_2_attributes()
    {
        (string) $expectedElement = '<img href="#" class="thumbnail" />' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        $element->addHref('#');
        $element->addClass('thumbnail');

        (object) $EOMVoidElementWriter = new EOMVoidElementWriter($element);

        $EOMVoidElementWriter->write();
    }

    public function test_writes_void_element_3_attributes_1_attribute_composed_by_three_words()
    {
        (string) $expectedElement = '<img href="#" class="thumbnail" one-two-three="one" />' . PHP_EOL;

        $this->expectOutPutString($expectedElement);

        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        $element->addHref('#');
        $element->addClass('thumbnail');
        $element->addOneTwoThree('one');

        (object) $EOMVoidElementWriter = new EOMVoidElementWriter($element);

        $EOMVoidElementWriter->write();
    }
}

