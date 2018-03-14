<?php 

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Finder\MYSQL\AliasedFinder;
use Stratum\Custom\Finder\MYSQL\Categories;
use Stratum\Custom\Finder\MYSQL\CommentsFinderTest;
use Stratum\Custom\Finder\MYSQL\ConcreteFinder;
use Stratum\Custom\Finder\MYSQL\CustomPrimaryKey;
use Stratum\Custom\Finder\MYSQL\FieldAliasesFinder;
use Stratum\Custom\Finder\MYSQL\Posts;
use Stratum\Custom\Finder\MYSQL\Tags;
use Stratum\Extend\Counter\MYSQL\Count;
use Stratum\Extend\Finder\MYSQL\MYSQL;
use Stratum\Original\Data\Creator\SingleModelOrGroupOfModelsCreator;
use Stratum\Original\Data\DatabaseQuerier;

Class MYSQLTest extends TestCase
{
    protected $ConcreteFinder;
    protected $DatabaseQuerier;

    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Extend/Tests/TestClasses/ConcreteFinder.php');
        file_put_contents('Design/Model/Finders/MYSQL/ConcreteFinder.php', $TestFinder);

        (string) $TestFinder = file_get_contents('Extend/Tests/TestClasses/AuthorsFinder.php');
        file_put_contents('Design/Model/Finders/MYSQL/AuthorsFinder.php', $TestFinder);

        (string) $AliasedFinder = file_get_contents('Extend/Tests/TestClasses/AliasedFinder.php');
        file_put_contents('Design/Model/Finders/MYSQL/AliasedFinder.php', $AliasedFinder);

        (string) $CustomPrimaryKey = file_get_contents('Extend/Tests/TestClasses/CustomPrimaryKey.php');
        file_put_contents('Design/Model/Finders/MYSQL/CustomPrimaryKey.php', $CustomPrimaryKey);

        (string) $FieldAliasesFinder = file_get_contents('Extend/Tests/TestClasses/FieldAliasesFinder.php');
        file_put_contents('Design/Model/Finders/MYSQL/FieldAliasesFinder.php', $FieldAliasesFinder);

        (string) $CommentsFinderTest = file_get_contents('Extend/Tests/TestClasses/CommentsFinderTest.php');
        file_put_contents('Design/Model/Finders/MYSQL/CommentsFinderTest.php', $CommentsFinderTest);
    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/ConcreteFinder.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/AliasedFinder.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/CustomPrimaryKey.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/FieldAliasesFinder.php');
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Model/Finders/MYSQL/CommentsFinderTest.php');
    }

    public function setUp()
    {
        $this->ConcreteFinder = new ConcreteFinder;
        $this->AliasedFinder = new AliasedFinder;
        $this->CustomPrimaryKey = new CustomPrimaryKey;
        $this->FieldAliasesFinder = new FieldAliasesFinder;
        $this->CommentsFinderTest = new \Stratum\Custom\Finder\MYSQL\CommentsFinderTest;


        $this->DatabaseQuerier = $this->createMock(DatabaseQuerier::class);
        $this->SingleModelOrGroupOfModelsCreator = $this->createMock(SingleModelOrGroupOfModelsCreator::class);


        $this->ConcreteFinder->setDatabaseQuerier($this->DatabaseQuerier);
        $this->AliasedFinder->setDatabaseQuerier($this->DatabaseQuerier);
        $this->CustomPrimaryKey->setDatabaseQuerier($this->DatabaseQuerier);
        $this->FieldAliasesFinder->setDatabaseQuerier($this->DatabaseQuerier);
        $this->CommentsFinderTest->setDatabaseQuerier($this->DatabaseQuerier);

        $this->ConcreteFinder->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);
        $this->AliasedFinder->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);
        $this->CustomPrimaryKey->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);
        $this->FieldAliasesFinder->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);
        $this->CommentsFinderTest->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->ConcreteFinderAliases = [
            'name' => 'finder_name',
            'comments' => 'commentsFinderTest',
            'authors' => 'authorsFinder'
        ];

        $this->primaryKey = 'id';


    }

     public function test_builds_correct_sql_single_equality_field()
     {
         $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id = ? ');
     
         $this->expectedSQLParameters([9]);

         $this->expectedPrimaryKey($this->primaryKey);

         $this->expectedAliases($this->ConcreteFinderAliases);
     
         $this->ConcreteFinder->withId(9)->find();
     
     }
     
    public function test_uses_aliased_name()
    {

        $this->expectedSQL('SELECT * FROM comments WHERE id = ? ');
    
        $this->expectedSQLParameters([9]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases([]);
    
        $this->AliasedFinder->withId(9)->find();
    
    }

    public function test_uses_fieldAliases()
    {
    
        $this->FieldAliasesFinder->setDatabaseQuerier($this->DatabaseQuerier);
    
        $this->expectedSQL('SELECT * FROM FieldAliasesFinder WHERE post_title = ? ');
        $this->expectedSQLParameters([9]);

        $this->expectedAliases([
            'title' => 'post_title'
        ]);

        $this->expectedPrimaryKey($this->primaryKey);
    
        $this->FieldAliasesFinder->withTitle(9)->find();
    }
    
    public function test_uses_custom_primary_key()
    {
    
        $this->CustomPrimaryKey->setDatabaseQuerier($this->DatabaseQuerier);
    
        $this->expectedSQL('SELECT * FROM CustomPrimaryKey WHERE comment_id = ? ');

        $this->expectedAliases([
            'id' => 'comment_id'
        ]);

        $this->expectedPrimaryKey('comment_id');
    
        $this->CustomPrimaryKey->withId(9)->find();
    }


    
    public function test_selects_only_one_column()
    {
        (string) $sql = ConcreteFinder::selectOnly(['id'])->withId(9)->sqlQuery();
    
        $this->assertEquals('SELECT ConcreteFinder.id FROM ConcreteFinder WHERE id = ? ', $sql);
    }
    
    public function test_selects_only_10()
    {
    
        $this->expectedSQL('SELECT * FROM ConcreteFinder LIMIT ? ');
    
        $this->expectedSQLParameters([10]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->first(10)->find();
    
    }
    
    public function test_skips_the_first_10()
    {
    
        $this->expectedSQL('SELECT * FROM ConcreteFinder LIMIT ? OFFSET ? ');
    
        $this->expectedSQLParameters([10, 10]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->first(10)->excludeFirst(10)->find();
    
    }
    
    public function test_skips_the_first_10_with_condition()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE type = ? LIMIT ? OFFSET ? ');
    
        $this->expectedSQLParameters(['post', 10, 20]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->first(10)->withType('post')->excludeFirst(20)->find();
    
    
    }
    
    public function test_selects_two_columns()
    {
        (string) $sql = ConcreteFinder::selectOnly(['title', 'body'])->withId(9)->sqlQuery();
    
        $this->assertEquals('SELECT ConcreteFinder.title, ConcreteFinder.body FROM ConcreteFinder WHERE id = ? ', $sql);
    }
    
    public function test_equality_field_twice_with_with_AND()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id = ? AND title = ? ');
    
        $this->expectedSQLParameters([9, 'Welcome Post']);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
        
        $this->ConcreteFinder->withId(9)->andWithTitle('Welcome Post')->find();
    
    }
    
    public function test_equality_field_twice_with_with_OR()
    {
    
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id = ? OR title = ? ');
    
        $this->expectedSQLParameters([9, 'Welcome Post']);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId(9)->orWithTitle('Welcome Post')->find();
    
    
    }
    
    public function test_single_moreThan_field()
    {
    
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id > ? ');
    
        $this->expectedSQLParameters([10]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId()->higherThan(10)->find();
    
    }
    
    public function test_single_lessThan_field()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id < ? ');
    
        $this->expectedSQLParameters([10]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId()->lowerThan(10)->find();
    }
    
    public function test_single_moreThan_field_combined_with_lessThan()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id > ? AND date < ? ');
    
        $this->expectedSQLParameters([10, 2020]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId()->higherThan(10)
                                        ->withDate()->lowerThan(2020)
                                        ->find();
    
    }
    
    public function test_single_moreThan_field_combined_with_lessThan_OR()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id > ? OR date < ? ');
    
        $this->expectedSQLParameters([10, 2020]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId()->higherThan(10)
                                        ->orWithDate()->lowerThan(2020)
                                        ->find();
    
    }
    
    public function test_single_entity()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id < ? ');
    
        $this->expectedSQLParameters([10]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withId()->lowerThan(10)->find();
    
    }
    
    public function test_equality_and_moreOrLessThan_combined()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE title = ? AND date > ? OR type = ? AND authorId < ? ');
    
        $this->expectedSQLParameters(['a title', 2010, 'Link', 100]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withTitle('a title')
                                        ->andWithDate()->higherThan(2010)
                                        ->orWithType('Link')
                                        ->byAuthorId()->lowerThan(100)
                                        ->find();
    
    }
    
    public function test_oneToMany_relationship_equality()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) ');
    
        $this->expectedSQLParameters([5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->Comments()->find();

    
    }
    
    public function test_oneToMany_relationship_foreign_key_singular()
    {
        $this->expectedSQL('SELECT * FROM products WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) ');
    
        $this->expectedSQLParameters([5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->alias = 'products';
    
        $this->ConcreteFinder->with(5)->Comments()->find();
    
    }
    
        public function test_oneToMany_relationship_lessThan()
        {
            $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                            .'FROM Comments '
                                                            .'GROUP BY concreteFinder_id '
                                                            .'HAVING count(*) <= ?) ');
//
            $this->expectedSQLParameters([5]);

            $this->expectedPrimaryKey($this->primaryKey);

            $this->expectedAliases($this->ConcreteFinderAliases);
//
            $this->ConcreteFinder->with(5)->orLessComments()
                                                   ->find();
//
//
        }
//
    public function test_oneToMany_relationship_moreThan()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) >= ?) ');
    
        $this->expectedSQLParameters([5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->orMoreComments()->find();
    }
    
    public function test_oneToMany_relationship_with_conditions_from_the_related_entity()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) ');
    
        $this->expectedSQLParameters([65, 2020, 5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->Comments()
                                               ->byAuthor(65)
                                               ->inDate(2020)
                                               ->find();
    
    }
    
    public function test_oneToMany_relationship_with_conditions_from_the_related_entity_moreThan()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) >= ?) ');
    
        $this->expectedSQLParameters([65, 2020, 5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->orMoreComments()
                                               ->byAuthor(65)
                                               ->inDate(2020)
                                               ->find();
    
    }
    
    public function test_oneToMany_relationship_with_conditions_from_the_related_entity_lessThan()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) <= ?) ');
    
        $this->expectedSQLParameters([65, 2020, 5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->orLessComments()
                                               ->byAuthor(65)
                                               ->inDate(2020)
                                               ->find();
    
    
    }
    
    public function test_oneToMany_relationship_with_conditions_and_with_one_condition_to_the_main_entity()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) '
                                            .'AND date = ? ');
    
        $this->expectedSQLParameters([65, 2020, 5, 2030]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->Comments()
                                               ->byAuthor(65)
                                               ->inDate(2020)
                                               ->andConcreteFinder()
                                               ->inDate(2030)
                                               ->find();
    
    }
    
    public function test_oneToMany_relationship_with_conditions_and_with_one_condition_to_the_main_entity_OR()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) '
                                            .'OR date = ? ');
    
        $this->expectedSQLParameters([65, 2020, 5, 2030]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->Comments()
                                               ->byAuthor(65)
                                               ->inDate(2020)
                                               ->orConcreteFinder()
                                               ->inDate(2030)
                                               ->find();
    
    
    }

    public function test_uses_custom_foreign_key()
    {
        
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) ');
    
        $this->expectedSQLParameters([5]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->CommentsFinderTest()->find();

    }
    
    public function test_oneToMany_relationship_alternated()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE type = ? AND id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) '
                                            .'OR date > ? '
                                            .'OR id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) <= ?) ');
    
        $this->expectedSQLParameters(['post', 65, 2020, 5, 2030, 1]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withType('post')->andWith(5)->Comments()
                                                    ->byAuthor(65)
                                                    ->inDate(2020)
                                               ->orConcreteFinder()
                                               ->inDate()->higherThan(2030)
                                               ->orWith(1)->orLessComments()
                                               ->find();
    
    
    }
    
    public function test_oneToMany_relationship_alternated_reversed()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE author = ? '
                                                        .'AND date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) = ?) '
                                                        .'OR type = ? '
                                            .'AND id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) <= ?) '
                                                        .'AND date > ? ');
    
        $this->expectedSQLParameters([65, 2020, 5, 'post', 4, 2020]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->with(5)->Comments()->byAuthor(65)
                                                    ->inDate(2020)
                                               ->orConcreteFinder()
                                               ->withType('post')
                                               ->andWith(4)->orLessComments()
                                               ->andConcreteFinder()
                                               ->inDate()->higherThan(2020)
                                               ->find();
    
    }
    
    public function test_manyToOne_relationship()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE author_id IN (SELECT id '
                                                        .'FROM Authors '
                                                        .'WHERE role = ? OR date > ? ) ');
    
        $this->expectedSQLParameters(['Admin', 2020]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->byAuthors()->withRole('Admin')->orInDate()->higherThan(2020)
                                               ->find();
    
    }
    
    public function test_manyToOne_relationship_mixed_with_conditions_in_the_main_entity()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE type = ? '
                                                        .'AND author_id IN (SELECT id '
                                                        .'FROM Authors '
                                                        .'WHERE role = ? OR date > ? ) '
                                                        .'AND date = ? ');
    
        $this->expectedSQLParameters(['post','Admin', 2020, 2030]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        $this->ConcreteFinder->withType('post')
                                        ->byAuthors()->withRole('Admin')->orInDate()->higherThan(2020)
                                        ->andConcreteFinder()
                                        ->inDate(2030)
                                        ->find();
    
    }
    
    public function test_manyToOne_relationship_mixed_oneToMany_and_with_conditions_in_the_main_entity()
    {
        $this->expectedSQL('SELECT * FROM ConcreteFinder WHERE '
                                                        .'author_id IN (SELECT id '
                                                        .'FROM Authors '
                                                        .'WHERE role = ? OR date > ? ) '
                                                        .'AND id IN (SELECT concreteFinder_id '
                                                        .'FROM Comments '
                                                        .'WHERE date = ? '
                                                        .'GROUP BY concreteFinder_id '
                                                        .'HAVING count(*) >= ?) '
                                                        .'OR type = ? LIMIT ? OFFSET ? ');
    
        $this->expectedSQLParameters(['Admin', 2020, 2030, 2, 'post', 10, 20]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases($this->ConcreteFinderAliases);
    
        (string) $sql = $this->ConcreteFinder->first(10)->byAuthors()->withRole('Admin')->orInDate()->higherThan(2020)
                                        ->andConcreteFinder()
                                        ->with(2)->orMoreComments()->inDate(2030)
                                        ->orConcreteFinder()
                                        ->withType('post')
                                        ->excludeFirst(20)
                                               ->find();
        
        
    }
    
    protected function expectedSQL($query)
    {
        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );
    }
    
    protected function expectedSQLParameters(array $parameters)
    {
        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );
    }

    protected function expectedAliases(array $aliases)
    {
        $this->SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setAliases')->with($aliases);
    }

    protected function expectedPrimaryKey($primaryKey)
    {
        $this->SingleModelOrGroupOfModelsCreator->expects($this->once())->method('setPrimaryKey')->with($primaryKey);
    }

    public function test_ManyToManyRelationships_with_posts()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE (taxonomy = 'Category' AND terms.name = ?) ";

        (array) $parameters = [
            'news'
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->in()->categories()->withName('news')->find();
    }

    public function test_ManyToManyRelationships_with_posts_and_taxonomy_aliases()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE (taxonomy = 'post_tag' AND terms.name = ?) ";

        (array) $parameters = [
            '#favorite'
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->in()->tags()->withName('#favorite')->find();
    }

    public function test_ManyToManyRelationships_with_posts_and_term_id()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE (taxonomy = 'Category' AND terms.term_id = ?) ";

        (array) $parameters = [
            6
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->in()->categories()->withId(6)->find();
    }

    public function test_ManyToManyRelationships_with_posts_and_slug()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE (taxonomy = 'Category' AND terms.slug = ?) ";

        (array) $parameters = [
            'food-and-drinks'
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->in()->categories()->withSlug('food-and-drinks')->find();
    }


    public function test_ManyToManyRelationships_with_posts_combined_with_other_fields()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE (taxonomy = 'Category' AND terms.name = ?) "
                         ."AND post_date = ? "
                         ."OR post_author = ? ";

        (array) $parameters = [
            'news', 2012, 9
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->in()->categories()->withName('news')
              ->andPosts()->inDate(2012)->orByAuthorId(9)->find();
    }

    public function test_ManyToManyRelationships_with_posts_combined_with_other_fields_different_postitions()
    {
        (string) $query = "SELECT wp_posts.* FROM wp_posts " 
                         ."JOIN wp_term_relationships AS term_relationships ON wp_posts.id = term_relationships.object_id "
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_terms AS terms ON taxonomy.term_id = terms.term_id " 
                         ." WHERE post_date = ? "
                         ."AND (taxonomy = 'Category' AND terms.name = ?) "
                         ."OR post_author = ? ";

        (array) $parameters = [
             2012, 'news', 9
        ];
        (object) $posts = new Posts;

        $posts->setDatabaseQuerier($this->DatabaseQuerier);

        $posts->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $posts->inDate(2012)
              ->andPosts()->in()->categories()->withName('news')
              ->orPosts()->byAuthorId(9)->find();
    }

    public function test_ManyToManyRelationships_with_taxonomies_to_posts()
    {
        (string) $query = "SELECT terms.* FROM wp_terms AS terms " 
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_id = terms.term_id "
                         ."JOIN wp_term_relationships AS term_relationships ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_posts AS posts ON posts.id = term_relationships.object_id  " 
                         ."WHERE taxonomy.taxonomy = 'Category' AND posts.id = ? ";
                                               
        (array) $parameters = [
            6
        ];
        (object) $categories = new Categories;

        $categories->setDatabaseQuerier($this->DatabaseQuerier);

        $categories->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $categories->in()->Posts()->withId(6)->find();
    }

    public function test_ManyToManyRelationships_with_taxonomies_to_posts_tag_alias()
    {
        (string) $query = "SELECT terms.* FROM wp_terms AS terms " 
                         ."JOIN wp_term_taxonomy AS taxonomy ON taxonomy.term_id = terms.term_id "
                         ."JOIN wp_term_relationships AS term_relationships ON taxonomy.term_taxonomy_id = term_relationships.term_taxonomy_id "
                         ."JOIN wp_posts AS posts ON posts.id = term_relationships.object_id  " 
                         ."WHERE taxonomy.taxonomy = 'post_tag' AND posts.id = ? ";
                                               
        (array) $parameters = [
            6
        ];
        (object) $tags = new Tags;

        $tags->setDatabaseQuerier($this->DatabaseQuerier);

        $tags->setSingleModelOrGroupOfModelsCreator($this->SingleModelOrGroupOfModelsCreator);

        $this->DatabaseQuerier->expects($this->once())->method('setSQLParameters')->with(
            $parameters
        );

        $this->DatabaseQuerier->expects($this->once())->method('setSQL')->with(
            $query
        );

        $tags->in()->Posts()->withId(6)->find();
    }

    public function test_order_ascending()
    {

        $this->expectedSQL('SELECT * FROM comments WHERE id = ? ORDER BY date ASC');
    
        $this->expectedSQLParameters([9]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases([]);
    
        $this->AliasedFinder->withId(9)->lowestDateFirst()->find();
    
    }

    public function test_order_descending()
    {

        $this->expectedSQL('SELECT * FROM comments WHERE id = ? ORDER BY date DESC');
    
        $this->expectedSQLParameters([9]);

        $this->expectedPrimaryKey($this->primaryKey);

        $this->expectedAliases([]);
    
        $this->AliasedFinder->withId(9)->highestDateFirst()->find();
    
    }



}
