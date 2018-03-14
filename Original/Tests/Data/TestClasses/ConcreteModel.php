<?php

namespace Stratum\Original\Test\Data\TestClass;

use Stratum\Original\Data\Model;

Class ConcreteModel extends Model
{
    protected $alias = 'test_table_posts';
    
    protected function getName()
    {
        return 'Edited Name by Model';
    }

    protected function getYear()
    {
        return 'Model took precedence: year';
    }
}