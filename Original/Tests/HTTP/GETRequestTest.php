<?php

use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\Message;
use Stratum\Original\HTTP\Exception\ForbiddenOverrideException;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

Class GETRequestTest extends TestCase
{
	public function test_gets_GET_query_value()
	{
		$GET = [
			'name' => 'Rafa'
		];
		$symfonyRequest = new Request(
				$GET,
				$_POST,
				[],
				$_COOKIE,
				$_FILES,
				$_SERVER
			);

		$Request = new GETRequest($symfonyRequest);

		$this->assertEquals('Rafa', $Request->name);
	}

	public function test_throws_exception_when_attempting_to_override_query_value()
	{
		$this->expectException(ForbiddenOverrideException::class);

		$symfonyRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

		$Request = new GETRequest($symfonyRequest);

		$Request->name = 'won\'t be set';
	}

	public function test_returns_a_message_object()
	{
		$symfonyRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

		$Request = new GETRequest($symfonyRequest);

		$this->assertInstanceOf(Message::class, $Request->http);
			
	}

}














