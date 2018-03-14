<?php

namespace Stratum\original\HTTP\Registrator;

use Stratum\Original\HTTP\Exception\MalformedFullyQualifiedFilterNameException;
use Stratum\Original\HTTP\Exception\UnexistentClassException;
use Stratum\Original\HTTP\Exception\UnexistentMethodException;
use Stratum\Original\HTTP\Exception\FilterNameUnavailableException;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\Exception\MissingActionException;

Class FiltersRegistrator
{
	protected $fullyQualifiedFilterName;
	protected $filterClassName;
	protected $filterMethodName;
	protected $fullyQualifiedClassName;
	protected $aFilterNameWasPassed;
	protected static $preBuiltFilters = [
		'integer' => [
			'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
			'methodName' => 'integer'
		], 
		'lenght' => [
			'className' => 'Stratum\Custom\Filter\CustomFilter',
			'methodName' => 'lenght'
		]
	];
	protected static $registeredFilters = [];
	protected static $filtersRegistrationFileHasBeenIncluded = false;

	public function __construct($fullyQualifiedFilterName = null)
	{
		$this->aFilterNameWasPassed = !empty($fullyQualifiedFilterName);

		if ($this->aFilterNameWasPassed) {

			$this->fullyQualifiedFilterName = $this->cleanFilterName($fullyQualifiedFilterName);

			$this->throwExceptionIfIsNotAFullyQualifiedFilterName();
			$this->setFilterClassAndMethodNames();

			$this->throwExceptionIfFilterClassOrMethodDoesNotexist();

		}


	}

	protected function setFilterClassAndMethodNames()
	{
		(array) $ClassAndMethod = explode('.', $this->fullyQualifiedFilterName);

		$this->filterClassName = $ClassAndMethod[0];
		$this->filterMethodName = strtolower($ClassAndMethod[1]);
		$this->fullyQualifiedClassName = "Stratum\\Custom\\Filter\\{$this->filterClassName}";
	}

	protected function throwExceptionIfIsNotAFullyQualifiedFilterName()
	{
		if ($this->passedFilterNameIsNotACompilantFullyQualifiedFilterName()) {
			throw new MalformedFullyQualifiedFilterNameException;
		}
	}

	protected function throwExceptionIfFilterClassOrMethodDoesNotexist() 
	{
		if ($this->filterClassDoesNotExist()) {
			throw new UnexistentClassException;
		}

		if ($this->filterMethodDoesNotExist()) {
			throw new UnexistentMethodException;
		}
	}

	protected function filterClassDoesNotExist()
	{
		if ($this->ClientFilterClassDoesntExist()) {
			return true;
		}

		return false;
	}

	protected function ClientFilterClassDoesntExist()
	{
		if (class_exists($this->fullyQualifiedClassName)) {
			return false;
		}

		return true;
	}

	protected function filterMethodDoesNotExist()
	{
		if (method_exists($this->fullyQualifiedClassName, $this->filterMethodName)) {
			return false;
		}

		return true;
	}

	protected function passedFilterNameIsNotACompilantFullyQualifiedFilterName()
	{
		(string) $oneOrMoreAlphanumericCharacters = '[A-Za-z0-9]+';
		(string) $oneDot = '\.';
		(boolean) $isNotFullyQualifiedName = !preg_match(
			"/^{$oneOrMoreAlphanumericCharacters}{$oneDot}{$oneOrMoreAlphanumericCharacters}$/",
			$this->fullyQualifiedFilterName
		);

		if ($isNotFullyQualifiedName) {
			return true;
		}

		return false;
	}

	protected function filterAlreadyExists()
	{
		(boolean) $filterNameIsInArray = isset(static::$registeredFilters[$this->filterMethodName]);

		return $filterNameIsInArray;
	}

	protected function filterNameHasBeenSet()
	{
		return isset($this->filterName);
	}

	protected function cleanFilterName($filterName)
	{
		return trim($filterName);
	}

	public function register()
	{
		$this->throwExceptionIfFilterNameIsAPreBuiltFilter();
		$this->throwExceptionIfFilterNameAlreadyExists();

		static::$registeredFilters[$this->filterMethodName] = $this->createArrayOfFilterData();
	}

	public function registeredFilters()
	{
		$this->includeFiltersRegistrationFileIfItHasNotBeenIncluded();

		return array_merge(static::$preBuiltFilters, static::$registeredFilters);
	}

	protected function includeFiltersRegistrationFileIfItHasNotBeenIncluded()
	{
		(boolean) $filtersRegistrationFilehasNotBeenIncluded = static::$filtersRegistrationFileHasBeenIncluded === false;

        if ($filtersRegistrationFilehasNotBeenIncluded) {
            
            require_once STRATUM_ROOT_DIRECTORY . '/Design/Control/Filters/Register.php';

            static::$filtersRegistrationFileHasBeenIncluded = true;
        }
	}

	protected function throwExceptionIfFilterNameIsAPreBuiltFilter()
	{	
		(boolean) $filterNameExistsInThePrebuiltFiltersArray = isset(static::$preBuiltFilters[$this->filterMethodName]);

		if ($filterNameExistsInThePrebuiltFiltersArray) {
			throw new FilterNameUnavailableException(
				'Cannot register a filter with the same name as a prebuilt filter'
				);
		}
	}

	protected function throwExceptionIfFilterNameAlreadyExists()
	{
		if ($this->filterAlreadyExists()) {
			throw new FilterNameUnavailableException(
				'Cannot register filter because a filter with the same name has already been registered'
			);
		}
	}

	protected function createArrayOfFilterData()
	{
		$filterData = [
			'className' => "Stratum\\Custom\\Filter\\{$this->filterClassName}",
			'methodName' => $this->filterMethodName
		];

		return $filterData;
	}

	public function unRegister()
	{
		$this->throwExceptionIfNoFilterNameWasSet();
		$this->throwExceptionIfFilterHasNotBeenRegistered();

		unset(static::$registeredFilters[$this->filterMethodName]);
	}

	protected function throwExceptionIfNoFilterNameWasSet()
	{
		$noFilterNameWasSet = !$this->aFilterNameWasPassed;

		if ($noFilterNameWasSet) {
			throw new MissingRequiredPropertyException(
				'A fully qualified name must be set in order to register and unregister a filter'
			);
		} 
	}

	protected function throwExceptionIfFilterHasNotBeenRegistered()
	{
		$currentFilterDoesNotExistInArray = !isset(static::$registeredFilters[$this->filterMethodName]);

		if ($currentFilterDoesNotExistInArray) {
			throw new MissingActionException(
				'The filter must be set prior trying to unregister it'
			);
		}
	}
}












