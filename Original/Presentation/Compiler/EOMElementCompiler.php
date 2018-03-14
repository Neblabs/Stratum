<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Node;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Registrator\ManagerTaskRegistrator;
use Stratum\Original\Utility\ClassUtility\ClassName;
use Stratum\Original\Utility\StringConverter;

Class EOMElementCompiler extends Compiler
{
    use ClassName;

    protected $element;
    protected $elementVariable;
    protected $elementManagerVariables = [];
    protected $descendantNodesVariables = [];
    protected $variableResolvers = [];
    protected $childrenCompilers = [];
    protected $aliasedVariableForForeach;
    protected $ancestorElementIsInsideForeach = false;
    protected $missingClosingCurlyBraceForeach = false;
    protected $missingClosingCurlyBraceForIfStatement = false;

    public function __construct(Element $element, Compiler $parentCompiler = null)
    {
        $this->requireManagerRegistrationsFile();

        $this->parentCompiler = $parentCompiler;
        $this->element = $element;
        $this->managerTaskRegistrator = new ManagerTaskRegistrator;
        $this->elementIsInsideForeach = $this->elementHasValidForeachTask();
        $this->hadForeach = $this->elementIsInsideForeach;
        $this->foreachArgument = $this->foreachArgument();
        $this->hadShowIfTask = $this->elementHasValidShowIfTask();
        $this->showIfArgument = $this->showIfArgument();
        $this->elementHasAddContentAsTextManager = $this->elementHasAddContentAsTextManager();

        
    }

    public function compiledType()
    {
        return $this->compiledElement();
    }

    public function compiledElement()
    {
        return $this->elementObjectDefinitions();
    }

    public function elementVariable()
    {
        return $this->elementVariable;
    }

    public function descendantNodesVariables()
    {
        return $this->descendantNodesVariables;
    }

    public function childrenCompilers()
    {
        return $this->childrenCompilers;
    }

    public function formattersHandlerVariable()
    {
        return $this->variableResolver->formattersHandlerVariable();
    }

    public function elementManagerVariables()
    {
        return $this->elementManagerVariables;
    }

    public function variableResolvers()
    {
        return $this->variableResolvers;
    }

    protected function requireManagerRegistrationsFile()
    {
        require_once STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/Register.php';
    }

    protected function elementObjectDefinitions()
    {
        (string) $this->elementVariable = $this->generateElementVariableName();

        (string) $elementDefintions = $this->elementInstantiationDefinition();

        $elementDefintions.= PHP_EOL . $this->taskOrAttributeDefinitions();
        $elementDefintions.= PHP_EOL . $this->createChildrenDefinitions();
        $elementDefintions.= PHP_EOL . $this->addToGroupOfNodesIfHasNoParent();
        
        $elementDefintions = $this->wrapInsideforeachIfHasCreateForEachTask($elementDefintions);

        $elementDefintions = $this->wrapInsideIfStatementIfHasShowifTask($elementDefintions);

        return $elementDefintions;
    }

    protected function generateElementVariableName()
    {
        return '$element' . random_int(1000000, 9999999);
    }

    protected function elementInstantiationDefinition()
    {
        return PHP_EOL . "(object) {$this->elementVariable} = new Element([
                'type' => '{$this->element->type()}',
                'isVoid' => {$this->elementIsVoid($this->element)}
            ]);" . PHP_EOL;
    }

    protected function elementIsVoid(Element $element)
    {
        return $element->isVoid() ? 'true' : 'false';
    }

    protected function taskOrAttributeDefinitions()
    {
        $attributeDefinitions = '';
        foreach ($this->element->attributes() as $attributeName => $attributeValue) {

            if ($this->managerTaskRegistrator->taskIsRegistered($attributeName)) {
                $attributeDefinitions .= $this->managerDefinitions($attributeName, $attributeValue);
            }  else {
                $attributeDefinitions .= $this->attributeDefinitions($attributeName, $attributeValue);
            }
        }

        return $attributeDefinitions;
    }

    protected function managerDefinitions($taskName, $taskValue)
    {
        $this->elementManagerVariable = '$elementManager' . random_int(10000, 99999);
        $this->elementManagerVariables[] = $this->elementManagerVariable;
        (string) $managerClass = $this->managerTaskRegistrator->managerTypeFor($taskName)->className();
        (string) $taskMethod = (new StringConverter($taskName))->removeDashes();
        (object) $this->variableResolver = new VariableResolver($taskValue, $this);
        $this->variableResolvers[] = $this->variableResolver;

        (string) $managerDefinitions = "(object) {$this->elementManagerVariable} = new $managerClass({$this->elementVariable});" . PHP_EOL;
        $managerDefinitions .= PHP_EOL ."{$this->elementManagerVariable}->setTask('$taskMethod');" . PHP_EOL;
        $managerDefinitions .= $this->setFormattersHandlerIfVariableDefinitionHasFormatters($this->variableResolver);
        $managerDefinitions .= PHP_EOL ."{$this->elementManagerVariable}->setTaskArgument({$this->variableResolver->compiledString()});" . PHP_EOL;
        $managerDefinitions .= PHP_EOL ."{$this->elementManagerVariable}->setVariables(\$this->variables);" . PHP_EOL;
        $managerDefinitions .= PHP_EOL . $this->addToQueueOrExecuteIfIsBody() . PHP_EOL;

        return $managerDefinitions;


    }

    protected function addToQueueOrExecuteIfIsBody()
    {
        if ($this->element->is('body')) {
            return "{$this->elementManagerVariable}->executeTask();";
        }

        return "\$elementManagersQueue->addManagerToQueue({$this->elementManagerVariable});";
    }

    protected function attributeDefinitions($attributeName, $attributeValue)
    {

        (string) $attributeName = (new StringConverter($attributeName))->replaceDashesWithUpperCasedLetters();
        (string) $addAttribute = "add{$attributeName}";
        (object) $this->variableResolver = new VariableResolver($attributeValue, $this);

        $this->variableResolvers[] = $this->variableResolver;

        $attributeDefinitions = $this->setFormattersHandlerIfVariableDefinitionHasFormatters($this->variableResolver);

        
            $attributeDefinitions .= "{$this->elementVariable}->addAttribute([
            'name' => '$attributeName',
            'value' => {$this->variableResolver->compiledString()}
        ]);" . PHP_EOL;

        

        return $attributeDefinitions;
    }

    protected function setFormattersHandlerIfVariableDefinitionHasFormatters($variableResolver)
    {
        if ($variableResolver->expressionHasFormatters()) {
            return PHP_EOL . implode(' ', $variableResolver->formattersHandlers()) . PHP_EOL;
        }

        return '';
    }

    protected function generateFormattersHandlerVariableName()
    {
        return '$formattersHandler' . random_int(1000000, 9999999);
    }

    protected function createChildrenDefinitions()
    {
        (string) $childrenDefinition = '';

        $childrenDefinition.= $this->element->is('body')? 
                                    "{$this->elementVariable}->writer()->writeOpeningTag();" . PHP_EOL .
                                    "ViewCacheWriter::addTopLevelNodesToQueue({$this->elementVariable});" . PHP_EOL
                                    : '';

        foreach ($this->element->children() as $child) {
            (object) $childCompiler = EOMCompiler::createCompilerFrom($child, $this);

            $this->setIsInsideForeachIfCurrentElementHasACreateForEachTask($childCompiler);

            $childrenDefinition.= $this->childElementNodeDefintion($childCompiler);
            $childrenDefinition.= $this->addChildNodeDefinintion($child, $childCompiler);

            $this->addDescendantNodeVariableIfNodeIsElement($childCompiler);
        }


        $childrenDefinition.= $this->element->is('body')? "{$this->elementVariable}->writer()->writeClosingTag();" . PHP_EOL : '';

        return $childrenDefinition;
    }

    protected function setIsInsideForeachIfCurrentElementHasACreateForEachTask($childCompiler)
    {
        $childCompiler->elementIsInsideForeach = $this->elementIsInsideForeach;
        $childCompiler->ancestorElementIsInsideForeach = $this->elementIsInsideForeach;
        $childCompiler->aliasedVariableForForeach = $this->aliasedVariableForForeach;

    }

    protected function childElementNodeDefintion($childCompiler)
    {
        (string) $EOMElementCompiler = get_class($this);
        (string) $ComponentCompiler = ComponentCompiler::className();

        if (($childCompiler instanceof $EOMElementCompiler) or ($childCompiler instanceof $ComponentCompiler)) {

            $this->childrenCompilers[] = $childCompiler;

            return $childCompiler->compiledType();
        } 

        return '';
    }

    protected function addChildNodeDefinintion(Node $child, $childCompiler)
    {
    
        if ($this->isWritable()) {
            return $this->writeElementDefinition($child, $childCompiler);
        }
        
        return $this->addChildDefinition($child, $childCompiler);
    }

    protected function writeElementDefinition(Node $child, $childCompiler)
    {
        (string) $EOMTextCompiler = EOMTextCompiler::className();
        (string) $EOMElementCompiler = EOMElementCompiler::className();
        (string) $ComponentCompiler = ComponentCompiler::className();

        if ($childCompiler instanceof $EOMTextCompiler) {
            (string) $formattersHandlers = $this->setFormattersHandlerIfVariableDefinitionHasFormatters($childCompiler->variableResolver());

            $this->variableResolvers[] = $childCompiler->variableResolver();
            (string) $contentOrPlainText = $this->elementHasAddContentAsTextManager? 'Text' : 'Content';
            
            return $formattersHandlers . PHP_EOL . 
                   "print {$childCompiler->compiledNode()}";
        } 
            
        (string) $componentOrElementVariable = ($childCompiler instanceof $EOMElementCompiler)? $childCompiler->elementVariable() : $childCompiler->ComponentVariable();
        return $this->executeManagersIfIsElement($childCompiler) . PHP_EOL . 
               "EOMNodeWriter::createFrom({$componentOrElementVariable})->write();" .  PHP_EOL .
               "Flusher::flush();" .PHP_EOL . 
               "ViewCacheWriter::addTopLevelNodesToQueue({$componentOrElementVariable});" . PHP_EOL . 
               "ComponentCacheWriter::saveComponentsInQueue();" . PHP_EOL;
                
    }

    protected function executeManagersIfIsElement($childCompiler)
    {
        (string) $EOMElementCompiler = EOMElementCompiler::className();

        if ($childCompiler instanceof $EOMElementCompiler) {
            return "\$elementManagersQueue->executeManagerTasks();" . PHP_EOL . 
                   "\$elementManagersQueue->clearQueue();";
        }
    }

    protected function addChildDefinition(Node $child, $childCompiler)
    {
        (string) $EOMTextCompiler = EOMTextCompiler::className();
        (string) $EOMElementCompiler = EOMElementCompiler::className();
        (string) $ComponentCompiler = ComponentCompiler::className();
        
        if ($childCompiler instanceof $EOMTextCompiler) {

            (string) $formattersHandlers = $this->setFormattersHandlerIfVariableDefinitionHasFormatters($childCompiler->variableResolver());

            $this->variableResolvers[] = $childCompiler->variableResolver();
            (string) $contentOrPlainText = $this->elementHasAddContentAsTextManager? 'Text' : 'Content';
            
            return $formattersHandlers . "{$this->elementVariable}->add{$contentOrPlainText}({$childCompiler->compiledNode()});";
        } elseif ($childCompiler instanceof $ComponentCompiler) {
            return "{$this->elementVariable}->addChildren({$childCompiler->ComponentVariable()}->elements());" .  PHP_EOL ;
        } 

        (string) $addChild = "{$this->elementVariable}->addChild({$childCompiler->elementVariable()});" .  PHP_EOL ;
        $addChild .= $childCompiler->missingClosingCurlyBraceForeach ? $this->closingForeachDefinition() : '';
        $addChild .= $childCompiler->missingClosingCurlyBraceForIfStatement ? '}' : '';

        return $addChild;
    }

    protected function addToGroupOfNodesIfHasNoParent()
    {
        (boolean) $nodeIsTopLevelNode = $this->element->parent() === null;
        (boolean) $isNotBodyElement = !$this->element->is('body');

        if ($nodeIsTopLevelNode && $isNotBodyElement) {
            return "\$groupOfNodes->add($this->elementVariable);" . PHP_EOL;
        }

        return '';
    }

    protected function addDescendantNodeVariableIfNodeIsElement($childCompiler)
    {
        (string) $EOMElementCompiler = static::className();
        (string) $ComponentCompiler = ComponentCompiler::className();

        if ($childCompiler instanceof $EOMElementCompiler) {
            $this->descendantNodesVariables[] = $childCompiler->elementVariable();
        } elseif ($childCompiler instanceof $ComponentCompiler) {
            $this->descendantNodesVariables[] = $childCompiler->componentVariable();
        }
    }

    protected function wrapInsideforeachIfHasCreateForEachTask($compiledElement)
    {
        if ($this->hadForeach and !$this->ancestorElementIsInsideForeach) {
            
            (string) $compiledElementInsideForeach = PHP_EOL.
            "(integer) \$currentIndex = 1;" . PHP_EOL.
            "foreach ({$this->foreachArgument}) {".PHP_EOL.
            "\$this->variables['currentItemNumber'] = \$currentIndex;" . PHP_EOL.
            PHP_EOL . $compiledElement . PHP_EOL.
            "\$currentIndex += 1;" . PHP_EOL;
            $compiledElementInsideForeach .= $this->element->parent() === null ? $this->closingForeachDefinition() : '';

            $this->missingClosingCurlyBraceForeach = $this->element->parent() === null ? false : true;

            return $compiledElementInsideForeach;
        }
        return $compiledElement;
    }

    protected function closingForeachDefinition()
    {
        return '}' . PHP_EOL . ' $currentIndex = 1;';
    }

    protected function wrapInsideIfStatementIfHasShowifTask($compiledElement)
    {
        if ($this->hadShowIfTask) {
            (string) $showifTaskDefinition = "if ({$this->showIfArgument}) {";

            $showifTaskDefinition.= PHP_EOL . $compiledElement . PHP_EOL;
            $showifTaskDefinition.= $this->element->parent() === null ? '}' . PHP_EOL : '';
            $this->missingClosingCurlyBraceForIfStatement = $this->element->parent() === null ? false : true;

            return $showifTaskDefinition;

        }

        return $compiledElement;
    }

    protected function elementHasValidForeachTask()
    {
        return $this->element->createForEach !== null;
    }

    protected function elementHasValidShowIfTask()
    {
        return $this->element->showIf !== null;
    }

    protected function foreachArgument()
    {
        if ($this->elementIsInsideForeach) {

            list($aliasedItem, $iterableItem) = explode(' in ', $this->element->createForEach);
        
            (object) $variableResolver1 = new VariableResolver("({$iterableItem})");
            (object) $variableResolver2 = new VariableResolver("({$aliasedItem})");

            $iterableItem = $variableResolver1->compiledString();
            $aliasedItem = $variableResolver2->compiledString();
    
            $this->aliasedVariableForForeach = substr($aliasedItem, 1);

            $this->element->removeCreateForEach($this->element->createForEach);
    
            return "$iterableItem as $aliasedItem";
        }
        
    }

    protected function showIfArgument()
    {
        if ($this->hadShowIfTask) {

            (object) $variableResolver = new VariableResolver($this->element->showIf);

            $this->element->removeShowIf($this->element->showIf);

            return $variableResolver->compiledString();
        }
    }

    protected function elementHasAddContentAsTextManager()
    {
        (boolean) $elementHasAddContentAsTextAttribute = $this->element->addContentAsText != null;

        if ($elementHasAddContentAsTextAttribute) {
            $this->element->removeAddContentAsText($this->element->addContentAsText);
        }

        return $elementHasAddContentAsTextAttribute;
    }

    protected function isWritable()
    {
        return $this->element->is('body');
    }




}