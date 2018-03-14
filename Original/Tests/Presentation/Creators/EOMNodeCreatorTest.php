<?php

use Stratum\Original\Presentation\Creator\EOMNodeCreator;
use Stratum\Original\Presentation\Creator\EOMTextCreator;
use Stratum\Original\Presentation\Creator\EOMElementCreator;
use PHPUnit\Framework\TestCase;

Class EOMNodeCreatorTest extends TestCase
{
    public function test_given_a_DOMElement_object_creates_an_EOMElementCreator()
    {
        $this->assertInstanceOf(EOMElementCreator::class, EOMNodeCreator::getCreatorFrom(new DomElement('div')));
    }

    public function test_given_a_DOMText_object_creates_an_EOMTextCreator()
    {
        $this->assertInstanceOf(EOMTextCreator::class, EOMNodeCreator::getCreatorFrom(new DomText));
    }
}