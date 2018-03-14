<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Writer\EOMNodeWriter;
use Stratum\Original\Presentation\Writer\EOMNonVoidElementWriter;
use Stratum\Original\Presentation\Writer\EOMTextWriter;
use Stratum\Original\Presentation\Writer\EOMVoidElementWriter;

Class EOMNodeWriterTest extends TestCase
{
    public function test_creates_EOMTextWriter()
    {
        (object) $Text = new Text;

        (object) $EOMTextWriter = EOMNodeWriter::createFrom($Text);

        $this->assertInstanceOf(EOMTextWriter::class, $EOMTextWriter);
    }

    public function test_creates_EOMNonVoidElementWriter()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $EOMNonVoidElementWriter = EOMNodeWriter::createFrom($element);

        $this->assertInstanceOf(EOMNonVoidElementWriter::class, $EOMNonVoidElementWriter);
    }

    public function test_creates_EOMVoidElementWriter()
    {
        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        (object) $EOMVoidElementWriter = EOMNodeWriter::createFrom($element);

        $this->assertInstanceOf(EOMVoidElementWriter::class, $EOMVoidElementWriter);
    }
}
