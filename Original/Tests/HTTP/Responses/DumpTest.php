<?php

use Stratum\Original\HTTP\Response\Dump;
use PHPUnit\Framework\TestCase;

Class DumpTest extends TestCase
{
    public function test_respons_with_a_dummped_version_of_a_variable()
    {
        (object) $dump = new Dump(new Symfony\Component\HttpFoundation\Response);

        (array) $variable = [1, true, 'name'];

        $dump->variable($variable);

        ob_start();

        $dump->send();

        $responseContents = ob_get_contents();
        
        ob_end_clean();
        
        $responseContents = trim($responseContents);

        

        $this->assertEquals(trim('array(3) {
  [0]=>
  int(1)
  [1]=>
  bool(true)
  [2]=>
  string(4) "name"
}'), $responseContents);
        

    }
}