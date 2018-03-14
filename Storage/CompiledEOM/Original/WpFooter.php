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


(object) $element1636053 = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);


$element1636053->addText("
	{$content}
");
$groupOfNodes->add($element1636053);



return $groupOfNodes;
