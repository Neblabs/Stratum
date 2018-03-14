<?php

namespace Stratum\Original\Presentation\EOM\Finder;

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\ElementFinder;
use Stratum\Original\Presentation\EOM\Node;

Class ElementFinderByAttribute extends ElementFinder
{
    protected function nodePassesFilter(Node $node, $attribute)
    {
        extract($attribute);
        
        (string) $element = Element::className();
        (string) $hasAttribute = "has{$attribute['name']}";

        if (($node instanceof $element) and $node->$hasAttribute($attribute['value'])) {
            return true;
        }

        return false;
    }
}