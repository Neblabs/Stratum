<?php

use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\GETRequest;
use Stratum\Original\HTTP\POSTRequest;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;
use Stratum\Original\Exception\ForbiddenOverrideException;
use PHPUnit\Framework\TestCase;

Class RequestTest extends TestCase
{
	public function test_creates_a_GETRequest_object_if_is_an_http_GET_request()
	{
		$SymfonyRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

		$SymfonyRequest->method('getRealMethod')->willReturn('GET');

		$GETRequest = Request::createBasedOn($SymfonyRequest);

		$this->assertInstanceOf(GETRequest::class, $GETRequest);
	}

	public function test_creates_a_POSTRequest_object_if_is_an_http_POST_request()
	{
		$SymfonyRequest = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->getMock();

		$SymfonyRequest->method('getRealMethod')->willReturn('POST');

		$GETRequest = Request::createBasedOn($SymfonyRequest);

		$this->assertInstanceOf(POSTRequest::class, $GETRequest);
	}

}
