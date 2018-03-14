<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class Dispatcher
{
    use ClassName;

    public function to($controller)
    {
        return $this->controller($controller);
    }
    
    protected function controller($fullyQualifiedControllerName)
    {   
        (array) $controllerData = explode('.', $fullyQualifiedControllerName);

        $this->className = $controllerData[0];
        $this->methodName = $controllerData[1];
        
        return $this;
    }

    public function data()
    {
        return [
            'className' => $this->className,
            'methodName' => $this->methodName
        ];
    }
}