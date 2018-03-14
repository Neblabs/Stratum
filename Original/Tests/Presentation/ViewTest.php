<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\View;

Class ViewTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $testCompiledView = file_get_contents('Original/Tests/Presentation/TestClasses/stratumTestView5572.php');
        file_put_contents('Storage/CompiledEOM/stratumTestView5572.php', $testCompiledView);

        (string) $testCompiledView = file_get_contents('Original/Tests/Presentation/TestClasses/stratumTestViewWithVariable5572.php');
        file_put_contents('Storage/CompiledEOM/stratumTestViewWithVariable5572.php', $testCompiledView);

        self::createACopyOfMasterPageIfExists();

        (string) $testCompiledView = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestViewMaster.php');
        file_put_contents('Storage/CompiledEOM/Master.php', $testCompiledView);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/stratumTestView5572.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/stratumTestViewWithVariable5572.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/Master.php');

        static::restoreMasterPageFromBackUpIfAvailable();
    }





    public function test_returns_an_HTML_element_with_requested_partial_view()
    {
        (object) $View = (new View)->from('stratumTestView5572.html')->with(['value' => '']);
        
        (object) $elementManagersQueue = $this->createMock(ElementManagersQueue::class);

        $elementManagersQueue->expects($this->once())->method('executeManagerTasks');

        $View->setElementManagersQueue($elementManagersQueue);

        (object) $rootElement = $View->rootElement();
        
        $this->assertInstanceOf(Element::class, $rootElement);
        $this->assertEquals('html', $rootElement->type());
        $this->assertTrue($rootElement->children()->wereFound());
        $this->assertEquals(2, $rootElement->children()->count());

        $this->assertEquals('head', $rootElement->children()->first()->type());
        $this->assertEquals('body', $rootElement->children()->last()->type());

        $this->assertTrue($rootElement->children()->first()->children()->wereFound());
        $this->assertEquals(1, $rootElement->children()->first()->children()->count());
        $this->assertEquals('title', $rootElement->children()->first()->children()->first()->type());

        $this->assertTrue($rootElement->children()->first()->children()->first()->children()->wereFound());

        $this->assertTrue($rootElement->children()->last()->children()->wereFound());
        $this->assertEquals(1, $rootElement->children()->last()->children()->count());
        $this->assertEquals('strike', $rootElement->children()->last()->children()->first()->type());

        $this->assertFalse($rootElement->children()->last()->children()->first()->children()->wereFound());
    }

    public function test_returns_an_HTML_element_with_requested_partial_view_with_binded_variable()
    {
        (object) $View = (new View)->from('stratumTestViewWithVariable5572.html')->with(['value' => 'Binded Value!']);
        (object) $elementManagersQueue = $this->createMock(ElementManagersQueue::class);

        $elementManagersQueue->expects($this->once())->method('executeManagerTasks');

        $View->setElementManagersQueue($elementManagersQueue);

        (object) $rootElement = $View->rootElement();
        
        $this->assertInstanceOf(Element::class, $rootElement);
        $this->assertEquals('html', $rootElement->type());
        $this->assertTrue($rootElement->children()->wereFound());
        $this->assertEquals(2, $rootElement->children()->count());

        $this->assertEquals('head', $rootElement->children()->first()->type());
        $this->assertEquals('body', $rootElement->children()->last()->type());

        $this->assertTrue($rootElement->children()->first()->children()->wereFound());
        $this->assertEquals(1, $rootElement->children()->first()->children()->count());
        $this->assertEquals('title', $rootElement->children()->first()->children()->first()->type());


        $this->assertTrue($rootElement->children()->first()->children()->first()->children()->wereFound());
        $this->assertInstanceOf(Text::class, $rootElement->children()->first()->children()->first()->children()->first());
        $this->assertEquals('Binded Value!', $rootElement->children()->first()->children()->first()->children()->first()->content());

        $this->assertTrue($rootElement->children()->last()->children()->wereFound());
        $this->assertEquals(1, $rootElement->children()->last()->children()->count());
        $this->assertEquals('strike', $rootElement->children()->last()->children()->first()->type());

        $this->assertTrue($rootElement->children()->last()->children()->first()->children()->wereFound());

        $this->assertInstanceOf(Text::class, $rootElement->children()->last()->children()->first()->children()->first());
        $this->assertEquals('Binded Value!', $rootElement->children()->last()->children()->first()->children()->first()->content());
    }









    protected static function createACopyOfMasterPageIfExists()
    {
        (boolean) $masterExists = file_exists('Storage/CompiledEOM/Master.php') or file_exists('Storage/CompiledEOM/master.php');

        if ($masterExists) {
            (string) $originalMaster = file_get_contents('Storage/CompiledEOM/Master.php');

            file_put_contents('Storage/CompiledEOM/MasterBackup.php', $originalMaster);
        }
    }

    protected static function restoreMasterPageFromBackUpIfAvailable()
    {
        (boolean) $masterBackupExists = file_exists('Storage/CompiledEOM/MasterBackup.php');

        if ($masterBackupExists) {
            (string) $masterBackup = file_get_contents('Storage/CompiledEOM/MasterBackup.php');

            file_put_contents('Storage/CompiledEOM/Master.php', $masterBackup);
        }
    }










}