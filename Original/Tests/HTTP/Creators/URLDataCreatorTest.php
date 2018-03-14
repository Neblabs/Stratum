<?php

use Stratum\Original\HTTP\Creator\URLDataCreator;
use Stratum\Original\HTTP\URLData;
use PHPUnit\Framework\TestCase;

Class URLDataCreatorTest extends TestCase
{
    public function test_returns_a_URLData_object()
    {
        (object) $URLDataCreator = new URLDataCreator;

        $URLDataCreator->setRequestedPath('users/5572/comments/6543');
        $URLDataCreator->setPathDefinition('users/(id | integer | lenght:4)/comments/(commentId)');

        $URLData = $URLDataCreator->create();

        $this->assertInstanceOf(URLData::class, $URLData);
        $this->assertEquals('5572', $URLData->id);
        $this->assertEquals('6543', $URLData->commentId);
    }
}