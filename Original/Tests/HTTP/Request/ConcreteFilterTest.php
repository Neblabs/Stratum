<?php

use Stratum\Prebuilt\Filter\ConcreteFilter;
use Stratum\Original\HTTP\Exception\MissingRequiredPropertyException;
use PHPUnit\Framework\TestCase;

Class ConcreteFilterTest extends TestCase
{
    protected $filter;

    public function setUp()
    {
        $this->filter = new ConcreteFilter;
    }

    public function test_filter_method_gets_called()
    {
        $filter = $this->getMockBuilder(ConcreteFilter::class)
                    ->setMethods(['alwaysPasses'])
                    ->getMock();

        $filter->expects($this->once())
                ->method('alwaysPasses');

        $filter->setFilterMethod('alwaysPasses');
        $filter->setValue(5572);

        $filter->validate();
    }

    public function test_calls_filter_with_argument()
    {
        $filter = $this->getMockBuilder(ConcreteFilter::class)
                    ->setMethods(['filterWithArgument'])
                    ->getMock();

        (boolean) $argument = true;

        $filter->expects($this->once())
                ->method('filterWithArgument')
                ->with($argument);

        $filter->setFilterMethod('filterWithArgument');
        $filter->setFilterArgument($argument);
        $filter->setValue(5572);

        $filter->validate();
    }
    
    public function test_throws_exception_if_no_method_name_nor_value_has_been_set()
    {
        $this->expectException(MissingRequiredPropertyException::class);

        $this->filter->validate();
    }

    public function test_throws_exception_if_only_the_filters_method_name_has_been_set()
    {
        $this->expectException(MissingRequiredPropertyException::class);

        $this->filter->setFilterMethod('alwaysPasses');

        $this->filter->validate();
    }

    public function test_throws_exception_if_only_the_value_has_been_set()
    {
        $this->expectException(MissingRequiredPropertyException::class);

        $this->filter->setValue(5572);

        $this->filter->validate();
    }

    public function test_returns_true_when_filter_has_passed()
    { 

        $this->filter->setFilterMethod('alwaysPasses');
        $this->filter->setValue(5572);

        $this->filter->validate();

        $this->assertTrue($this->filter->hasPassed());
    }   

    public function test_returns_false_when_filter_has_failed()
    { 

        $this->filter->setFilterMethod('alwaysFails');
        $this->filter->setValue(5572);

        $this->filter->validate();

        $this->assertFalse($this->filter->hasPassed());
    } 
}