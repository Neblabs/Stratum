<?php

namespace Stratum\Original\HTTP\Response;

use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Original\HTTP\Response;
use Stratum\Original\Utility\ClassUtility\ClassName;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

Class Redirection extends Response
{
    use className;
    
    protected function ContentType()
    {
        return '';
    }

    protected function Body()
    {
        return $this->body;
    }

    public function send()
    {   
        return $this->redirectResponse->send();
    }

    public function to($path)
    {
        (object) $this->redirectResponse = new RedirectResponse($this->path($path));
        return $this;
    }

    protected function path($path)
    {
        if ($this->isRelative($path)) {
            return $this->absolute($path);
        }

        return $path;
    }

    protected function isRelative($path)
    {
        return preg_match('/^\/(.)*/', $path);
    }

    protected function absolute($path)
    {
        (string) $url = Options::withName('siteurl')->find()->first()->value;

        return "$url{$path}";
    }








}