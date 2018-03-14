<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\Exception\InvalidReturnTypeException;
use Stratum\Original\Presentation\PartialView;

Class ComponentTest extends TestCase
{
    public function test_throws_exception_if_load_methods_returns_no_PartialView_object()
    {
        $this->expectException(InvalidReturnTypeException::class);
        $this->expectExceptionmessage(
            Component::class . "::load() must return a " . PartialView::class . " object, string returned"
        );

        (object) $Component = $this->getMockBuilder(Component::class)
                                   ->setMethods(['load'])
                                   ->setConstructorArgs([[]])
                                   ->getMock();

        $Component->expects($this->any())->method('load')->willReturn('Wrong Type');

        $Component->elements();
    }

    public function test_calls_elements_method_from_the_returned_partialView_object_and_returns_its_content()
    {
        (object) $Component = $this->getMockBuilder(Component::class)
                                   ->setMethods(['load'])
                                   ->setConstructorArgs([[]])
                                   ->getMock();
        (object) $PartialView = $this->createMock(PartialView::class);

        $Component->expects($this->atLeastOnce())->method('load')->with($this->callBack(
            function(PartialView $PartialView) {
                return true;
            }))->willReturn($PartialView);

        $PartialView->expects($this->once())->method('elements')->will($this->returnSelf());

        $this->assertsame($PartialView, $Component->elements());


    }
}