<?php

use Stratum\Original\HTTP\Validator\SameValueValidator;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use PHPUnit\Framework\TestCase;

Class SameValueValidatorTest extends TestCase
{
	protected $sameValueValidator;

	public function setUp()
	{
		$this->sameValueValidator = new SameValueValidator;
	}

	public function test_throws_exception_if_either_value_has_not_been_set()
	{
		$this->expectException(MissingRequiredPropertyException::class);

		$this->sameValueValidator->validate();
	}

	public function test_throws_exception_if_expected_value_has_not_been_set()
	{
		$this->expectException(MissingRequiredPropertyException::class);

		$this->sameValueValidator->setValueToValidate('users');

		$this->sameValueValidator->validate();
	}

	public function test_throws_exception_if_actual_value_has_not_been_set()
	{
		$this->expectException(MissingRequiredPropertyException::class);

		$this->sameValueValidator->setExpectedValue('users');

		$this->sameValueValidator->validate();
	}

	public function test_passes_if_both_values_are_equal()
	{
		$this->sameValueValidator->setExpectedValue('users');
		$this->sameValueValidator->setValueToValidate('users');

		$this->sameValueValidator->validate();

		$this->assertTrue($this->sameValueValidator->hasPassed());
	}

	public function test_passes_even_when_values_have_different_case()
	{
		$this->sameValueValidator->setExpectedValue('USErS');
		$this->sameValueValidator->setValueToValidate('users');

		$this->sameValueValidator->validate();

		$this->assertTrue($this->sameValueValidator->hasPassed());
	}

	public function test_passes_even_when_values_have_different_whitespace()
	{
		$this->sameValueValidator->setExpectedValue('users    ');
		$this->sameValueValidator->setValueToValidate('    users   ');

		$this->sameValueValidator->validate();

		$this->assertTrue($this->sameValueValidator->hasPassed());
	}

	public function test_fails_when_values_differ()
	{
		$this->sameValueValidator->setExpectedValue('users');
		$this->sameValueValidator->setValueToValidate('administrators');

		$this->sameValueValidator->validate();

		$this->assertFalse($this->sameValueValidator->hasPassed());
	}
}









