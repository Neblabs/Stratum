<?php

use Stratum\Original\HTTP\POSTRequest;
use Stratum\Original\HTTP\Exception\ForbiddenOverrideException;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

Class POSTRequestTest extends TestCase
{
	public function test_gets_POST_query_value()
	{
		$POST = [
			'password' => 'secret'
		];
		$symfonyRequest = new Request(
				$_GET,
				$POST,
				[],
				$_COOKIE,
				$_FILES,
				$_SERVER
			);

		$Request = new POSTRequest($symfonyRequest);

		$this->assertEquals('secret', $Request->password);
	}

	public function test_throws_exception_when_attempting_to_override_query_value()
	{
		$this->expectException(ForbiddenOverrideException::class);

		$symfonyRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

		$Request = new POSTRequest($symfonyRequest);

		$Request->password = 'won\'t be set';
	}
}
