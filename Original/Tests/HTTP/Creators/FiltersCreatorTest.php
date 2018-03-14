<?php 

use Stratum\Original\HTTP\Creator\FiltersCreator;
use Stratum\Original\HTTP\Validator\PassingValidator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Creator\FilterCreator;
use Stratum\Original\HTTP\Request\Filter;
use Stratum\Prebuilt\Filter\DefaultFilter;
use Stratum\Prebuilt\Filter\concreteFilter;
use Stratum\Original\HTTP\Exception\MalFormedFilterDefinitionException;
use PHPUnit\Framework\TestCase;

Class FiltersCreatorTest extends TestCase
{
	public function test_returns_one_single_passing_validator_if_filter_definition_has_no_filters_only_the_wildcard()
	{
		$filtersCreator = new FiltersCreator;

		$filtersCreator->setFilterDefinition('(id)');
		$filtersCreator->setValueToFilter(5572);

		(array) $filters = $filtersCreator->create();

		$this->assertCount(1, $filters);
		$this->assertInstanceOf(PassingValidator::class, $filters[0]);
	}

	public function test_returns_array_of_filters_from_a_filter_definition()
	{

		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'integer' => [
									'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
									'methodName' => 'integer'
								],
								'filterwithargument' => [
									'className' => 'Stratum\Prebuilt\Filter\concreteFilter', 
									'methodName' => 'filterwithargument'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		(object) $filtersCreator = new FiltersCreator;

		$filtersCreator->setFiltersRegistrator($filtersRegistrator);
		$filtersCreator->setFilterCreator($filterCreator);
//
		$filtersCreator->setFilterDefinition('(id | integer | filterWithArgument: 55720432)');
		$filtersCreator->setValueToFilter(5572);
//
		(array) $filters = $filtersCreator->create();
//
		$this->assertCount(2, $filters);
	
		$this->assertInstanceOf(DefaultFilter::class, $filters[0]);
		$this->assertInstanceOf(concreteFilter::class, $filters[1]);

		foreach ($filters as $filter) {
			$filter->validate();
		}

		$this->assertTrue($filters[0]->hasPassed());
		$this->assertTrue($filters[1]->hasPassed());
		


	}

	public function test_parses_correct_parameter_with_spaces_in_filter_definition()
	{

		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'filterwithargument' => [
									'className' => 'Stratum\Prebuilt\Filter\concreteFilter', 
									'methodName' => 'filterwithargument'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		(object) $filtersCreator = new FiltersCreator;

		$filtersCreator->setFiltersRegistrator($filtersRegistrator);
		$filtersCreator->setFilterCreator($filterCreator);
//
		$filtersCreator->setFilterDefinition('(id | filterWithArgument: a string or two)');
		$filtersCreator->setValueToFilter(5572);
//
		(array) $filters = $filtersCreator->create();
//
		$this->assertCount(1, $filters);

		$this->assertInstanceOf(concreteFilter::class, $filters[0]);
	
		$filters[0]->validate();


		$this->assertTrue($filters[0]->hasPassed());
		
	}

	public function test_throws_exception_if_filter_with_parameter_is_passed_no_parameter()
	{
		$this->expectException(MalFormedFilterDefinitionException::class);
		$this->expectExceptionMessage('Filter with parameter requires a parameter value');
		
		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'filterwithargument' => [
									'className' => 'Stratum\Prebuilt\Filter\concreteFilter', 
									'methodName' => 'filterwithargument'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		(object) $filtersCreator = new FiltersCreator;

		$filtersCreator->setFiltersRegistrator($filtersRegistrator);
		$filtersCreator->setFilterCreator($filterCreator);
//
		$filtersCreator->setFilterDefinition('(id | filterWithArgument: )');
		$filtersCreator->setValueToFilter(5572);
//
		(array) $filters = $filtersCreator->create();
//

	}











}