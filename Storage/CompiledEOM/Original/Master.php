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


(object) $element9770767 = new Element([
                'type' => 'body',
                'isVoid' => false
            ]);


$element9770767->writer()->writeOpeningTag();
ViewCacheWriter::addTopLevelNodesToQueue($element9770767);
(object) $ContentComponent80407 = new Stratum\Prebuilt\Component\Content($this->variables);
$ContentComponent80407->setBindedData($stratumPartialView);
$ContentComponent80407->setBindedDataDefinition('(stratumPartialView)');


EOMNodeWriter::createFrom($ContentComponent80407)->write();
Flusher::flush();
ViewCacheWriter::addTopLevelNodesToQueue($ContentComponent80407);
ComponentCacheWriter::saveComponentsInQueue();
$element9770767->writer()->writeClosingTag();



if ($canBeCached) {
    (object) $ViewCacheWriter = new ViewCacheWriter;
    
    $ViewCacheWriter->write();
}

return $groupOfNodes;
