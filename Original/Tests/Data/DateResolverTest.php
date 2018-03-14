<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Data\DateResolver;
use Stratum\Original\Data\Exception\BadlyFormatedDateException;

Class DateResolverTest extends TestCase
{
    public function test_returns_true_if_is_only_year_and_correctly_returns_it()
    {
        (object) $DateResolver = new DateResolver('2012');

        $this->assertTrue($DateResolver->isYear());
        $this->assertFalse($DateResolver->isMonthOfYear());
        $this->assertFalse($DateResolver->isDayOfYear());

        $this->assertEquals(2012, $DateResolver->year());
        $this->assertEquals(null, $DateResolver->month());
        $this->assertEquals(null, $DateResolver->day());
    }


    public function test_returns_true_if_is_year_and_a_month_and_correctly_returns_it()
    {
        (object) $DateResolver = new DateResolver('01/2012');

        $this->assertFalse($DateResolver->isYear());
        $this->assertTrue($DateResolver->isMonthOfYear());
        $this->assertFalse($DateResolver->isDayOfYear());

        $this->assertEquals(2012, $DateResolver->year());
        $this->assertEquals(01, $DateResolver->month());
        $this->assertEquals(null, $DateResolver->day());
    }

    public function test_returns_true_if_is_year_month_and_day_and_correctly_returns_it()
    {
        (object) $DateResolver = new DateResolver('30/01/2012');

        $this->assertFalse($DateResolver->isYear());
        $this->assertFalse($DateResolver->isMonthOfYear());
        $this->assertTrue($DateResolver->isDayOfYear());

        $this->assertEquals(2012, $DateResolver->year());
        $this->assertEquals(01, $DateResolver->month());
        $this->assertEquals(30, $DateResolver->day());
    }

    public function test_throws_exception_if_passed_a_string_as_date()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: februaryTwelve");

        (object) $DateResolver = new DateResolver('februaryTwelve');
    }

    public function test_throws_exception_if_single_year_is_less_than_4_digits()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 201");

        (object) $DateResolver = new DateResolver('201');
    }

    public function test_throws_exception_if_single_year_is_more_than_4_digits()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 20121");

        (object) $DateResolver = new DateResolver('20121');
    }

    public function test_throws_exception_if_is_not_separated_by_slashes()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 122012");

        (object) $DateResolver = new DateResolver('122012');
    }

    public function test_throws_exception_if_is_not_separated_by_slashes_2()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 12-2012");

        (object) $DateResolver = new DateResolver('12-2012');
    }

    public function test_throws_exception_if_month_is_one_digit()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 1/2012");

        (object) $DateResolver = new DateResolver('1/2012');
    }

    public function test_throws_exception_if_month_is_more_than_2_digits()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 122/2012");

        (object) $DateResolver = new DateResolver('122/2012');
    }

    public function test_throws_exception_if_month_is_higher_than_12()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 13/2012");

        (object) $DateResolver = new DateResolver('13/2012');
    }

    public function test_throws_exception_if_day_is_one_digit()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 4/11/2012");

        (object) $DateResolver = new DateResolver('4/11/2012');
    }

    public function test_throws_exception_if_day_is_more_than_2_digits()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 023/11/2012");

        (object) $DateResolver = new DateResolver('023/11/2012');
    }

    public function test_throws_exception_if_month_is_one_digit_with_day()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 04/1/2012");

        (object) $DateResolver = new DateResolver('04/1/2012');
    }

    public function test_throws_exception_if_month_is_more_than_2_digits_with_day()
    {
        $this->expectException(BadlyFormatedDateException::class);
        $this->expectExceptionMessage("Date Format must be either a four digit year (2012), a valid month and year (09/2012) or a valid day, month and year (31/09/2012), given date: 04/124/2012");

        (object) $DateResolver = new DateResolver('04/124/2012');
    }











}