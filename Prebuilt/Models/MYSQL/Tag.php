<?php

namespace Stratum\Prebuilt\Model\MYSQL;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class Tag extends TaxonomyHelper
{
    use ClassName;

    protected static $taxonomyQueryName = 'tag';
}