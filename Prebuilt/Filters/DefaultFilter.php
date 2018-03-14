<?php

namespace Stratum\PreBuilt\Filter;

use Stratum\Original\HTTP\Request\Filter;

Class DefaultFilter extends Filter
{
	public function integer()
	{
		if (is_integer($this->value)) {

			$this->passed();

		} else {

			$this->failed();

		}
	}


}