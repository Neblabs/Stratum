<?php

use Stratum\Original\HTTP\Creator\SegmentsValidatorCreator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Validator\FailingValidator;
use PHPUnit\Framework\TestCase;

Class SegmentsValidatorCreatorTest extends TestCase
{

	public function setUp()
	{
		$this->segmentsValidator = new SegmentsValidatorCreator;

		(object) $filtersRegistrator = $this->createMock(FiltersRegistrator::class);

		$filtersRegistrator->method('registeredFilters')
							->willReturn([
								'integer' => [
									'className' => 'Stratum\Prebuilt\Filter\DefaultFilter', 
									'methodName' => 'integer'
								],
								'lenght' => [
									'className' => 'Stratum\Custom\Filter\CustomFilter', 
									'methodName' => 'lenght'
								]
							]);

		$this->filtersRegistrator = $filtersRegistrator;

		$this->segmentsValidator->setFiltersRegistrator($this->filtersRegistrator);
	}
	
	public function test_returns_a_passing_ValidatorsValidator_object_with_only_a_single_slash_as_the_path()
	{

		$this->segmentsValidator->setPathDefinition('/');
		$this->segmentsValidator->setRequestedPath('/');

		(object) $pathValidator = $this->segmentsValidator->create();

		$pathValidator->validate();

		$this->assertTrue($pathValidator->hasPassed());

	}

	public function test_returns_a_ValidatorsValidator_object()
	{

		$this->segmentsValidator->setPathDefinition('users/(id | integer | lenght: 4)');
		$this->segmentsValidator->setRequestedPath('users/5572');

		(object) $pathValidator = $this->segmentsValidator->create();

		$pathValidator->validate();

		$this->assertTrue($pathValidator->hasPassed());

	}

	public function test_returns_a_failing_validator_object_if_the_requested_path_has_different_number_of_segments_than_the_path_definition()
	{
		$this->segmentsValidator->setPathDefinition('users/(id | integer)/delete');
		$this->segmentsValidator->setRequestedPath('users/5572');

		(object) $pathValidator = $this->segmentsValidator->create();

		$this->assertInstanceOf(FailingValidator::class, $pathValidator);
	}

	public function test_passing_routes_1()
	{
		$this->segmentsValidator->setPathDefinition('posts/(id | integer | lenght: 3)');
		$this->segmentsValidator->setRequestedPath('posts/326');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertTrue($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_2()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/326.js');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_3()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/32');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_4()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/3');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_5()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/3265');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_6()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/57a');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_7()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/word');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_8()
	{
		$this->segmentsValidator->setPathDefinition('/posts/(id | integer | lenght: 3)/');
		$this->segmentsValidator->setRequestedPath('/posts/abc');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

	public function test_failing_routes_9()
	{
		$this->segmentsValidator->setPathDefinition('posts/(id | integer | lenght: 3)');
		$this->segmentsValidator->setRequestedPath('posts/123/a');

		(object) $segmentsValidator = $this->segmentsValidator->create();

		$segmentsValidator->validate();

		$this->assertFalse($segmentsValidator->hasPassed());
	}

















}