<?php

namespace Stratum\Prebuilt\Domain;

use Stratum\Prebuilt\Domain\MetaGroup as MetaGroupDomain;
use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Custom\Finder\MYSQL\UserMeta;
use Stratum\Custom\Formatter\CharacterSafetyFormatter;
use Stratum\Extend\Counter\MYSQL\Count;
use Stratum\Original\Data\Domain;

Class User extends Domain
{
    protected $meta;
    
    public function __get($property)
    {
        (object) $safetyFormatter = new CharacterSafetyFormatter(parent::__get($property));
        
        return $safetyFormatter->escaped();
    }
    public function getName()
    {
        if ($this->data->name !== null) {
            return $this->data->name;
        }

        return 'User';
    }

    public function posts()
    {
        if ($this->posts == null) {
            $this->posts = Posts::byUsers()->withId($this->id)->find();
        }

        return $this->posts;
    }

    public function url()
    {
        if ($this->urlHasNotBeenRequestedYet()) {
            $this->setAuthorUrlProperty();
        }
        return $this->data->url;
    }

    public function profileImageUrl()
    {
        if ($this->profileImageUrl == null) {
            $this->profileImageUrl = get_avatar_url($this->email);
        }

        return $this->profileImageUrl;
    }

    public function meta()
    {
        if ($this->meta == null) {
            $this->meta = MetaGroupDomain::from(UserMeta::withUserId($this->id)->find());
        }

        return $this->meta;
    }

    public function cachedNumberOfPosts()
    {
        if ($this->cacheNumberOfPosts == null) {
            $this->cacheNumberOfPosts = get_user_meta($this->id, 'corebox-number-of-posts', true);
        }

        return $this->cacheNumberOfPosts;
    }

    public function numberOfPosts()
    {
        if ($this->numberOfPosts == null) {
            $this->numberOfPosts = Count::posts()
                                        ->withAuthorId($this->id)
                                        ->withType('post')
                                        ->withStatus('publish')
                                        ->withDate()->higherThan(0)
                                        ->find()
                                        ->first()
                                        ->count;
        }

        return $this->numberOfPosts;
    }

    protected function urlHasNotBeenRequestedYet()
    {
        return $this->data->url === null;
    }

    protected function setAuthorUrlProperty()
    {
        $this->data->url = get_author_posts_url($this->id);
    }
}