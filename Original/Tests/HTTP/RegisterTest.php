<?php

use Stratum\Original\HTTP\Registrator\Register;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use PHPUnit\Framework\TestCase;

Class RegisterTest extends TestCase
{
	public static function setUpBeforeClass()
	{
		(string) $exampleFilter = file_get_contents(STRATUM_ROOT_DIRECTORY . "/Original/Tests/HTTP/Registrators///StratumTestFilter.php");

		
		file_put_contents(STRATUM_ROOT_DIRECTORY . "/Design/Control/Filters/StratumTestFilter.php", $exampleFilter);
	} 

	public static function tearDownAfterClass()
	{
		unlink(STRATUM_ROOT_DIRECTORY . "/Design/Control/Filters/StratumTestFilter.php");
	}

	public function test_returns_a_FiltersRegistrator_object()
	{
		$filtersRegistrator = Register::filter('StratumTestFilter.filterForStaticRegistrator');

		$this->assertInstanceOf(FiltersRegistrator::class, $filtersRegistrator);

		$filtersRegistrator->unRegister();
	}
//
	public function test_registers_filter()
	{
		(array) $registeredFilters = [
			'integer' => [
				'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
				'methodName' => 'integer'
			],
			'lenght' => [
				'className' => 'Stratum\Custom\Filter\CustomFilter',
				'methodName' => 'lenght'
			],
			'filterforstaticregistrator' => [
				'className' => 'Stratum\Custom\Filter\StratumTestFilter',
				'methodName' => 'filterforstaticregistrator'
			]
		];

		$filtersRegistrator = Register::filter('StratumTestFilter.filterForStaticRegistrator');

		$this->assertSame($registeredFilters, $filtersRegistrator->registeredFilters());

		$filtersRegistrator->unRegister();
	}
}