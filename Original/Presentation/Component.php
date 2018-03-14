<?php

namespace Stratum\Original\Presentation;

use Stratum\Original\Establish\Environment;
use Stratum\Original\Presentation\Balance\Cache\ComponentCache;
use Stratum\Original\Presentation\Balance\Cache\Writer\ComponentCacheWriter;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\Exception\InvalidReturnTypeException;
use Stratum\Original\Presentation\Exception\UnexistentPartialViewType;
use Stratum\Original\Presentation\Writer\ComponentWriter;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Component
{
    use className;
    
    protected $bindedData;
    protected $bindedDataDefinition;
    private $inheritedVariables = [];
    protected $canBeCached = false;
    protected $isDynamicComponent = false;
    protected $componentCache;
    protected $partialViewElements;

    abstract protected function load(PartialView $view);

    public function __construct(array $inheritedVariables)
    {
        $this->inheritedVariables = $inheritedVariables;
    }

    public function inheritedVariables()
    {
        return $this->inheritedVariables;
    }

    public function inheritedVariableWithName($variableName)
    {
        return $this->inheritedVariables[$variableName];
    }

    public function setBindedData($bindedData)
    {
        $this->bindedData = $bindedData;
    }

    public function setBindedDataDefinition($bindedDataDefinition)
    {
        $this->bindedDataDefinition = $bindedDataDefinition;
    }

    public function bindedDataDefinition()
    {
        return $this->bindedDataDefinition;
    }

    public function bindedData()
    {
        return $this->bindedData;
    }

    public function name()
    {
        return $this->singleClassName();
    }

    final public function elements()
    {
        if (!empty($this->partialViewElements)) {
            return $this->partialViewElements;
        }

        if ($this->environmentAllowsCaching() and $this->canBeCached() and $this->componentCache()->componentIsCached()) {
            
            return $this->cachedComponentContent();
        }

        return $this->nonCachedElements();
    }

    public function nonCachedElements()
    {
        (object) $partialViewElements = $this->partialViewElements();

        $this->addToCacheQueueIfCanBeCached($partialViewElements);
        $this->markNodesIfComponentCannotBeCached($partialViewElements);

        $this->partialViewElements = $partialViewElements;

        return $partialViewElements;
    }

    public function canBeCached()
    {
        return $this->canBeCached;
    }

    protected function partialViewElements()
    {
        (object) $partialView = $this->load(new PartialView($this->inheritedVariables));

        $this->throwExceptionIfLoadDoesNotReturnAPartialViewObject($partialView);

        return $partialView->elements();

    }

    protected function cachedComponentContent()
    {
        (object) $text = new Text;

        $text->addContent($this->componentCache()->cachedComponentContent());

        return $text;
    }

    protected function componentCache()
    {
        if (empty($this->componentCache)) {
            $this->componentCache = new ComponentCache(
                $componentName = $this->name(),
                $componentParameter = $this->bindedData
            );
        }

        return $this->componentCache;
    }

    protected function addToCacheQueueIfCanBeCached(GroupOfNodes $partialViewElements)
    {
        if ($this->environmentAllowsCaching() and $this->canBeCached()) {

            (object) $componentWriter = new ComponentWriter($this);

            $componentWriter->setComponentElements($partialViewElements);

            ComponentCacheWriter::addComponentToQueue($componentWriter);

        }
    }

    protected function markNodesIfComponentCannotBeCached(GroupOfNodes $partialViewElements)
    {
        if ($this->isDynamicComponent) {
            foreach ($partialViewElements as $node) {
                $node->setParentComponent($this);
            }
        }
    }

    protected function throwExceptionIfloadDoesNotReturnAPartialViewObject($partialView)
    {
        $PartialView = PartialView::className();

        (boolean) $loadReturnsNoPartialViewObject = $partialView instanceof $PartialView === false;

        if ($loadReturnsNoPartialViewObject) {
            throw new InvalidReturnTypeException(
                Component::className() . "::load() must return a $PartialView object, " .
                gettype($this->load(new FullPartialView)) . ' returned'
            );  
        }
    }

    protected function environmentAllowsCaching()
    {
        return Environment::is()->production();
    }

}
