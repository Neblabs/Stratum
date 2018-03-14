<?php

namespace Stratum\Original\HTTP\Wordpress;

Class WordpressConditionalsResolver
{
    public function isHome()
    {
        return is_home() && (!is_paged());
    }

    public function isFrontPage()
    {
        return is_front_page();
    }

    public function isPost()
    {
        return is_singular('post');
    }

    public function isPage()
    {
        return is_page();
    }

    public function isPagination()
    {
        return is_paged();
    }

    public function isCategory()
    {
        return is_category();
    }

    public function isTag()
    {
        return is_tag();
    }

    public function isAuthor()
    {
        return is_author();
    }

    public function isSearch()
    {
        return is_search();
    }

    public function isArchive()
    {
        return is_archive();
    }

    public function is404()
    {
        return is_404();
    }

    public function isAttachment()
    {
        return is_attachment();
    }


}