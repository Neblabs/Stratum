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


(object) $element7788170 = new Element([
                'type' => 'strike',
                'isVoid' => false
            ]);


$element7788170->addContent($value);
$groupOfNodes->add($element7788170);


return $groupOfNodes;
