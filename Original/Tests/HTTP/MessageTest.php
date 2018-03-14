<?php

use Stratum\Original\HTTP\Message;
use Stratum\Original\HTTP\Header;
use Stratum\Original\HTTP\URL;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

Class MessageTest extends TestCase
{
	public function setUp()
	{
		$SymfonyRequest = Request::create(
				'/users/5572',
				'GET',
				[]
			);

		$this->HTTPMessage = new Message($SymfonyRequest);
	}
	public function test_returns_correct_http_method_name_for_request()
	{
		$this->assertEquals('GET', $this->HTTPMessage->method);
	}

	public function test_returns_URL_object()
	{
		$this->assertInstanceOf(URL::class, $this->HTTPMessage->URL);
	}

	public function test_returns_http_body()
	{
		$SymfonyRequest = new Request(
				$_GET,
				$_POST,
				[],
				$_COOKIE,
				$_FILES,
				$_SERVER,
				'The contents of the http message body'
			);

		$HTTPMessage = new Message($SymfonyRequest);

		$this->assertEquals('The contents of the http message body', $HTTPMessage->body);
	}

	public function test_returns_http_header_object()
	{

		$this->assertInstanceOf(Header::class, $this->HTTPMessage->header);
	}



}














