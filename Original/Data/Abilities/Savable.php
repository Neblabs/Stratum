<?php

namespace Stratum\Original\Data\Ability;

Interface Savable
{
    public function save();
    public function wasSaved();
}