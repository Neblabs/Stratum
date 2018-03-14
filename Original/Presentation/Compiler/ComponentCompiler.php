<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Utility\ClassUtility\ClassName;


Class ComponentCompiler extends Compiler
{
    use ClassName;

    protected $componentElement;
    protected $componentVariable;
    protected $variableResolver;
    protected $addToGroupOfNodesDefinition = true;
    protected $prebuiltComponents = [
        'Content', 
        'Output',
        'WpHead',
        'WpFooter',
        'Widgets'
    ];

    public static function createFromComponent(Component $component)
    {
        (object) $componentElement = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => false
        ]);

        $componentElement->addName($component->name());
        $componentElement->addUse($component->bindedDataDefinition());

        return (new Static($componentElement))->setAddToGroupOfNodes(false);

    }

    public function __construct(Element $component)
    {
        $this->componentElement = $component;
    }

    public function setAddToGroupOfNodes($addToGroupOfNodes)
    {
        $this->addToGroupOfNodesDefinition = $addToGroupOfNodes;

        return $this;
    }

    public function compiledType()
    {
        return $this->compiledComponent();
    }

    public function compiledComponent()
    {
        $this->componentVariable = $this->generateComponentVariableName();
        (string) $CustomComponent = $this->ComponentNameSpace();
        (string) $componentInstantiationDefinition = "(object) {$this->componentVariable} = new $CustomComponent(\$this->variables);";
        (string) $componentBindedValue = $this->bindedValueDefinition();
        (string) $componentBindedValueDefinition = $this->componentBindedValueDefinition();
        (string) $addToGroupDefinition = $this->addToGroupOfNodesIfHasNoParent();

        return $componentInstantiationDefinition . PHP_EOL . 
               $componentBindedValue . PHP_EOL . 
               $componentBindedValueDefinition . PHP_EOL . 
               $addToGroupDefinition . PHP_EOL;
    }

    protected function ComponentNameSpace()
    {
        if ($this->isPrebuiltComponent()) {
            return "Stratum\\Prebuilt\\Component\\{$this->componentElement->name}";
        }

        return "Stratum\\Custom\\Component\\{$this->componentElement->name}";
    }

    public function getNodesDefinition()
    {
        return "{$this->componentVariable}->elements();";
    }

    public function componentVariable()
    {
        return $this->componentVariable;
    }

    public  function variableResolver()
    {
        return $this->variableResolver;
    }

    protected function generateComponentVariableName()
    {
        return "\${$this->componentElement->name}Component" . random_int(10000, 99999);
    }

    protected function bindedValueDefinition()
    {
        $this->variableResolver = new VariableResolver($this->componentElement->use, $this);
        (boolean) $componentHasUseAttribute =  $this->componentElement->use !== null;

        if ($componentHasUseAttribute) {

            (string) $variableFormatters =  $this->formattersHandlersDefinitionIfHasFormatters();
            return $variableFormatters . "{$this->componentVariable}->setBindedData({$this->variableResolver->compiledString()});";
        }
    }

    protected function formattersHandlersDefinitionIfHasFormatters()
    {
        if ($this->variableResolver->expressionHasFormatters()) {
            return $this->variableResolver->formattersHandlers()[0] . PHP_EOL;
        }
    }

    protected function addToGroupOfNodesIfHasNoParent()
    {
        (boolean) $nodeIsTopLevelNode = $this->componentElement->parent() === null;
        
        if ($nodeIsTopLevelNode and $this->addToGroupOfNodesDefinition) {
            return "\$groupOfNodes->addNodes({$this->componentVariable}->elements());" . PHP_EOL;
        }

        return '';
    }

    protected function componentBindedValueDefinition()
    {
        if ($this->componentElement->use != null) {
            return "{$this->componentVariable}->setBindedDataDefinition('{$this->componentElement->use}');";
        }
    }

    protected function isPrebuiltComponent()
    {
        return in_array($this->componentElement->name, $this->prebuiltComponents);
    }




}
