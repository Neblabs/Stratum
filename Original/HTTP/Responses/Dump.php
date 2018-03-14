<?php

namespace Stratum\Original\HTTP\Response;

use Stratum\Original\HTTP\Response;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class Dump extends Response
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

    public function variable($variable)
    {
        $this->body = $this->dumppedVariable($variable);

        return $this;
    }

    protected function dumppedVariable($variable)
    {
        ob_start();

        var_dump($variable);

        $dumppedVariable = ob_get_contents();

        ob_end_clean();

        return $dumppedVariable;
    }
}