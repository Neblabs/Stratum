<?php

namespace Stratum\Original\Utility\Type;

Class TypeConverter
{
	public function convertType($value)
	{
		$value = trim($value);
		
		if ($this->stringIsNumeric($value)) {
			$value = (integer) $value;
		}

		return $value; 
	}

	protected function stringIsNumeric($string) {
		(string) $oneOrMoreNumbers = '[0-9]+';

		(boolean) $isTheStringComposedByNumbersOnly = preg_match("/^{$oneOrMoreNumbers}$/", $string);

		return $isTheStringComposedByNumbersOnly;
	}
}