<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Creator\SegmentsValidatorCreator;
use Stratum\Original\HTTP\HTTPRoute;
use Stratum\Original\HTTP\Registrator\FiltersRegistrator;
use Stratum\Original\HTTP\Request;
use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator;

Class HTTPRouteValidator extends RouteValidator  
{
    protected $route;
    public $request;
    protected $segmentsValidatorCreator;
    protected $segmentsValidator;
    protected $HTTPMethodValidator;
    protected $filtersRegistrator;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setSegmentsValidatorCreator(SegmentsValidatorCreator $segmentsValidatorCreator)
    {
        $this->segmentsValidatorCreator = $segmentsValidatorCreator;
    }

    public function setFiltersRegistrator(FiltersRegistrator $filtersRegistrator)
    {  
        $this->filtersRegistrator = $filtersRegistrator;
    }

    public function validate() 
    {
        $this->setHTTPMethodValidator();
        $this->setSegmentsValidator();

        $this->HTTPMethodValidator->validate();
        
        if ($this->HTTPMethodValidator->hasPassed()) {

            $this->segmentsValidator->validate();

            if ($this->segmentsValidator->hasPassed()) {
                $this->passed();
            } else {
                $this->failed();
            }

        } else {

            $this->failed();
        }

    }

    protected function setHTTPMethodValidator()
    {
        $this->HTTPMethodValidator = new SameValueValidator();

        $this->HTTPMethodValidator->setExpectedValue($this->route->method());
        $this->HTTPMethodValidator->setValueToValidate($this->request->http->method);
    }

    protected function setSegmentsValidator()
    {
        $this->segmentsValidatorCreator->setFiltersRegistrator($this->filtersRegistrator);
        $this->segmentsValidatorCreator->setPathDefinition($this->route->pathDefinition());
        $this->segmentsValidatorCreator->setRequestedPath($this->request->http->URL->path);

        $this->segmentsValidator = $this->segmentsValidatorCreator->create();

        
    }
}















