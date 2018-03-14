<?php

use Stratum\Original\HTTP\URLData;
use PHPUnit\Framework\TestCase;

Class URLDataTest extends TestCase
{
    public function test_gets_value_from_array()
    {
        (object) $URLData = new URLData([
            'id' => '5572'
        ]);

        $this->assertEquals('5572', $URLData->id);
    }
}