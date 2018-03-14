<?php

namespace Stratum\Extend\Finder\MYSQL;

use Stratum\Original\Data\Creator\SingleModelOrGroupOfModelsCreator;
use Stratum\Original\Data\DatabaseQuerier;
use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder\RelatableEntityFinder;

Class MYSQL extends RelatableEntityFinder
{
    protected $table;
    public $columns = '*';
    protected $conditions;
    protected $numberOfRows;
    protected $offsetNumber;
    protected $sqlParameters = [];
    protected $DatabaseQuerier;
    protected $finderIsUsingCustomSql = false;
    protected $foreignKeys;

    public function __construct()
    {
        $this->DatabaseQuerier = new DatabaseQuerier;

        parent::__construct();
    }

    public function setDatabaseQuerier(DatabaseQuerier $DatabaseQuerier)
    {
        $this->DatabaseQuerier = $DatabaseQuerier;
    }

    public static function fromCustomSQL($sql)
    {
        (object) $finder = new Static;

        $finder->finderIsUsingCustomSql = true;

        $finder->sql = $sql;

        return $finder;
    }

    public function withParameters(array $parameters)
    {
        $this->sqlParameters = $parameters;

        $this->DatabaseQuerier->setSQLParameters($parameters);

        return $this;
    }

    public static function selectOnly(array $selectedColumns)
    {
        (object) $finder = new static;

        $finder->columns = $finder->sqlColumnsFrom($selectedColumns);

        return $finder;
    }

    public function setSelectedColumnsFrom(array $selectedColumns)
    {
        $this->columns = $this->sqlColumnsFrom($selectedColumns);

        return $this;
    }

    public function selectPrimaryKeyOnly()
    {
        $this->columns = $this->primaryKey;   
    }

    protected function first($numberOfRows)
    {

        $this->numberOfRows = $numberOfRows;

        return $this;
    }

    public function excludeFirst($numberOfRows)
    {
        $this->offsetNumber = $numberOfRows;

        return $this;
    }

    public function SQLQuery()
    {
        $this->finishBuilder();

        return $this->sql();
    }

    public function sqlParameters()
    {
        return $this->sqlParameters;
    }

    public function useForeignKeyFor($entityType)
    {
        $this->columns = $this->foreignKeyFor($entityType);
        $this->groupByColumn = $this->foreignKeyFor($entityType);
    }

    protected function relatedFinder()
    {
        return $this->relatedFinder;
    }

    protected function sql()
    {
        $this->callOnStartBuilderEventIfBuilderHasJustStarted();
        $this->callOnRelationshipEndIfHasntEnded();

        if ($this->finderIsUsingCustomSql) {
            return $this->sql;
        }

        return ltrim(
             $this->select()
            .$this->from()
            .$this->where()
            .$this->groupBy()
            .$this->having()
            .$this->orderBy()
            .$this->limit()
            .$this->offset()
        );
    }

    protected function select()
    {
        (string) $columns = strtolower($this->columns);

        return "SELECT {$columns}";
    }

    protected function from()
    {
        (string) $table = strtolower($this->table);

        return " FROM {$table}";
    }

    protected function where()
    {
        (string) $conditions = strtolower($this->conditions);

        return !empty($this->conditions) ? " WHERE $conditions" : ' ';
    }

    protected function limit()
    {
        (string) $limit = '';

        if (!empty($this->numberOfRows)) {
            $limit = "LIMIT ? ";
            $this->sqlParameters[] = $this->numberOfRows;
        }

        return $limit;
    }

    protected function offset()
    {
        (string) $offset = '';

        if (!empty($this->offsetNumber)) {
            $offset = "OFFSET ? ";
            $this->sqlParameters[] = $this->offsetNumber;
        }

        return $offset;
    }

    protected function groupBy()
    {
        (string) $groupBy = '';
        

        if (!empty($this->groupByColumn)) {

            (string) $column = strtolower($this->groupByColumn);

            $groupBy  = "GROUP BY $column";
        }

        return $groupBy;
    }

    protected function having()
    {
        (string) $groupBy = '';

        if (!empty($this->groupOperator)) {
            $groupBy  = " HAVING count(*) $this->groupOperator ?";
            $this->sqlParameters[] = $this->groupNumber;
        }

        return $groupBy;

        
    }

    protected function orderBy()
    {
        (object) $orderByColumnIsNotEmpty = !empty($this->orderByColumn);
        

        if ($orderByColumnIsNotEmpty) {
            (string) $column = strtolower($this->orderByColumn);
            
            return "ORDER BY $column $this->orderDirection";
        }        
  
    }

    protected function sqlColumnsFrom(array $columns)
    {
        (string) $sqlColumns = '';

        foreach ($columns as $column) {
            $sqlColumns.= "{$this->tableNameWithPrefix()}.$column, ";
        }

        return rtrim($sqlColumns, ', ');
    }

    protected function onBuilderStart()
    {

        $this->table = $this->tableNameWithPrefix();
    }

    protected function onBuilderEnd()
    {
        
    }

    protected function onQuery()
    {  
        $this->DatabaseQuerier->setSQL($this->sql());
        $this->DatabaseQuerier->setSQLParameters($this->sqlParameters);

        $this->SingleModelOrGroupOfModelsCreator->setEntityType($this->fullyQualifiedClassName());
        $this->SingleModelOrGroupOfModelsCreator->setPrimaryKey($this->primaryKey);
        $this->SingleModelOrGroupOfModelsCreator->setWhetherOnlyOneSingleModelIsMeantToBeReturned($this->hasOneSingleEntityBeenRequested());
        $this->SingleModelOrGroupOfModelsCreator->setAliases($this->fieldAliases);
        $this->SingleModelOrGroupOfModelsCreator->setTablePrefix($this->tablePrefix());

        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->DatabaseQuerier->query());

        return $this->SingleModelOrGroupOfModelsCreator->create();
    }

    protected function onEqualityField(Field $field)
    {
        $this->conditions .= "$field->name = ? ";

        $this->sqlParameters[] = $field->value;
    }

    protected function onMoreThanField(Field $field)
    {
        $this->conditions .= "$field->name > ? ";

        $this->sqlParameters[] = $field->value;
    }

    protected function onLessThanField(Field $field)
    {
        $this->conditions .= "$field->name < ? ";

        $this->sqlParameters[] = $field->value;
    }

    protected function onConditionalAND()
    {
        $this->conditions .= 'AND ';
    }

    protected function onConditionalOR()
    {    
        $this->conditions .= 'OR ';
    }


    protected function onOneToManyRelationshipStart(EntityData $entityData)
    {
        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entityData->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->relatedFinder->useForeignKeyFor($this->singleClassName());

        $this->relatedFinder->groupOperator = $this->generateGroupByOperatorBasedOn($entityData);
        $this->relatedFinder->groupNumber = $entityData->numberOfEntities;

        $this->conditions .= "$this->primaryKey IN (";

    }

    protected function onManyToOneRelationshipStart(EntityData $entityData)
    {
        (string) $foreignKey = $this->foreignKeyFor($entityData->entityType);

        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entityData->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->relatedFinder->selectPrimaryKeyOnly();
        
       $this->conditions .= "{$foreignKey} IN (";
    }

    protected function onManyToManyRelationshipStart(EntityData $entityData)
    {
        
    }

    protected function onOneToManyRelationshipEnd()
    {
  
        $this->conditions .= $this->relatedFinder->sql() . ') ';

        $this->addSqlParametersFromRelatedEntity();

    }       

    protected function onManyToOneRelationshipEnd()
    {
        $this->conditions .= $this->relatedFinder->sql() . ') ';

        $this->addSqlParametersFromRelatedEntity();
    }    

    protected function onManyToManyRelationshipEnd()
    {

    }    

    protected function onOrderByAscending(Field $field)
    {
        $this->orderByColumn = $field->name;
        $this->orderDirection = 'ASC ';
    }

    protected function onOrderByDescending(Field $field)
    {
        $this->orderByColumn = $field->name;
        $this->orderDirection = 'DESC ';   
    }

    protected function addSqlParametersFromRelatedEntity()
    {
        foreach ($this->relatedFinder->sqlParameters() as $sqlParameter) {
            $this->sqlParameters[] = $sqlParameter;
        }
    }

    protected function removeWHEREClauseFromConditionsifNoConditionsWereSet()
    {
        (boolean)$WHEREClauseIsTheLastWordInConditionsString = substr(trim($this->conditions), -5) === 'WHERE';

        if ($WHEREClauseIsTheLastWordInConditionsString) {
            $this->conditions = substr_replace($this->conditions, '', -6);
        }
    }

    protected function tableNameWithPrefix()
    {
        return strtolower("{$this->tablePrefix()}{$this->className()}");
    }

    public function tablePrefix()
    {
        return '';
    }

}