<?php

namespace Stratum\Original\Presentation\EOM\Finder;

use Stratum\Original\Presentation\EOM\ElementFinder;
use Stratum\Original\Presentation\EOM\Node;

Class ElementFinderByType extends ElementFinder
{
    protected function nodePassesFilter(Node $node, $type)
    {
        
        if ($node->is($type)) {
            return true;
        }

        return false;
    }
}