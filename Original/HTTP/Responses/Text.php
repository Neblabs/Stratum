<?php

namespace Stratum\Original\HTTP\Response;

use Stratum\Original\HTTP\Response;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class Text extends Response
{
    use className;
    
    protected $body;
    
    protected function ContentType()
    {
        return 'text/plain';
    }

    protected function Body()
    {
        return $this->body;
    }

    public function containing($content)
    {
        $this->body = $content;

        return $this;
    }
}