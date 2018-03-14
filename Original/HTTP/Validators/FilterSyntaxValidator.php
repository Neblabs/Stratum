<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Validator;

Class FilterSyntaxValidator extends Validator
{

	protected $filterDefinition;

	public function setValueToValidate($filterDefinition)
	{
		$this->filterDefinition = $filterDefinition;
	}

	public function validate()
	{
		if ($this->passedFilterIsValid()) {
			$this->passed();
		} else {
			$this->failed();
		}
	}

	protected function passedFilterIsValid()
	{
		(string) $openingParenthesis = '\(';
		(string) $anyCharacterAnyNumberOfTimesAtLeastOnce = '.+';
		(string) $closingParenthesis = '\)';

		(boolean) $isValidFilterSyntax = preg_match(
					"/^{$openingParenthesis}{$anyCharacterAnyNumberOfTimesAtLeastOnce}{$closingParenthesis}$/",
					$this->filterDefinition
				);

		if ($isValidFilterSyntax) {
			return true;
		}

		return false;
	}
}