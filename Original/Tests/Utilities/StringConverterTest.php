<?php

use PHPUnit\Framework\Testcase;
use Stratum\Original\Utility\StringConverter;

Class StringConverterTest extends Testcase
{
    public function test_removes_dashes_from_string()
    {
        (object) $StringConverter = new StringConverter('separated-words');

        $this->assertEquals('separatedwords', $StringConverter->removeDashes());
    }

    public function test_replaces_dashes_with_upper_cased_letters()
    {
        (object) $StringConverter = new StringConverter('separated-words');

        $this->assertEquals('separatedWords', $StringConverter->replaceDashesWithUpperCasedLetters());
    }

    public function test_replaces_dashes_with_upper_cased_letters_3_words()
    {
        (object) $StringConverter = new StringConverter('separated-by-dashes');

        $this->assertEquals('separatedByDashes', $StringConverter->replaceDashesWithUpperCasedLetters());
    }
}