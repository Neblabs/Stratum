<?php

use Stratum\Original\Utility\Type\TypeConverter;
use PHPUnit\Framework\TestCase;

Class TypeConverterTest extends TestCase
{
	public function setUp()
	{
		$this->converter = new TypeConverter;
	}

	public function test_converts_strings_that_only_contain_numbers_to_integers()
	{
		$this->assertInternalType('integer', $this->converter->convertType('557334848'));
		$this->assertInternalType('integer', $this->converter->convertType('   5    '));
		$this->assertInternalType('string', $this->converter->convertType('a979'));
		$this->assertInternalType('string', $this->converter->convertType('979leuf'));
		$this->assertInternalType('string', $this->converter->convertType(' kjrhglirhg'));
		$this->assertInternalType('string', $this->converter->convertType('88474hghgh9898'));
	}
}