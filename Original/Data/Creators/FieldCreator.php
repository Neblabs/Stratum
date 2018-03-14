<?php

namespace Stratum\Original\Data\Creator;

use Stratum\Original\Data\Field;

Class FieldCreator
{
    public function createFrom(array $fieldData)
    {
        (object) $field = new Field;

        $field->name = $fieldData['fieldName'];
        $field->value = $fieldData['fieldValue'];

        return $field;
    }
}