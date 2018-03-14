<?php

namespace Stratum\Original\Presentation\Creator;

use Stratum\Original\Presentation\EOM\Text;
use DomText;


Class EOMTextCreator extends EOMNodeCreator
{
    public function create()
    {
        (object) $text = new Text;
        (array) $whitespacedCharacters = [' ,', ' .', ' :', ' ;'];
        (array) $nonWhitespaceCharacters = [',', '.', ':', ';'];

        $text->addContent(str_replace($whitespacedCharacters, $nonWhitespaceCharacters, htmlspecialchars($this->DOMNode->textContent)));

        return $text;
    }
}