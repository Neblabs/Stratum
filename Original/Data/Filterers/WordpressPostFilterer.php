<?php

namespace Stratum\Original\Data\Filterer;

use Stratum\Original\Data\Data;

Abstract Class WordpressPostFilterer
{
    protected $data;

    abstract public function applyFilterToTitleIfExists();
    abstract public function applyFilterToBodyIfExists();

    public function __construct(Data $data)
    {
        $this->data = $data;
    }

    public static function create(Data $data)
    {
        if (function_exists('apply_filters')) {
            return new ExistingFilterAPIPostFilterer($data);
        } 

        return new UnexistingFilterAPIPostFilterer($data);
    }
}