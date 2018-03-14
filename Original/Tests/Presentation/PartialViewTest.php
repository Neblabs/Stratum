<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Exception\InvalidReturnTypeException;
use Stratum\Original\Presentation\Exception\UnbindedVariableException;
use Stratum\Original\Presentation\PartialView;

Class PartialViewTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $testCompiledView = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestView9506.php');
        file_put_contents('Storage/CompiledEOM/StratumTestView9506.php', $testCompiledView);

        (string) $testCompiledView = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestViewWithVariable9506.php');
        file_put_contents('Storage/CompiledEOM/StratumTestViewWithVariable9506.php', $testCompiledView);
    }

    public static function tearDownAfterClass()
    {
        //unlink(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/StratumTestView9506.php');
        //unlink(STRATUM_ROOT_DIRECTORY . '/Storage/CompiledEOM/StratumTestViewWithVariable9506.php');
    }

    public function test_returns_GroupOfNodes_object()
    {
        (object) $PartialView = new PartialView;

        $PartialView->from('StratumTestView9506.html');

        (object) $generatedElements = $PartialView->elements();

        $this->assertTrue($generatedElements->wereFound());
        $this->assertEquals(1, $generatedElements->count());
        $this->assertEquals('div', $generatedElements->first()->type());
        $this->assertTrue($generatedElements->first()->children()->wereFound());
        $this->assertInstanceOf(Text::class, $generatedElements->first()->children()->first());
        $this->assertEquals('A Div!', $generatedElements->first()->children()->first()->content());
    }

    public function test_returns_GroupOfNodes_object_with_variable()
    {
        (object) $PartialView = new PartialView;

        $PartialView->from('StratumTestViewWithVariable9506.html')->with([
            'state' => 'managed'
        ]);

        (object) $generatedElements = $PartialView->elements();

        $this->assertTrue($generatedElements->wereFound());
        $this->assertEquals(1, $generatedElements->count());
        $this->assertEquals('div', $generatedElements->first()->type());
        $this->assertTrue($generatedElements->first()->children()->wereFound());
        $this->assertInstanceOf(Text::class, $generatedElements->first()->children()->first());
        $this->assertEquals('A managed Div!', $generatedElements->first()->children()->first()->content());
    }

    public function test_exception_when_view_defined_a_variable_but_a_variable_with_such_name_is_not_binded()
    {
        $this->expectException(PHPUnit_Framework_Error::class);
        $this->expectExceptionMessage('Undefined variable: state');

        (object) $PartialView = new PartialView;

        $PartialView->from('StratumTestViewWithVariable9506.html');

        (object) $generatedElements = $PartialView->elements();

    }





}
