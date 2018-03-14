<?php

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\Validator\ValidatorsValidator;
use Stratum\Original\HTTP\Validator\SameValueValidator;
use Stratum\Original\HTTP\Validator\FilterSyntaxValidator;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Creator\FiltersCreator;
use Stratum\Original\HTTP\Creator\FilterCreator;
use Stratum\Original\HTTP\Validator\FailingValidator;
use Stratum\Original\Utility\Type\TypeConverter;

Class SegmentsValidatorCreator
{
	protected $pathDefinition;
	protected $requestedPath;
	protected $segmentValidators;
	protected $filtersRegistrator;

	public function __construct()
	{
		$this->converter = new TypeConverter;
	}
	public function setFiltersRegistrator(FiltersRegistrator $filtersRegistrator)
	{
		$this->filtersRegistrator = $filtersRegistrator;
	}

	public function setPathDefinition($pathDefinition)
	{
		$this->pathDefinition = $pathDefinition;
		$this->definitionSegments = $this->createArrayOfSegmentsFrom($pathDefinition);
		
	}

	public function setRequestedPath($requestedPath)
	{
		$this->requestedPath = $requestedPath;
		$this->requestSegments = $this->createArrayOfSegmentsFrom($requestedPath);
	}

	protected function createArrayOfSegmentsFrom($path)
	{
		(boolean) $pathIsASingleSlash = $path === '/';

		if ($pathIsASingleSlash) {
			return [$path];
		}

		return explode('/', $path);

	}


	/**
	 * [create description]
	 * @return ValidatorsValidator | FailingValidator 
	 * 
	 * A validator that validates singleValueValidators and/or 	
	 * ValidatorsValidator containing filter objects if the path 
	 * definition has filters defined. 
	 * 
	 * For example, if this path was given: /users/(id | integer | lenght: 3), then the 
	 * returning ValidatorsValidator object will contain two validators: the first one would be a Samevaluevalidator 
	 * representing the first segment /users/ and the second validator would be a ValidatorsValidator 
	 * that validates the filters defined in the given path, in this example, it would validate the integer and lenght 
	 * filters. 
	 *
	 * If, however, the number of segments differ from both paths, then a Failing Validator would be returned as the 
	 * current route does not match the requested path.
	 *
	 * Example:
	 *
	 * 		/users/(id | integer)/comments/new
	 * 		/users/5532
	 *
	 * 	The expected path defines 4 segments whereas the incoming (requested) path only specifies 2 segments, 
	 * 	making the route incompatible with the current request and thus a failingValidator is returned.
	 */
	public function create()
	{

		if ($this->pathDefinitionHasDifferentNumberOfSegmentsThanTheRequestedPath()) return new FailingValidator;

		$this->setArrayOfSegmentValidatorsBasedOnADefinedPathAndARequestedPath();

		(object) $segmentsValidator = new ValidatorsValidator;

		$segmentsValidator->setValueToValidate($this->segmentValidators);

		return $segmentsValidator;

	}

	protected function pathDefinitionHasDifferentNumberOfSegmentsThanTheRequestedPath()
	{
		(boolean) $numberOfSegmentsInArrayDiffer = count($this->definitionSegments) !== count($this->requestSegments);

		if($numberOfSegmentsInArrayDiffer) {
			return true;
		}

		return false;
	}

	protected function setArrayOfSegmentValidatorsBasedOnADefinedPathAndARequestedPath()
	{
		foreach ($this->definitionSegments as $index => $segment) {

			if ($this->segmentIsFilter($segment)) {

				(object) $filtersValidator = new ValidatorsValidator;
				(object) $filtersCreator = new FiltersCreator;

				$filtersCreator->setFiltersRegistrator($this->filtersRegistrator);
				$filtersCreator->setFilterCreator(new FilterCreator);
				$filtersCreator->setFilterDefinition($segment);
				$filtersCreator->setValueToFilter($this->converter->convertType($this->requestSegments[$index]));

				$filtersValidator->setValueToValidate($filtersCreator->create());

				$this->segmentValidators[] = $filtersValidator;

			} else {

				(object) $sameValueValidator = new SameValueValidator;

				$sameValueValidator->setExpectedValue($segment);
				$sameValueValidator->setValueToValidate($this->requestSegments[$index]);

				$this->segmentValidators[] = $sameValueValidator;

			}

		}
	}

	protected function segmentIsFilter($segment)
	{
		(object) $filterSyntaxValidator = new FilterSyntaxValidator;

		$filterSyntaxValidator->setValueToValidate($segment);

		$filterSyntaxValidator->validate();

		if ($filterSyntaxValidator->hasPassed()) {
			return true;
		}

		return false;
	}
}












