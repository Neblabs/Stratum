<?php

namespace Stratum\Original\HTTP\Response;

use Stratum\Original\HTTP\Response;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class JSON extends Response
{
    use className;
    
    protected $body;
    
    protected function ContentType()
    {
        return 'application/json';
    }

    protected function Body()
    {
        return $this->body;
    }

    public function fromArray(Array $array)
    {
        $this->body = $this->convertArraytoJson($array);

        return $this;
    }

    protected function convertArraytoJson($array)
    {
        return json_encode($array, JSON_UNESCAPED_SLASHES);
    }
}