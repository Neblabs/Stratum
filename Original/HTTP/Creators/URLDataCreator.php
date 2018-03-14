<?php 

namespace Stratum\Original\HTTP\Creator;

use Stratum\Original\HTTP\URLData;
use Stratum\Original\HTTP\Validator\FilterSyntaxValidator;

Class URLDataCreator
{
    protected $requestedPath;
    protected $pathDefinition;
    protected $URLDataArray = [];

    public function setRequestedPath($path)
    {
        $this->requestedPath = $path;
    }

    public function setPathDefinition($pathDefinition)
    {
        $this->pathDefinition = $pathDefinition;
    }

    public function create()
    {
        $this->createArrayofUrlData();
        
        return new URLData($this->URLDataArray);
    }

    protected function createArrayofUrlData()
    {
        (array) $pathDefinitionSegments = explode('/', $this->pathDefinition);
        (array) $requestedPathSegments = explode('/', $this->requestedPath);

        foreach ($pathDefinitionSegments as $index => $segment) {
            
            if ($this->segmentIsFilter($segment)) {

                (string) $wildcardName = $this->wildcardNameFromFilterDefinition($segment);
                (string) $wildcardValue = $requestedPathSegments[$index];

                $this->URLDataArray[$wildcardName] = $wildcardValue;

            } 
        }
    }

    protected function segmentIsFilter($segment)
    {
        (object) $filterSyntaxValidator = new FilterSyntaxValidator;

        $filterSyntaxValidator->setValueToValidate("$segment");

        $filterSyntaxValidator->validate();

        if ($filterSyntaxValidator->hasPassed()) {
            return true;
        }

        return false;
    }

    protected function wildcardNameFromFilterDefinition($segment)
    {
        (array) $arrayOfWildcardAndFilters = explode('|', trim($segment, '()'));
        (string) $wildcardName = trim($arrayOfWildcardAndFilters[0]);

        return $wildcardName;
    }












}