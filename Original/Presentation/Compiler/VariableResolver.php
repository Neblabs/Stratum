<?php

namespace Stratum\Original\Presentation\Compiler;

use Stratum\Original\Presentation\EOM\Element;

Class VariableResolver
{
    protected $textString;
    protected $compiledString;
    protected $formattersHandlers = [];
    protected $expressionHasFormatters = false;
    protected $formattersHandlerVariableNames = [];
    protected $parentElementCompiler;

    public function __construct($string, Compiler $Compiler = null)
    {
        $this->textString = $string;
        $this->parentElementCompiler = $Compiler;
        $this->parseAndCompileVariable();
    }

    public function expressionHasFormatters()
    {
        return $this->expressionHasFormatters;
    }
    
    public function formattersHandlers()
    {
        return $this->formattersHandlers;
    }

    public function compiledString()
    {
        return $this->compiledString;
    }

    public function formattersHandlerVariable()
    {
        return $this->formattersHandlerVariableName;
    }

    protected function parseAndCompileVariable()
    {
        (string) $alphanumericInsideParenthesis = '([A-Za-z0-9]+)';
        (string) $anyNumberOfAlphanumericSeparatedByADotOrSemicolon = '([a-zA-Z0-9]+((\.|:)[a-zA-Z0-9_]+))+';
        (string) $anyNumberOfAlphanumericWithAParenthesisSeparatedByADotOrSemicolon = 
                                        '([a-zA-Z0-9]+((\.|:){1}([a-zA-Z0-9]+\(\)|[a-zA-Z0-9_]+))+)+';
        (string) $oneSpacePlusOneOrMoreAlphanumericSeparatedByAPipe = '(\s\|(\s[a-zA-Z0-9]+))*';

        $this->compiledString =  preg_replace_callback(
            "/\(".
            "($alphanumericInsideParenthesis|".
            "$anyNumberOfAlphanumericSeparatedByADotOrSemicolon|".
            "$anyNumberOfAlphanumericWithAParenthesisSeparatedByADotOrSemicolon)".
            "$oneSpacePlusOneOrMoreAlphanumericSeparatedByAPipe".
            "\)/", [$this, 'createPHPStringFrom'], $this->textString);

        $this->compiledString = str_replace('"', '\\"', $this->compiledString);

        $this->wrapCompiledStringInDoubleQuotesIfTextStringContainsText();
    }

    protected function wrapCompiledStringInDoubleQuotesIfTextStringContainsText()
    {
        if (!$this->isVariableWithNoText()) {
            $this->compiledString = '"' . $this->compiledString . '"';
        }
    }

    protected function createPHPStringFrom(array $matches)
    {      
        (string) $matchedVariable =  $this->removeParenthesisFrom($matches[0]);
                  
        return $this->directPropertyOrFormattersHandlerFor($matchedVariable);
    }

    protected function removeParenthesisFrom($matchedVariable)
    {
        return substr($matchedVariable, 1, strlen($matchedVariable) - 2);
    }

    protected function directPropertyOrFormattersHandlerFor($matchedVariable)
    {
        if ($this->matchedVariableHasFormatters($matchedVariable)) {

            $this->expressionHasFormatters = true;

            (array) $variableExpressionAndFormatters = explode('|', $matchedVariable);

            (string) $variableExpression = $this->matchedExpressionAsIsOrWithArraySyntaxIfArray(trim($variableExpressionAndFormatters[0]));
            (array) $formatters = array_slice($variableExpressionAndFormatters, 1);

            (string) $formattersHandlerVariableName = '$formattersHandler' . random_int(1000, 9999);

            $this->formattersHandlers[] =  
                   "(object) {$formattersHandlerVariableName} = new FormattersHandler(\$$variableExpression);" . PHP_EOL . 
                   "{$formattersHandlerVariableName}->setFormatterNames({$this->compiledArrayOfFormatters($formatters)});" .PHP_EOL;
            $this->formattersHandlerVariableName[] = $formattersHandlerVariableName;
            return "{{$formattersHandlerVariableName}->formatText()}";

        }

        if ($this->isVariableWithNoText()) {
            return '$' . $this->matchedExpressionAsIsOrWithArraySyntaxIfArray($matchedVariable);
        }

        return '{' . '$' . $this->matchedExpressionAsIsOrWithArraySyntaxIfArray($matchedVariable) . '}';
    }

    protected function isVariableWithNoText()
    {
        return preg_match('/^\([a-zA-Z0-9_:.()|]+\)$/', $this->textString);
    }

    protected function aliasedVariableIsTheSameAsCurrentVariable($variable)
    {
        (string) $variableWithMemberAccess = explode('.', $variable)[0];
        (string) $variableWithMemberAccessPHP = explode('->', $variable)[0];
        (string) $variableWithArrayAccess = explode(':', $variable)[0];

        return ($this->parentElementCompiler->aliasedVariableForForeach() === $variableWithMemberAccess) or 
               ($this->parentElementCompiler->aliasedVariableForForeach() === $variableWithArrayAccess) or
               ($this->parentElementCompiler->aliasedVariableForForeach() === $variableWithMemberAccessPHP) or
               $this->variableWithArrayAccessPHP($variable);
    }

    protected function variableWithArrayAccessPHP($variable)
    {
        if ($this->parentElementCompiler === null or $this->parentElementCompiler->aliasedVariableForForeach() === null) {
            return false;
        }

        return preg_match('/^' . $this->parentElementCompiler->aliasedVariableForForeach() . '+.+/', $variable);
    }


    protected function matchedVariableHasFormatters($matchedVariable)
    {
        (boolean) $expressionIsSeparatedByOneOrMorePipes = count(explode('|', $matchedVariable)) > 1;

        return $expressionIsSeparatedByOneOrMorePipes;
    }

    

    protected function matchedExpressionAsIsOrWithArraySyntaxIfArray($matchedVariable)
    {
        $matchedVariable = str_replace('.', '->', $matchedVariable);

        return preg_replace_callback('/:[a-zA-Z0-9_]+/', function($matches){
            (string) $matchedArrayKey = ltrim($matches[0], ':');
            $matchedArrayKey = preg_match('/[0-9]+/', $matchedArrayKey) ? $matchedArrayKey : "'$matchedArrayKey'";
            return "[$matchedArrayKey]";
        }, $matchedVariable);
    }

    protected function compiledArrayOfFormatters(array $formatters)
    {
        $arraySyntax = '[';

        foreach ($formatters as $formatter) {
            $arraySyntax.= "'" . trim($formatter) . "'" . ', ';
        }

        return trim($arraySyntax, ', ') . ']';
    }

    


}