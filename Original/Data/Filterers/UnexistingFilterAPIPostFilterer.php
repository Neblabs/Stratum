<?php

namespace Stratum\Original\Data\Filterer;

Class UnexistingFilterAPIPostFilterer extends WordpressPostFilterer
{
    public function applyFilterToTitleIfExists()
    {
        return $this->data->title;
    }

    public function applyFilterToBodyIfExists()
    {
        return $this->data->post_content;
    }

    public function applyFilterToExcerptIfExists()
    {
        return $this->data->post_excerpt;
    }
}