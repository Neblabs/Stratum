<?php

namespace Stratum\Original\Presentation\EOM;

use DOMDocument;
use DOMNodeList;
use Stratum\Original\Presentation\Creator\EOMNodeCreator;
use Stratum\Original\Presentation\EOM\Finder\ElementFinderByType;
use Stratum\Original\Presentation\Exception\ForbiddenAttributeWriteException;
use Stratum\Original\Presentation\Exception\InvalidChildException;
use Stratum\Original\Presentation\Writer\EOMNodeWriter;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class Element extends Node
{
    use ClassName;

    protected $type;
    protected $isVoid;
    protected $attributes;
    protected $children;
    protected $DOMDocument;
    protected $writer;

    public function addContent($content)
    {
        if ($this->contentHasHTML($content)) {
            $this->createChildrenElementsFrom($content);
        } else {
            $this->addTextNodeToTreeWith($content, ['type' => 'child']);
        }
    }

    public function addText($content)
    {
        $this->addTextNodeToTreeWith($content, ['type' => 'child']);
    }

    public function addContentAsPreviousSibling($content)
    {
        if ($this->contentHasHTML($content)) {
            $this->createPreviousSiblingElementsFrom($content);
        } else {
            $this->addTextNodeToTreeWith($content, ['type' => 'previousSibling']);
        }
    }

    public function addContentAsNextSibling($content)
    {
        if ($this->contentHasHTML($content)) {
            $this->createNextSiblingElementsFrom($content);
        } else {
            $this->addTextNodeToTreeWith($content, ['type' => 'nextSibling']);
        }
    }

    public function addPreviousSibling(Node $node)
    { 
        if ($this->previousSiblings()->wereFound()) {
            $node->moveAfter($this->previousSiblings()->last());
        } else {
            $node->moveBefore($this);
        }
        
    }

    public function addNextSibling(Node $node)
    { 
        
        if ($this->nextSiblings()->wereFound()) {
            $node->moveBefore($this->nextSiblings()->first());
        } else {
            $node->moveAfter($this);
        }
    }

    public function content(){}

    public function __construct(array $elementData)
    {
        $this->type = $elementData['type'];
        $this->isVoid = $elementData['isVoid'];
    }

    public function __clone()
    {
        $this->parent = null;
        $this->previousSiblings()->reset();
        $this->nextSiblings()->reset();

        $this->cloneChildren();
    }

    public function type()
    {
        return $this->type;
    }

    public function is($type)
    {
        return $this->type === $type;
    }

    public function isVoid()
    {
        return $this->isVoid;
    }

    public function addChild(Node $node)
    {
        $this->throwExceptionIfIsVoidElement();

        $node->previousSiblings = clone $this->children();
        $this->updateNextSiblingsFromChildrenWith($node);

        $this->children()->add($node);

        $node->parent = $this;
    }

    public function addChildren($nodes)
    {
        (string) $GroupOfNodes = GroupOfNodes::className();

        if ($nodes instanceof $GroupOfNodes) {
            foreach ($nodes as $node) {
                $this->addChild($node);
            }
        } else {
            $this->addChild($nodes);
        }
        
    }

    public function children()
    {
        if (!isset($this->children)) {
            $this->children = new GroupOfNodes;
        }

        return $this->children;
    }

    /*
        Experimental: Not complete, for searching elements through the entire descendant tree 
     */
    public function descendants()
    {
        $this->children()->searchThroughAllDescendants();

        return $this->children();
    }

    public function select($type)
    {   
        (object) $elementFinder = new ElementFinderByType($this->children());

        return $elementFinder->selectBy([
            'attribute' => $type,
            'searchAllDescendants' => false
        ]);
    }

    public function remove()
    {
        if (!$this->hasParent()) { 
            
            return; 
        }

        $this->parent->children()->remove($this);

        (array) $childrenWithoutRemoveChild = $this->parent->children->asArray();

        $this->parent->children()->reset();

        $this->parent->addChildren(new GroupOfNodes($childrenWithoutRemoveChild));

        $this->parent = null;
        $this->nextSiblings()->reset();
        $this->previousSiblings()->reset();
    }

    public function attributes()
    {
        return $this->attributesObject()->asArray();    
    }

    public function addAttribute(array $attribute)
    {
        
        $this->attributesObject()->add($attribute);
    }

    public function __call($method, $arguments)
    {
        (object) $methodResolver = new DynamicAttributesResolver($method);

        (array) $attributeData = [
            'name' => $methodResolver->attributeName(),
            'value' => $arguments[0]
        ];

        if ($methodResolver->isSet()) {
            $this->attributesObject()->set($attributeData);
        } elseif ($methodResolver->isAdd()) {
            $this->attributesObject()->add($attributeData);
        } elseif ($methodResolver->isHas()) {
            return $this->attributesObject()->has($attributeData);
        } elseif ($methodResolver->isRemove()) {
            $this->attributesObject()->remove($attributeData);
        } elseif ($methodResolver->isWith()) {
            return $this->children()->$method($arguments[0]);
        }
    }

    public function __get($property)
    {
        return $this->attributesObject()->get($property);
    }

    public function __set($property, $value)
    {
        throw new ForbiddenAttributeWriteException('Attributes via properties are read-only');
    }

    public function writer()
    {
        if (!isset($this->writer)) {
            $this->writer = EOMNodeWriter::createFrom($this);
        }

        return $this->writer;
    }

    public function setDOMDocument(DOMDocument $DOMDocument)
    {
        $this->DOMDocument = $DOMDocument;
    }

    protected function attributesObject()
    {
        if (!isset($this->attributes)) {
            $this->attributes = new Attributes;
        }

        return $this->attributes;
    }

    protected function addTextNodeToTreeWith($content, array $relationship)
    {
        (object) $text = new Text();

        $text->addContent($content);

        switch ($relationship['type']) {
            case 'child':
                $this->addChild($text);
                break;
            case 'previousSibling':
                $this->addPreviousSibling($text);
                break;
            case 'nextSibling':
                $this->addNextSibling($text);
                break;
        
        }
    }

    protected function contentHasHTML($content)
    {
        (boolean) $openningHTMLTagExistsInContent = strpos($content, '<') !== false;

        return $openningHTMLTagExistsInContent;
    }

    protected function createChildrenElementsFrom($content)
    {
        (object) $nodes = $this->createDOMNodesFrom($content);

        $this->addNodesFromDOMDocumentNodesToTree($nodes, ['type' => 'children']);
    }

    protected function createDOMNodesFrom($content)
    {
        $this->setDOMDocumentIfUnset();

        (string) $contentWrappedByTemplateElement = "<div>$content</div>";

        libxml_use_internal_errors(true);
        //$this->DOMDocument->substituteEntities = false;
        //$this->DOMDocument->resolveExternals = false;
        $this->DOMDocument->loadHTML(mb_convert_encoding($contentWrappedByTemplateElement, 'HTML-ENTITIES', 'UTF-8'));
        libxml_clear_errors();
        (object) $nodes = $this->DOMNodes();

        return $nodes;
    }

    protected function DOMNodes()
    {
        (boolean) $phpVersionIsOlderThan6 = PHP_VERSION < 6;
        
        if ($phpVersionIsOlderThan6) {
            return $this->DOMDocument->getElementsByTagName('body')->item(0)->childNodes->item(0)->childNodes;
        } else {
            return $this->DOMDocument->getElementsByTagName('body')[0]->childNodes[0]->childNodes;
        }
    }

    protected function setDOMDocumentIfUnset()
    {
        if (!isset($this->DOMDocument)) {
            $this->DOMDocument = new DOMDocument;
        }
    }

    protected function createPreviousSiblingElementsFrom($content)
    {
        (object) $nodes = $this->createDOMNodesFrom($content);

        $this->addNodesFromDOMDocumentNodesToTree($nodes, ['type' => 'previousSibling']);
    }

    protected function createNextSiblingElementsFrom($content)
    {
        (object) $nodes = $this->createDOMNodesFrom($content);

        $this->addNodesFromDOMDocumentNodesToTree($nodes, ['type' => 'nextSibling']);
    }

    protected function addNodesFromDOMDocumentNodesToTree(DOMNodeList $nodes, array $relationship)
    {

        foreach ($nodes as $node) {
            
            (boolean) $isNotTextNode = ($node instanceOf \DOMText) === false;
            
            (boolean) $textNodeisNotEmpty = !empty(trim($node->nodeValue));

            if ($isNotTextNode or $textNodeisNotEmpty) {
                (object) $EOMNode = EOMNodeCreator::createFrom($node);

            switch ($relationship['type']) {
                case 'children':
                    $this->addChild($EOMNode);
                    break;
                case 'previousSibling':
                    $this->addPreviousSibling($EOMNode);
                    break;
                case 'nextSibling':
                    $this->addNextSibling($EOMNode);
                    break;
            }

            } 
        }
    }

    protected function throwExceptionIfIsVoidElement()
    {
        if ($this->isVoid()) {
           
            throw new InvalidChildException('A void element cannot have children');
        }
    }

    protected function updateNextSiblingsFromChildrenWith(Node $node)
    {   
        if (!is_null($node->nextSiblings)) {
            $node->nextSiblings()->reset();
        }

        foreach ($this->children()->asArray() as $child) {
            $child->nextSiblings()->add($node);
        }
    }

    protected function updateNodeToBeMovedSiblings()
    {
            (array) $nodeSiblings = array_merge($node->previousSiblings()->asArray(), $node->nextSiblings()->asArray());

            foreach ($nodeSiblings as $sibling) {

            }

    }

    protected function cloneChildren()
    {
        $this->children = new GroupOfNodes($this->children()->asArray());
    }

    public function hasParent()
    {
        return $this->parent() !== null;
    }
















}