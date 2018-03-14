<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Validator;

Class FailingValidator extends Validator
{
	public function validate()
	{
		$this->failed();
	}
}