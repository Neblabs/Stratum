<?php

use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Creator\EOMTextCreator;
use PHPUnit\Framework\TestCase;

Class EOMTextCreatorTest extends TestCase
{
    public function test_text_object_gets_created_from_DOMText()
    {
        (object) $DOMText = new DOMText('The text content');

        (object) $EOMTextCreator = new EOMTextCreator($DOMText);

        $this->assertInstanceOf(Text::class, $EOMTextCreator->create());
        $this->assertEquals('The text content', $EOMTextCreator->create()->content());
    }
}