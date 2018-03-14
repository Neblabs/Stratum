<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class POSTRequest extends Request
{
    use className;
    
	protected function getValue($requestedValue)
	{
		return $this->request->request->get($requestedValue);
	}
}