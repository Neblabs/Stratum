<?php

namespace Stratum\Original\Establish;

use Stratum\Original\Establish\Exception\MissingRequiredValueException;
use Stratum\Original\WordPress\WordpressConfigurationManager;

Class DatabaseEstablisher
{
    public $name;
    public $host;
    public $username;
    public $password;

    public function __construct(array $database)
    {
        //$this->throwExceptionIfOneRequiredValueIsMissingIn($database);
        $database = $this->mergeWordpressValuesWithGivenValues($database);

        $this->name = $database['name'];
        $this->host = $database['host'];
        $this->username = $database['username'];
        $this->password = $database['password'];
    }

    protected function mergeWordpressValuesWithGivenValues(array $stratumDatabase)
    {
        (object) $wordpressConfigurationManager = new WordpressConfigurationManager;
        (array) $wordpressDatabase = $wordpressConfigurationManager->databaseConfigurationData();

        return [
            'name' => ($stratumDatabase['name'] !== '')? $stratumDatabase['name'] : $wordpressDatabase['name'],
            'host' => ($stratumDatabase['host'] !== '')? $stratumDatabase['host'] : $wordpressDatabase['host'],
            'username' => ($stratumDatabase['username'] !== '')? $stratumDatabase['username'] : $wordpressDatabase['username'],
            'password' => ($stratumDatabase['password'] !== null)? $stratumDatabase['password'] : $wordpressDatabase['password'],
        ];  
    }

    protected function throwExceptionIfOneRequiredValueIsMissingIn(array $database)
    {
        (array) $databaseMeta = array_keys($database);

        if (!in_array('name', $databaseMeta) or empty($database['name'])) {

            throw new MissingRequiredValueException('A database name must be established.');

        } elseif (!in_array('host', $databaseMeta) or empty($database['host'])) {

            throw new MissingRequiredValueException('A database host must be established.');

        } elseif (!in_array('username', $databaseMeta) or empty($database['username'])) {

            throw new MissingRequiredValueException('A database username must be established.');

        } elseif (!in_array('password', $databaseMeta)) {

            throw new MissingRequiredValueException('A database password must be established.');

        }
    }
}