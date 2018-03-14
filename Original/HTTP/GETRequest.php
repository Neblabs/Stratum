<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class GETRequest extends Request
{
    use className;
    
	protected function getValue($requestedValue)
	{
		return $this->request->query->get($requestedValue);
	}
}