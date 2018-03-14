<?php

use Stratum\Original\HTTP\URL;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

Class URLTest extends TestCase
{
	public function setUp()
	{
		$SymfonyRequest = Request::create(
				'http://neblabs.com/users/5572/',
				'GET',
				[]
			);

		$this->HTTPURL = new URL($SymfonyRequest);
	}

	public function test_gets_correct_resource_path_without_slashes()
	{
		$this->assertEquals('users/5572', $this->HTTPURL->path);
	}

	public function test_gets_path_segments_as_array()
	{
		$pathSegments = [
			'users',
			'5572'
		];
		$this->assertSame($pathSegments, $this->HTTPURL->segments);
	}
}