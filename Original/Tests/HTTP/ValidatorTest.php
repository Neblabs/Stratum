<?php

use Stratum\Original\HTTP\Validator;
use PHPUnit\Framework\TestCase;

Class ValidatorTest extends TestCase
{
	public function setUp()
	{
		$this->validator = $this->getmockForAbstractClass(Validator::class);
	}

	public function test_retuns_true_when_passed()
	{
		$this->validator->passed();
		$this->assertTrue($this->validator->hasPassed());
	}

	public function test_returns_false_when_failed()
	{
		$this->validator->failed();
		$this->assertFalse($this->validator->hasPassed());
	}

	public function test_method_hasFailed_returns_true_when_failed()
	{
		$this->validator->failed();
		$this->assertTrue($this->validator->hasFailed());
	}

	public function test_method_hasFailed_returns_false_when_passed()
	{
		$this->validator->passed();
		$this->assertFalse($this->validator->hasFailed());
	}
}