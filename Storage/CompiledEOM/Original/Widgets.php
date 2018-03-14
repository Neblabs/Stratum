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


(integer) $currentIndex = 1;
foreach ($widgets as $widget) {
$this->variables['currentItemNumber'] = $currentIndex;


(object) $element1256060 = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);

$element1256060->addAttribute([
            'name' => 'class',
            'value' => "widget-container"
        ]);

(object) $CustomOrWordpressWidgetComponent14125 = new Stratum\Custom\Component\CustomOrWordpressWidget($this->variables);
$CustomOrWordpressWidgetComponent14125->setBindedData($widget);
$CustomOrWordpressWidgetComponent14125->setBindedDataDefinition('(widget)');

$element1256060->addChildren($CustomOrWordpressWidgetComponent14125->elements());

$groupOfNodes->add($element1256060);

$currentIndex += 1;
}
 $currentIndex = 1;


return $groupOfNodes;
