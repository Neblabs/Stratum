<?php

namespace Stratum\Original\Data;

use Iterator;
use Stratum\Original\Data\Ability\Groupable;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class GroupOf implements Groupable, Iterator
{
    use ClassName;
    
    protected $elements;
    protected $index = 0;

    public function __construct(array $elements = null)
    {
        $this->elements = $this->resetIndexOf($elements);
    }

    public function resetIndexOf($array)
    {
        if (empty($array)) {
            return $array;
        }
        
        return array_values($array);
    }

    public function add($item)
    {
        if (!is_array($this->elements)) {
            $this->elements = [];
        }

        $this->elements[] = $item;
    }

    public function addItems(GroupOf $items)
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function remove($item)
    {
        (integer) $itemKey = array_search($item, $this->elements);
        (boolean) $itemExistsInArray = $itemKey !== false;

        if ($itemExistsInArray) {

            unset($this->elements[$itemKey]);

            $this->elements = $this->resetIndexOf($this->elements);
            
        }
    }

    public function first()
    {
        (integer) $firstPosition = 0;
        (boolean) $firstElementInArrayExists = isset($this->elements[$firstPosition]);

        if ($firstElementInArrayExists) {

            return $this->elements[$firstPosition];

        }

    }

    public function last()
    {
        (integer) $lastPosition = $this->countElementsInArray() - 1;

        (boolean) $lastElementInArrayExists = isset($this->elements[$lastPosition]);

        if ($lastElementInArrayExists) {

            return $this->elements[$lastPosition];

        }

    }

    public function atPosition($numberStartingAt1)
    {
        (integer) $indexStartingAt0 = $numberStartingAt1 - 1;

        (boolean) $elementExistsAtRequestedIndexInArray = isset($this->elements[$indexStartingAt0]);
        if ($elementExistsAtRequestedIndexInArray) {

            return $this->elements[$indexStartingAt0];

        }

    }

    /**
     * Will dispatch to GroupOf::groupsOf() method. This dynamic, and 'magical' implementation of said method is 
     * designed to be used inside the templating engine as no arguments are supported in it.
     * When using plain PHP, it is recomended the use of the GroupOf::groupsOf() method directly instead of this
     * adaptation.  
     *
     * Example:
     *
     *  $posts->groupsOf4() will be dispatched to:
     *
     *  $posts->groupsOf(4) 
     * 
     * 
     * @return GroupOf
     */
    public function __call($method, $arguments)
    {
        if (strpos($method, 'groupsOf') === false) {
            throw new \BadMethodCallException("Call to undefinded method: $method");
        }

        (integer) $numberOfItemsPerGroup = substr($method, strlen('groupsOf'));

        return $this->groupsOf($numberOfItemsPerGroup);
    }

    public function count()
    {
        return is_array($this->elements)? $this->countElementsInArray() : 0;
    }

    public function wereFound()
    {
        (boolean) $doesTheArrayHaveAnyElements = !empty($this->elements);

        return $doesTheArrayHaveAnyElements;
    }

    public function groupsOf($numberOfItemsPerGroup)
    {
        (array) $groupObjects = [];

        foreach (array_chunk($this->elements, $numberOfItemsPerGroup) as $splitElements) {

            $groupObjects[] = new GroupOf($splitElements);

        }

        return new GroupOf($groupObjects);
    }

    public function asArray()
    {
        return is_array($this->elements)? $this->elements : [];
    }

    public function reset()
    {
        $this->elements = [];
    }

    protected function countElementsInArray()
    {
        return count($this->elements);
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function valid()
    {
        (boolean) $elementExistsAtCurrentIndex = isset($this->elements[$this->index]);

        return $elementExistsAtCurrentIndex;
    }

    public function current()
    {
        $currentElement = $this->elements[$this->index];

        return $currentElement;
    }

    public function  key()
    {
        $currentIndex = $this->index;

        return $currentIndex;
    }

    public function next()
    {
        ++$this->index;
    }

























}