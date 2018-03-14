<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Finder\MYSQL\StratumTestPosts;
use Stratum\Custom\Model\MYSQL\StratumTestPost;
use Stratum\Original\Data\GroupOf;
use Stratum\Original\Establish\Established;

Class DatabaseMysqlTest extends TestCase
{


    public static function setUpBeforeClass()
    {
        (object) $database = Established::database();
        
        $pdo = new PDO("mysql:host={$database->host};dbname={$database->name}", $database->username, $database->password);

        $pdo->query('DELETE FROM test_table_posts');

        $pdo->query(
            "INSERT INTO `test_table_posts` (`id`, `title`, `type`)
                    VALUES
                        (1,'first title','product'),
                        (2,'second title','post'),
                        (3,'third title','post'),
                        (4,'fourth title','product'),
                        (5,'fifth titte','post'),
                        (6,'sixth title','product'),
                        (7,'seventh title','post'),
                        (8,'eighth title','product'),
                        (9,'ninth title','product'),
                        (10,'tenth title','product'),
                        (11,'eleventh title','post')"
        );

        (string) $TestFinder = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPost.php');
        file_put_contents('Design/Model/Models/MYSQL/StratumTestPost.php', $TestFinder);

        (string) $TestFinder = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPosts.php');
        file_put_contents('Design/Model/Finders/MYSQL/StratumTestPosts.php', $TestFinder);

        (string) $TestDomain = file_get_contents('Original/Tests/Data/TestClasses/StratumTestPostDomain.php');
        file_put_contents('Design/Model/Domain/StratumTestPost.php', $TestDomain);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Models/MYSQL/StratumTestPost.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/StratumTestPosts.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Domain/StratumTestPost.php');
    }

    public function test_returns_row_with_id_of_1()
    {
        (object) $postModel = StratumTestPosts::withId(1)->find();

        $this->assertInstanceOf(StratumTestPost::class, $postModel);

        $this->assertEquals(1, $postModel->id);
        $this->assertEquals('first title', $postModel->title);
        $this->assertEquals('product', $postModel->type);
    }

    public function test_returns_all_entities_with_type_product()
    {
        (object) $groupOfProducts = StratumTestPosts::withType('product')->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfProducts);

        $this->assertEquals(6, $groupOfProducts->count());

        foreach ($groupOfProducts as $product) {
            $this->assertEquals('product', $product->type);
        }
 
    }

    public function test_returns_all_entities_with_type_post()
    {
        (object) $groupOfPosts = StratumTestPosts::withType('post')->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfPosts);

        $this->assertEquals(5, $groupOfPosts->count());

        foreach ($groupOfPosts as $post) {
            $this->assertEquals('post', $post->type);
        }
 
    }

    public function test_returns_only_3_with_type_product()
    {
        (object) $groupOfProducts = StratumTestPosts::first(3)->withType('product')->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfProducts);

        $this->assertEquals(3, $groupOfProducts->count());

        foreach ($groupOfProducts as $product) {
            $this->assertEquals('product', $product->type);
        }
 
    }

    public function test_returns_all_entities_with_type_post_and_id_bigger_than_6()
    {
        (object) $groupOfPosts = StratumTestPosts::withType('post')
                                                    ->withId()->higherThan(6)
                                                    ->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfPosts);

        $this->assertEquals(2, $groupOfPosts->count());

        foreach ($groupOfPosts as $post) {
            $this->assertEquals('post', $post->type);
        }
 
    }

    public function test_returns_all_entities_with_type_post_and_id_less_than_6()
    {
        (object) $groupOfPosts = StratumTestPosts::withType('post')
                                                    ->withId()->lowerThan(6)
                                                    ->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfPosts);

        $this->assertEquals(3, $groupOfPosts->count());

        foreach ($groupOfPosts as $post) {
            $this->assertEquals('post', $post->type);
        }
 
    }

    public function test_returns_all_entities_with_type_post_or_id_bigger_than_6()
    {
        (object) $groupOfPosts = StratumTestPosts::withType('post')
                                                    ->orWithId()->higherThan(6)
                                                    ->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfPosts);

        $this->assertEquals(8, $groupOfPosts->count());

 
    }

    public function test_returns_all_posts_or_products()
    {
        (object) $groupOfPosts = StratumTestPosts::withType('post')->orWithType('product')->find();

        $this->assertInstanceOf(GroupOf::class, $groupOfPosts);

        $this->assertEquals(11, $groupOfPosts->count());
 
    }


















}