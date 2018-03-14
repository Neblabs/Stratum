<?php

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\FormattersHandler;
use Stratum\Original\Presentation\Compiler\VariableResolver;
use Stratum\Original\Presentation\ElementManagersQueue;
use Stratum\Original\Presentation\Element\Adder\ElementContentAdder;
use Stratum\Original\Presentation\Writer\EOMNodeWriter;
use Stratum\Original\Presentation\Balance\Cache\Writer\ComponentCacheWriter;
use Stratum\Original\Presentation\Balance\Flusher;
use Stratum\Original\Presentation\Balance\Cache\Writer\ViewCacheWriter;

(object) $groupOfNodes = new GroupOfNodes;
(object) $elementManagersQueue = new ElementManagersQueue;


(object) $element4000630 = new Element([
                'type' => 'h1',
                'isVoid' => false
            ]);


$element4000630->addContent("S");
$groupOfNodes->add($element4000630);

(object) $element9953068 = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);


$element9953068->addContent("stratum");
$groupOfNodes->add($element9953068);



return $groupOfNodes;
