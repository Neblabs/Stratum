<?php

namespace Stratum\Original\Presentation\Element;

Class ManagerTaskType
{
    protected $managerClassName;

    protected $usesEOM = false;
    protected $usesPreviousSiblings = false;
    protected $usesNextSiblings = false;
    protected $usesChildren = false;
    protected $usesDescendants = false;
    protected $descendantsDeepLevels = 2;
    protected $usesParent = false;

    protected $previousSiblingsAccessType = 'manageAttributes';
    protected $nextSiblingsAccessType = 'manageAttributes';
    protected $childrenAccessType = 'manageAttributes';
    protected $descendantsAccessType = 'manageAttributes';
    protected $parentAccessType = 'manageAttributes';

    public static function __set_state(array $properties)
    {
        (object) $ManagerTaskType = new Static($properties['managerClassName']);

        foreach ($properties as $property => $value) {
            $ManagerTaskType->{$property} = $value;
        }

        return $ManagerTaskType;
    }
    public function __construct($managerClassName)
    {
        $this->managerClassName = $managerClassName;
    }

    public function className()
    {
        return $this->managerClassName;
    }

    public function setUsesEOM($usesEOM)
    {
        $this->usesEOM = $usesEOM;
    }

    public function setUsesParent(To $accessType)
    {
        $this->usesParent = true;
        $this->parentAccessType = $accessType->accessType();
    }

    public function setUsesPreviousSiblings(To $accessType)
    {
        $this->usesPreviousSiblings = true;
        $this->previousSiblingsAccessType = $accessType->accessType();
    }

    public function setUsesNextSiblings(To $accessType)
    {
        $this->usesNextSiblings = true;
        $this->nextSiblingsAccessType = $accessType->accessType();
    }

    public function setUsesAllSiblings(To $accessType)
    {
        $this->usesPreviousSiblings = true;
        $this->usesNextSiblings = true;

        $this->previousSiblingsAccessType = $accessType->accessType();
        $this->nextSiblingsAccessType = $accessType->accessType();
    }

    public function setUsesChildren(To $accessType)
    {
        $this->usesChildren = true;
        $this->childrenAccessType = $accessType->accessType();
    }

    public function setUsesDescendants(To $accessType)
    {
        $this->usesDescendants = true;
        $this->descendantsDeepLevels = $accessType->numberOfLevelsDeep();
        $this->descendantsAccessType = $accessType->accessType();
    }
}











