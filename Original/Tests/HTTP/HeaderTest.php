<?php

use Stratum\Original\HTTP\Header;
use \Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

Class HeaderTest extends TestCase
{
	public function test_get_HTTP_header_value()
	{	
		$_SERVER['HTTP_USER_AGENT'] = 'Mozila 5.0';
		$_SERVER['HTTP_REFERER'] = 'localhost';

		$symfonyRequest = new Request(
				$_GET,
				$_POST,
				[],
				$_COOKIE,
				$_FILES,
				$_SERVER
			);

		$HTTPHeader = new Header($symfonyRequest);

		$this->assertEquals('Mozila 5.0', $HTTPHeader->userAgent);
		$this->assertEquals('localhost', $HTTPHeader->referer);
	}
}