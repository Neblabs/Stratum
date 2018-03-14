<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Counter\MYSQL\Count;
use Stratum\Extend\Finder\MYSQL\Wordpress;

Class Comments extends Wordpress
{

    public $oneToManyRelationships = [
        'commentMeta'
    ];

    public $manyToOneRelationships = [
        'posts', 'users'
    ];

    protected $fieldAliases = [
        'id' => 'comment_ID',
        'postId' => 'comment_post_ID',
        'authorName' => 'comment_author',
        'authorEmail' => 'comment_author_email',
        'authorUrl' => 'comment_author_url',
        'authorIp' => 'comment_author_ip',
        'date' => 'comment_date',
        'dateGMT' => 'comment_date_gmt',
        'content' => 'comment_content',
        'karma' => 'comment_karma',
        'numberOfLikes' => 'comment_karma',
        'approvalStatus' => 'comment_approved',
        'userAgent' => 'comment_agent',
        'type' => 'comment_type',
        'parentId' => 'comment_parent',
        'userId' => 'user_id',
        'authorId' => 'user_id',
        'meta' => 'commentMeta'
    ]; 

    protected $primaryKey = 'comment_ID';

    protected $foreignKeys = [
        'posts' => 'comment_post_id',
        'users' => 'user_id'
    ];

    public static function thatAreApproved()
    {
        (object) $comments = new Static;

        return $comments->withApprovalStatus((boolean) 'approved');
    }

    public function approved()
    {
        return $this->withApprovalStatus((boolean) 'approved');
    }

    public static function topLevelInPostWithId($postId)
    {
        (object) $comments = new Static;
        (string) $commentsOrder = get_option('comment_order');

        (string) $orderOfComments = $commentsOrder === 'asc'? 'lowestDateFirst' : 'highestDateFirst';
        (boolean) $comentsArePaginated = get_option('page_comments');
        (string) $commentsOnly = '';

        if ($comentsArePaginated) {

            (integer) $numberOfCommentsPerPage = (integer) get_option('comments_per_page');

            (integer) $previousPage = max(0, get_query_var('cpage') - 1);
            (integer) $currentPostIndex = ($previousPage) * $numberOfCommentsPerPage;

            $comments->first($numberOfCommentsPerPage)
                        ->excludeFirst($currentPostIndex);
        }
                   
        return $comments->withPostId($postId)
                       ->withApprovalStatus(1)
                       ->withParentId(0)
                       ->withType($commentsOnly)
                       ->$orderOfComments()
                       ->find();
    }

    public static function pingbacksAndTrackbacksInPostWithId($postId)
    {
        return Static::withPostId($postId)
                     ->withApprovalStatus(1)
                     ->withType('pingback')
                     ->orWithType('trackback')
                     ->find();
    }

    public static function totalNumberFirstLevelApprovedInPost($postId)
    {
        return Count::comments()
                    ->withParentId(0)
                    ->withPostId($postId)
                    ->withApprovalStatus(1)
                    ->find()
                    ->first()
                    ->count;
    }

}