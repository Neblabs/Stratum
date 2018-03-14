<?php

namespace Stratum\Original\Test\Data\TestClass;

use Stratum\Original\Data\Finder\SingleEntityFinder;

Abstract Class FinderWithSecondaryKeys extends SingleEntityFinder
{
    protected $secondaryKeys = [
        'name'
    ];
}