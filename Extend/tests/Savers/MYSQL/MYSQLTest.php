<?php 

use PHPUnit\Framework\TestCase;
use Stratum\Extend\Saver\MYSQL\MYSQL;
use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\Data;
use Stratum\Original\Data\Saver;
use Stratum\Original\Establish\Established;

Class MYSQLSaverTest extends TestCase
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
        $this->saver = new MYSQL($this->data);
        $this->databaseQuerier = $this->createMock(DatabaseQuerier::class);
    }

    public function test_builds_correct_sql_for_inserting_a_row()
    {
        $this->data->id = 7;
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;

        $this->saver->setSingleEntityType('posts');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);

        (array) $expectedQueryParameters = [7, 'New Title', 2012];

        $this->assertEquals(
            'INSERT INTO posts (id, title, post_date) VALUES (?, ?, ?) ',
            $this->saver->INSERTsql()
        );

        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
    }

    public function test_builds_correct_sql_for_updating_a_row()
    {
        $this->data->id = 7;
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;

        $this->saver->setSingleEntityType('posts');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);

        (array) $expectedQueryParameters = [7, 'New Title', 2012, 7];

        $this->assertEquals(
            'UPDATE posts SET id=?, title=?, post_date=? WHERE id = ?',
            $this->saver->UPDATEsql()
        );

        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
    }

    public function test_generates_INSERT_sql_when_data_has_no_primary_key()
    {
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;

        $this->saver->setSingleEntityType('posts');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);

        (array) $expectedQueryParameters = ['New Title', 2012];

        $this->databaseQuerier->expects($this->once())->method('setSql')->with(
            'INSERT INTO posts (title, post_date) VALUES (?, ?) '
        );

        $this->databaseQuerier->expects($this->once())->method('query')->willReturn(true);



        $this->saver->save();

        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
        $this->assertTrue($this->saver->wasSaved());
    }

    public function test_generates_UPDATE_sql_when_data_has_primary_key()
    {
        $this->data->id = 7;
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;
    
        $this->saver->setSingleEntityType('posts');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);
    
        (array) $expectedQueryParameters = [7, 'New Title', 2012, 7];
    
        $this->databaseQuerier->expects($this->once())->method('setSql')->with(
            'UPDATE posts SET id=?, title=?, post_date=? WHERE id = ?' 
        );

        $this->databaseQuerier->expects($this->once())->method('query')->willReturn(true);


    
        $this->saver->save();
        
        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
        $this->assertTrue($this->saver->wasSaved());
    }

    public function test_generates_INSERT_sql_when_data_has_no_primary_key_when_custom_primary_key_has_been_defined()
    {
        $this->data->id = 8;
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;

        $this->saver->setSingleEntityType('posts');
        $this->saver->setPrimaryKey('post_id');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);

        (array) $expectedQueryParameters = [8, 'New Title', 2012];

        $this->databaseQuerier->expects($this->once())->method('setSql')->with(
            'INSERT INTO posts (id, title, post_date) VALUES (?, ?, ?) '
        );

        $this->databaseQuerier->expects($this->once())->method('query')->willReturn(true);

        

        $this->saver->save();

        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
        $this->assertTrue($this->saver->wasSaved());
    }

    public function test_generates_INSERT_sql_when_data_has_no_primary_key_and_custom_primary_key_is_defined()
    {
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;

        $this->saver->setSingleEntityType('posts');
        $this->saver->setPrimaryKey('post_id');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);

        (array) $expectedQueryParameters = ['New Title', 2012];

        $this->databaseQuerier->expects($this->once())->method('setSql')->with(
            'INSERT INTO posts (title, post_date) VALUES (?, ?) '
        );

        $this->databaseQuerier->expects($this->once())->method('query')->willReturn(true);



        $this->saver->save();

        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
        $this->assertTrue($this->saver->wasSaved());
    }

    public function test_generates_UPDATE_sql_when_data_has_custom_defined_primary_key()
    {
        $this->data->post_id = 7;
        $this->data->title = 'New Title';
        $this->data->post_date = 2012;
    
        $this->saver->setSingleEntityType('posts');
        $this->saver->setPrimaryKey('post_id');
        $this->saver->setDatabaseQuerier($this->databaseQuerier);
    
        (array) $expectedQueryParameters = [7, 'New Title', 2012, 7];
    
        $this->databaseQuerier->expects($this->once())->method('setSql')->with(
            'UPDATE posts SET post_id=?, title=?, post_date=? WHERE post_id = ?' 
        );

        $this->databaseQuerier->expects($this->once())->method('query')->willReturn(true);


    
        $this->saver->save();
        
        $this->assertEquals($expectedQueryParameters, $this->saver->sqlParameters());
        $this->assertTrue($this->saver->wasSaved());
    }

    public function test_saves_new_record_to_database_NO_MOCKS()
    {
        (object) $data = new Data;
        (object) $saver = new MYSQL($data);

        $saver->setSingleEntityType('test_table_posts');

        $data->title = '12th title';
        $data->type = 'post';
        
        $this->assertCount(11, $this->queryTable());

        $this->assertSame($this->exepectedResultsBeforeINSERTandUPDATE(), $this->queryTable());

        $saver->save();

        $this->assertTrue($saver->wasSaved());

        $this->assertCount(12, $this->queryTable());
        
        $this->assertSame($this->exepectedResultsAfterINSERT(), $this->queryTable());

        

        

    }

    public function test_updates_new_record_to_database_NO_MOCKS()
    {
        self::resetTable();

        (object) $data = new Data;
        (object) $saver = new MYSQL($data);

        $saver->setSingleEntityType('test_table_posts');

        $data->id = 7;
        $data->title = 'edited title';
        $data->type = 'post';

        $this->assertSame($this->exepectedResultsBeforeINSERTandUPDATE(), $this->queryTable());

        $saver->save();

        $this->assertTrue($saver->wasSaved());

        $this->assertSame($this->exepectedResultsAfterUPDATE(), $this->queryTable());

    }

    protected static function resetTable()
    {
        self::$pdo->query('TRUNCATE TABLE test_table_posts');

        self::$pdo->query(
            "INSERT INTO `test_table_posts` (`id`, `title`, `type`)
                    VALUES
                        (1,'first title','product'),
                        (2,'second title','post'),
                        (3,'third title','post'),
                        (4,'fourth title','product'),
                        (5,'fifth title','post'),
                        (6,'sixth title','product'),
                        (7,'seventh title','post'),
                        (8,'eighth title','product'),
                        (9,'ninth title','product'),
                        (10,'tenth title','product'),
                        (11,'eleventh title','post')"
        );
    }

    protected function queryTable()
    {
        (object) $statement = self::$pdo->prepare('SELECT * FROM test_table_posts');
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function exepectedResultsBeforeINSERTandUPDATE()
    {
        return [
            [
                'id' => '1',
                'title' => 'first title',
                'type' => 'product'
            ],
            [
                'id' => '2',
                'title' => 'second title',
                'type' => 'post'
            ],
            [
                'id' => '3',
                'title' => 'third title',
                'type' => 'post'
            ],
            [
                'id' => '4',
                'title' => 'fourth title',
                'type' => 'product'
            ],
            [
                'id' => '5',
                'title' => 'fifth title',
                'type' => 'post'
            ],
            [
                'id' => '6',
                'title' => 'sixth title',
                'type' => 'product'
            ],
            [
                'id' => '7',
                'title' => 'seventh title',
                'type' => 'post'
            ],
            [
                'id' => '8',
                'title' => 'eighth title',
                'type' => 'product'
            ],
            [
                'id' => '9',
                'title' => 'ninth title',
                'type' => 'product'
            ],
            [
                'id' => '10',
                'title' => 'tenth title',
                'type' => 'product'
            ],
            [
                'id' => '11',
                'title' => 'eleventh title',
                'type' => 'post'
            ],

            

        ];
    }

    protected function exepectedResultsAfterINSERT()
    {
        return [
            [
                'id' => '1',
                'title' => 'first title',
                'type' => 'product'
            ],
            [
                'id' => '2',
                'title' => 'second title',
                'type' => 'post'
            ],
            [
                'id' => '3',
                'title' => 'third title',
                'type' => 'post'
            ],
            [
                'id' => '4',
                'title' => 'fourth title',
                'type' => 'product'
            ],
            [
                'id' => '5',
                'title' => 'fifth title',
                'type' => 'post'
            ],
            [
                'id' => '6',
                'title' => 'sixth title',
                'type' => 'product'
            ],
            [
                'id' => '7',
                'title' => 'seventh title',
                'type' => 'post'
            ],
            [
                'id' => '8',
                'title' => 'eighth title',
                'type' => 'product'
            ],
            [
                'id' => '9',
                'title' => 'ninth title',
                'type' => 'product'
            ],
            [
                'id' => '10',
                'title' => 'tenth title',
                'type' => 'product'
            ],
            [
                'id' => '11',
                'title' => 'eleventh title',
                'type' => 'post'
            ],
            [
                'id' => '12',
                'title' => '12th title',
                'type' => 'post'
            ],
            

        ];
    }

    protected function exepectedResultsAfterUPDATE()
    {
        return [
            [
                'id' => '1',
                'title' => 'first title',
                'type' => 'product'
            ],
            [
                'id' => '2',
                'title' => 'second title',
                'type' => 'post'
            ],
            [
                'id' => '3',
                'title' => 'third title',
                'type' => 'post'
            ],
            [
                'id' => '4',
                'title' => 'fourth title',
                'type' => 'product'
            ],
            [
                'id' => '5',
                'title' => 'fifth title',
                'type' => 'post'
            ],
            [
                'id' => '6',
                'title' => 'sixth title',
                'type' => 'product'
            ],
            [
                'id' => '7',
                'title' => 'edited title',
                'type' => 'post'
            ],
            [
                'id' => '8',
                'title' => 'eighth title',
                'type' => 'product'
            ],
            [
                'id' => '9',
                'title' => 'ninth title',
                'type' => 'product'
            ],
            [
                'id' => '10',
                'title' => 'tenth title',
                'type' => 'product'
            ],
            [
                'id' => '11',
                'title' => 'eleventh title',
                'type' => 'post'
            ],

            

        ];
    }













}