<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Model\MYSQL\StratumTestPost;
use Stratum\Original\Data\Creator\GroupOfModelsCreator;
use Stratum\Original\Data\Creator\ModelCreator;
use Stratum\Original\Data\Creator\SingleModelOrGroupOfModelsCreator;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\GroupOf;

Class SingleModelOrGroupOfModelsCreatorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPost.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumTestPost.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumTestPost.php');
    }

    public function setUp()
    {
        $this->SingleModelOrGroupOfModelsCreator = new SingleModelOrGroupOfModelsCreator;

        $this->modelCreator = $this->createMock(ModelCreator::class);
        $this->groupOfModelsCreator = $this->createMock(GroupOfModelsCreator::class);

        $this->SingleModelOrGroupOfModelsCreator->setEntityType('Stratum\\Custom\\Finder\\MYSQL\\StratumTestPosts');

        $this->oneDataObject = [new Data];
        $this->fourDataObjects = [
            new Data, 
            new Data, 
            new Data, 
            new Data
        ];

        $this->oneModel = new StratumTestPost(new Data);
        $this->groupOfModels = new GroupOf([
            new StratumTestPost(new Data),
            new StratumTestPost(new Data),
            new StratumTestPost(new Data),
            new StratumTestPost(new Data)
        ]);

        $this->aliases = [
            'id' => 'post_id'
        ];

        $this->primaryKey = 'post_id';

        

    }

    public function test_returns_a_single_model()
    {
        $this->SingleModelOrGroupOfModelsCreator->setModelCreator($this->modelCreator);
        $this->SingleModelOrGroupOfModelsCreator->setGroupOfModelsCreator($this->groupOfModelsCreator);
        $this->SingleModelOrGroupOfModelsCreator->setAliases($this->aliases);

        $this->groupOfModelsCreator->expects($this->never())->method('setDataObjects');
        $this->groupOfModelsCreator->expects($this->never())->method('create');

        $this->modelCreator->expects($this->once())->method('setData')->with($this->oneDataObject[0]);
        $this->modelCreator->expects($this->once())->method('setPrimaryKey')->with($this->primaryKey);
        $this->modelCreator->expects($this->once())->method('setAliases')->with($this->aliases);
        $this->modelCreator->expects($this->once())->method('create')->willReturn($this->oneModel);

        $this->SingleModelOrGroupOfModelsCreator->setPrimaryKey($this->primaryKey);
        $this->SingleModelOrGroupOfModelsCreator->setWhetherOnlyOneSingleModelIsMeantToBeReturned(true);

        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->oneDataObject);

        (object) $model = $this->SingleModelOrGroupOfModelsCreator->create();

        $this->assertSame($model, $this->oneModel);


    }

    public function test_returns_a_GroupOf_object_with_four_models()
    {
        $this->SingleModelOrGroupOfModelsCreator->setModelCreator($this->modelCreator);
        $this->SingleModelOrGroupOfModelsCreator->setGroupOfModelsCreator($this->groupOfModelsCreator);

        $this->groupOfModelsCreator->expects($this->once())->method('setDataObjects')->with($this->fourDataObjects);
        $this->groupOfModelsCreator->expects($this->once())->method('setPrimaryKey')->with($this->primaryKey);
        $this->groupOfModelsCreator->expects($this->once())->method('create')->willReturn($this->groupOfModels);

        $this->modelCreator->expects($this->never())->method('setData');
        $this->modelCreator->expects($this->never())->method('create');

        $this->SingleModelOrGroupOfModelsCreator->setPrimaryKey($this->primaryKey);
        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->fourDataObjects);

        (object) $groupOfObjects = $this->SingleModelOrGroupOfModelsCreator->create();

        $this->assertSame($groupOfObjects, $this->groupOfModels);


    }

    public function test_returns_a_single_model_NO_MOCKS()
    {

        $this->SingleModelOrGroupOfModelsCreator->setWhetherOnlyOneSingleModelIsMeantToBeReturned(true);
        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->oneDataObject);

        (object) $model = $this->SingleModelOrGroupOfModelsCreator->create();

        $this->assertInstanceOf(StratumTestPost::class, $model);

    }

    public function test_returns_a_GroupOf_object_with_four_models_NO_MOCKS()
    {
        
        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->fourDataObjects);

        (object) $groupOfObjects = $this->SingleModelOrGroupOfModelsCreator->create();

        $this->assertInstanceOf(GroupOf::class, $groupOfObjects);

        $this->assertEquals(4, $groupOfObjects->count());


    }











}