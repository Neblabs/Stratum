<?php

use Stratum\Original\HTTP\Creator\FilterCreator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Prebuilt\Filter\ConcreteFilter;
use Stratum\Original\HTTP\Exception\UnexistentFilterException;
use PHPUnit\Framework\TestCase;

Class FilterCreatorTest extends TestCase
{
	public static function setUpBeforeClass()
    {
        (string) $TestController = file_get_contents('Original/Tests/HTTP/TestClasses/concreteFilter.php');
        file_put_contents('Prebuilt/Filters/concreteFilter.php', $TestController);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Prebuilt/Filters/concreteFilter.php');
    }

	public function test_throws_exception_if_attempting_to_instantiate_a_filter_that_has_not_been_registered()
	{
		$this->expectException(UnexistentFilterException::class);
		$this->expectExceptionMessage('A filter must be registered before attempting to create an instance of it');

		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->expects($this->once())
							->method('registeredFilters')
							->willReturn([
								'integer' => [
									'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
									'methodName' => 'integer'
								],
								'customfilter' => [
									'className' => 'Stratum\Custom\Filter\CustomFilter', 
									'methodName' => 'customfilter'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		$filterCreator->setFiltersRegistrator($filtersRegistrator);

		$filterCreator->createFromFilterName('nonRegisteredFilter');
	}

	public function test_creates_a_new_filter_object()
	{
		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'alwayspasses' => [
									'className' => 'Stratum\Prebuilt\Filter\ConcreteFilter', 
									'methodName' => 'alwayspasses'
								],
								'customfilter' => [
									'className' => 'Stratum\Custom\Filter\CustomFilter', 
									'methodName' => 'customfilter'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		$filterCreator->setFiltersRegistrator($filtersRegistrator);

		(object) $filter = $filterCreator->createFromFilterName('alwayspasses');

		$this->assertInstanceOf(ConcreteFilter::class, $filter);

	}

	public function test_filter_sets_correct_method()
	{
		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'alwayspasses' => [
									'className' => 'Stratum\Prebuilt\Filter\concreteFilter', 
									'methodName' => 'alwayspasses'
								],
								'customfilter' => [
									'className' => 'Stratum\Custom\Filter\CustomFilter', 
									'methodName' => 'customfilter'
								]				
							]);

		(object) $filterCreator = new FilterCreator;

		$filterCreator->setFiltersRegistrator($filtersRegistrator);

		(object) $filter = $filterCreator->createFromFilterName('alwayspasses');

		$filter->setValue(5572);

		$filter->validate();

		$this->assertEquals('alwayspasses', $filter->calledMethod());	
		$this->assertTrue($filter->hasPassed());	
	}

	public function test_filter_sets_correct_method_with_argument()
	{
		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'filterwithargument' => [
									'className' => 'Stratum\Prebuilt\Filter\concreteFilter', 
									'methodName' => 'filterwithargument'
								],
								'customfilter' => [
									'className' => 'Stratum\Custom\Filter\CustomFilter', 
									'methodName' => 'customfilter'
								]				
							]);

		$filterData =[
			'filterName' => 'filterwithargument',
			'filterArgument' => false
		];

		(object) $filterCreator = new FilterCreator;

		$filterCreator->setFiltersRegistrator($filtersRegistrator);

		(object) $filter = $filterCreator->createFromFilterNameAndArgument($filterData);

		$filter->setValue(5572);

		$filter->validate();

		$this->assertEquals('filterwithargument', $filter->calledMethod());	
		$this->assertFalse($filter->hasPassed());	
	}
















}