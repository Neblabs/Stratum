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


(object) $element4927279 = new Element([
                'type' => 'html',
                'isVoid' => false
            ]);



(object) $element8890594 = new Element([
                'type' => 'head',
                'isVoid' => false
            ]);


(object) $element8479279 = new Element([
                'type' => 'title',
                'isVoid' => false
            ]);

$element8479279->addContent($value);

$element8890594->addChild($element8479279);

$element4927279->addChild($element8890594);

(object) $element5523803 = new Element([
                'type' => 'body',
                'isVoid' => false
            ]);


(object) $ContentComponent39071 = new Stratum\Prebuilt\Component\Content($this->variables);
$ContentComponent39071->use($stratumPartialView);

$element5523803->addChildren($ContentComponent39071->elements());

$element4927279->addChild($element5523803);

$groupOfNodes->add($element4927279);


return $groupOfNodes;
