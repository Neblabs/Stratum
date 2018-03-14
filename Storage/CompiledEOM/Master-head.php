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


(object) $element9283379 = new Element([
                'type' => 'head',
                'isVoid' => false
            ]);



(object) $element6265139 = new Element([
                'type' => 'link',
                'isVoid' => true
            ]);

$element6265139->addAttribute([
            'name' => 'href',
            'value' => "https://fonts.googleapis.com/css?family=Rubik:500"
        ]);
$element6265139->addAttribute([
            'name' => 'rel',
            'value' => "stylesheet"
        ]);


$element9283379->addChild($element6265139);

(object) $element8205269 = new Element([
                'type' => 'title',
                'isVoid' => false
            ]);


$element8205269->addContent($title);
$element9283379->addChild($element8205269);

(object) $element4720779 = new Element([
                'type' => 'style',
                'isVoid' => false
            ]);

$element4720779->addAttribute([
            'name' => 'type',
            'value' => "text/css"
        ]);

$element4720779->addContent("
        body {
            overflow: hidden;
        }

        h1 {
            text-align: center;
            width: 100%;
            display: block;
            height: 100vh;
            line-height: 100vh;
            font-family: Rubik;
            font-size: 150px;
            color: #c7c7c7;
            margin-top: 0;
        }

        span  {
            font-family: Rubik;
            font-size: 20px;
            color: #c7c7c7;
            position: absolute;
            bottom: 6%;
            left: 0;
            width: 100%;
            text-align: center;
        }
    ");
$element9283379->addChild($element4720779);

$groupOfNodes->add($element9283379);



return $groupOfNodes;
