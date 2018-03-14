<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Exception\ForbiddenOverrideException;
use Stratum\Original\HTTP\Message;
use Stratum\Original\Utility\ClassUtility\ClassName;


Abstract Class Request
{
    use className;
    
    /**
     * The Symfony HTTP Request object
     * @var Symfony\Component\Http\Request
     */
	protected $request;
    /**
     * The Message object
     * @var Stratum\Original\HTTP\Message
     */
    public $http; 

    abstract protected function getValue($requestValue);

	public function __construct(\Symfony\Component\HttpFoundation\Request $request)
	{
		$this->request = $request;
        $this->http = new Message($request);
	}
    
	public function __get($requestValue)
	{
        
		return $this->getValue($requestValue);
	}

	public function __set($name, $requestValue)
	{
		throw new ForbiddenOverrideException("Cannot override request values");
		
	}

    public static function createBasedOn(\Symfony\Component\HttpFoundation\Request $request)
    {
        switch ($request->getRealMethod()) {
            case 'GET':
                return new GETRequest($request);
                break;
            
            case 'POST':
                return new POSTRequest($request);
                break;
        }
    }

}