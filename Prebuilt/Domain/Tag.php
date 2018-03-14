<?php

namespace Stratum\Prebuilt\Domain;

use Stratum\Original\Data\Domain;

Class Tag extends Domain
{
    public function url()
    {
        return get_tag_link($this->id);
    }
}