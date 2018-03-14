<?php

namespace Stratum\Original\Presentation\EOM;

use Stratum\Original\Data\GroupOf;
use Stratum\Original\Presentation\EOM\Finder\ElementFinderByAttribute;
use Stratum\Original\Presentation\EOM\Finder\ElementFinderByType;
use Stratum\Original\Presentation\EOM\Node;
use Stratum\Original\Presentation\Exception\InvalidTypeException;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class GroupOfNodes extends GroupOf
{
    use ClassName;
    
    public $searchThroughAllDescendants = false;

    public function __construct(array $nodes = null)
    {
        $this->throwExceptionIfArrayContainsTypesThatAreNotNodes($nodes);

        parent::__construct($nodes);
    }

    public function add($node)
    {
        $this->throwExceptionifIsNotANodeObject($node);

        parent::add($node);
    }

    public function addNodes($nodes)
    {  
        (string) $GroupOfNodes = Self::className();

        if ($nodes instanceof $GroupOfNodes) {
            parent::addItems($nodes);
        } else {
            $this->add($nodes);
        }
        
    }

    public function searchThroughAllDescendants()
    {
        $this->searchThroughAllDescendants = true;
    }

    public function select($type)
    {   
        (object) $elementFinder = new ElementFinderByType($this);

        return $elementFinder->selectBy([
            'attribute' => $type,
            'searchAllDescendants' => $this->searchThroughAllDescendants
        ]);
    }

    public function __call($method, $arguments)
    {
        (object) $methodResolver = new DynamicAttributesResolver($method);

        if ($methodResolver->isWith()) {

            return $this->selectByAttribute([
                'name' => $methodResolver->attributeName(), 
                'value' => $arguments[0]
            ]);
        }

        return parent::__call($method, $arguments);
    }

    protected function selectByAttribute(array $attribute)
    {
        return $this->findForeachNode($attribute);
    }

    protected function findForeachNode($attribute)
    {
        (array) $foundElements = [];


        (object) $elementFinder = new ElementFinderByAttribute($this);

        $foundElements[] = $elementFinder->selectBy([
            'attribute' => $attribute,
            'searchAllDescendants' => $this->searchThroughAllDescendants
        ])->asArray();


        return $this->createGroupOfElementsFrom($foundElements);
    }

    protected function createGroupOfElementsFrom(array $arrayOfFoundElements)
    {
        (object) $GroupOfNodes = new GroupOfNodes;

        foreach ($arrayOfFoundElements as $foundElements) {
            foreach ($foundElements as $foundElement) {
                $GroupOfNodes->add($foundElement);
            }
        }

        return $GroupOfNodes;
    }

    protected function throwExceptionIfArrayContainsTypesThatAreNotNodes($nodes)
    {
        if (is_null($nodes)) {
            return;
        }

        foreach ($nodes as $node) {
            $this->throwExceptionifIsNotANodeObject($node);
        }
    }

    protected function throwExceptionifIsNotANodeObject($node)
    {   
        (string) $nodeType = Node::className();

        if (($node instanceof $nodeType) === false) {
            throw new InvalidTypeException("A GroupOfNodes object can only contain $nodeType objects");
        }
    }
}