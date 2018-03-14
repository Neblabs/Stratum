<?php

namespace Stratum\Original\HTTP\Registrator;

use Stratum\Original\HTTP\Registrator\FiltersRegistrator;


Class Register
{
	/**
	 * Registers a unique filter
	 * 
	 * @param  string $fullyQualifiedFilterName The fully qualified filter name. In Stratum, a fully qualified
	 * filter name is composed by an unqualified classname folowed by a dot and then followed by the filter method
	 * name; thus, if you had a filter called integer in a filter class called NumbersFilter, its fully qualified name
	 * would be: NumbersFilter.integer  
	 * 
	 * @return null
	 */
	public static function filter($fullyQualifiedFilterName)
	{
		$filtersRegistrator = new FiltersRegistrator($fullyQualifiedFilterName);

		$filtersRegistrator->register();

		return $filtersRegistrator;
	}
}