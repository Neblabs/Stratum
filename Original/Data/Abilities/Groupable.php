<?php

namespace Stratum\Original\Data\Ability;

Interface Groupable
{
    public function first();

    public function last();

    public function atPosition($number);

    public function count();

    public function wereFound();

    public function groupsOf($numberOfItemsPerGroup);

    public function asArray();
}