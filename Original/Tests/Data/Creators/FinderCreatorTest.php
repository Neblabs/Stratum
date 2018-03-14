<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Finder\MYSQL\ConcreteFinder;
use Stratum\Original\Data\Creator\FinderCreator;
use Stratum\Original\Data\Exception\UnexistentFinderClassException;

Class FinderCreatorTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Extend/Tests/TestClasses/ConcreteFinder.php');
        file_put_contents('Design/Model/Finders/MYSQL/ConcreteFinder.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/ConcreteFinder.php');
    }

    public function setUp()
    {
        $this->finderCreator = new FinderCreator;
    }

    public function test_creates_a_new_finder()
    {
        $this->finderCreator->setEntityType(ConcreteFinder::class);

        $this->assertInstanceOf(ConcreteFinder::class, $this->finderCreator->create());
    }

    public function test_throws_exception_if_no_finder_exists()
    {
        $this->expectException(UnexistentFinderClassException::class);
        $this->finderCreator->setEntityType('Stratum\\Cutsom\\Finder\\IDontExist');

        $this->finderCreator->create();
    }
}