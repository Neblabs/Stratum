<?php

namespace Stratum\Original\HTTP;

Abstract Class Validator
{
	protected $hasItPassed;

	abstract public function validate();

	public function passed()
	{
		$this->hasItPassed = true;
	}

	public function failed()
	{
		$this->hasItPassed = false;
	}

	public function hasPassed()
	{
		return $this->hasItPassed;
	}

	public function hasFailed()
	{
		if ($this->hasPassed()) {
			return false;
		} elseif ($this->hasItPassed === false) {
			return true;
		}

		
	}
}