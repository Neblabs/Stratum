<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Model\MYSQL\StratumTestFakeModel;
use Stratum\Custom\Model\MYSQL\StratumTestPost;
use Stratum\Original\Data\Creator\ModelCreator;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Exception\UnexistentModelClassException;
use Stratum\Original\Data\Model;

Class ModelcreatorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestModel = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPost.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumTestPost.php', $TestModel);

        (string) $TestDomain = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPostDomain.php');
        file_put_contents('Design/Model/Domain/StratumTestPost.php', $TestDomain);

        (string) $TestModel = file_get_contents('Original/Tests/Data/TestClasses/StratumTestFakeModel.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumTestFakeModel.php', $TestModel);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumTestPost.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Domain/StratumTestPost.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumTestFakeModel.php');
    }
    public function setUp()
    {
        (array) $this->aliases = [
            'post' => 'post_id'
        ];

        $this->modelCreator = new ModelCreator;
        (object) $this->Data = $this->createMock(Data::class);



        $this->modelCreator->setData($this->Data);
    }

    public function test_throws_exception_if_no_model_class_exists()
    {
        $this->expectException(UnexistentModelClassException::class);
        $this->expectExceptionMessage(
            "Entity: Stratum\\Custom\\Finder\\MYSQL\\StratumTestComments requires a model class: Stratum\\Custom\\Model\\MYSQL\\StratumTestComment"
        );

        $this->modelCreator->setEntityType('Stratum\\Custom\\Finder\\MYSQL\\StratumTestComments');

        $this->modelCreator->create();
    }

    public function test_throws_exception_if_class_does_not_extend_Model()
    {
        $this->expectException(UnexistentModelClassException::class);
        $this->expectExceptionMessage(
            "Model Class: Stratum\\Custom\\Model\\MYSQL\\StratumTestFakeModel must extend: " . Model::className()
        );

        $this->modelCreator->setEntityType('Stratum\\Custom\\Finder\\MYSQL\\StratumTestFakeModels');

        $this->modelCreator->create();
    }


    public function test_returns_a_fresh_Model_object()
    {   
        $this->Data->expects($this->once())->method('setAliases')->with($this->aliases);

        $this->modelCreator->setAliases($this->aliases);
        $this->modelCreator->setEntityType('Stratum\\Custom\\Finder\\MYSQL\\StratumTestPosts');
        

        $this->assertInstanceof(StratumTestPost::Class, $this->modelCreator->create());
    }




}