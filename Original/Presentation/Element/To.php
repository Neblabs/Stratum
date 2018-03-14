<?php

namespace Stratum\Original\Presentation\Element;

Class To
{
    protected $accessType;

    public static function manageAttributes()
    {
        return new Static('manageAttributes');
    }

    public static function managePositions()
    {
        return new Static('managePositions');
    }

    public static function manageContent()
    {
        return new Static('manageContent');
    }

    public function __construct($accessType)
    {
        $this->accessType = $accessType;
    }

    public function accessType()
    {
        return $this->accessType;
    }

    public function levelsDeep($numberOfLevelsDeep)
    {
        $this->numberOfLevelsDeep = $numberOfLevelsDeep;

        return $this;
    }

    public function numberOfLevelsDeep()
    {
        return $this->numberOfLevelsDeep;
    }
}




