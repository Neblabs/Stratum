<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\Data;
use Stratum\Original\Establish\Established;

Class DatabaseQuerierTest extends TestCase
{
    public function setUp()
    {
        (object) $database = Established::database();
        
        $pdo = new PDO("mysql:host={$database->host};dbname={$database->name}", $database->username, $database->password);

        $pdo->query('TRUNCATE test_table');

        $pdo->query('INSERT INTO test_table (id, name) VALUES (1, "Rafa")');
        $pdo->query('INSERT INTO test_table (id, name) VALUES (2, "Alex")');

        $this->DatabaseQuerier = new DatabaseQuerier;


    }

    public function test_returns_data_objects_from_the_database_correctly_set()
    {
        $this->DatabaseQuerier->setSql('SELECT * FROM test_table');
        $this->DatabaseQuerier->setSqlParameters([]);

        (array) $results = $this->DatabaseQuerier->query();

        foreach ($results as $result) {
            $this->assertInstanceOf(Data::className(), $result);
        }

        $this->assertEquals(1, $results[0]->id);
        $this->assertEquals('Rafa', $results[0]->name);

        $this->assertEquals(2, $results[1]->id);
        $this->assertEquals('Alex', $results[1]->name);
    }

    









    
}