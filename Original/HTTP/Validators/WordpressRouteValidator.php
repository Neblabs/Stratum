<?php

namespace Stratum\Original\HTTP\Validator;

use Stratum\Original\HTTP\Route;
use Stratum\Original\HTTP\Validator;
use Stratum\Original\HTTP\Wordpress\WordpressConditionalsResolver;

Class WordpressRouteValidator extends RouteValidator 
{

    public function __construct()
    {
        $this->WordpressConditionalsResolver = new WordpressConditionalsResolver;
    }

    public function setWordpressConditionalsResolver(WordpressConditionalsResolver $WordpressConditionalsResolver)
    {
        $this->WordpressConditionalsResolver = $WordpressConditionalsResolver;
    }

    public function validate()
    {
        if ($this->routeMatchesWordpressConditional()) {
            $this->passed();
        } else {
            $this->failed();
        }
    }

    protected function routeMatchesWordpressConditional()
    {
        if (($this->route->sitePage() === 'home') and $this->WordpressConditionalsResolver->isHome()) {
            return true;
        } elseif (($this->route->sitePage() === 'frontpage') and $this->WordpressConditionalsResolver->isFrontPage()) {
            return true;
        } elseif (($this->route->sitePage() === 'post') and $this->WordpressConditionalsResolver->isPost()) {
            return true;
        } elseif (($this->route->sitePage() === 'page') and $this->WordpressConditionalsResolver->isPage()) {
            return true;
        } elseif (($this->route->sitePage() === 'category') and $this->WordpressConditionalsResolver->isCategory()) {
            return true;
        } elseif (($this->route->sitePage() === 'tag') and $this->WordpressConditionalsResolver->isTag()) {
            return true;
        } elseif (($this->route->sitePage() === 'author') and $this->WordpressConditionalsResolver->isAuthor()) {
            return true;
        } elseif (($this->route->sitePage() === 'search') and $this->WordpressConditionalsResolver->isSearch()) {
            return true;
        } elseif (($this->route->sitePage() === 'archive') and $this->WordpressConditionalsResolver->isArchive()) {
            return true;
        } elseif (($this->route->sitePage() === 'pagination') and $this->WordpressConditionalsResolver->isPagination()) {
            return true;
        } elseif (($this->route->sitePage() === '404') and $this->WordpressConditionalsResolver->is404()) {
            return true;
        } elseif (($this->route->sitePage() === 'attachment') and $this->WordpressConditionalsResolver->isAttachment()) {
            return true;
        } elseif (($this->route->sitePage() === 'defaultview')) {
            return true;
        }

        return false;

    }
}















