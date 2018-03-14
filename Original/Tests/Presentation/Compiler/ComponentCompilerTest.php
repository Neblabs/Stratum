<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Compiler\ComponentCompiler;
use Stratum\Original\Presentation\EOM\Element;

Class ComponentCompilerTest extends TestCase
{
    public function test_compiles_simple_component()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Header');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Custom\\Component\\Header(\$this->variables);" . PHP_EOL . PHP_EOL;
        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

    public function test_compiles_component_with_binded_text()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Header');
        $componentElement->adduse('text');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Custom\\Component\\Header(\$this->variables);" . PHP_EOL;

        $expectedCompiledComponent.= "{$ComponentCompiler->componentVariable()}->use(\"text\");" . PHP_EOL;

        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

    public function test_compiles_component_with_binded_value_from_variable()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Header');
        $componentElement->adduse('(options)');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Custom\\Component\\Header(\$this->variables);" . PHP_EOL;

        $expectedCompiledComponent.= "{$ComponentCompiler->componentVariable()}->use(\$options);" . PHP_EOL;

        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

    public function test_compiles_prebuilt_content_component()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Content');
        $componentElement->addUse('(stratumPartialView)');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Prebuilt\\Component\\Content(\$this->variables);" . PHP_EOL;

        $expectedCompiledComponent.= "{$ComponentCompiler->componentVariable()}->use(\$stratumPartialView);" . PHP_EOL;

        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

    public function test_compiles_component_with_binded_value_from_variable_text()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Header');
        $componentElement->adduse('options: (options.count())');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Custom\\Component\\Header(\$this->variables);" . PHP_EOL;

        $expectedCompiledComponent.= "{$ComponentCompiler->componentVariable()}->use(\"options: {\$options->count()}\");" . PHP_EOL;

        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

    public function test_compiles_component_with_binded_value_from_variable_with_formatters()
    {
        (object) $componentElement  = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => true
        ]);

        $componentElement->addName('Header');
        $componentElement->adduse('(options | inUpperCase)');

        (object) $ComponentCompiler = new ComponentCompiler($componentElement);

        (string) $actualCompiledComponent = $ComponentCompiler->compiledComponent();
        (string) $expectedCompiledComponent = "(object) {$ComponentCompiler->componentVariable()} = new Stratum\\Custom\\Component\\Header(\$this->variables);" . PHP_EOL;

        (string) $formattersHandlerName = $ComponentCompiler->variableResolver()->formattersHandlerVariable()[0];

        $expectedCompiledComponent.= "(object) {$formattersHandlerName} = new FormattersHandler(\$options);". PHP_EOL .
            "{$formattersHandlerName}->setFormatterNames([inUpperCase]);" . PHP_EOL;

        $expectedCompiledComponent.= PHP_EOL . "{$ComponentCompiler->componentVariable()}->use(\"{{$formattersHandlerName}->formatText()}\");" . PHP_EOL;

        $expectedCompiledComponent.= "\$groupOfNodes->addNodes({$ComponentCompiler->componentVariable()}->elements());". PHP_EOL . PHP_EOL;

        $this->assertEquals($expectedCompiledComponent, $actualCompiledComponent); 
        $this->assertEquals("{$ComponentCompiler->componentVariable()}->elements();", $ComponentCompiler->getNodesDefinition());
    }

}