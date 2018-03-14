<?php

use Stratum\Original\Presentation\EOM\DynamicAttributesResolver;
use PHPUnit\Framework\TestCase;

Class DynamicAttributesResolverTest extends TestCase
{
    public function setUp()
    {
        $this->hasIdMethod = new DynamicAttributesResolver('hasId');
        $this->setIdMethod = new DynamicAttributesResolver('setId');
        $this->addIdMethod = new DynamicAttributesResolver('addId');
        $this->removeIdMethod = new DynamicAttributesResolver('removeId');
        $this->withIdMethod = new DynamicAttributesResolver('withId');

        $this->hasDataPositionMethod = new DynamicAttributesResolver('hasDataPosition');
        $this->setDataPositionMethod = new DynamicAttributesResolver('setDataPosition');
        $this->addDataPositionMethod = new DynamicAttributesResolver('addDataPosition');
        $this->removeDataPositionMethod = new DynamicAttributesResolver('removeDataPosition');
        $this->withDataPositionMethod = new DynamicAttributesResolver('withDataPosition');
        
    }

    public function test_throws_exception_if_is_not_a_supported_method()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage(
            'Call to undefinded method: undefindedMethod()'
        );

        new DynamicAttributesResolver('undefindedMethod');
    }

    public function test_returns_only_the_field_name()
    {   

        $this->assertEquals('id', $this->hasIdMethod->attributeName());
        $this->assertEquals('id', $this->setIdMethod->attributeName());
        $this->assertEquals('id', $this->addIdMethod->attributeName());
        $this->assertEquals('id', $this->removeIdMethod->attributeName());
        $this->assertEquals('id', $this->withIdMethod->attributeName());

        $this->assertEquals('dataPosition', $this->hasDataPositionMethod->attributeName());
        $this->assertEquals('dataPosition', $this->setDataPositionMethod->attributeName());
        $this->assertEquals('dataPosition', $this->addDataPositionMethod->attributeName());
        $this->assertEquals('dataPosition', $this->removeDataPositionMethod->attributeName());
        $this->assertEquals('dataPosition', $this->withDataPositionMethod->attributeName());
    }

    public function test_returns_true_only_when_method_starts_with_has()
    {   

        $this->assertTrue($this->hasIdMethod->isHas());
        $this->assertFalse($this->setIdMethod->isHas());
        $this->assertFalse($this->addIdMethod->isHas());
        $this->assertFalse($this->removeIdMethod->isHas());
        $this->assertFalse($this->withIdMethod->isHas());

        $this->assertTrue($this->hasDataPositionMethod->isHas());
        $this->assertFalse($this->setDataPositionMethod->isHas());
        $this->assertFalse($this->addDataPositionMethod->isHas());
        $this->assertFalse($this->removeDataPositionMethod->isHas());
        $this->assertFalse($this->withDataPositionMethod->isHas());

    }

    public function test_returns_true_only_when_method_starts_with_set()
    {   

        $this->assertFalse($this->hasIdMethod->isSet());
        $this->assertTrue($this->setIdMethod->isSet());
        $this->assertFalse($this->addIdMethod->isSet());
        $this->assertFalse($this->removeIdMethod->isSet());
        $this->assertFalse($this->withIdMethod->isSet());

        $this->assertFalse($this->hasDataPositionMethod->isSet());
        $this->assertTrue($this->setDataPositionMethod->isSet());
        $this->assertFalse($this->addDataPositionMethod->isSet());
        $this->assertFalse($this->removeDataPositionMethod->isSet());
        $this->assertFalse($this->withDataPositionMethod->isSet());

    }

    public function test_returns_true_only_when_method_starts_with_add()
    {   

        $this->assertFalse($this->hasIdMethod->isAdd());
        $this->assertFalse($this->setIdMethod->isAdd());
        $this->assertTrue($this->addIdMethod->isAdd());
        $this->assertFalse($this->removeIdMethod->isAdd());
        $this->assertFalse($this->withIdMethod->isAdd());

        $this->assertFalse($this->hasDataPositionMethod->isAdd());
        $this->assertFalse($this->setDataPositionMethod->isAdd());
        $this->assertTrue($this->addDataPositionMethod->isAdd());
        $this->assertFalse($this->removeDataPositionMethod->isAdd());
        $this->assertFalse($this->withDataPositionMethod->isAdd());

    }

    public function test_returns_true_only_when_method_starts_with_remove()
    {   

        $this->assertFalse($this->hasIdMethod->isRemove());
        $this->assertFalse($this->setIdMethod->isRemove());
        $this->assertFalse($this->addIdMethod->isRemove());
        $this->assertTrue($this->removeIdMethod->isRemove());
        $this->assertFalse($this->withIdMethod->isRemove());

        $this->assertFalse($this->hasDataPositionMethod->isRemove());
        $this->assertFalse($this->setDataPositionMethod->isRemove());
        $this->assertFalse($this->addDataPositionMethod->isRemove());
        $this->assertTrue($this->removeDataPositionMethod->isRemove());
        $this->assertFalse($this->withDataPositionMethod->isRemove());

    }

    public function test_returns_true_only_when_method_starts_with_with()
    {   

        $this->assertFalse($this->hasIdMethod->isWith());
        $this->assertFalse($this->setIdMethod->isWith());
        $this->assertFalse($this->addIdMethod->isWith());
        $this->assertFalse($this->removeIdMethod->isWith());
        $this->assertTrue($this->withIdMethod->isWith());

        $this->assertFalse($this->hasDataPositionMethod->isWith());
        $this->assertFalse($this->setDataPositionMethod->isWith());
        $this->assertFalse($this->addDataPositionMethod->isWith());
        $this->assertFalse($this->removeDataPositionMethod->isWith());
        $this->assertTrue($this->withDataPositionMethod->isWith());

    }











    
}