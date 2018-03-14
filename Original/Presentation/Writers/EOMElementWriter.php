<?php

namespace Stratum\Original\Presentation\Writer;

use Stratum\Original\Presentation\EOM\Element;

Abstract Class EOMElementWriter extends EOMNodeWriter
{
    public static function createElementWriterFrom(Element $Element)
    {
        if ($Element->isVoid()) {
            return new EOMVoidElementWriter($Element);
        } else {
            return new EOMNonVoidElementWriter($Element);
        }    
    }

    protected function elementAttributes()
    {
        (string) $attributes = ' ';

        foreach ($this->node->attributes() as $attributeName => $attributeValue) {
            $attributes.= "$attributeName=\"" . $this->escapedAttribute($attributeValue) . '" ';
        }

        return rtrim($attributes);
    }

    protected function escapedAttribute($attributeValue)
    {
        return htmlspecialchars($attributeValue);
    }


}