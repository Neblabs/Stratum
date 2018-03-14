<?php

use Stratum\Original\HTTP\Validator\FilterSyntaxValidator;
use PHPUnit\Framework\TestCase;

Class FilterSyntaxValidatorTest extends TestCase
{
	protected $filterSyntaxValidator;

	public function setUp()
	{
		$this->filterSyntaxValidator = new FilterSyntaxValidator;
	}

	public function test_passes_whem_given_a_valid_filter_definition()
	{
		$this->filterSyntaxValidator->setValueToValidate('(id | integer)');

		$this->filterSyntaxValidator->validate();

		$this->assertTrue($this->filterSyntaxValidator->hasPassed());
	}

	public function test_passes_whem_given_a_valid_filter_definition_with_parameters()
	{
		$this->filterSyntaxValidator->setValueToValidate('(id | integer | lenght: 3)');

		$this->filterSyntaxValidator->validate();

		$this->assertTrue($this->filterSyntaxValidator->hasPassed());
	}

	public function test_passes_whem_given_a_valid_filter_definition_without_filters()
	{
		$this->filterSyntaxValidator->setValueToValidate('(id)');

		$this->filterSyntaxValidator->validate();

		$this->assertTrue($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_an_invalid_filter_defintion()
	{
		$this->filterSyntaxValidator->setValueToValidate('users');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_a_filter_with_no_wildcard_nor_filters()
	{
		$this->filterSyntaxValidator->setValueToValidate('()');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_an_incorrect_filter_that_doesnt_start_with_a_parenthesis()
	{
		$this->filterSyntaxValidator->setValueToValidate('wrong(id | integer)');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_an_incorrect_filter_that_doesnt_end_with_a_parenthesis()
	{
		$this->filterSyntaxValidator->setValueToValidate('(id | integer)wrong*2');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_an_incorrect_filter_that_is_missing_the_opening_parenthesis()
	{
		$this->filterSyntaxValidator->setValueToValidate('id | integer)');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}

	public function test_fails_when_given_an_incorrect_filter_that_is_missing_the_closing_parenthesis()
	{
		$this->filterSyntaxValidator->setValueToValidate('(id | integer');

		$this->filterSyntaxValidator->validate();

		$this->assertFalse($this->filterSyntaxValidator->hasPassed());
	}
}

























