<?php

namespace Stratum\Prebuilt\Domain;

use Stratum\Prebuilt\Domain\MetaGroup;
use Stratum\Custom\Finder\MYSQL;
use Stratum\Custom\Finder\MYSQL\Categories;
use Stratum\Custom\Finder\MYSQL\Comments;
use Stratum\Custom\Finder\MYSQL\Options;
use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Custom\Finder\MYSQL\Tags;
use Stratum\Custom\Finder\MYSQL\Users;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Domain;
use Stratum\Original\Data\Filterer\WordpressPostFilterer;
use Stratum\Original\WordPress\ThumbNailHandler;

Class Post extends Domain
{
    protected $WordpressPostFilterer;
    protected $thumbnailHandler;

    public function __construct(Data $data)
    {
        parent::__construct($data);

        $this->WordpressPostFilterer = WordpressPostFilterer::create($data);

        $this->thumbnailHandler = new ThumbNailHandler;

        $this->thumbnailHandler->setPost($this);

    }

    public function getTitle()
    {
        return $this->WordpressPostFilterer->applyFilterToTitleIfExists();
    }

    public function getContent()
    {
        return $this->WordpressPostFilterer->applyFilterToBodyIfExists();
    }

    public function getExcerpt()
    {
        return $this->WordpressPostFilterer->applyFilterToExcerptIfExists();
    }

    public function hasTitle()
    {
        return $this->title != '';
    }

    public function author()
    {
        if ($this->author == null) {
            $this->author = Users::with(1)->orMorePosts()->withId($this->id)->find()->first();
        }

        return $this->author;
    }

    public function comments()
    {
        if ($this->comments == null) {
            $this->comments = Comments::topLevelInPostWithId($this->id);
        }
        
        return $this->comments;
    }

    public function commentsPaginationLinks()
    {
        (boolean) $comentsArePaginated = get_option('page_comments');

        if (!$comentsArePaginated) { return '' ;}
        
        (integer) $totalNumberFirstLevelApprovedInPost = $this->numberOfComments;

        (integer) $totalNumberOfCommentPages = $totalNumberFirstLevelApprovedInPost / get_option('comments_per_page');
        
        return paginate_links([
            'base' => add_query_arg('cpage', '%#%'),
            'total' => ceil($totalNumberOfCommentPages),
            'current' => max(1, get_query_var('cpage')),
            'echo' => false,
            'add_fragment' => '#comments'
         ]);
    }

    public function meta()
    {
        if ($this->meta == null) {
            $this->meta = MetaGroup::from(MYSQL\PostMeta::highestValueFirst()->withPosts()->withId($this->id)->find());
        }

        return $this->meta;
    }

    public function categories()
    {
        if ($this->categories == null) {
            $this->categories = Categories::in()->posts()->withId($this->id)->find();
        }

        return $this->categories;
    }

    public function tags()
    {
        if ($this->tags == null) {
            $this->tags = Tags::in()->posts()->withId($this->id)->find();
        }

        return $this->tags;
    }

    public function url()
    {
     
        if ($this->urlHasNotBeenRequestedYet()) {
            $this->setPostUrlProperty();
        }
        return $this->data->url;
    }

    public function thumbnailUrlFor()
    {
        $this->thumbnailHandler->setState('gettingThumbnail');

        return $this->thumbnailHandler;
    }

    public function hasThumbNail()
    {
        (boolean) $thumbnailHasNotbeenChecked = empty($this->hasThumbNail);
        if ($thumbnailHasNotbeenChecked) {
            $this->hasThumbNail = has_post_thumbnail($this->id);
        } 

        return $this->hasThumbNail;
    }

    public function doesNotHaveThumbNail()
    {
        return !$this->hasThumbNail();
    }

    public function previousPost()
    {
        if ($this->previousPost == null) {
            $this->previousPost = Posts::selectOnly(['ID', 'post_author', 'post_title'])
                                       ->first(1)
                                       ->withStatus('publish')
                                       ->withType('post')
                                       ->withDate()->lowerThan($this->date)
                                       ->highestDateFirst()
                                       ->find();
        }

        return $this->previousPost;
    }

    public function nextPost()
    {
        if ($this->nextPost == null) {
            $this->nextPost = Posts::selectOnly(['ID', 'post_author', 'post_title'])
                                   ->first(1)
                                   ->withStatus('publish')
                                   ->withType('post')
                                   ->withDate()->higherThan($this->date)
                                   ->lowestDateFirst()
                                   ->find();
        }

        return $this->nextPost;
    }

    public function isPasswordProtected()
    {
        return $this->password != '';
    }

    protected function urlHasNotBeenRequestedYet()
    {
        return $this->data->url === null;
    }

    protected function setPostUrlProperty()
    {
        if (function_exists('get_permalink')) {
            $this->data->url = get_permalink($this->id);
        } else {
            $this->data->url = Options::withName('siteurl')->find()->first()->value . '/?p=' . $this->id;
        }
    }




}