<?php

namespace Stratum\Original\Data\Querier;

use WP_Query;

Class WPQuerier
{
    protected $queryArguments;

    public function setQueryArguments($queryArguments)
    {
        $this->queryArguments = $queryArguments;
    }

    public function query()
    {
        (object) $posts = $this->createWpQuery();

        return $this->arrayOfPostsArraysFrom($posts);
        
    }

    protected function createWpQuery()
    {
        
        if ($this->isCustomQuery()) {
        
            return new WP_Query($this->queryArguments);
        }

        return $GLOBALS['wp_query'];
    }

    protected function arrayOfPostsArraysFrom(WP_Query $posts)
    {
        (array) $arrayOfPosts = [];

        while ($posts->have_posts()) {

            $posts->the_post();

            (object) $post = get_post();

            $post->post_title = get_the_title();
            $post->post_content = get_the_content();
            $post->post_excerpt = get_the_excerpt();

            $arrayOfPosts[] = $post->to_array();
        }

        $this->resetPostDataIfIsCustomWpQuery();


        return $arrayOfPosts;
    }

    protected function resetPostDataIfIsCustomWpQuery()
    {
        if ($this->isCustomQuery()) {
            wp_reset_postdata();
        }
    }

    protected function isCustomQuery()
    {
        return !empty($this->queryArguments);
    }
}




