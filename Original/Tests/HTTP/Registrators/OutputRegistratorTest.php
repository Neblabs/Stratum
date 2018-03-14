<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Registrator\OutputRegistrator;


Class OutputRegistratorTest extends TestCase
{
    public function test_registers_and_retreives_output()
    {
        (object) $OutputRegistrator = new OutputRegistrator;
        (string) $outputContent = 'Not So Fast!';

        $OutputRegistrator->setOutput($outputContent);
        $OutputRegistrator->register();

        (object) $anotherOutputRegistrator = new OutputRegistrator;

        $this->assertTrue($anotherOutputRegistrator->outPutExists());
        $this->assertEquals($outputContent, $anotherOutputRegistrator->registeredOutput());
    }  

    public function test_returns_false_for_empty_output()
    {
        (object) $OutputRegistrator = new OutputRegistrator;
        (string) $outputContent = ' ';

        $OutputRegistrator->setOutput($outputContent);
        $OutputRegistrator->register();

        (object) $anotherOutputRegistrator = new OutputRegistrator;

        $this->assertFalse($anotherOutputRegistrator->outPutExists());
    }    


}


