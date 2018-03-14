<?php

namespace Stratum\Custom\Filter;

use Stratum\Original\HTTP\Request\Filter;

Class StratumTestCustomFilter extends Filter
{
	protected function lenght($number)
	{
		(boolean) $segmentHasRequiredLenght = strlen($this->value) === $number;

		if ($segmentHasRequiredLenght) {

			$this->passed();
			
		} else {

			$this->failed();

		}
	}
}