<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Text;

Class TextTest extends TestCase
{
    public function test_sets_and_gets_text_content()
    {
        (object) $Text = new Text;

        $Text->addContent('The text content');

        $this->assertEquals('The text content', $Text->content());
    }
}