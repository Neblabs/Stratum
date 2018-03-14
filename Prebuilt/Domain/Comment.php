<?php

namespace Stratum\Prebuilt\Domain;

use Stratum\Custom\Finder\MYSQL\Comments;
use Stratum\Original\Data\Domain;

Class Comment extends Domain
{
    protected $replies;

    public function content()
    {
        (object) $comment = new \Wp_Comment(new \Stdclass);
        
        return apply_filters( 'comment_text',
            apply_filters('get_comment_text', $this->content, $comment, []), 
            $comment, 
            []
        );
    }

    public function authorProfileImageUrl()
    {
        (string) $emailHash = md5($this->authorEmail);
        return "https://www.gravatar.com/avatar/{$emailHash}?d=mm";
    }

    public function authorHasNoAssociatedUrl()
    {
        return $this->authorUrl == '';
    }

    public function replies()
    {
        if ($this->replies == null) {
            $this->replies =  Comments::withApprovalStatus(1)
                                  ->withParentId($this->id)
                                  ->lowestDateFirst()
                                  ->find();
        }

        return $this->replies;
    }

    public function isReply()
    {
        return ((integer) $this->parentId) !== 0;
    }

    





}