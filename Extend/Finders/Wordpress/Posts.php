<?php

namespace Stratum\Extend\Finder\WordPress;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Custom\Model\Wordpress\Post;
use Stratum\Original\Data\Creator\ArrayOfDataCreator;
use Stratum\Original\Data\DateResolver;
use Stratum\Original\Data\EntityData;
use Stratum\Original\Data\Field;
use Stratum\Original\Data\Finder\RelatableEntityFinder;
use Stratum\Original\Data\GroupOf;
use Stratum\Original\Data\Model;
use Stratum\Original\Data\Querier\WPQuerier;
use Stratum\Original\Utility\ClassUtility\ClassName;

Class Posts extends RelatableEntityFinder
{
    public $oneToManyRelationships = [
        'comments', 'meta'
    ];

    public $manyToOneRelationships = [
        'users'
    ];

    public $manyToManyRelationships = [
        'categories', 'tags'
    ];

    protected $fieldAliases = [
        'type' => 'post_type',
        'status' => 'post_status',
        'numberOfComments' => 'comment_count',
        'parentId' => 'post_parent',
        'authorId' => 'author',
        'passwordProtection' => 'has_password',
        'password' => 'post_password',
        'keywords' => 's'
    ];

    protected $OrderByAliases = [
        'id' => 'ID',
        'post_type' => 'type'
    ];

    protected $primaryKey = 'id';

    protected $WP_QueryArguments = [];
    protected $WPQuerier;

    public static function fromMainQuery()
    {
        (object) $posts = new Static;

        $posts->WPQuerier = new WPQuerier;
        
        $posts->executeWP_Query();       

        return $posts->onQuery();

    }

    public function __construct()
    {
        $this->WPQuerier = new WPQuerier;

        parent::__construct();

        $this->WP_QueryArguments['date_query'] = [
            [
                'before'    => [ 
                    'year'  => date('Y'),
                    'month' => date('n'),
                    'day'   => date('j'),
                ],
                'after' => [
                    'year' => 0000
                ]
            ],
        ];

        
    }

    public function WP_QueryArguments()
    {
        return $this->WP_QueryArguments;
    }

    public function setWPQuerier(WPQuerier $WPQuerier)
    {
        $this->WPQuerier = $WPQuerier;
    }

    public function setSelectedColumnsFrom($a)
    {
        return $this;
    }

    public function published()
    {
        return $this;
    }

    public function __call($method, $arguments)
    {
        if ($method === 'excludeFirst') {
            $this->setOffsetTo($arguments[0]);

            return $this;
        }

        return parent::__call($method, $arguments);
    }

    



    protected function handleDate($date)
    {
        (object) $Date = new DateResolver($date);

        if ($Date->isYear()) {
            $this->WP_QueryArguments['date_query'][]['year'] = $Date->year();
        } elseif ($Date->isMonthOfYear()) {
            $this->WP_QueryArguments['date_query'][]['month'] = $Date->month();
            $this->WP_QueryArguments['date_query'][0]['year'] = $Date->year();
        } elseif ($Date->isDayOfYear()) {
            $this->WP_QueryArguments['date_query'][]['day'] = $Date->day();
            $this->WP_QueryArguments['date_query'][0]['month'] = $Date->month();
            $this->WP_QueryArguments['date_query'][0]['year'] = $Date->year();
        }

        
    }

    protected function setOffsetTo($offsetNumber)
    {
        $this->WP_QueryArguments['offset'] = $offsetNumber;
    }

    protected function first($numberOfPosts)
    {
        $this->WP_QueryArguments['posts_per_page'] = $numberOfPosts;

        return $this;
    }

    protected function excludeFirst($numberOfRows)
    {
        $this->WP_QueryArguments['offset'] = $numberOfRows;

        return $this;
    }

    protected function onBuilderStart()
    {

    }

    protected function onBuilderEnd()
    {
        $this->WPQuerier->setQueryArguments($this->WP_QueryArguments);
        $this->executeWP_Query();        
    }

    protected function onQuery()
    {
        return $this->createPostModelOrGroupOfPostModelsBasedOnQueryResult();
    }

    protected function onConditionalAND()
    {
 
    }

    protected function onConditionalOR()
    {
 
    }

    protected function onEqualityField(Field $field)
    {   
        if ($field->name === 'date') {
            $this->handleDate($field->value);
        } else {
            $this->WP_QueryArguments[$this->convert($field->name)] = $field->value;
        }
        
    }

    protected function onMoreThanField(Field $field)
    {
        
    }

    protected function onLessThanField(Field $field)
    {
        
    }

    protected function onManyToOneRelationshipStart(EntityData $entity)
    {
        $this->initializeIdsArrayIfEmptyFor('author__in');

        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entity->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->relatedFinder->selectPrimaryKeyOnly();
    }

    protected function onOneToManyRelationshipStart(EntityData $entity)
    {
        $this->initializeIdsArrayIfEmptyFor('post__in');

        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entity->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->relatedFinder->useForeignKeyFor(lcfirst($this->singleClassName()));

        $this->relatedFinder->groupOperator = $this->generateGroupByOperatorBasedOn($entity);
        $this->relatedFinder->groupNumber = $entity->numberOfEntities;
    }

    protected function onManyToManyRelationshipStart(EntityData $entity)
    {
        $this->finderCreator->setEntityType("Stratum\\Custom\\Finder\\MYSQL\\$entity->entityType");

        $this->relatedFinder = $this->finderCreator->create();

        $this->WP_QueryArguments['tax_query'][] = [
            'taxonomy' => strtolower(Inflector::singularize($this->relatedFinder->className())),
            'field' => 'term_id'
        ];
    }

    protected function onManyToOneRelationshipEnd()
    {
        $this->updateAuthorIdsArgumentFor('author__in');
    }

    protected function onOneToManyRelationshipEnd()
    {
        $this->updatePostIdsArgumentFor('post__in');  
    }

    protected function onManyToManyRelationshipEnd()
    {
        $this->WP_QueryArguments['tax_query'][0]['terms'] = $this->relatedFinder->sqlParameters()[0];
    }

    protected function onOrderByAscending(Field $field)
    {
        $this->WP_QueryArguments['orderby'] = $this->useOrderByAliasesFor($field->name);
        $this->WP_QueryArguments['order'] = 'ASC';
    }

    protected function onOrderByDescending(Field $field)
    {
        $this->WP_QueryArguments['orderby'] = $this->useOrderByAliasesFor($field->name);
        $this->WP_QueryArguments['order'] = 'DESC';
    }

    protected function convert($fieldName)
    {
        if ($fieldName === 'id') {
            return 'p';
        }

        return $fieldName;
    }


    protected function relatedFinder()
    {
        return $this->relatedFinder;
    }

    protected function executeWP_Query()
    {
        (object) $posts = $this->WPQuerier->query();

        $this->arrayOfDataCreator = new ArrayOfDataCreator($posts);
    }

    protected function createPostModelOrGroupOfPostModelsBasedOnQueryResult()
    {   
        $this->SingleModelOrGroupOfModelsCreator->setEntityType($this->fullyQualifiedClassName());
        $this->SingleModelOrGroupOfModelsCreator->setAliases($this->fieldAliases);
        $this->SingleModelOrGroupOfModelsCreator->setPrimaryKey($this->primaryKey);
        $this->SingleModelOrGroupOfModelsCreator->setWhetherOnlyOneSingleModelIsMeantToBeReturned(
            $this->hasOneSingleEntityBeenRequested()
        );
        $this->SingleModelOrGroupOfModelsCreator->setQueryResult($this->arrayOfDataCreator->create());
    
        return $this->SingleModelOrGroupOfModelsCreator->create();
    }

    protected function foreignIdsFrom(GroupOf $models)
    {
        (array) $postIds = [];

        foreach ($models as $model) {
            $postIds[] = $model->{$model->foreignKeyFor('posts')};
        }

        return $postIds;
    }

    protected function authorIdsFrom(GroupOf $models)
    {
        (array) $postIds = [];

        foreach ($models as $model) {
            $postIds[] = $model->{$model->primaryKey};
        }

        return $postIds;
    }

    protected function isMoreThanOnePost()
    {
        return $this->numberOfPostsFound > 1;
    }

    protected function onlyOnePostWasFound()
    {
        return $this->numberOfPostsFound === 1;
    }

    protected function useOrderByAliasesFor($fieldName)
    {
        if (isset($this->OrderByAliases[$fieldName])) {
            return $this->OrderByAliases[$fieldName];
        }
        return $fieldName;
    }

    protected function initializeIdsArrayIfEmptyFor($fieldName)
    {
        if (!isset($this->WP_QueryArguments[$fieldName])) {
            $this->WP_QueryArguments[$fieldName] = [];
        }
        
    }

    protected function updatePostIdsArgumentFor($fieldName)
    {
        $this->WP_QueryArguments[$fieldName] = array_merge(
            $this->WP_QueryArguments[$fieldName], 
            $this->foreignIdsFrom($this->relatedFinder->find())
        );
    }

    protected function updateAuthorIdsArgumentFor($fieldName)
    {
        $this->WP_QueryArguments[$fieldName] = array_merge(
            $this->WP_QueryArguments[$fieldName], 
            $this->authorIdsFrom($this->relatedFinder->find())
        );
    }

















}