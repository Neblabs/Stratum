<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Validator;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;

Class SameValueValidator extends Validator
{
	protected $expectedValue;
	protected $actualValue;

	public function setExpectedValue($expectedValue)
	{
		$this->expectedValue = $this->cleanValue($expectedValue);
	}

	public function setValueToValidate($actualValue)
	{
		$this->actualValue = $this->cleanValue($actualValue);
	}

	public function validate()
	{
		$this->throwExceptionIfNoExpectedNorActualValueHaveBeenSet();

		(boolean) $valuesAreDifferent = $this->expectedValue !== $this->actualValue;

		if ($valuesAreDifferent) {
			$this->failed();
		} else {
			$this->passed();
		}
	}

	protected function throwExceptionIfNoExpectedNorActualValueHaveBeenSet()
	{
		(boolean) $oneValueIsMissing = (empty($this->expectedValue) or empty($this->actualValue));
		
		if ($oneValueIsMissing) {
			throw new MissingRequiredPropertyException(
				"Both expected and actual must be set with SameValueValidator::setExpectedValue() and SameValueValidator::setValueToValidate() respectively before calling SameValueValidator::validate()"
			);
		}
	}

	protected function cleanValue($value)
	{
		if (is_string($value)) {
			return trim(strtolower($value));
		}

		return $value;
	}
}



















