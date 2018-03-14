<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator;

Abstract Class RouteValidator extends Validator 
{
	protected $route;

	public function setRoute(Route $route) {
		$this->route = $route;
	}

	public function route()
	{
		return $this->route;
	}

}















