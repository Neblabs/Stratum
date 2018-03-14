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


(object) $element6804946 = new Element([
                'type' => 'body',
                'isVoid' => false
            ]);


$element6804946->writer()->writeOpeningTag();
ViewCacheWriter::addTopLevelNodesToQueue($element6804946);
(object) $ContentComponent44366 = new Stratum\Prebuilt\Component\Content($this->variables);
$ContentComponent44366->setBindedData($stratumPartialView);
$ContentComponent44366->setBindedDataDefinition('(stratumPartialView)');


EOMNodeWriter::createFrom($ContentComponent44366)->write();
Flusher::flush();
ViewCacheWriter::addTopLevelNodesToQueue($ContentComponent44366);
ComponentCacheWriter::saveComponentsInQueue();
$element6804946->writer()->writeClosingTag();



if ($canBeCached) {
    (object) $ViewCacheWriter = new ViewCacheWriter;
    
    $ViewCacheWriter->write();
}

return $groupOfNodes;
