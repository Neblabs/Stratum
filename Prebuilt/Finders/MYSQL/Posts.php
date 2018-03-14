<?php

namespace Stratum\Prebuilt\Finder\MYSQL;

use Stratum\Extend\Finder\MYSQL;

Class Posts extends MYSQL\Posts
{    
    public $oneToManyRelationships = [
        'comments', 'PostMeta'
    ];

    public $manyToOneRelationships = [
        'users'
    ];

    public $manyToManyRelationships = [
        'categories', 'tags'
    ];

    protected $fieldAliases = [
        'id' => 'ID',
        'authorId' => 'post_author',
        'date' => 'post_date',
        'dateGMT' => 'post_date_gmt',
        'content' => 'post_content',
        'title' => 'post_title',
        'excerpt' => 'post_excerpt',
        'status' => 'post_status',
        'commentStatus' => 'comment_status',
        'pingStatus' => 'ping_status',
        'password' => 'post_password',
        'name' => 'post_name',
        'toPing' => 'to_ping',
        'editedDate' => 'post_modified',
        'editedDateGMT' => 'post_modified_gmt',
        'contentFiltered' => 'post_content_filtered',
        'parentId' => 'post_parent',
        'menuOrder' => 'menu_order',
        'type' => 'post_type',
        'mimeType' => 'post_mime_type',
        'numberOfComments' => 'comment_count',
        'meta' => 'PostMeta',    
    ];

    protected $primaryKey = 'ID';

    protected $foreignKeys = [
        'users' => 'post_author'
    ];

    public static function withMetaKey($metaKey)
    {
        (object) $posts = new Static;

        return $posts->with(1)->orMoreMeta()->withKey($metaKey);

    }

    public function published()
    {
        $this->withStatus('publish')->andWithDate()->higherThan(1);

        return $this;
    }

}



