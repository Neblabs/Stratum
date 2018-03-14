<?php

use PHPUnit\Framework\testCase;
use Stratum\Custom\Model\MYSQL\Post;
use Stratum\Extend\Saver\MYSQL\MYSQL;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Saver;
use Stratum\Original\Establish\Established;
use Stratum\Original\Test\Data\TestClass\ConcreteDomain;
use Stratum\Original\Test\Data\TestClass\ConcreteModel;

Class ConcreteModelTest extends TestCase
{
    protected static $pdo;

    public static function setUpBeforeClass()
    {
        (object) $database = Established::database();
        
        self::$pdo = new \PDO("mysql:host={$database->host};dbname={$database->name}", $database->username, $database->password);

        self::resetTable();
    }

    public function setUp()
    {
        $this->data = new Data;
        $this->domain = new ConcreteDomain($this->data);
        $this->saver = $this->createMock(Saver::class);
        $this->model = new ConcreteModel($this->data, $this->domain, $this->saver);
    }

    public function test_returns_property_from_data_object_NO_getters()
    {
        $this->data->title = 'New Title';

        $this->assertEquals('New Title', $this->model->title);
    }

    public function test_returns_aliased_property_from_data_object()
    {
        $this->data->setAliases([
            'title' => 'post_title',
            'id' =>'post_id'
        ]);

        $this->data->post_id = 55;
        $this->data->post_title = '55th Title';

        $this->assertEquals(55, $this->model->id);
        $this->assertEquals('55th Title', $this->model->title);
    }

    public function test_returns_property_filtered_by_specified_getter_in_model()
    {
        $this->data->name = 'Clean Name';

        $this->assertEquals('Edited Name by Model', $this->model->name);
    }

    public function test_returns_property_filtered_by_specified_getter_in_Domain()
    {
        $this->data->AuthorName = 'Clean Author Name';

        $this->assertEquals('Edited Author by Domain', $this->model->AuthorName);
    }

    public function test_returns_property_getter_in_model_takes_precedence_when_a_getter_is_defined_in_both_model_and_domain()
    {
        $this->data->year = 'Clean year';

        $this->assertEquals('Model took precedence: year', $this->model->year);
    }

    public function test_method_call_gets_delegated_to_the_domain_object()
    {
        (object) $domain = $this->createMock(ConcreteDomain::class);
        (object) $model = new ConcreteModel($this->data, $domain, $this->saver);

        $domain->expects($this->once())->method('operateOnData')->willReturn('called');

        (string) $result = $model->operateOnData();

        $this->assertEquals('called', $result);

    }

    public function test_method_call_gets_delegated_to_the_domain_object_with_arguments()
    {
        (object) $domain = $this->createMock(ConcreteDomain::class);
        (object) $model = new ConcreteModel($this->data, $domain, $this->saver);

        $domain->expects($this->once())->method('operateOnDataWithArguments')->will($this->returnArgument(0));

        (string) $result = $model->operateOnDataWithArguments('argument');

        $this->assertEquals('argument', $result);

    }

    public function test_sets_property()
    {
        $this->model->type = 'product';

        $this->assertEquals('product', $this->data->type);
        $this->assertEquals('product', $this->model->type);

    }

    public function test_sets_aliased_property()
    {
        $this->data->setAliases([
            'id' => 'post_id'
        ]);

        $this->model->id = 7;

        $this->assertEquals(7, $this->data->id);
        $this->assertEquals(7, $this->model->id);
    }

    public function test_sets_properties_using_dynamic_fields_and_return_itslef_for_a_fluent_interface()
    {
        (object) $self1 = $this->model->withTitle('New Title');
        (object) $self2 = $this->model->byAuthor('Rafa Serna');
        (object) $self3 = $this->model->inDate(2012);
        (object) $self4 = $this->model->ableToBeCommented(true);

        $this->assertEquals('New Title', $this->data->title);
        $this->assertEquals('Rafa Serna', $this->data->author);
        $this->assertEquals(2012, $this->data->date);
        $this->assertEquals(true, $this->data->beCommented);

        $this->assertEquals('New Title', $this->model->title);
        $this->assertEquals('Rafa Serna', $this->model->author);
        $this->assertEquals(2012, $this->model->date);
        $this->assertEquals(true, $this->model->beCommented);

        $this->assertSame($this->model, $self1);
        $this->assertSame($this->model, $self2);
        $this->assertSame($this->model, $self3);
        $this->assertSame($this->model, $self4);

    }

    public function test_dynamic_fields_chained()
    {
        $this->model->withTitle('New Title')
                    ->byAuthor('Rafa Serna')
                    ->inDate(2012)
                    ->ableToBeCommented(true);

        $this->assertEquals('New Title', $this->data->title);
        $this->assertEquals('Rafa Serna', $this->data->author);
        $this->assertEquals(2012, $this->data->date);
        $this->assertEquals(true, $this->data->beCommented);

        $this->assertEquals('New Title', $this->model->title);
        $this->assertEquals('Rafa Serna', $this->model->author);
        $this->assertEquals(2012, $this->model->date);
        $this->assertEquals(true, $this->model->beCommented);


    }

    public function test_sets_aliased_property_using_dynamic_fields()
    {
        $this->data->setAliases([
            'title' => 'post_title',
            'author' => 'author_name',
            'date' => 'post_date',
            'beCommented' => 'comments_open'
        ]);

        $this->model->withTitle('New Title')
                    ->byAuthor('Rafa Serna')
                    ->inDate(2012)
                    ->ableToBeCommented(true);

        $this->assertEquals('New Title', $this->data->post_title);
        $this->assertEquals('Rafa Serna', $this->data->author_name);
        $this->assertEquals(2012, $this->data->post_date);
        $this->assertEquals(true, $this->data->comments_open);
    }

    public function test_saver_gets_called()
    {
        $this->saver->expects($this->once())->method('save');
        $this->saver->expects($this->once())->method('wasSaved')->willReturn(true);

        $this->model->title = 'a title';
        $this->model->type = 'post';

        $this->model->save();

        $this->assertTrue($this->model->wasSaved());
    }

    public function test_saves_new_row_in_the_database()
    {
        (array) $initialRows = [
            [
                'id' => '1',
                'title' => 'First Title',
                'type' => 'product' 
            ]
        ];
        (array) $expectedRows = [
            [
                'id' => '1',
                'title' => 'First Title',
                'type' => 'product' 
            ],[
                'id' => '2',
                'title' => 'Second Title',
                'type' => 'post' 
            ]
        ];
        $data = new Data;
        $post = new ConcreteModel($data, new ConcreteDomain($data), new MYSQL($data));
    
        $post->title = 'Second Title';
        $post->type = 'post';
        
        $this->assertCount(1, $this->queryTable());
        $this->assertEquals($initialRows, $this->queryTable());

        $post->save();

        $this->assertCount(2, $this->queryTable());

        $this->assertEquals($expectedRows, $this->queryTable());
                    
    
    
    }

    

    protected static function resetTable()
    {
        self::$pdo->query('TRUNCATE TABLE test_table_posts');
        self::$pdo->query(
            "INSERT INTO `test_table_posts` (`id`, `title`, `type`)
                    VALUES
                        (1,'First Title','product')"
        );
    }

    protected function queryTable()
    {
        (object) $statement = self::$pdo->prepare('SELECT * FROM test_table_posts');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function test_uses_finder_aliases()
    {
        (object) $post = new Post;

        $post->authorId = 7;

        $this->assertEquals(7, $post->post_author);
    }

    public function test_was_found_returns_false()
    {
        $this->assertFalse($this->model->wasFound());
    }

    public function test_was_found_returns_true()
    {
        $this->data->id = 9;
        $this->assertTrue($this->model->wasFound());
    }












}