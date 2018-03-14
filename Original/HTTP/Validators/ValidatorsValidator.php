<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Validator;

Class ValidatorsValidator extends Validator
{
	protected $validators = [];

	public function setValueToValidate(array $validators)
	{
		$this->validators = $validators;
	}

	public function validate()
	{
		foreach ($this->validators as $validator) {
			$validator->validate();

			if ($validator->hasFailed()) {
				$this->failed();
				break;
			} else {
				$this->passed();
			}
		}
	}
}