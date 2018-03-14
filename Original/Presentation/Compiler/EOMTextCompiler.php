<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Presentation\Compiler\VariableResolver;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Utility\ClassUtility\ClassName;


Class EOMTextCompiler extends Compiler
{
    protected $Text;
    protected $VariableResolver;

    use ClassName;

    public function __construct(Text $Text)
    {
        $this->Text = $Text;
    }

    public function compiledNode()
    {
        return $this->VariableResolver()->compiledString();
    }

    public function textVariable()
    {
        return $this->textVariable;
    }

    public function VariableResolver()
    {
        if ($this->VariableResolver === null) {
            $this->VariableResolver = new VariableResolver($this->Text->content(), $this);
        }

        return $this->VariableResolver;
    }

    public function compiledType()
    {
        (string) $this->textVariable = $this->generateVariableName();
        (string) $textInstantiation = "{$this->textVariable} = new Text;";
        (string) $formattersHandlers = $this->setFormattersHandlerIfVariableDefinitionHasFormatters($this->variableResolver());
        (string) $textAddContentDefinition = "{$this->textVariable}->addContent({$this->VariableResolver()->compiledString()});";
        (string) $addToGroupDefinition = "\$groupOfNodes->add({$this->textVariable});";

        return $textInstantiation . PHP_EOL .
               $formattersHandlers . PHP_EOL .
               $textAddContentDefinition . PHP_EOL .
               $addToGroupDefinition . PHP_EOL;
    }

    protected function generateVariableName()
    {
        return '$text' . random_int(10000, 99999);
    }

    protected function setFormattersHandlerIfVariableDefinitionHasFormatters($variableResolver)
    {
        if ($variableResolver->expressionHasFormatters()) {
            return PHP_EOL . implode(' ', $variableResolver->formattersHandlers()) . PHP_EOL;
        }

        return '';
    }
}