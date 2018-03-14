<?php

use Stratum\Original\HTTP\Validator\ValidatorsValidator;
use Stratum\Original\HTTP\Creator\FiltersCreator;
use Stratum\Prebuilt\Filter\ConcreteFilter;
use PHPUnit\Framework\TestCase;

Class ValidatorsValidatorTest extends TestCase
{
	protected $validatorsValidator;

	public function setUp()
	{
		$this->validatorsValidator = new ValidatorsValidator;
	}

	public function test_at_least_the_first_validator_in_array_gets_called_once()
	{
		$validatorOne = $this->createMock(ConcreteFilter::class);

		$validatorOne->expects($this->once())
					->method('validate');

		$this->validatorsValidator->setValueToValidate([$validatorOne]);

		$this->validatorsValidator->validate();
	}

	public function test_subsequent_validators_do_not_get_called_when_previous_one_has_failed()
	{
		$validatorOne = $this->createMock(ConcreteFilter::class);

		$validatorOne->expects($this->once())
					->method('hasFailed')
					->willReturn(true);

		$validatorTwo = $this->createMock(ConcreteFilter::class);

		$validatorTwo->expects($this->never())
					->method('hasFailed');

		$validatorThree = $this->createMock(ConcreteFilter::class);

		$validatorThree->expects($this->never())
					->method('hasFailed');

		$this->validatorsValidator->setValueToValidate(
			[
			$validatorOne, 
			$validatorTwo, 
			$validatorThree
			]
		);

		$this->validatorsValidator->validate();
	}

	public function test_validator_passes_when_validators_to_validate_have_passed()
	{
		$validatorOne = $this->createMock(ConcreteFilter::class);

		$validatorOne->expects($this->once())
					->method('validate');

		$validatorOne->method('hasFailed')
					->willReturn(false);

		$this->validatorsValidator->setValueToValidate([$validatorOne]);

		$this->validatorsValidator->validate();

		$this->assertTrue($this->validatorsValidator->hasPassed());
	}

	public function test_validator_passes_when_ALL_validators_to_validate_have_passed()
	{
		$validatorOne = $this->createMock(ConcreteFilter::class);

		$validatorOne->expects($this->once())
					->method('hasFailed')
					->willReturn(false);

		$validatorTwo = $this->createMock(ConcreteFilter::class);

		$validatorTwo->expects($this->once())
					->method('hasFailed')
					->willReturn(false);

		$validatorThree = $this->createMock(ConcreteFilter::class);

		$validatorThree->expects($this->once())
					->method('hasFailed')
					->willReturn(false);

		$this->validatorsValidator->setValueToValidate(
			[
			$validatorOne, 
			$validatorTwo, 
			$validatorThree
			]
		);

		$this->validatorsValidator->validate();

		$this->assertTrue($this->validatorsValidator->hasPassed());
	}

	public function test_validator_fails_when_only_one_validator_to_validate_has_failed()
	{
		$validatorOne = $this->createMock(ConcreteFilter::class);

		$validatorOne->method('hasFailed')
					->willReturn(false);

		$validatorTwo = $this->createMock(ConcreteFilter::class);

		$validatorTwo->method('hasFailed')
					->willReturn(true);

		$validatorThree = $this->createMock(ConcreteFilter::class);

		$validatorThree->method('hasFailed')
					->willReturn(false);

		$this->validatorsValidator->setValueToValidate(
			[
			$validatorOne, 
			$validatorTwo, 
			$validatorThree
			]
		);

		$this->validatorsValidator->validate();

		$this->assertTrue($this->validatorsValidator->hasFailed());
	}
}





















