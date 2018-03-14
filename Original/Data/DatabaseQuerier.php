<?php

namespace Stratum\Original\Data;

use Stratum\Original\Data\Creator\PDOCreator;
use Stratum\Original\Data\Data;
use PDO;
use PDOStatement;

Class DatabaseQuerier
{
    protected $pdo;
    protected $sql;
    protected $SQLParameters;
    protected $isPreparedStatement = false;
    protected $isSELECT = true;

    public static $queries = [];
    public function __construct(PDOCreator $PDOCreator = null)
    {
        $PDOCreator = is_null($PDOCreator) ? new PDOCreator : $PDOCreator;

        $this->pdo = $PDOCreator->create();

        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function __sleep()
    {
        $this->pdo = null;

        return array_keys(get_object_vars($this));
    }

    
    public function setSQL($query)
    {
        $this->sql = $query;
    }

    public function setSQLParameters(array $SQLParameters)
    {
        $this->SQLParameters = $SQLParameters;
        $this->isPreparedStatement = true;
    }

    public function isNotSELECT()
    {
        $this->isSELECT = false;
    }

    public function closeCursor()
    {
        if (isset($this->statement)) {
            $this->statement->closeCursor();
        }
    }

    public function setEmulatePrepares()
    {
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }

    public function query()
    {
        $result = $this->runPDOQuery();

        return $result;
    }

    protected function runPDOQuery()
    {

        if ($this->isPreparedStatement) {
            return $this->runPreparedStatement();
        } else {
            return $this->runQuery();
        }
    }

    protected function runPreparedStatement()
    {

        if ($this->isSELECT) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
            (object) $statement = $this->pdo->prepare($this->sql);

            $this->statement = $statement;  

            $statement->setFetchMode(PDO::FETCH_CLASS, Data::className());

            $statement->execute($this->SQLParameters);

            (array) $result = $statement->fetchALL();

            $this->closeCursor();

            return $result;
            
        } else {

            (object) $statement = $this->pdo->prepare($this->sql); 
            $this->statement = $statement;
            
            (boolean) $result = $statement->execute($this->SQLParameters);

            return $result and ($statement->rowCount() !== 0);

        }
    }

    protected function runQuery()
    {
        $result = $this->pdo->query($this->sql);

        if ($result instanceof PDOStatement) {
            $this->statement = $result;
        }

        return $result;
    }













}