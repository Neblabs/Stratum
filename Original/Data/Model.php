<?php

namespace Stratum\Original\Data;

use Doctrine\Common\Inflector\Inflector;
use Stratum\Extend\Remover;
use Stratum\Original\Data\Ability\Savable;
use Stratum\Original\Data\DynamicFieldResolver;
use Stratum\Original\Data\Exception\UnexistentDomainClassException;
use Stratum\Original\Data\Exception\UnexistentSaverClassException;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class Model extends GetterAndSetter implements Savable
{
    use ClassName;

    protected $data;
    private $domain;
    private $saver;
    private $entityModelType;
    public $primaryKey = 'id';
    private $tablePrefixHasNotBeenSet = true;
    private $finder;
    private $tablePrefix;

    public function __construct(Data $data = null, Domain $domain = null, Saver $saver = null)
    {
        $this->entityModelType = $this->fullyQualifiedClassName();

        $this->data = is_null($data) ? $this->createDataObject() : $data;
        $this->domain = is_null($domain) ? $this->createDomainObject() : $domain;
       
    }

    public function asArray()
    {
        return get_object_vars($this->data);
    }

    public function __get($property)
    {
        if ($this->hasGetterFor($property)) {

            return $this->get($property);

        } elseif ($this->domain->hasGetterFor($property)) {

            return $this->domain->get($property);

        }

        return $this->data->$property;
    }

    public function __set($property, $value)
    {
        if ($this->hasSetterFor($property)) {
            $this->set($property, $value);
        } elseif ($this->domain->hasSetterFor($property)) {
            $this->domain->set($property, $value);
        } else {
            $this->data->$property = $value;
        }
    }

    public function __call($method, $arguments)
    {   
        (object) $dynamicField = new DynamicFieldResolver($method);
        $dynamicField->setFieldAliases($this->data->aliases());

        if ($dynamicField->isSetterForSameEntity()) {
            $this->__set($dynamicField->fieldName(), $arguments[0]); 
            return $this;
        }

        return call_user_func_array([$this->domain, $method], $arguments);
    }

    public function setTablePrefix($tablePrefix = 'noFinderIsSettingIt')
    {
        if ($tablePrefix === 'noFinderIsSettingIt') {
            $this->tablePrefix = $this->createFinder()->tablePrefix();
        } else {
            $this->tablePrefix = $tablePrefix;
        }
        
        $this->tablePrefixHasNotBeenSet = false;
    }

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
    }

    public function wasFound()
    {
        return !$this->data->isEmpty();
    }

    public function numberOfFields()
    {
        return $this->data->count();
    }

    /*
        Wrapper for PDO::lastInsertId
    */
    public function createdId()
    {
        return $this->saver->lastInsertId();
    }

    public function save()
    {
        $this->tablePrefixHasNotBeenSet?$this->setTablePrefix():null;

        $this->createSaverObjectIfDoesNotExist();

        $this->saver->save();

        return $this;
    }

    public function wasSaved()
    {
        return $this->saver->wasSaved();
    }

    /*
        Super Experimental, use it at your won risk.

        Gets the lastest inserted model by using PDO::lastInsertId
        Requires an 'id' field.
    */
    public function getSaved()
    {
        (object) $findModel = $this->createFinder();
        return $findModel->withId($this->createdId())->find();   
    }

    public function remove()
    {
        $this->tablePrefixHasNotBeenSet?$this->setTablePrefix():null;

        $this->createRemoverObjectIfDoesNotExist();

        $this->remover->remove();
    }

    public function foreignKeyFor($entity)
    {
        (object) $finder = $this->createRelatedFinder();

        return $finder->foreignKeyFor($entity);

    }

    protected function setFieldAliases()
    {
        
    }

    protected function createDomainObject()
    {
        (string) $Domain = "Stratum\\Custom\\Domain\\{$this->singleClassName()}";
    
        $this->throwExceptionifNoDomainClassExistsForTheCurrentModelOrIsNotADomainType($Domain);
    
        return new $Domain($this->data);
    }

    protected function createDataObject()
    {
        (object) $Data = new Data;
        (object) $finder = $this->createFinder();

        $Data->setAliases($finder->fieldAliases());

        return $Data;
    }

    protected function createSaverObjectIfDoesNotExist()
    {
        $this->saver = is_null($this->saver) ? $this->createSaverObjectWith($this->data) : $this->saver;

        $this->saver->setPrimaryKey($this->primaryKey);
        $this->saver->setSingleEntityType($this->singleClass());
        $this->saver->setTablePrefix($this->tablePrefix);
    }

    protected function createSaverObjectWith(Data $data)
    {
        (array) $modelSubnamespaces = explode('\\', substr($this->entityModelType, strlen('Stratum\\Custom\\Model\\')));

        (integer) $saverType = $modelSubnamespaces[count($modelSubnamespaces) - 2];
        (integer) $saverClass = $modelSubnamespaces[count($modelSubnamespaces) - 1];

        (string) $CustomEntitySaver = "Stratum\\Extend\\Saver\\$saverType\\$saverClass";
        (string) $GenericEntitySaver = "Stratum\\Extend\\Saver\\$saverType\\$saverType";

        if (class_exists($CustomEntitySaver)) {

            return new $CustomEntitySaver($this->data);
            
        } elseif (class_exists($GenericEntitySaver)) {

            return new $GenericEntitySaver($this->data);

        } else {
            throw new UnexistentSaverClassException("
                Model: $this->entityModelType requires a Saver Class of type $CustomEntitySaver or $GenericEntitySaver
                ");
        }


    }

    protected function createRemoverObjectIfDoesNotExist()
    {
        $this->remover = is_null($this->remover) ? new Remover\MYSQL\MYSQL($this->data) : $this->remover;

        $this->remover->setPrimaryKey($this->primaryKey);
        $this->remover->setSingleEntityType($this->singleClass());
        $this->remover->setTablePrefix($this->tablePrefix);
    }

    protected function createFinder()
    {
        if ($this->finder != null) {
            return $this->finder;
        }
        (string) $Finder = substr_replace($this->fullyQualifiedClassName(), 'Finder', strpos($this->fullyQualifiedClassName(), 'Model'), strlen('model'));

        $Finder = strpos(strtolower($Finder), 'meta') !== false ? $Finder : Inflector::pluralize($Finder);

        return ($this->finder = new $Finder);
    }

    protected function createRelatedFinder()
    {
        (string) $Finder = substr_replace($this->fullyQualifiedClassName(), 'Finder', strpos($this->fullyQualifiedClassName(), 'Model'), strlen('model'));

        $FinderNamespaces = explode('\\', $Finder);

        $lastElement = count($FinderNamespaces) - 1;

        $FinderNamespaces[$lastElement] = Inflector::pluralize($this->singleClassName());

        $Finder = implode('\\', $FinderNamespaces);

        return new $Finder;
    }

    protected function singleClass()
    {
        if ($this->classAliasExist()) {
            return $this->createFinder()->classAlias();
        }

        return Inflector::pluralize($this->singleClassName());
    }

    protected function classAliasExist()
    {
        (boolean) $doesTheAliasPropertyHaveAValue = !empty($this->createFinder()->classAlias());

        return $doesTheAliasPropertyHaveAValue;
    }

    protected function classAlias()
    {
        return $this->alias;
    }
    
    protected function throwExceptionifNoDomainClassExistsForTheCurrentModelOrIsNotADomainType($fullyQualifiedDomainClassName)
    {
        if (!class_exists($fullyQualifiedDomainClassName)) {
            throw new UnexistentDomainClassException(
                "Model: {$this->fullyQualifiedClassName()} requires a Domain: $fullyQualifiedDomainClassName"
            );
        }

        if (!is_subclass_of($fullyQualifiedDomainClassName, Domain::className())) {
            throw new UnexistentDomainClassException(
                "Domain Class: $fullyQualifiedDomainClassName must extend: " . Domain::className()
            );
        }

    }













}
