<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Test\Presentation\TestClass\ConcreteFormatter;

Class ConcreteFormatterTest extends TestCase
{
    public function test_formatter_method_gets_called_and_its_returned_value_is_returned()
    {
        (string) $text = 'shout';

        (object) $ConcreteFormatter = $this->getMockBuilder(ConcreteFormatter::class)
                                           ->setMethods(['inUpperCase'])
                                           ->setConstructorArgs([$text])
                                           ->getMock();

        $ConcreteFormatter->expects($this->once())->method('inUpperCase')->willReturn($text);

        $ConcreteFormatter->setFormatterMethod('inUpperCase');

        $this->assertEquals($text, $ConcreteFormatter->formattedText());
    }
}