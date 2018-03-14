<?php

namespace Stratum\Prebuilt\Filter;

use Stratum\Original\HTTP\Request\Filter;

Class ConcreteFilter extends Filter
{
	public function alwaysPasses()
	{
		$this->passed();
	}

	public function alwaysFails()
	{
		$this->failed();
	}

	public function filterWithArgument($secretCode)
	{
		if (($secretCode === 55720432) or ($secretCode === 'a string or two')) {
			$this->passed();
		} else {
			$this->failed();
		}
	}
}