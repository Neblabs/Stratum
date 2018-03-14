<?php

namespace Stratum\Original\Presentation\EOM;

Class Attributes
{
    protected $attributes = [];
    protected static $cleanedAttributeNames = [];

    public function add(array $attribute)
    {
        $this->createIndexIfItDoesntExistForAttributeName($attribute['name']);

        $this->attributes[$attribute['name']] .= $attribute['value'] . ' ';
    }

    public function set(array $attribute)
    {
        $this->createIndexIfItDoesntExistForAttributeName($attribute['name']);

        $this->attributes[$attribute['name']] = $attribute['value'] . ' ';
    }

    public function get($attributeName)
    {
        return $this->attributeNameExists($attributeName) ? trim($this->attributes[$attributeName]) : null;
    }

    public function has(array $attribute)
    {
        if (!$this->attributeNameExists($attribute['name'])) return false;

        (boolean) $attributeValueExists = strpos(
            ' ' . $this->attributes[$attribute['name']],
            ' ' . $attribute['value'] . ' '
            ) !== false;

        return $attributeValueExists;
    }

    public function remove(array $attribute)
    {
        if ($this->attributeNameExists($attribute['name'])
            and
            $this->has(['name' => $attribute['name'], 'value' => $attribute['value']])) {

            (array) $attributesArray = $this->attributes[$attribute['name']];

            (string) $updatedWithRequestedValueRemoved = substr_replace(
                $attributesArray,
                '',
                stripos($attributesArray, $attribute['value']),
                strlen($attribute['value'] . ' '));

            $this->attributes[$attribute['name']] = 
            $updatedWithRequestedValueRemoved === '' ? null : $updatedWithRequestedValueRemoved;
        }
    }

    public function asArray()
    {
        return $this->cleanAttributes();
    }

    protected function cleanAttributes() {
        (array) $cleanedAttributes = [];

        foreach ($this->attributes as $name => $value) {
            $cleanedAttributes[$this->convertToHyphen($name)] = trim($value);
        }

        return array_filter($cleanedAttributes);
    }

    protected function createIndexIfItDoesntExistForAttributeName($name)
    {
        if (!$this->attributeNameExists($name)) {
            $this->attributes[$name] = '';
        }
    }

    protected function attributeNameExists($name)
    {
        return isset($this->attributes[$name]) or isset($this->attributes[$this->convertToHyphen($name)]);
    }

    protected function convertToHyphen($attributeName)
    {
        if (isset(static::$cleanedAttributeNames[$attributeName])) {
            return static::$cleanedAttributeNames[$attributeName];
        }

        (string) $cleanedAttributeName = strtolower(preg_replace('/[A-Z]/', '-$0', lcfirst($attributeName)));

        static::$cleanedAttributeNames[$attributeName] = $cleanedAttributeName;

        return $cleanedAttributeName;
    }









}