<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Model\MYSQL\StratumModelNoDomain;
use Stratum\Custom\Model\MYSQL\StratumModelWithFakeDomain;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Domain;
use Stratum\Original\Data\Exception\UnexistentDomainClassException;
use Stratum\Original\Data\Model;
use Stratum\Original\Data\Saver;

Class ModelTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestModel = file_get_contents('Original/Tests/Data/TestClasses/StratumModelNoDomain.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumModelNoDomain.php', $TestModel);

        (string) $TestModel = file_get_contents('Original/Tests/Data/TestClasses/StratumModelWithFakeDomain.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumModelWithFakeDomain.php', $TestModel);

        (string) $TestModel = file_get_contents('Original/Tests/Data/TestClasses/StratumFakeDomain.php');
        file_put_contents('Design/Model/Domain/StratumModelWithFakeDomain.php', $TestModel);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumModelNoDomain.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumModelWithFakeDomain.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Domain/StratumModelWithFakeDomain.php');
    }

    public function test_throws_exception_if_no_Domain_exists()
    {
        $this->expectException(UnexistentDomainClassException::class);
        $this->expectExceptionMessage('Model: Stratum\\Custom\\Model\\MYSQL\\StratumModelNoDomain requires a Domain: Stratum\\Custom\\Domain\\StratumModelNoDomain');
        new StratumModelNoDomain(new Data);
    }

    public function test_throws_exception_if_no_Domain_class_does_not_extend_Domain()
    {
        $this->expectException(UnexistentDomainClassException::class);
        $this->expectExceptionMessage('Domain Class: Stratum\\Custom\\Domain\\StratumModelWithFakeDomain must extend: Stratum\\Original\\Data\\Domain');
        new StratumModelWithFakeDomain(new Data);
    }

    //public function test_delegates_method_calls_to_the_domain_object()
    //{
    //    (object) $domain = $this->createMock(Domain::class);
//
    //    (object) $model = $this->getMockForAbstractClass(Model::class, [new data, $domain]);
//
    //    //$domain->expects($this->once())->method('__call')->with(['testCall, [0]']);
//
    //}

    public function test_returns_value_from_data_object()
    {
        (object) $domain = $this->createMock(Domain::class);
        (object) $data = new Data;
        (object) $saver = $this->createMock(Saver::class);
        (object) $model = $this->getMockForAbstractClass(Model::class, [$data, $domain, $saver]);

        $data->name = 'Rafael';

        $this->assertEquals('Rafael', $model->name);
        $this->assertNull($model->unexistent);
    }

    













}