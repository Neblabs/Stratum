<?php

use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Exception\MalformedFullyQualifiedFilterNameException;
use Stratum\Original\HTTP\Exception\UnexistentClassException;
use Stratum\Original\HTTP\Exception\UnexistentMethodException;
use Stratum\Original\HTTP\Exception\FilterNameUnavailableException;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\HTTP\Exception\MissingActionException;
use PHPUnit\Framework\TestCase;

Class FiltersRegistratorTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		(string) $exampleFilter = file_get_contents(STRATUM_ROOT_DIRECTORY . "/Original/Tests/HTTP/Registrators/StratumTestFilter.php");
		
		file_put_contents(STRATUM_ROOT_DIRECTORY . "/Design/Control/Filters/StratumTestFilter.php", $exampleFilter);
	} 

	public static function tearDownAfterClass()
	{
		unlink(STRATUM_ROOT_DIRECTORY . "/Design/Control/Filters/StratumTestFilter.php");

	}

	public function test_throws_exception_if_attempting_to_register_a_non_fully_qualified_filter_name()
	{
		$this->expectException(MalformedFullyQualifiedFilterNameException::class);

		new FiltersRegistrator('incompleteFilterName');
	}

	public function test_throws_exception_if_Class_doesnt_exist()
	{
		$this->expectException(UnexistentClassException::class);

		new FiltersRegistrator('unexistentFilterClass.filterMethod');
	}

	public function test_throws_exception_if_filter_class_exists_but_method_does_not()
	{
		$this->expectException(UnexistentMethodException::class);

		new FiltersRegistrator('StratumTestFilter.unknown');
	}

	public function test_throws_exception_if_attempting_to_register_a_filter_with_the_same_name_as_a_pre_built_filter()
	{
		$this->expectException(FilterNameUnavailableException::class);
		$this->expectExceptionMessage('Cannot register a filter with the same name as a prebuilt filter');

		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.integer');

		$filtersRegistrator->register();
	}

	public function test_throws_exception_if_attempting_to_register_a_case_insensitive_filter_with_the_same_name_as_a_pre_built_filter()
	{
		$this->expectException(FilterNameUnavailableException::class);
		$this->expectExceptionMessage('Cannot register a filter with the same name as a prebuilt filter');

		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.INTEGer');

		$filtersRegistrator->register();
	}

	public function test_returns_array_of_registered_filter_data()
	{
		$filtersRegistrator = new FiltersRegistrator;

		(array) $builtInFilterData = [
					'integer' => [
						'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
						'methodName' => 'integer'
					], 
					'lenght' => [
						'className' => 'Stratum\Custom\Filter\CustomFilter',
						'methodName' => 'lenght'
					]
				];

		$this->assertSame($builtInFilterData, $filtersRegistrator->registeredFilters());
	}

	public function test_registers_and_retreives_filter_data()
	{
		(array) $filterData = [
					'integer' => [
						'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
						'methodName' => 'integer'
					],
					'lenght' => [
						'className' => 'Stratum\Custom\Filter\CustomFilter',
						'methodName' => 'lenght'
					],
					'testfilter' => [
						'className' => 'Stratum\Custom\Filter\StratumTestFilter',
						'methodName' => 'testfilter'
					]
				];

		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.testFilter');

		$filtersRegistrator->register();

		$this->assertSame($filterData, $filtersRegistrator->registeredFilters());

		return $filterData;
	}

	public function test_throws_exception_if_attempting_to_register_a_filter_with_the_same_name_as_an_already_registered_filter()
	{
		$this->expectException(FilterNameUnavailableException::class);

		$this->expectExceptionMessage("Cannot register filter because a filter with the same name has already been registered");
		
		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.testFilter');

		$filtersRegistrator->register();

		$filtersRegistrator2 = new FiltersRegistrator('StratumTestFilter.testFilter');

		$filtersRegistrator2->register();
	}


	/**
	 * @depends test_registers_and_retreives_filter_data
	 * 
	 */
	public function test_retreives_filter_data_from_a_fresh_object(array $filterData)
	{
		$anotherFiltersRegistrator = new FiltersRegistrator;

		$this->assertSame($filterData, $anotherFiltersRegistrator->registeredFilters());
	}

	public function test_throws_exception_when_attempting_to_unregister_a_filter_with_no_filter_name_set_in_the_constructor()
	{
		$this->expectException(MissingRequiredPropertyException::class);
		$this->expectExceptionMessage(
			'A fully qualified name must be set in order to register and unregister a filter'
		);

		$filtersRegistrator = new FiltersRegistrator;

		$filtersRegistrator->unRegister();
	}

	/**
	 * @depends test_registers_and_retreives_filter_data
	 * 
	 */
	public function test_unregisters_the_previously_registered_filter_by_the_same_object(array $originalFilterData)
	{
		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.filterToBeUnregistered');

		$filtersRegistrator->register();

		$filtersRegistrator->unRegister();

		$this->assertSame($originalFilterData, $filtersRegistrator->registeredFilters());
	}

	public function test_throws_exception_if_attempting_to_unregister_a_filter_without_calling_register_first()
	{
		$this->expectException(MissingActionException::class);
		$this->expectExceptionMessage('The filter must be set prior trying to unregister it');

		$filtersRegistrator = new FiltersRegistrator('StratumTestFilter.filterToBeUnregistered');

		$filtersRegistrator->unRegister();
	}


}


























