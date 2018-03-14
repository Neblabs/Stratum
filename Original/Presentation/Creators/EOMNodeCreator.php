<?php

namespace Stratum\Original\Presentation\Creator;

use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\EOM\Element;
use DomNode;
use DomElement;
use DOMText;
use Domcomment;

Abstract Class EOMNodeCreator 
{
    protected $DOMNode;

    abstract function create();

    public function __construct(DomNode $DomNode)
    {
        $this->DOMNode = $DomNode;
    }

    public static function getCreatorFrom(DomNode $DomNode)
    {
        if ($DomNode instanceof DomElement) {
            return new EOMElementCreator($DomNode);
        } elseif (($DomNode instanceof DomText ) or ($DomNode instanceof Domcomment)) {
            return new EOMTextCreator($DomNode);
        }
    }

    public static function createFrom(DomNode $DomNode)
    {
        (object) $EOMNodeCreator = self::getCreatorFrom($DomNode);

        return $EOMNodeCreator->create();
    }

}