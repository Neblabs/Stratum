<?php

namespace Stratum\Original\Data;

use Stratum\Original\Data\Exception\BadlyFormatedDateException;

Class DateResolver
{
    protected $date;

    public function __construct($date)
    {
        $this->date = $date;
        $this->dateElements = explode('/', $this->date);
        $this->numberOfDateElements = count($this->dateElements);

        $this->throwExceptionIfDateIsBadlyFormated();
    }

    public function isDayOfYear()
    {
        return $this->numberOfDateElementsIs(3);
    }

    public function isMonthOfYear()
    {
        return $this->numberOfDateElementsIs(2);
    }

    public function isYear()
    {
        return $this->numberOfDateElementsIs(1) and strlen($this->dateElements[0]) === 4;
    }

    public function day()
    {
        return $this->isDayOfYear() ? (integer) $this->dateElements[0] : null;
    }

    public function month()
    {
        if ($this->isMonthOfYear()) {
            return (integer) $this->dateElements[0];
        } elseif ($this->isDayOfYear()) {
            return (integer) $this->dateElements[1];
        }
    }

    public function year()
    {
        if ($this->isDayOfYear()) {
            return (integer) $this->dateElements[2];
        } elseif ($this->isMonthOfYear()) {
            return (integer) $this->dateElements[1];
        } elseif ($this->isYear()) {
            return (integer) $this->dateElements[0];
        }
    }

    protected function numberOfDateElementsIs($number)
    {
        return $this->numberOfDateElements === $number;
    }

    protected function throwExceptionIfDateIsBadlyFormated()
    {
        (string) $oneToTwelve = '(0[1-9]|1[0-2])';
        (string) $oneToThirtyOne = '(0[1-9]|1[0-9]||2[0-9]||3[0-1])';
        (string) $fourDigits = '[0-9]{4}';
        
        (string) $validDate = "/^($fourDigits|($oneToTwelve\/$fourDigits)|($oneToThirtyOne\/$oneToTwelve\/$fourDigits))$/";
        (boolean) $isNotValidDate = !preg_match($validDate, $this->date);

        if ($isNotValidDate) {
            throw new BadlyFormatedDateException(
                "Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: $this->date"
            );
        }
    }










}