<?php

namespace Stratum\Original\HTTP;

Class URL
{
	public $path;
	public $uri;
	public $segments;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request)
	{
		$this->request = $request;
		$this->path = $this->getCorrectPathFrom($request->getPathInfo());
		$this->uri = $this->getCorrectPathFrom($request->getRequestUri());
		$this->segments = explode('/', $this->path);
	}

	protected function getCorrectPathFrom($path)
	{
		(boolean) $pathIsASingleSlash = $path === '/';

		if ($pathIsASingleSlash) {
			return $path;
		} 
		
		$pathWithoutSlashes = trim($path, '/');

		return $pathWithoutSlashes;
	}
}