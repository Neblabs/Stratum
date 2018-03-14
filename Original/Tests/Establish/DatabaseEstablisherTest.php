<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Establish\DatabaseEstablisher;
use Stratum\Original\Establish\Exception\MissingRequiredValueException;

Class DatabaseEstablishertest extends TestCase
{
    public function test_throws_exception_if_no_name_is_passed_in_array()
    {
        $this->expectException(MissingRequiredValueException::class);
        $this->expectExceptionMessage('A database name must be established.');

        new DatabaseEstablisher([
            'host' => 'localhost',
            'username' => 'root',
            'password' => ''
        ]);
    }

    public function test_throws_exception_if_no_host_is_passed_in_array()
    {
        $this->expectException(MissingRequiredValueException::class);
        $this->expectExceptionMessage('A database host must be established.');

        new DatabaseEstablisher([
            'name' => 'name',
            'username' => 'root',
            'password' => ''
        ]);
    }

    public function test_throws_exception_if_no_username_is_passed_in_array()
    {
        $this->expectException(MissingRequiredValueException::class);
        $this->expectExceptionMessage('A database username must be established.');

        new DatabaseEstablisher([
            'name' => 'name',
            'host' => 'root',
            'password' => ''
        ]);
    }

    public function test_throws_exception_if_no_password_is_passed_in_array()
    {
        $this->expectException(MissingRequiredValueException::class);
        $this->expectExceptionMessage('A database password must be established.');

        new DatabaseEstablisher([
            'name' => 'name',
            'host' => 'root',
            'username' => 'user'
        ]);
    }

    public function test_throws_no_exception_when_all_required_values_are_passed_password_may_be_empty()
    {

        new DatabaseEstablisher([
            'name' => 'name',
            'host' => 'root',
            'username' => 'user',
            'password' => ''
        ]);
    }

    public function test_returns_the_correct_values()
    {

        (object) $database = new DatabaseEstablisher([
            'name' => 'name',
            'host' => 'localhost',
            'username' => 'user',
            'password' => ''
        ]);

        $this->assertEquals('name', $database->name);
        $this->assertEquals('localhost', $database->host);
        $this->assertEquals('user', $database->username);
        $this->assertEquals('', $database->password);
    }













}