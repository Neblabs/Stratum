<?php

use Stratum\Original\HTTP\Validator\FailingValidator;
use PHPUnit\Framework\TestCase;

Class FailingValidatorTest extends TestCase
{
	public function test_always_fails()
	{
		$failingValidator = new FailingValidator;

		$failingValidator->validate();

		$this->assertTrue($failingValidator->hasFailed());
		$this->assertFalse($failingValidator->hasPassed());
	}
}