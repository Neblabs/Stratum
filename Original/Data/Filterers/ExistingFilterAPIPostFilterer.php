<?php

namespace Stratum\Original\Data\Filterer;

Class ExistingFilterAPIPostFilterer extends WordpressPostFilterer
{
    public function applyFilterToTitleIfExists()
    {
        return apply_filters('the_title', $this->data->title);
    }

    public function applyFilterToBodyIfExists()
    {
        return apply_filters('the_content', $this->data->content);
    }

    public function applyFilterToExcerptIfExists()
    {
        return apply_filters('the_excerpt', $this->data->excerpt);
    }
}