<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Response
{
    use ClassName;
    
    protected $body;

    public function __construct(\Symfony\Component\HttpFoundation\Response $response)
    {
        $this->response = $response;
    }

    abstract protected function contentType();
    abstract protected function body();

    public function send()
    {
        $this->throwExceptionIfNoContentTypeHasBeenReturnedOrIsNotValid($this->contentType());

        
        $this->response->headers->set('Content-Type', $this->contentType());
        $this->response->setContent($this->body());

        $this->response->send();
    }

    public function withStatusCode($statusCode)
    {
        $this->response->setStatusCode($statusCode);

        return $this;
    }

    public function withHeaders(array $headers)
    {
        foreach ($headers as $headerName => $headerValue) {
            $this->response->headers->set($headerName, $headerValue);
        }

        return $this;
    }

    protected function throwExceptionIfNoContentTypeHasBeenReturnedOrIsNotValid($contentType)
    {
        (boolean) $contentTypePropertyIsEmpty = empty($contentType);
        
        if ($contentTypePropertyIsEmpty) {
            throw new MissingRequiredPropertyException('A Content-Type must be set in order to send a response');
        }
    }



}