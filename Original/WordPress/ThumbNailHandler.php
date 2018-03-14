<?php

namespace Stratum\Original\WordPress;

use Stratum\Custom\Domain\Post;
use Stratum\Original\Data\GroupOf;

Class ThumbNailHandler 
{
	protected $attachment;
	protected $state;
    protected $post;

    public function setPost(Post $post)
    {
        $this->post = $post;
    }

	public function setState($state)
	{
		$this->state = $state;
	}

    protected function getThumbNailWithName($ThumbNailName)
    {
        return get_the_post_thumbnail_url($this->post->id, $ThumbNailName);
    }

	public function __call($method, $arguments)
	{
		switch ($this->state) {
			case 'checkingThumbnail':
				return $this->hasThumbNailWithName($method);
				break;
            case 'checkingThumbnailAbsence':
                return !$this->hasThumbNailWithName($method);
                break;
			case 'gettingThumbnail':
				return $this->getThumbNailWithName($method);
				break;			
			default:
				throw new \BadMethodCallException("Call to undefined method: {$method}");
				break;
		}
	}

    






}