<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Custom\Model\MYSQL\Post;
use Stratum\Original\Data\GroupOf;
use Stratum\Original\Establish\Established;
use Stratum\Original\Test\Data\TestClass\DataBaseSetter;

Class MYSQLPostsTest extends TestCase
{
    public static function setUpBeforeClass()
    {
          DataBaseSetter::setInitialData();
    }

    public function test_field_status_published()
    {
        (object) $posts = Posts::withStatus('publish')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(6, $posts->count());
    }

    public function test_field_status_autoDraft()
    {
        (object) $posts = Posts::withStatus('auto-draft')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_status_unknown()
    {
        (object) $posts = Posts::withStatus('unknown')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertFalse($posts->wereFound());
        $this->assertEquals(0, $posts->count());
    }

    public function test_field_type_post()
    {
        (object) $posts = Posts::withType('post')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(6, $posts->count());
    }

    public function test_field_type_page()
    {
        (object) $posts = Posts::withType('page')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_type_product()
    {
        (object) $posts = Posts::withType('product')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertFalse($posts->wereFound());
        $this->assertEquals(0, $posts->count());
    }

    public function test_field_status_published_and_type_post()
    {
        (object) $posts = Posts::withStatus('publish')->andWithType('post')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(5, $posts->count());
    }

    public function test_field_status_published_and_type_page()
    {
        (object) $posts = Posts::withStatus('publish')->andWithType('page')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_status_autoDraft_and_type_page()
    {
        (object) $posts = Posts::withStatus('auto-draft')->andWithType('page')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertFalse($posts->wereFound());
        $this->assertEquals(0, $posts->count());
    }

    public function test_field_commentStatus_open()
    {
        (object) $posts = Posts::withCommentStatus('open')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(6, $posts->count());
    }

    public function test_field_commentStatus_closed()
    {
        (object) $posts = Posts::withCommentStatus('closed')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_commentStatus_open_type_post_and_status_published()
    {
        (object) $posts = Posts::withCommentStatus('open')
                                ->withType('post')
                                ->withStatus('publish')
                                ->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(5, $posts->count());
    }

    public function test_field_type_id_exact()
    {
        (object) $post = Posts::withId(6)->find();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertTrue($post->wasFound());
        $this->assertEquals(6, $post->id);
        $this->assertEquals('Sixth Post', $post->title);
    }

    public function test_field_type_id_moreThan()
    {
        (object) $posts = Posts::withId()->higherThan(4)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    }

    public function test_field_type_id_lessThan()
    {
        (object) $posts = Posts::withId()->lowerThan(4)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    }

    public function test_field_type_id_lessThan_and_commentStatus_published()
    {
        (object) $posts = Posts::withId()->lowerThan(4)->withCommentStatus('open')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    }

    public function test_field_author_id()
    {
        (object) $posts = Posts::byAuthorId(1)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(5, $posts->count());
    }

    public function test_field_author_id_2()
    {
        (object) $posts = Posts::byAuthorId(2)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    }

    public function test_field_author_id_3333()
    {
        (object) $posts = Posts::byAuthorId(3333)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertFalse($posts->wereFound());
        $this->assertEquals(0, $posts->count());
    }

    public function test_field_author_id_and_type_post_and_status_published()
    {
        (object) $posts = Posts::byAuthorId(1)
                                 ->withType('post')
                                 ->withStatus('publish')
                                 ->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    }

    public function test_field_content()
    {
        (object) $posts = Posts::withContent(
            'Welcome to WordPress. This is your first post. Edit or delete it, then start writing!'
            )->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_title()
    {
        (object) $posts = Posts::withTitle('Hello World!')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_excerpt()
    {
        (object) $posts = Posts::withExcerpt('excerpt')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_pingStatus()
    {
        (object) $posts = Posts::withPingStatus('open')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(7, $posts->count());
    }

    public function test_field_password()
    {
        (object) $posts = Posts::withPassword('pass')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_name()
    {
        (object) $posts = Posts::withName('2-post')->orWithName('hello-world')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    }

    public function test_field_parentId()
    { 
        (object) $posts = Posts::withParentId(0)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(7, $posts->count());
    }

    public function test_field_guid()
    { 
        (object) $posts = Posts::withGuid('http://localhost/wordpress/?p=8')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    }

    public function test_field_mime_type()
    { 
        (object) $posts = Posts::withMimeType('')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(7, $posts->count());
    }

    public function test_field_comment_count()
    { 
        (object) $posts = Posts::withNumberOfComments(0)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(5, $posts->count());
    }

    public function test_one_to_many_relationship_comments()
    {   
        (object) $posts = Posts::with(1)->orMoreComments()->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    }

    public function test_one_to_many_relationship_comments_complex()
    {   
        (object) $posts = Posts::with(1)->orMoreComments()->byAuthorId(1)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());

    }

    public function test_one_to_many_relationship_comments_complex_author_id_or()
    {   
        (object) $posts = Posts::with(1)->orMoreComments()->byAuthorId(1)->orByAuthorId(0)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());

    }

    public function test_one_to_many_relationship_comments_and_extra_fields_to_posts()
    {   
        (object) $posts = Posts::byAuthorId(1)
                                 ->with(1)->orMoreComments()->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());

    }

    public function test_one_to_many_relationship_comments_complex_comment_author()
    {   
        (object) $posts = Posts::with(1)->orMoreComments()->byAuthorName('Commenter')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());

    } 

    public function test_many_to_one_relationship()
    {   
        (object) $posts = Posts::byUsers()->withName('rafark')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(5, $posts->count());

    } 

    public function test_many_to_one_relationship_with_posts_field()
    {   
        (object) $posts = Posts::byUsers()->withName('rafark')
                                 ->andPosts()->withType('post')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(4, $posts->count());
    } 

    public function test_many_to_one_relationship_with_posts_field_2()
    {   
        (object) $posts = Posts::byUsers()->withName('rafark')
                               ->andPosts()
                               ->withType('post')
                               ->withStatus('publish')
                               ->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    } 

    public function test_many_to_one_relationship_with_posts_field_2_OR()
    {   
        (object) $posts = Posts::byUsers()->withName('rafark')
                               ->orPosts()
                               ->withStatus('publish')
                               ->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(7, $posts->count());
    } 

    public function test_many_to_many_relationship_categories()
    {   
        (object) $posts = Posts::with()->categories()->withName('Uncategorized')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());
    } 

    public function test_many_to_many_relationship_tags()
    {   
        (object) $posts = Posts::with()->tags()->withName('#brandNew')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(1, $posts->count());
    } 

    public function test_many_to_many_relationship_tags_none()
    {   
        (object) $posts = Posts::with()->tags()->withName('#favorite')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertFalse($posts->wereFound());
        $this->assertEquals(0, $posts->count());
    } 

    public function test_many_to_many_relationship_categories_and_comments()
    {   
        (object) $posts = Posts::with()->categories()->withName('Uncategorized')
                               ->andPosts()->with(2)->orMoreComments()->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    } 

    public function test_one_to_many_relationship_PostMeta()
    {   
        (object) $posts = Posts::with(1)->orMoreMeta()
                                        ->withKey('_edit_last')
                                        ->withValue(1)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    }

    public function test_one_to_many_relationship_PostMeta_custom_function()
    {   
        (object) $posts = Posts::withMetaKey('_edit_last')->withValue(1)->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(2, $posts->count());
    }

    public function test_order_by_ascending()
    {   
        (object) $posts = Posts::withStatus('publish')->lowestDateFirst()->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(6, $posts->count());

        $this->assertEquals(3, $posts->first()->id);
        $this->assertEquals(4, $posts->atPosition(2)->id);
        $this->assertEquals(1, $posts->atPosition(3)->id);
        $this->assertEquals(2, $posts->atPosition(4)->id);
        $this->assertEquals(6, $posts->atPosition(5)->id);
        $this->assertEquals(8, $posts->atPosition(6)->id);
    }

    public function test_order_by_descending()
    {   
        (object) $posts = Posts::withStatus('publish')->highestDateFirst()->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(6, $posts->count());

        $this->assertEquals(8, $posts->atPosition(1)->id);
        $this->assertEquals(6, $posts->atPosition(2)->id);
        $this->assertEquals(1, $posts->atPosition(3)->id);
        $this->assertEquals(2, $posts->atPosition(4)->id);
        $this->assertEquals(3, $posts->atPosition(5)->id);
        $this->assertEquals(4, $posts->atPosition(6)->id);        
    }

    public function test_single_post_all_fields_get_filled()
    {   
        (object) $post = Posts::withId(1)->find();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertTrue($post->wasFound());
        $this->assertEquals(23, $post->numberOfFields());

        $this->assertEquals(1, $post->id);
        $this->assertEquals(1, $post->authorId);
        $this->assertEquals('2016-11-13 21:30:57', $post->date);
        $this->assertEquals('2016-11-13 21:30:57', $post->dateGMT);
        $this->assertEquals(
            'Welcome to WordPress. This is your first post. Edit or delete it, then start writing!',
            $post->content
        );
        $this->assertEquals('Hello world!', $post->title);
        $this->assertEquals('', $post->excerpt);
        $this->assertEquals('publish', $post->status);
        $this->assertEquals('open', $post->commentStatus);
        $this->assertEquals('open', $post->pingStatus);
        $this->assertEquals('', $post->password);
        $this->assertEquals('hello-world', $post->name);
        $this->assertEquals('', $post->toPing);
        $this->assertEquals('', $post->pinged);
        $this->assertEquals('2016-11-13 21:30:57', $post->editedDate);
        $this->assertEquals('2016-11-13 21:30:57', $post->editedDateGMT);
        $this->assertEquals('', $post->contentFiltered);
        $this->assertEquals(0, $post->parentId);
        $this->assertEquals('http://localhost/wordpress/?p=1', $post->guid);
        $this->assertEquals(0, $post->menuOrder);
        $this->assertEquals('post', $post->type);
        $this->assertEquals('', $post->mimeType);
        $this->assertEquals(1, $post->numberOfComments);
    }

    public function test_single_post_get_only_2_fields()
    {   
        (object) $post = Posts::selectOnly(['id', 'post_title'])->withId(1)->find();

        $this->assertInstanceOf(Post::class, $post);
        $this->assertTrue($post->wasFound());
        $this->assertEquals(2, $post->numberOfFields());

        $this->assertEquals(1, $post->id);
        $this->assertEquals('Hello world!', $post->title);
        $this->assertEquals(null, $post->authorId);
        $this->assertEquals(null, $post->date);
        $this->assertEquals(null, $post->dateGMT);
        $this->assertEquals(null, $post->content);
        $this->assertEquals(null, $post->excerpt);
        $this->assertEquals(null, $post->status);
        $this->assertEquals(null, $post->commentStatus);
        $this->assertEquals(null, $post->pingStatus);
        $this->assertEquals(null, $post->password);
        $this->assertEquals(null, $post->name);
        $this->assertEquals(null, $post->toPing);
        $this->assertEquals(null, $post->pinged);
        $this->assertEquals(null, $post->editedDate);
        $this->assertEquals(null, $post->editedDateGMT);
        $this->assertEquals(null, $post->contentFiltered);
        $this->assertEquals(null, $post->parentId);
        $this->assertEquals(null, $post->guid);
        $this->assertEquals(null, $post->menuOrder);
        $this->assertEquals(null, $post->type);
        $this->assertEquals(null, $post->mimeType);
        $this->assertEquals(null, $post->numberOfComments);
    }

    public function test_single_post_get_only_2_fields_using_many_to_many_categories()
    {   
        (object) $posts = Posts::selectOnly(['id', 'post_title'])
                                ->with()->categories()->withName('Uncategorized')->find();

        $this->assertInstanceOf(GroupOf::class, $posts);
        $this->assertTrue($posts->wereFound());
        $this->assertEquals(3, $posts->count());

        $this->assertEquals(2, $posts->first()->numberOfFields());

        $this->assertEquals(1, $posts->first()->id);
        $this->assertEquals('Hello world!', $posts->first()->title);
        $this->assertEquals(null, $posts->first()->authorId);
        $this->assertEquals(null, $posts->first()->date);
        $this->assertEquals(null, $posts->first()->dateGMT);
        $this->assertEquals(null, $posts->first()->content);
        $this->assertEquals(null, $posts->first()->excerpt);
        $this->assertEquals(null, $posts->first()->status);
        $this->assertEquals(null, $posts->first()->commentStatus);
        $this->assertEquals(null, $posts->first()->pingStatus);
        $this->assertEquals(null, $posts->first()->password);
        $this->assertEquals(null, $posts->first()->name);
        $this->assertEquals(null, $posts->first()->toPing);
        $this->assertEquals(null, $posts->first()->pinged);
        $this->assertEquals(null, $posts->first()->editedDate);
        $this->assertEquals(null, $posts->first()->editedDateGMT);
        $this->assertEquals(null, $posts->first()->contentFiltered);
        $this->assertEquals(null, $posts->first()->parentId);
        $this->assertEquals(null, $posts->first()->guid);
        $this->assertEquals(null, $posts->first()->menuOrder);
        $this->assertEquals(null, $posts->first()->type);
        $this->assertEquals(null, $posts->first()->mimeType);
        $this->assertEquals(null, $posts->first()->numberOfComments);

    }










}