<?php

use Stratum\Original\HTTP\Response\JSON;
use PHPUnit\Framework\TestCase;

Class JSONTest extends TestCase
{
    public function test_respons_with_a_json_array_or_object_from_a_php_array()
    {
        (object) $JSONResponse = new JSON(new Symfony\Component\HttpFoundation\Response);

        $JSONResponse->fromArray([
            'name' => 'Rafael',
            'last' => 'Serna'
        ]);

        ob_start();

        $JSONResponse->send();

        $responseContents = ob_get_contents();

        ob_end_clean();

        $this->assertEquals('{"name":"Rafael","last":"Serna"}', $responseContents);

    }
}