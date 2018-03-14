<?php

namespace Stratum\Original\Presentation\Compiler;

Class ComponentResolver
{
    protected $html;

    public function __construct($html)
    {
        $this->html = $html;
        $this->convertComponentDefinitionsToHTMLTags();
    }

    public function preCompiledHTML()
    {
        return $this->html;
    }

    protected function convertComponentDefinitionsToHTMLTags()
    {
        $this->html = preg_replace_callback(
            '/<<[a-zA-Z0-9]+(\s[a-zA-Z0-9]+="[a-zA-Z0-9-_.:()\s|]+")*>>/',
            [$this, 'handleSingleComponentConversion'], 
            $this->html
        );
    }

    protected function handleSingleComponentConversion(array $matches)
    {
        (string) $componentWithNoAnglebrackets = trim($matches[0], '<>');
        (string) $componentName = ucfirst(explode(' ', $componentWithNoAnglebrackets)[0]);

        (string) $attributes = $this->attributes($componentName, $matches);

        return "<stratumcomponent name=\"$componentName\"$attributes></stratumcomponent>";
    }

    protected function attributes($componentName, $matches)
    {
        (boolean) $isContentComponent = $componentName == 'Content';

        if ($isContentComponent) {
            return ' use="(stratumPartialView)"';
        }

        return count($matches) > 1 ? $matches[1] : '';
    }






}