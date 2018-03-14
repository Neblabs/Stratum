<?php

namespace Stratum\Original\HTTP;

use Stratum\Original\HTTP\Exception\UnsupportedMethodException;

Class WordpressRoute extends Route
{
    protected $sitePage;
    protected $postType;

    public function setSitePage($sitePage)
    {
        $this->sitePage = $sitePage;
    }

    public function setPostType($postType)
    {
        $this->postType = $postType;
    }

    public function sitePage()
    {
        return strtolower($this->sitePage);
    }

    public function postType()
    {
        return $this->postType;
    }

    public function hasPostType()
    {
        return $this->postType !== null;
    }

    public function pathdefinition()
    {
        
    }

}