<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Finder\MYSQL\ConcreteFinder;
use Stratum\Custom\Finder\MYSQL\PostMeta;
use Stratum\Custom\Model\MYSQL\Comment;
use Stratum\Custom\Model\MYSQL\Post;
use Stratum\Custom\Model\MYSQL\User;
use Stratum\Extend\Finder\MYSQL\MYSQL;
use Stratum\Extend\Finder\WordPress\Posts;
use Stratum\Original\Data\Creator\FinderCreator;
use Stratum\Original\Data\Creator\SingleModelOrGroupOfModelsCreator;
use Stratum\Original\Data\GroupOf;
use Stratum\Original\Data\Model;
use Stratum\Original\Data\Querier\WPQuerier;

Class PostsTest extends TestCase
{
    public function setUp()
    {
        $this->aliases = [
            'type' => 'post_type',
            'status' => 'post_status',
            'parentId' => 'post_parent',
            'authorId' => 'author',
            'passwordProtection' => 'has_password',
            'password' => 'post_password',
            'keywords' => 's'
        ];
    }
    public function test_sets_correct_id()
    {
        (array) $expectedArrayOfArguments = [
            'p' => 7
        ];

        (array) $actualArrayOfArguments = Posts::withId(7)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_type()
    {
        (array) $expectedArrayOfArguments = [
            'post_type' => 'post'
        ];

        (array) $actualArrayOfArguments = Posts::withType('post')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_status()
    {
        (array) $expectedArrayOfArguments = [
            'post_status' => 'open'
        ];

        (array) $actualArrayOfArguments = Posts::withStatus('open')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_author_id()
    {
        (array) $expectedArrayOfArguments = [
            'author' => '47'
        ];

        (array) $actualArrayOfArguments = Posts::byAuthorId(47)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_name()
    {
        (array) $expectedArrayOfArguments = [
            'name' => 'first-post'
        ];

        (array) $actualArrayOfArguments = Posts::withName('first-post')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_title()
    {
        (array) $expectedArrayOfArguments = [
            'title' => 'First Post'
        ];

        (array) $actualArrayOfArguments = Posts::withTitle('First Post')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_parent_id()
    {
        (array) $expectedArrayOfArguments = [
            'post_parent' => 1000
        ];

        /*
        *      * * * 1000 ASSERTIONS  * * *
         */

        (array) $actualArrayOfArguments = Posts::withParentId(1000)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_post_password_enabled()
    {
        (array) $expectedArrayOfArguments = [
            'has_password' => true
        ];

        (array) $actualArrayOfArguments = Posts::withPasswordProtection(true)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_post_password()
    {
        (array) $expectedArrayOfArguments = [
            'post_password' => 'Unbre@kable'
        ];

        (array) $actualArrayOfArguments = Posts::withPassword('Unbre@kable')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_posts_per_page()
    {
        (array) $expectedArrayOfArguments = [
            'posts_per_page' => 5
        ];

        (array) $actualArrayOfArguments = Posts::first(5)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_offset()
    {
        (array) $expectedArrayOfArguments = [
            'offset' => 10
        ];

        (array) $actualArrayOfArguments = Posts::excludeFirst(10)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_orderBy_descending()
    {
        (array) $expectedArrayOfArguments = [
            'orderby' => 'title',
            'order' => 'DESC'
        ];

        (array) $actualArrayOfArguments = Posts::highestTitleFirst()->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_orderBy_ascending()
    {
        (array) $expectedArrayOfArguments = [
            'orderby' => 'title',
            'order' => 'ASC'
        ];

        (array) $actualArrayOfArguments = Posts::lowestTitleFirst()->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_correct_orderBy_descending_alias()
    {
        (array) $expectedArrayOfArguments = [
            'orderby' => 'type',
            'order' => 'DESC'
        ];

        (array) $actualArrayOfArguments = Posts::highestTypeFirst()->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }


    public function test_sets_correct_orderBy_descending_alias_id()
    {
        (array) $expectedArrayOfArguments = [
            'orderby' => 'ID',
            'order' => 'DESC'
        ];

        (array) $actualArrayOfArguments = Posts::highestIdFirst()->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }


    public function test_sets_two_types()
    {
        (array) $expectedArrayOfArguments = [
            'post_type' => ['post', 'product']
        ];

        (array) $actualArrayOfArguments = Posts::withType(['post', 'product'])->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_date_year()
    {
        (array) $expectedArrayOfArguments = [
            'date_query' => [
                [
                    'year' => 2012
                ]
            ]
        ];

        (array) $actualArrayOfArguments = Posts::inDate(2012)->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_date_year_and_month()
    {
        (array) $expectedArrayOfArguments = [
            'date_query' => [
                [
                    'year' => 2012,
                    'month' => 11
                ]
            ]
        ];

        (array) $actualArrayOfArguments = Posts::inDate('11/2012')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_date_year_month_and_day()
    {
        (array) $expectedArrayOfArguments = [
            'date_query' => [
                [
                    'year' => 2012,
                    'month' => 11,
                    'day' => 31
                ]
            ]
        ];

        (array) $actualArrayOfArguments = Posts::inDate('31/11/2012')->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sets_all_correct_arguments()
    {
        (array) $expectedArrayOfArguments = [
            'offset' => 10,
            'date_query' => [
                [
                    'year' => 2012,
                    'month' => 11
                ]
            ],
            'p' => 7,
            'post_type' => 'post',
            'author' => 44,
            'post_status' => 'draft',
            'name' => 'test-post',
            'title' => 'Test Post',
            'post_parent' => 65,
            'has_password' => true,
            'post_password' => 's3cr3t',
            'posts_per_page' => 5
            
        ];

        (array) $actualArrayOfArguments = Posts::excludeFirst(10)
                                                ->inDate('11/2012')
                                                ->withId(7)
                                                ->withType('post')
                                                ->byAuthorId(44)
                                                ->withStatus('draft')
                                                ->withName('test-post')
                                                ->withTitle('Test Post')
                                                ->withParentId(65)
                                                ->withPasswordProtection(true)
                                                ->withPassword('s3cr3t')
                                                ->first(5)
                                                ->WP_QueryArguments();

        $this->assertEquals($expectedArrayOfArguments, $actualArrayOfArguments);
    }

    public function test_sends_correct_arguments_to_wp_query_only_one_post()
    {
        (array) $sets = [[
            'id' => 1, 
            'title' => 'First Title'
        ]];
        (array) $expectedArrayOfArguments = [
            'p' => 1
        ];

        (object) $WPQuerier = $this->createMock(WPQuerier::class);
        (object) $SingleModelOrGroupOfModelsCreator = $this->createMock(SingleModelOrGroupOfModelsCreator::class);

        (object) $Posts = new Posts;

        $Posts->setWPQuerier($WPQuerier);
        $Posts->setSingleModelOrGroupOfModelsCreator($SingleModelOrGroupOfModelsCreator);

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn($sets);

        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setEntityType')->with(Posts::class);
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setAliases')->with($this->aliases);
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setPrimaryKey')->with('id');
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setWhetherOnlyOneSingleModelIsMeantToBeReturned')->with(true);
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setQueryResult')->with($this->callBack(function($dataObjects) {
            (string) $data = 'Stratum\Original\Data\Data';
            (boolean) $isOnlyOneDataObject = count($dataObjects) === 1;
            (boolean) $isDataObject = $dataObjects[0] instanceOf $data;
            (boolean) $isCorrectId = $dataObjects[0]->id === 1;
            (boolean) $isCorrectTitle = $dataObjects[0]->title === 'First Title';

            return $isOnlyOneDataObject and $isDataObject and $isCorrectTitle and $isCorrectId;
        }));

        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('create')->willReturn('created');

        (string) $result = $Posts->withId(1)->find();

        $this->assertEquals('created', $result);


    }

    public function test_sends_correct_arguments_to_wp_query_for_two_post_models()
    {
        (array) $sets = [
        [
            'id' => 1, 
            'title' => 'First Title',
            'type' => 'post'
        ],
        [
            'id' => 2, 
            'title' => 'Second Title',
            'type' => 'product'
        ]
        ];
        (array) $expectedArrayOfArguments = [
            'post_type' => ['product', 'post'] 
        ];
    
        (object) $WPQuerier = $this->createMock(WPQuerier::class);
        (object) $SingleModelOrGroupOfModelsCreator = $this->createMock(SingleModelOrGroupOfModelsCreator::class);
    
        (object) $Posts = new Posts;
    
        $Posts->setWPQuerier($WPQuerier);
        $Posts->setSingleModelOrGroupOfModelsCreator($SingleModelOrGroupOfModelsCreator);
    
        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn($sets);
    
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setEntityType')->with(Posts::class);
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setAliases')->with($this->aliases);
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setPrimaryKey')->with('id');
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setWhetherOnlyOneSingleModelIsMeantToBeReturned')->with(false);
            $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setQueryResult')->with($this->callBack(function($dataObjects) {
            (string) $data = 'Stratum\Original\Data\Data';
            (boolean) $twoDataObjectsWereCreated = count($dataObjects) === 2;

            (boolean) $firstIsDataObject = $dataObjects[0] instanceOf $data;
            (boolean) $firstIsCorrectId = $dataObjects[0]->id === 1;
            (boolean) $firstIsCorrectTitle = $dataObjects[0]->title === 'First Title';
            (boolean) $firstIsCorrectType = $dataObjects[0]->type === 'post';

            (boolean) $secondIsDataObject = $dataObjects[1] instanceOf $data;
            (boolean) $secondIsCorrectId = $dataObjects[1]->id === 2;
            (boolean) $secondIsCorrectTitle = $dataObjects[1]->title === 'Second Title';
            (boolean) $secondIsCorrectType = $dataObjects[1]->type === 'product';
    
            return $twoDataObjectsWereCreated and ($firstIsDataObject and $firstIsCorrectTitle and $firstIsCorrectId and $firstIsCorrectType) and ($secondIsDataObject and $secondIsCorrectTitle and $secondIsCorrectId and $secondIsCorrectType);
        }));
    
        $SingleModelOrGroupOfModelsCreator->expects($this->once())->method('create')->willReturn('created');
    
        (string) $result = $Posts->withType(['product', 'post'])->find();
    
        $this->assertEquals('created', $result);
    
    
    }

    public function test_OneToManyRelationships()
    {
        (array) $expectedArrayOfArguments = [
            'post__in' => [1, 7]
        ];
        (object) $model1 = new Comment;
        (object) $model2 = new Comment;

        $model1->comment_post_id = 1;
        $model2->comment_post_id = 7;

        (array) $postModels = [
            $model1,
            $model2
        ];


        (object) $commentsFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onMoreThanField', 'onQuery', 'useForeignKeyFor', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->once())->method('setEntityType')->with('Stratum\\Custom\\Finder\\MYSQL\\comments');

        $finderCreator->expects($this->once())->method('create')->willReturn($commentsFinder);

        $commentsFinder->expects($this->once())->method('useForeignKeyFor')->with('posts');
        $commentsFinder->expects($this->once())->method('find')->willReturn(new GroupOf($postModels));

        $commentsFinder->expects($this->once())->method('onBuilderStart');
        $commentsFinder->expects($this->once())->method('onMoreThanField');
        $commentsFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->with(1)->orMoreComments()->inDate()->higherThan(2012)->find();
    }

    public function test_OneToManyRelationships_with_direct_fields()
    {
        (array) $expectedArrayOfArguments = [
            'post__in' => [1, 7],
            'post_type' => 'product'
        ];
        (object) $model1 = new Comment;
        (object) $model2 = new Comment;

        $model1->comment_post_id = 1;
        $model2->comment_post_id = 7;

        (array) $postModels = [
            $model1,
            $model2
        ];


        (object) $commentsFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onMoreThanField', 'onQuery', 'useForeignKeyFor', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->once())->method('setEntityType')->with('Stratum\\Custom\\Finder\\MYSQL\\comments');

        $finderCreator->expects($this->once())->method('create')->willReturn($commentsFinder);

        $commentsFinder->expects($this->once())->method('useForeignKeyFor')->with('posts');
        $commentsFinder->expects($this->once())->method('find')->willReturn(new GroupOf($postModels));

        $commentsFinder->expects($this->once())->method('onBuilderStart');
        $commentsFinder->expects($this->once())->method('onMoreThanField');
        $commentsFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->with(1)->orMoreComments()->inDate()->higherThan(2012)
                ->andPosts()
                ->withType('product')->find();
    }

    public function test_OneToManyRelationships_twice_with_direct_fields()
    {
        (array) $expectedArrayOfArguments = [
            'post__in' => [1, 7, 3, 9],
            'post_type' => 'product'
        ];
        (object) $model1 = new Comment;
        (object) $model2 = new Comment;

        $model1->comment_post_id = 1;
        $model2->comment_post_id = 7;

        (array) $postModels = [
            $model1,
            $model2
        ];

        (object) $modelsFromMeta1 = new PostMeta;
        (object) $modelsFromMeta2 = new PostMeta;

        $modelsFromMeta1->post_id = 3;
        $modelsFromMeta2->post_id = 9;

        (array) $postModelsFromMeta = [
            $modelsFromMeta1,
            $modelsFromMeta2
        ];


        (object) $commentsFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onMoreThanField', 'onEqualityField', 'onQuery', 'useForeignKeyFor', 'find'])
                                        ->getMock();

        (object) $metaFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onMoreThanField', 'onEqualityField', 'onQuery', 'useForeignKeyFor', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->exactly(2))->method('setEntityType')->withConsecutive(['Stratum\\Custom\\Finder\\MYSQL\\comments'], ['Stratum\\Custom\\Finder\\MYSQL\\meta']);

        $finderCreator->expects($this->exactly(2))->method('create')->will($this->onConsecutiveCalls($commentsFinder, $metaFinder));

        $commentsFinder->expects($this->once())->method('useForeignKeyFor')->with('posts');
        $commentsFinder->expects($this->once())->method('find')->willReturn(new GroupOf($postModels));

        $commentsFinder->expects($this->once())->method('onBuilderStart');
        $commentsFinder->expects($this->once())->method('onMoreThanField');
        $commentsFinder->expects($this->once())->method('onBuilderEnd');

        $metaFinder->expects($this->once())->method('useForeignKeyFor')->with('posts');
        $metaFinder->expects($this->once())->method('find')->willReturn(new GroupOf($postModelsFromMeta));

        $metaFinder->expects($this->once())->method('onBuilderStart');
        $metaFinder->expects($this->exactly(2))->method('onEqualityField');
        $metaFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->with(1)->orMoreComments()->inDate()->higherThan(2012)
                ->andPosts()
                ->withType('product')
                ->andPosts()->with(1)->orMoreMeta()->withKey('color')->withValue('blue')
                ->find();
    }

    public function test_ManyToOneRelationships()
    {
        (array) $expectedArrayOfArguments = [
            'author__in' => [4, 8]
        ];
        (object) $model1 = new User;
        (object) $model2 = new User;

        $model1->id = 4;
        $model2->id = 8;

        (array) $userModels = [
            $model1,
            $model2
        ];


        (object) $UsersFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onEqualityField', 'onQuery', 'selectPrimaryKeyOnly', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->once())->method('setEntityType')->with('Stratum\\Custom\\Finder\\MYSQL\\users');

        $finderCreator->expects($this->once())->method('create')->willReturn($UsersFinder);

        $UsersFinder->expects($this->once())->method('selectPrimaryKeyOnly');
        $UsersFinder->expects($this->once())->method('find')->willReturn(new GroupOf($userModels));

        $UsersFinder->expects($this->once())->method('onBuilderStart');
        $UsersFinder->expects($this->once())->method('onEqualityField');
        $UsersFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->byUsers()->withNickName('mrWordPress')->find();
    }

    public function test_ManyToOneRelationships_combinde_with_fiedls_from_post()
    {
        (array) $expectedArrayOfArguments = [
            'post_status' => 'published',
            'author__in' => [4, 8]
        ];
        (object) $model1 = new User;
        (object) $model2 = new User;

        $model1->id = 4;
        $model2->id = 8;

        (array) $userModels = [
            $model1,
            $model2
        ];


        (object) $UsersFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onEqualityField', 'onQuery', 'selectPrimaryKeyOnly', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->once())->method('setEntityType')->with('Stratum\\Custom\\Finder\\MYSQL\\users');

        $finderCreator->expects($this->once())->method('create')->willReturn($UsersFinder);

        $UsersFinder->expects($this->once())->method('selectPrimaryKeyOnly');
        $UsersFinder->expects($this->once())->method('find')->willReturn(new GroupOf($userModels));

        $UsersFinder->expects($this->once())->method('onBuilderStart');
        $UsersFinder->expects($this->once())->method('onEqualityField');
        $UsersFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->withStatus('published')
              ->andByUsers()->withNickName('mrWordPress')->find();
    }

    public function test_ManyToOneRelationships_twice()
    {
        (array) $expectedArrayOfArguments = [
            'author__in' => [4, 8, 12, 16]
        ];
        (object) $model1 = new User;
        (object) $model2 = new User;

        $model1->id = 4;
        $model2->id = 8;

        (array) $userModels = [
            $model1,
            $model2
        ];

        (object) $secondmodel1 = new User;
        (object) $secondmodel2 = new User;

        $secondmodel1->id = 12;
        $secondmodel2->id = 16;

        (array) $secondUserModels = [
            $secondmodel1,
            $secondmodel2
        ];


        (object) $UsersFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onEqualityField', 'onQuery', 'selectPrimaryKeyOnly', 'find'])
                                        ->getMock();

        (object) $secondUsersFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onEqualityField', 'onQuery', 'selectPrimaryKeyOnly', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->exactly(2))->method('setEntityType')->withConsecutive(['Stratum\\Custom\\Finder\\MYSQL\\users'], ['Stratum\\Custom\\Finder\\MYSQL\\users']);

        $finderCreator->expects($this->exactly(2))->method('create')->will($this->onConsecutiveCalls($UsersFinder, $secondUsersFinder));

        $UsersFinder->expects($this->once())->method('selectPrimaryKeyOnly');
        $UsersFinder->expects($this->once())->method('find')->willReturn(new GroupOf($userModels));

        $UsersFinder->expects($this->once())->method('onBuilderStart');
        $UsersFinder->expects($this->once())->method('onEqualityField');
        $UsersFinder->expects($this->once())->method('onBuilderEnd');

        $secondUsersFinder->expects($this->once())->method('selectPrimaryKeyOnly');
        $secondUsersFinder->expects($this->once())->method('find')->willReturn(new GroupOf($secondUserModels));

        $secondUsersFinder->expects($this->once())->method('onBuilderStart');
        $secondUsersFinder->expects($this->once())->method('onEqualityField');
        $secondUsersFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->byUsers()->withNickName('mrWordPress')
              ->andPosts()->byUsers()->withNickName('mrSpamer')
              ->find();
    }

    public function test_OneToManyRelationships_combined_with_ManyToOneRelationships()
    {
        (array) $expectedArrayOfArguments = [
            'post__in' => [1, 7], 
            'author__in' => [4, 8]

        ];
        (object) $model1 = new Comment;
        (object) $model2 = new Comment;

        $model1->comment_post_id = 1;
        $model2->comment_post_id = 7;

        (array) $postModels = [
            $model1,
            $model2
        ];

        (object) $usermodel1 = new User;
        (object) $usermodel2 = new User;

        $usermodel1->id = 4;
        $usermodel2->id = 8;

        (array) $userModels = [
            $usermodel1,
            $usermodel2
        ];


        (object) $UsersFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onEqualityField', 'onQuery', 'selectPrimaryKeyOnly', 'find'])
                                        ->getMock();


        (object) $commentsFinder = $this->getMockBuilder(ConcreteFinder::class)
                                        ->setMethods(['onBuilderStart', 'onBuilderEnd', 'onMoreThanField', 'onQuery', 'useForeignKeyFor', 'find'])
                                        ->getMock();

        (object) $finderCreator = $this->createMock(FinderCreator::class);
        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $finderCreator->expects($this->exactly(2))->method('setEntityType')->withConsecutive(['Stratum\\Custom\\Finder\\MYSQL\\comments'], ['Stratum\\Custom\\Finder\\MYSQL\\users']);

        $finderCreator->expects($this->exactly(2))->method('create')->will($this->onConsecutiveCalls($commentsFinder, $UsersFinder));

        $commentsFinder->expects($this->once())->method('useForeignKeyFor')->with('posts');
        $commentsFinder->expects($this->once())->method('find')->willReturn(new GroupOf($postModels));

        $commentsFinder->expects($this->once())->method('onBuilderStart');
        $commentsFinder->expects($this->once())->method('onMoreThanField');
        $commentsFinder->expects($this->once())->method('onBuilderEnd');

        $UsersFinder->expects($this->once())->method('selectPrimaryKeyOnly');
        $UsersFinder->expects($this->once())->method('find')->willReturn(new GroupOf($userModels));

        $UsersFinder->expects($this->once())->method('onBuilderStart');
        $UsersFinder->expects($this->once())->method('onEqualityField');
        $UsersFinder->expects($this->once())->method('onBuilderEnd');

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);
        $posts->setFinderCreator($finderCreator);

        $posts->with(1)->orMoreComments()->inDate()->higherThan(2012)
              ->andPosts()
              ->byUsers()->withNickName('mrWordPress')->find();
    }

    public function test_ManyToManyRelationships()
    {
        (array) $expectedArrayOfArguments = [
            'tax_query' => [
                [
                    'taxonomy' => 'Category',
                    'field' => 'term_id',
                    'terms' => 2
                ]
            ]
        ];

        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);

        $posts->in()->categories()->withId(2)->find();
    }

    public function test_ManyToManyRelationships_tag_alias()
    {
        (array) $expectedArrayOfArguments = [
            'tax_query' => [
                [
                    'taxonomy' => 'post_tag',
                    'field' => 'term_id',
                    'terms' => 2
                ]
            ]
        ];

        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);

        $posts->in()->tags()->withId(2)->find();
    }

    public function test_ManyToManyRelationships_combined_with_other_fields()
    {
        (array) $expectedArrayOfArguments = [
            'tax_query' => [
                [
                    'taxonomy' => 'Category',
                    'field' => 'term_id',
                    'terms' => 2
                ]
            ],
            'post_type' => 'product'
        ];

        (object) $WPQuerier = $this->createMock(WPQuerier::class);

        (object) $posts = new Posts;

        $WPQuerier->expects($this->once())->method('setQueryArguments')->with($expectedArrayOfArguments);
        $WPQuerier->expects($this->once())->method('query')->willReturn([]);

        $posts->setWPQuerier($WPQuerier);

        $posts->in()->categories()->withId(2)->andPosts()->withType('product')->find();
    }










}