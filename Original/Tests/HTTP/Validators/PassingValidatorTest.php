<?php

use Stratum\Original\HTTP\Validator\PassingValidator;
use PHPUnit\framework\TestCase;

Class PassingValidatorTest extends TestCase
{
	public function test_always_passes()
	{
		$passingValidator = new PassingValidator;

		$passingValidator->validate();
		
		$this->assertTrue($passingValidator->hasPassed());
	}
}