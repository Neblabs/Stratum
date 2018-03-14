<?php 

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Header;
use Stratum\Original\HTTP\URL;

Class Message 
{
	protected $request;
	public $method;
	public $url;
	public $URL; #url alias.
	public $header;
	public $body;

	public function __construct(\Symfony\Component\HttpFoundation\Request $request)
	{
		$this->request = $request;
		$this->method = $request->getRealMethod();
		$this->url = new URL($request);
		$this->URL = $this->url;
		$this->header = new Header($request);
		$this->body = $request->getContent();
	}

	protected function addTrailingSlash($uri)
	{
		$UriHasNoSlashAtTheBeginning = strpos($uri, '/') !== 0;

		if ($UriHasNoSlashAtTheBeginning) {
			$uri = "/{$uri}";
		}

		return $uri;
	}

}