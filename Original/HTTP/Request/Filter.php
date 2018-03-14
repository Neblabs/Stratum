<?php

namespace Stratum\Original\HTTP\Request;


use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;

Abstract Class Filter extends \Stratum\Original\HTTP\Validator
{
	protected $methodName;
	protected $value;
	protected $filterArgument;
	protected $calledMethod;

	public function setFilterMethod($methodName)
	{
		$this->methodName = $methodName;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}

	public function setFilterArgument($filterArgument)
	{
		$this->filterArgument = $filterArgument; 
	}

	protected function hasArgument()
	{
		(boolean) $hasAnArgumentBeenSet = isset($this->filterArgument);

		return $hasAnArgumentBeenSet;
	}

	public function validate()
	{
		$this->throwExceptionIfNoFilterMethodNorValueHasBeenSet();

		$method = $this->methodName;

		$this->callFilter($method);
		
		$this->calledMethod = $method;
	}

	protected function callFilter($method)
	{
		if ($this->hasArgument()) {
			$this->$method($this->filterArgument);
		} else {
			$this->$method();
		}
	}

	public function calledMethod()
	{
		return $this->calledMethod;
	}

	protected function throwExceptionIfNoFilterMethodNorValueHasBeenSet()
	{
		if (is_null($this->methodName) or is_null($this->value)) {
			throw new MissingRequiredPropertyException(
				"The filter's method name and value must be set before calling Validator::validate"
				);
		}
	}

	
}














