<?php

namespace Stratum\Original\HTTP;

use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Original\HTTP\Exception\UnsupportedMethodException;

Class HTTPRoute extends Route
{
    protected $method;
    protected $path;

    public function setMethod($method)
    {
        $this->throwExceptionIfisNotASupportedHTTPMethod($method);
        $this->method = strtoupper($method);
    }

    public function setPathDefinition($path)
    {
        $this->path = $this->removeWhitespaceAndUnnecessarySlashesfrom($path);
    }    

    public function method()
    {
        return $this->method;
    }

    public function pathDefinition()
    {
        return $this->path;
    }

    protected function removeWhitespaceAndUnnecessarySlashesfrom($path)
    {
        (string) $path = trim($path);
        (boolean) $pathIsNotASingleSlash = $path !== '/';

        if ($pathIsNotASingleSlash) {
            return trim($path, '/');
        }
        return $path;
        
    }

    protected function throwExceptionIfisNotASupportedHTTPMethod($method)
    {
        (string) $method = strtoupper($method);
        (boolean) $methodIsNotGET = $method !== 'GET';
        (boolean) $methodIsNotPOST = $method !== 'POST';

        if ($methodIsNotGET and $methodIsNotPOST) {
            throw new UnsupportedMethodException('Only GET and POST are the supported HTTP methods');
        }
    }

    /*
        public static function parentDirectories()
    {
        if (static::$parentDirectories === null) {
            (object) $homeUrl = Options::withName('siteurl')->find()->first()->value;

            $relativePath = parse_url($homeUrl, PHP_URL_PATH);
            
            $relativePath = trim((string) $relativePath, '/');

            if (strlen($relativePath) > 1) {
                $relativePath.= '/';
            }

            static::$parentDirectories = $relativePath;
        }

        return static::$parentDirectories;
    }

     protected function prependPathIfInstalledInSubDirectory()
    {
        (string) $parentDirectories = static::parentDirectories();

        return "{$parentDirectories}{$this->path}";
    }
     */

}