<?php

namespace Stratum\Original\Presentation\EOM;

Class Text extends Node
{
    protected $content;
    
    public function is($type)
    {
        return $type === '__text__';
    }

    public function type()
    {
        return '__text__';
    }

    public function addContent($content)
    {
        $this->content = $content;
    }

    public function content()
    {
        return $this->content;
    }
}