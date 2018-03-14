<?php

namespace Stratum\Prebuilt\Model\MYSQL;

use Stratum\Original\Utility\ClassUtility\ClassName;

Class Category extends TaxonomyHelper
{
    use ClassName;

    protected static $taxonomyQueryName = 'cat';
}