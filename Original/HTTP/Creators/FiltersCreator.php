<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Creator\FilterCreator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Validator\PassingValidator;
use Stratum\Original\HTTP\Request\Filter;
use Stratum\Original\HTTP\Exception\MalFormedFilterDefinitionException;
use Stratum\Original\Utility\Type\TypeConverter; 

Class FiltersCreator
{
	protected $filterCreator;
	protected $filtersRegistrator;
	protected $filterDefinition;
	protected $valueToFilter;
	protected $typeConverter;
	protected $filters = [];

	public function __construct()
	{
		$this->typeConverter = new TypeConverter;
	}
	/**
	 * A FiltersRegistrator object from which the registered filters will be checked.
	 * 		
	 * @param FiltersRegistrator $filtersRegistrator 
	 */
	public function setFiltersRegistrator(FiltersRegistrator $filtersRegistrator)
	{
		$this->filtersRegistrator = $filtersRegistrator;
	}

	/**
	 * The factory object that will create each filter.
	 * 
	 * @param FilterCreator $filterCreator [description]
	 */
	public function setFilterCreator(FilterCreator $filterCreator)
	{
		$this->filterCreator = $filterCreator;
		$this->filterCreator->setFiltersRegistrator($this->filtersRegistrator);
	}

	/**
	 * The specified single filter and wildcard definition for a path segment
	 *
	 * Example: (id | integer)
	 * 
	 * @param [type] $filterDefinition [description]
	 */
	public function setFilterDefinition($filterDefinition)
	{
		$this->filterDefinition = $this->removeParenthesisFrom($filterDefinition);
		$this->setArrayOfFilterDefinitions();
	}

	
	public function setValueToFilter($valueToFilter)
	{
		$this->valueToFilter = $valueToFilter;
	}

	protected function removeParenthesisFrom($filterDefinition)
	{
		return trim($filterDefinition, '()');
	}

	protected function setArrayOfFilterDefinitions()
	{
		(array) $filterDefinitions = explode('|', $this->filterDefinition);

		foreach ($filterDefinitions as $singleFilterDefinition) {

			$singleFilterDefinition = trim($singleFilterDefinition);

			$this->throwExceptionIfFilterDefinitionHasParameterSyntaxButNoParameterValue($singleFilterDefinition);

			if ($this->filterDefinitionHasParameters($singleFilterDefinition)) {

				$this->filters[] = $this->createArrayOfFilterAndItsParameterFrom($singleFilterDefinition);

			} else {

				$this->filters[] = $singleFilterDefinition;

			}

		}

	}

	protected  function throwExceptionIfFilterDefinitionHasParameterSyntaxButNoParameterValue($singleFilterDefinition)
	{
		(string) $oneOrMoreCharacters = '[a-zA-Z0-9]+';
		(string) $aColon = ':';
		(string) $ceroOrMoreSpaces = '[\s]*';

		(boolean) $hasFilterNameWithColonButNoParameterValue = preg_match("/^{$oneOrMoreCharacters}{$aColon}{$ceroOrMoreSpaces}$/", $singleFilterDefinition);

		if ($hasFilterNameWithColonButNoParameterValue) {
			throw new MalFormedFilterDefinitionException(
				'Filter with parameter requires a parameter value'
			);
		}
	}

	protected function filterDefinitionHasParameters($singleFilterDefinition)
	{
		(string) $oneOrMoreAlphanumericCharacters = '[a-zA-Z0-9]+';
		(string) $colon = ':';
		(string) $oneOrMoreSpaces = '[\s]+';
		(string) $oneOrMoreAlphanumericCharactersOrSpaces = '[a-zA-Z0-9\s]+';

		(boolean) $isFilterWithParametersyntax = preg_match(
				"/^{$oneOrMoreAlphanumericCharacters}{$colon}{$oneOrMoreSpaces}{$oneOrMoreAlphanumericCharactersOrSpaces}$/",
				$singleFilterDefinition
			);

		if ($isFilterWithParametersyntax) {
			return true;
		}

		return false;
	}

	protected function createArrayOfFilterAndItsParameterFrom($singleFilterDefinition)
	{
		(array) $filterNameAndItsParameter = explode(':', $singleFilterDefinition);

		$filterData = [
			'filterName' => strtolower($filterNameAndItsParameter[0]),
			'filterArgument' => $this->typeConverter->convertType(trim($filterNameAndItsParameter[1]))
		];

		return $filterData;
	}

	/**
	 * [create description]
	 * @return array An array of filters
	 */
	public function create()
	{
		(array) $filters = [];

		if ($this->onlyWildCardExists()) {
			return [new PassingValidator];
		}
		
		$this->removeWildCardFromFilters();

		foreach ($this->filters as $filter) {

			if ($this->filterHasArgument($filter)) {

				(object) $createdFilter = $this->filterCreator->createFromFilterNameAndArgument($filter);

			} else {

				(object) $createdFilter = $this->filterCreator->createFromFilterName($filter);

			}

			$createdFilter->setValue($this->valueToFilter);

			$filters[] = $createdFilter;

		}

		return $filters;
	}

	protected function filterHasArgument($filter)
	{
		(boolean) $isFilterAnArray = is_array($filter);

		return $isFilterAnArray;
	}

	protected function onlyWildCardExists()
	{
		(boolean) $onlyOneElementInFiltersArray = count($this->filters) === 1;

		if ($onlyOneElementInFiltersArray) {
			return true;
		}

		return false;
	}

	protected function removeWildCardFromFilters()
	{
		(integer) $wildcardElement = 0;
		
		unset($this->filters[$wildcardElement]);
	}
}























