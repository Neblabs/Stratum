<?php

namespace Stratum\Original\Presentation\Element\Adder;

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class ElementContentAdder
{
    use ClassName;

    protected $elementToAddContentTo;
    protected $componentToLoad;
    protected $variables;
    protected $component;
    protected $bindedData;

    abstract public function addComponentContentToElement();

    public static function createFor($elementToAddContentTo)
    {
        return new FullDOMElementContentAdder($elementToAddContentTo);
    }

    public function __construct($elementToAddContentTo)
    {
        $this->throwExceptionIfObjectIsNotAnElementOrAGroupOfNodes($elementToAddContentTo);

        $this->elementToAddContentTo = $elementToAddContentTo;
    }

    public function setComponentNameToLoad($componentToLoad) 
    {
        $this->componentToLoad = $componentToLoad;
    }

    public function setVariables(array &$variables)
    {
        $this->variables = $variables;
    }

    public function setBindedData($bindedData)
    {
        $this->bindedData = $bindedData;
    }

    public function addComponentToElement()
    {
        (string) $type = $this->isOriginalComponent()? 'Prebuilt' : 'Custom';
        (string) $Component = "Stratum\\{$type}\Component\\{$this->componentToLoad}";

        $this->component = new $Component($this->variables);

        $this->component->setBindedData($this->bindedData);

        $this->addComponentContentToElement();
    }

    protected function isOriginalComponent()
    {
        return in_array($this->componentToLoad, [
            'Content', 'Output'
        ]);
    }

    protected function throwExceptionIfObjectIsNotAnElementOrAGroupOfNodes($elementToAddContentTo)
    {
        (string) $EOMElement = Element::className();
        (string) $GroupOfNodes = GroupOfNodes::className();
        (string) $ElementContentAdder = Self::className();

        if ((($elementToAddContentTo instanceof $EOMElement) === false) && (($elementToAddContentTo instanceof $GroupOfNodes) === false)) {
            throw new \InvalidArgumentException("A {$ElementContentAdder} object requires an object of type {$EOMElement} or {$GroupOfNodes}, " . get_class($elementToAddContentTo) . ' given.');
        }

    }








}