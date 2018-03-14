<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Request\Filter;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Exception\UnexistentFilterException;

Class FilterCreator
{
	protected $filtersRegistrator;
	protected $registeredFilters;
	
	public function setFiltersRegistrator(FiltersRegistrator $filtersRegistrator)
	{
		$this->filtersRegistrator = $filtersRegistrator;
		$this->registeredFilters = $this->filtersRegistrator->registeredFilters();
	}

	public function createFromFilterName($SinglefilterName)
	{
		$this->throwExceptionIfFilterNameDoesNotExist($SinglefilterName);

		(string) $filterClass = $this->registeredFilters[$SinglefilterName]['className'];

		$filter = new $filterClass;

		$filter->setfilterMethod($SinglefilterName);

		return $filter;
	}

	public function createFromFilterNameAndArgument(array $filterData)
	{
		(object) $filter = $this->createFromFilterName($filterData['filterName']);

		$filter->setFilterArgument($filterData['filterArgument']);

		return $filter;
	}

	protected function throwExceptionIfFilterNameDoesNotExist($SinglefilterName)
	{

		(boolean) $filterNameIsNotInArray = !isset($this->registeredFilters[$SinglefilterName]);

		if ($filterNameIsNotInArray) {
			throw new UnexistentFilterException(
				$SinglefilterName . 'A filter must be registered before attempting to create an instance of it'
			);
		}

	}
}