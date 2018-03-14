<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Model\MYSQL\StratumTestPost;
use Stratum\Original\Data\Creator\GroupOfModelsCreator;
use Stratum\Original\Data\Creator\ModelCreator;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\GroupOf;

Class GroupOfModelsCreatorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPost.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumTestPost.php', $TestFinder);

        (string) $TestDomain = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPostDomain.php');
        file_put_contents('Design/Model/Domain/StratumTestPost.php', $TestDomain);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumTestPost.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Domain/StratumTestPost.php');
    }

    public function setUp()
    {

        $this->groupOfModelsCreator = new GroupOfModelsCreator;

        $this->dataObjects = [
            new Data, 
            new Data, 
            new Data, 
            new Data
        ];

        $this->models = [
            new StratumTestPost($this->dataObjects[0]),
            new StratumTestPost($this->dataObjects[1]),
            new StratumTestPost($this->dataObjects[2]),
            new StratumTestPost($this->dataObjects[3])
        ];

        $this->aliases = [
            'name' => 'page_name'
        ];

        $this->primaryKey = 'post_id';

    }
    public function test_calls_modelCreator_4_times()
    {
        (string) $entityType = 'Stratum\\Custom\\Finder\\MYSQL\\StratumTestPosts';

        (object) $modelCreator = $this->createMock(ModelCreator::class);

        $modelCreator->expects($this->once())->method('setEntityType')->with($entityType);
        $modelCreator->expects($this->once())->method('setPrimaryKey')->with($this->primaryKey);
        $modelCreator->expects($this->exactly(4))->method('setAliases')->with($this->aliases);

        $modelCreator->expects($this->exactly(4))->method('setData')->withConsecutive(
            [$this->dataObjects[0]],
            [$this->dataObjects[1]],
            [$this->dataObjects[2]],
            [$this->dataObjects[3]]
        )->will($this->onConsecutiveCalls(
            [$this->dataObjects[0]],
            [$this->dataObjects[1]],
            [$this->dataObjects[2]],
            [$this->dataObjects[3]]
        ));

        $modelCreator->expects($this->exactly(4))->method('create')->will($this->onConsecutiveCalls(
            $this->models[0],
            $this->models[1],
            $this->models[2],
            $this->models[3]
        ));

        $this->groupOfModelsCreator->setModelCreator($modelCreator);

        $this->groupOfModelsCreator->setEntityType($entityType);

        $this->groupOfModelsCreator->setPrimaryKey($this->primaryKey);

        $this->groupOfModelsCreator->setDataObjects($this->dataObjects);

        $this->groupOfModelsCreator->setAliases($this->aliases);

        (object) $groupOfModels = $this->groupOfModelsCreator->create();

        $this->assertInstanceOf(GroupOf::class, $groupOfModels);

        $this->assertEquals(4, $groupOfModels->count());

        $this->assertSame($this->models[0], $groupOfModels->atPosition(1));
        $this->assertSame($this->models[1], $groupOfModels->atPosition(2));
        $this->assertSame($this->models[2], $groupOfModels->atPosition(3));
        $this->assertSame($this->models[3], $groupOfModels->atPosition(4));
    }

    public function test_creates_4_model_objects_NO_MOCKS()
    {
        $this->groupOfModelsCreator->setEntityType('Stratum\\Custom\\Finder\\MYSQL\\StratumTestPosts');
        $this->groupOfModelsCreator->setDataObjects($this->dataObjects);

        (object) $groupOfModels = $this->groupOfModelsCreator->create();

        $this->assertInstanceOf(GroupOf::class, $groupOfModels);

        $this->assertEquals(4, $groupOfModels->count());

        $this->assertInstanceOf(StratumTestPost::class, $groupOfModels->atPosition(1));
        $this->assertInstanceOf(StratumTestPost::class, $groupOfModels->atPosition(2));
        $this->assertInstanceOf(StratumTestPost::class, $groupOfModels->atPosition(3));
        $this->assertInstanceOf(StratumTestPost::class, $groupOfModels->atPosition(4));

        $this->assertNotSame($groupOfModels->atPosition(1), $groupOfModels->atPosition(2));
        $this->assertNotSame($groupOfModels->atPosition(1), $groupOfModels->atPosition(3));
        $this->assertNotSame($groupOfModels->atPosition(1), $groupOfModels->atPosition(4));

        $this->assertNotSame($groupOfModels->atPosition(2), $groupOfModels->atPosition(1));
        $this->assertNotSame($groupOfModels->atPosition(2), $groupOfModels->atPosition(3));
        $this->assertNotSame($groupOfModels->atPosition(2), $groupOfModels->atPosition(4));

        $this->assertNotSame($groupOfModels->atPosition(3), $groupOfModels->atPosition(1));
        $this->assertNotSame($groupOfModels->atPosition(3), $groupOfModels->atPosition(2));
        $this->assertNotSame($groupOfModels->atPosition(3), $groupOfModels->atPosition(4));

        $this->assertNotSame($groupOfModels->atPosition(4), $groupOfModels->atPosition(1));
        $this->assertNotSame($groupOfModels->atPosition(4), $groupOfModels->atPosition(2));
        $this->assertNotSame($groupOfModels->atPosition(4), $groupOfModels->atPosition(3));
    }















}