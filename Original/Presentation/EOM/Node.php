<?php

namespace Stratum\Original\Presentation\EOM;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Node
{
    use ClassName;
    
    protected $parent;
    protected $previousSiblings;
    protected $nextSiblings;

    protected $parentComponent;

    const BEFORE_NODE = 0;
    const AFTER_NODE = 1;

    abstract public function is($type);
    abstract public function type();
    abstract public function addContent($content);
    abstract public function content();

    public function moveBefore(Node $node)
    {
        $this->move($node, static::BEFORE_NODE);

    }

    public function moveAfter(Node $node)
    {
        $this->move($node, static::AFTER_NODE);
    }

    public function previousSiblings()
    {
        if (!isset($this->previousSiblings)) {
            $this->previousSiblings = new GroupOfNodes;
        }

        return $this->previousSiblings;
    }

    public function nextSiblings()
    {
        if (!isset($this->nextSiblings)) {
            $this->nextSiblings = new GroupOfNodes;
        }

        return $this->nextSiblings;
    }

    public function previousSiblingElements()
    {
        return $this->elementsOnly('previousSiblings');
    }

    public function nextSiblingElements()
    {
        return $this->elementsOnly('nextSiblings');
    }
    
    public function parent()
    {
        return $this->parent;
    }

    public function setParentComponent(Component $component)
    {
        $this->parentComponent = $component;
    }

    public function parentComponent()
    {
        return $this->parentComponent;
    }

    public function isPartOfNonCachableComponent()
    {
        return !empty($this->parentComponent);
    }

    protected function move(Node $node, $beforeOrAfter)
    {
        $this->updateNodeToBeMovedParent();

        $this->updateTargetNodeParent($node, $beforeOrAfter);
    }

    protected function updateNodeToBeMovedParent()
    {   
        (boolean) $nodeTobeMovedHasParent = $this->parent !== null;

        if ($nodeTobeMovedHasParent) {

            (array) $nodeSiblings = $this->parent->children->asArray();

            (integer) $keyOfNodeToBeMoved = array_search($this, $nodeSiblings);

            unset($nodeSiblings[$keyOfNodeToBeMoved]);

            $this->parent->children = new GroupOfNodes;

            foreach ($nodeSiblings as $nodeSibling) {
                $this->parent->addChild($nodeSibling);
            }

        }

    }

    protected function updateTargetNodeParent(Node $node, $beforeOrAfter)
    {   
        (boolean) $targetNodeHasParent = $node->parent !== null;

        if ($targetNodeHasParent) {

            (array) $nodeParentsChildren = $node->parent->children->asArray();
        
            $this->removeNodeToBeMovedFromArrayIfIsSiblingOfTarget($nodeParentsChildren);

            (integer) $length = 0;
            (integer) $start = array_search($node, $nodeParentsChildren) + $beforeOrAfter;
    
            array_splice($nodeParentsChildren, $start, $length, [$this]);
    
            $node->parent->children = new GroupOfNodes;
    
            foreach ($nodeParentsChildren as $nodeParentsChild) {
                
                $node->parent->addChild($nodeParentsChild);
            }

        }  

    }

    protected function removeNodeToBeMovedFromArrayIfIsSiblingOfTarget(&$nodeParentsChildren)
    {
        (integer) $keyOfNodeToBeMoved = array_search($this, $nodeParentsChildren);

        (integer) $nodeToBeMovedIsASiblingOfTargetNode = $keyOfNodeToBeMoved !== false;

        if ($nodeToBeMovedIsASiblingOfTargetNode) {
            unset($nodeParentsChildren[$keyOfNodeToBeMoved]);
        }
    }

    protected function elementsOnly($relatedElements)
    {
        (array) $elements = [];

        foreach ($this->{$relatedElements}()->asArray() as $element) {
            (string) $EOMElement = Static::className();

            (boolean) $isElement = $element instanceof $EOMElement;

            if ($isElement) {
                $elements[] = $element;
            }

        }

        return new GroupOfNodes($elements);
    }

}






