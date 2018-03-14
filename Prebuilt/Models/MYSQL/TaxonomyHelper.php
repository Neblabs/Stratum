<?php

namespace Stratum\Prebuilt\Model\MYSQL;

use Stratum\Original\Data\Creator\WPPModelObjectToDataObjectConverter;
use Stratum\Extend\Finder\MYSQL\MYSQL;
use Stratum\Original\Data\Model;
use Stratum\Original\Utility\ClassUtility\ClassName;

Abstract Class TaxonomyHelper extends Model
{
   use ClassName;

   protected $alias = '';

   protected static $fieldsToConvert = [
        'term_id',
        'name',
        'slug',
        'parent',
        'count'
   ];

   protected static $fieldAliases = [
        'parentId' => 'parent',
        'id' => 'term_id'
    ];

   public static function fromMainQuery()
   {
        (object) $WP_Term = get_term(get_queried_object_id());

        (object) $WPPModelObjectToDataObjectConverter = new WPPModelObjectToDataObjectConverter($WP_Term);

        $WPPModelObjectToDataObjectConverter->setFieldsToConvert(static::$fieldsToConvert);
        $WPPModelObjectToDataObjectConverter->setfieldAliases(static::$fieldAliases);

        (object) $taxonomy = new Static($WPPModelObjectToDataObjectConverter->convertedDataObject());

        return $taxonomy;
   }

   public function url()
   {
        if ($this->url == null) {
            $this->url = get_category_link($this->id);
        }

        return $this->url;
   }

   public function firstLetterOfName()
   {
       return substr($this->name, 0, 1);
   }

   public function description()
   {
       if ($this->description == null) {
           $this->description = term_description($this->id);
       }

       return $this->description;
   }

   public function hasDescription()
   {

       return !empty($this->description());
   }
}