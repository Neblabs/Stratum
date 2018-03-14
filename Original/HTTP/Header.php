<?php

namespace Stratum\Original\HTTP;

Class Header
{
	public function __construct(\Symfony\Component\HttpFoundation\Request $request)
	{
		$this->request = $request;
	}

	public function __get($headerName)
	{
		return $this->getHeaderValueFrom($headerName);
	}

	protected function getHeaderValueFrom($headerName)
	{
		$headerNameSeparatedByDashes = strtolower(preg_replace('/[A-Z]/', '-$0', $headerName));

		return $this->request->headers->get($headerNameSeparatedByDashes);
	}
}
