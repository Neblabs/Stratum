<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\HTTP\Validator\WordpressRouteValidator;
use Stratum\Original\HTTP\WordpressRoute;
use Stratum\Original\HTTP\Wordpress\WordpressConditionalsResolver;

Class WordpressRouteValidatorTest extends TestCase
{
    public function test_passes_when_is_home_and_route_defined_as_home()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('home');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isHome')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_front_page_and_route_defined_as_front_page()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('frontPage');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isFrontPage')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_post_and_route_defined_as_post()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('post');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isPost')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_page_and_route_defined_as_page()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('page');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isPage')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_category_and_route_defined_as_category()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('category');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isCategory')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_tag_and_route_defined_as_tag()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('tag');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isTag')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());

    }

    public function test_passes_when_is_archive_and_route_defined_as_archive()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('archive');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isArchive')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());
    }

    public function test_passes_when_is_author_and_route_defined_as_author()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('author');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isAuthor')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());
    }

    public function test_passes_when_is_search_and_route_defined_as_search()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('search');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isSearch')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());
    }

    public function test_passes_when_is_404_and_route_defined_as_404()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('404');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('is404')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());
    }
    
    public function test_passes_when_is_attachment_and_route_defined_as_attachment()
    {
        (object) $route = new WordpressRoute;

        $route->setSitePage('attachment');

        (object) $WordpressConditionalsResolver = $this->createMock(WordpressConditionalsResolver::class);

        (object) $WordpressRouteValidator = new WordpressRouteValidator;

        $WordpressRouteValidator->setRoute($route);

        $WordpressRouteValidator->setWordpressConditionalsResolver($WordpressConditionalsResolver);

        $WordpressConditionalsResolver->expects($this->once())->method('isAttachment')->willReturn(true);

        $WordpressRouteValidator->validate();

        $this->assertTrue($WordpressRouteValidator->hasPassed());
    }
    

   







}