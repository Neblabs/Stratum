<?php

namespace Stratum\Custom\Filter;

use Stratum\Original\HTTP\Request\Filter;

Class StratumTestFilter extends Filter
{
	public function testFilter()
	{
		$this->passed();
	}

	public function integer()
	{
		// will never get registered
	}

	public function anotherFilter()
	{
		
	}

	public function filterToBeUnregistered()
	{
		//won't last
	}

	public function filterForStaticRegistrator()
	{
		//won't last *2
	}

}