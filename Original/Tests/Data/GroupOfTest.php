<?php

use Stratum\Original\Data\GroupOf;
use PHPUnit\Framework\TestCase;

Class GroupOfTest extends TestCase
{
    protected $numbers;

    public function setUp()
    {
        (object) $numbers = new GroupOf([
            1, 2, 3, 4, 5, 6
        ]);

        $this->numbers = $numbers;
    }

    public function test_returns_the_first_element_in_the_group()
    {
        (integer) $firstElement = $this->numbers->first();

        $this->assertEquals(1, $firstElement);

    }

    public function test_returns_null_if_the_first_element_in_the_group_does_not_exist()
    {
        (object) $numbers = new GroupOf([]);

        $firstElement = $numbers->first();

        $this->assertNull($firstElement);

    }

    public function test_returns_the_first_element_even_in_inexed_arrays_not_starting_at_0()
    {
        (object) $numbers = new GroupOf([
            4 => 1,
            5 => 2,
            9 => 3,
            0 => 4
        ]);

        (integer) $firstElement = $numbers->first();

        $this->assertEquals(1, $firstElement);
    }

    public function test_returns_the_first_element_even_in_associative_arrays()
    {
        (object) $names = new GroupOf([
            'fisrtName' => 'Rafa',
            'lastName' => 'Serna'
        ]);

        (string) $firstElement = $names->first();

        $this->assertEquals('Rafa', $firstElement);
    }

    public function test_returns_the_last_element_in_the_group()
    {
        (integer) $lastElement = $this->numbers->last();

        $this->assertEquals(6, $lastElement);
    }

    public function test_returns_null_if_the_last_element_in_the_group_does_not_exist()
    {
        (object) $numbers = new GroupOf([]);

        $lastElement = $numbers->last();

        $this->assertNull($lastElement);

    }

    public function test_returns_the_last_element_in_the_group_even_in_indexed_arrays_not_strating_at_0()
    {
        (object) $numbers = new GroupOf([
            4 => 1,
            5 => 2,
            9 => 3,
            0 => 4
        ]);

        (integer) $lastElement = $numbers->last();

        $this->assertEquals(4, $lastElement);
    }

    public function test_returns_the_last_element_even_in_associative_arrays()
    {
        (object) $names = new GroupOf([
            'fisrtName' => 'Rafa',
            'lastName' => 'Serna'
        ]);

        (string) $lastElement = $names->last();

        $this->assertEquals('Serna', $lastElement);
    }

    public function test_returns_an_element_at_a_particular_index()
    {
        (integer) $thirdElement = $this->numbers->atPosition(3);

        $this->assertEquals(3, $thirdElement);
    }

    public function test_returns_null_if_the_element_at_the_specified_position_in_the_group_does_not_exist()
    {

        $fiveHundredElement = $this->numbers->atPosition(500);

        $this->assertNull($fiveHundredElement);

    }

    public function test_returns_the_element_at_a_particular_position_in_the_group_even_in_indexed_arrays_not_strating_at_0()
    {
        (object) $numbers = new GroupOf([
            4 => 1,
            5 => 2,
            9 => 3,
            0 => 4
        ]);

        (integer) $thirdElement = $numbers->atPosition(3);

        $this->assertEquals(3, $thirdElement);
    }

    public function test_returns_the_element_at_a_particular_position_even_in_associative_arrays()
    {
        (object) $names = new GroupOf([
            'fisrtName' => 'Rafa',
            'lastName' => 'Serna'
        ]);

        (string) $secondElement = $names->atPosition(2);

        $this->assertEquals('Serna', $secondElement);
    }

    public function test_returns_the_number_of_items_in_the_group()
    {
        (integer) $numberOfElements = $this->numbers->count();

        $this->assertEquals(6, $numberOfElements);
    }

    public function test_returns_true_when_there_are_elements_in_the_group()
    {
        (boolean) $groupIsNotEmpty = $this->numbers->wereFound();

        $this->assertTrue($groupIsNotEmpty);
    }

    public function test_returns_false_when_there_are_no_elements_in_the_group()
    {
        (object) $emptyGroup = new GroupOf([]);

        (boolean) $groupIsNotEmpty = $emptyGroup->wereFound();

        $this->assertFalse($groupIsNotEmpty);
    }

    public function test_returns_an_array_of_arrays_each_containing_the_specified_number_of_elements()
    {
        (array) $expectedArrayOfNumbers = [
            [1,2],
            [3,4],
            [5,6]
        ];

        (object) $groupOfGroups = $this->numbers->groupsOf(2);

        (integer) $numberOfCreatedGroupOfObjects = $groupOfGroups->count();

        $this->assertEquals(3, $numberOfCreatedGroupOfObjects);

        $this->assertEquals(2, $groupOfGroups->first()->count());
        $this->assertEquals(1, $groupOfGroups->first()->first());
        $this->assertEquals(2, $groupOfGroups->first()->last());

        $this->assertEquals(2, $groupOfGroups->atPosition(2)->count());
        $this->assertEquals(3, $groupOfGroups->atPosition(2)->first());
        $this->assertEquals(4, $groupOfGroups->atPosition(2)->last());

        $this->assertEquals(2, $groupOfGroups->atPosition(3)->count());
        $this->assertEquals(5, $groupOfGroups->atPosition(3)->first());
        $this->assertEquals(6, $groupOfGroups->atPosition(3)->last());

        $this->assertNull($groupOfGroups->atPosition(4));


    }

    public function test_returns_an_array_of_arrays_each_containing_the_specified_number_of_elements_from_dynamic_method()
    {
        (array) $expectedArrayOfNumbers = [
            [1,2],
            [3,4],
            [5,6]
        ];

        (object) $groupOfGroups = $this->numbers->groupsOf2();

        (integer) $numberOfCreatedGroupOfObjects = $groupOfGroups->count();

        $this->assertEquals(3, $numberOfCreatedGroupOfObjects);

    }

    public function test_returns_an_array_representation_of_the_elements_as_an_indexed_array()
    {
        (array) $expectedArrayOfNumbers = [1, 2, 3, 4, 5, 6];

        (array) $actualArrayOfNumbers = $this->numbers->asArray();

        $this->assertEquals($expectedArrayOfNumbers, $actualArrayOfNumbers);
    }

    public function test_iterates_over_the_group_correctly()
    {
        (array) $numbersArray = $this->numbers->asArray();
        
        foreach ($this->numbers as $index => $number) {

            $this->assertEquals($numbersArray[$index], $number);

        }
    }

    public function test_adds_item()
    {
        $this->numbers->add(7);

        (array) $expectedArrayOfNumbers = [1, 2, 3, 4, 5, 6, 7];

        (array) $actualArrayOfNumbers = $this->numbers->asArray();

        $this->assertEquals($expectedArrayOfNumbers, $actualArrayOfNumbers);
    }

    public function test_removes_first_found_item_and_resets_index()
    {
        (array) $expectedArrayOfNumbers = [1, 2, 3, 4, 5];

        $this->numbers->remove(6);

        $this->assertEquals(5, $this->numbers->count());

        $this->assertEquals(1, $this->numbers->first());
        $this->assertEquals(2, $this->numbers->atPosition(2));
        $this->assertEquals(3, $this->numbers->atPosition(3));
        $this->assertEquals(4, $this->numbers->atPosition(4));
        $this->assertEquals(5, $this->numbers->last());

        $this->assertEquals($expectedArrayOfNumbers, $this->numbers->asArray());
    }

    public function test_removes_nothing_when_value_wasnt_found()
    {
        (array) $expectedArrayOfNumbers = [1, 2, 3, 4, 5, 6];

        $this->numbers->remove(1000);

        $this->assertEquals(6, $this->numbers->count());

        $this->assertEquals(1, $this->numbers->first());
        $this->assertEquals(2, $this->numbers->atPosition(2));
        $this->assertEquals(3, $this->numbers->atPosition(3));
        $this->assertEquals(4, $this->numbers->atPosition(4));
        $this->assertEquals(5, $this->numbers->atPosition(5));
        $this->assertEquals(6, $this->numbers->last());

        $this->assertEquals($expectedArrayOfNumbers, $this->numbers->asArray());
    }

    public function test_adds_a_group_of_items()
    {
        (array) $expectedArrayOfNumbers = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        $this->numbers->addItems(new GroupOf([7, 8, 9]));

        $this->assertEquals(9, $this->numbers->count());

        $this->assertEquals(1, $this->numbers->first());
        $this->assertEquals(2, $this->numbers->atPosition(2));
        $this->assertEquals(3, $this->numbers->atPosition(3));
        $this->assertEquals(4, $this->numbers->atPosition(4));
        $this->assertEquals(5, $this->numbers->atPosition(5));
        $this->assertEquals(6, $this->numbers->atPosition(6));
        $this->assertEquals(7, $this->numbers->atPosition(7));
        $this->assertEquals(8, $this->numbers->atPosition(8));
        $this->assertEquals(9, $this->numbers->last(9));
   

        $this->assertEquals($expectedArrayOfNumbers, $this->numbers->asArray());
    }





}