<?php

namespace Stratum\Original\Presentation\EOM;

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\GroupOfNodes;

Abstract Class ElementFinder
{
    protected $nodes;
    protected $results = [];

    abstract protected function nodePassesFilter(Node $node, $attribute);

    public function __construct(groupOfNodes $nodes)
    {
        $this->nodes = $nodes;
    }

    public function selectBy(array $attribute)
    {
        (array) $foundElements = $this->findBy($attribute);

        return new GroupOfNodes($foundElements);
    }

    protected function findBy(array $elementOptions)
    {
        extract($elementOptions);

        foreach ($this->EOMElementsOnly() as $element) {     

            if ($searchAllDescendants) {
                (array) $childrenResults = (new Static($element->children()))->findBy([
                    'attribute' => $attribute,
                    'searchAllDescendants' => true
                 ]);
            
                $this->results = array_merge($this->results, $childrenResults);
            }

            if ($this->nodePassesFilter($element, $attribute)) {
                $this->results[] = $element;
            }
            
        }

        return $this->results;
    }

    protected function EOMElementsOnly() 
    {   
        (array) $elements = [];

        foreach ($this->nodes as $node) {
            if ($node instanceof Element) {
                $elements[] = $node;
            }
        }

        return $elements;
    }






}