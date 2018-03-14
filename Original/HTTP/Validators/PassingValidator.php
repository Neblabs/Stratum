<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Validator;

Class PassingValidator extends Validator
{
	public function validate()
	{
		$this->passed();
	}
}