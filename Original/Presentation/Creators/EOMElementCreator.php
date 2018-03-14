<?php

namespace Stratum\Original\Presentation\Creator;

use DomElement;
use DomNode;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Utility\StringConverter;


Class EOMElementCreator extends EOMNodeCreator
{
    protected $EOMElement;

    public function __construct(DomNode $DomNode)
    {
        $this->EOMElement = new Element([
            'type' => $DomNode->tagName,
            'isVoid' => $this->isDOMElementVoid($DomNode)
        ]);

        parent::__construct($DomNode);
    }

    public function create()
    {
        $this->setAttributes();

        $this->createChildren();

        return $this->EOMElement;
    }

    protected function createChildren()
    {
        foreach ($this->DOMNode->childNodes as $childNode) {

            (boolean) $isNotTextNode = ($childNode instanceOf \DOMText) === false;
            
            (boolean) $textNodeisNotEmpty = !empty(trim($childNode->nodeValue));

            if ($isNotTextNode or $textNodeisNotEmpty) {
                (object) $EOMElement = EOMNodeCreator::createFrom($childNode);
            
                $this->EOMElement->addChild($EOMElement);
            } 

            

        }
    }

    protected function setAttributes()
    {
        foreach ($this->DOMNode->attributes as $attribute) {
            if (strtolower($attribute->name) === 'content') {
                $this->EOMElement->addAttribute([
                    'name' => $attribute->name,
                    'value' => $attribute->value
                ]);
            } else {
                (string) $addAttribute = "add" . (new StringConverter($attribute->name))->replaceDashesWithUpperCasedLetters();
            
            $this->EOMElement->$addAttribute($attribute->value);
            }
            
        }

        
    }

    protected function isDOMElementVoid(DomElement $DomElement)
    {
        (boolean) $elementTypeIsVoidElement = in_array($DomElement->tagName, $this->HTMLVoidElements());
        (boolean) $elementHasIsVoidAttribute = $DomElement->hasAttribute('isVoid');

        return $elementTypeIsVoidElement or $elementHasIsVoidAttribute;
    }

    protected static function HTMLVoidElements()
    {
        return [   
            'area',
            'base',
            'basefont',
            'bgsound',
            'br',
            'col',
            'command',
            'embed',
            'frame',
            'hr',
            'image',
            'img',
            'input',
            'isindex',
            'keygen',
            'link',
            'menuitem',
            'meta',
            'nextid',
            'param',
            'source',
            'track',
            'wbr' 
        ];
    }



















}