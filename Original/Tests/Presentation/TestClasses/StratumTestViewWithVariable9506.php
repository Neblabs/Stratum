<?php

use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Component;
use Stratum\Original\Presentation\EOM\GroupOfNodes;
use Stratum\Original\Presentation\FormattersHandler;
use Stratum\Original\Presentation\Compiler\VariableResolver;
use Stratum\Original\Presentation\ElementManagersQueue;

(object) $groupOfNodes = new GroupOfNodes([]);
(object) $elementManagersQueue = new ElementManagersQueue;


(object) $element8051230 = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);


$element8051230->addContent("A {$state} Div!");
$groupOfNodes->add($element8051230);


return $groupOfNodes;
