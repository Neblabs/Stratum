<?php

use PHPUnit\Framework\TestCase;
use Stratum\Custom\Manager\StratumTestManager;
use Stratum\Original\Presentation\Compiler\EOMElementCompiler;
use Stratum\Original\Presentation\EOM\Element;
use Stratum\Original\Presentation\EOM\Text;
use Stratum\Original\Presentation\Registrator\ManagerTaskRegistrator;

Class EOMElementCompilerTest extends TestCase
{
    public static function setUpBeforeClass()
    {
        (string) $TestFinder = file_get_contents('Original/Tests/Presentation/TestClasses/StratumTestManager.php');
        file_put_contents('Design/Present/Managers/StratumTestManager.php', $TestFinder);

    }

    public static function tearDownAfterClass()
    {
        unlink(STRATUM_ROOT_DIRECTORY . '/Design/Present/Managers/StratumTestManager.php');
    }

    public function test_compiles_a_non_void_element()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL .
                                        PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_a_void_element()
    {
        (object) $element = new Element([
            'type' => 'img',
            'isVoid' => true
        ]);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'img',
                'isVoid' => true
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        
        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_static_class()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('main');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementVariable()}->addclass(\"main\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_2_static_attributes()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('main');
        $element->addDataPointer(56);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementVariable()}->addclass(\"main\");" . PHP_EOL;

            $expectedCompiledElement .= "{$EOMElementCompiler->elementVariable()}->adddataPointer(\"56\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_string_interpolation_simple_variable()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$direction}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_2_variables()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction) float-(float.direction)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$direction} float-{\$float->direction}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_string_interpolation_variable_with_property()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(position.direction)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$position->direction}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_string_interpolation_variable_with_method()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(position.direction())');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$position->direction()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_string_interpolation_variable_with_array()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(position:direction)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$position['direction']}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_static_id_and_a_dynamic_class_string_interpolation_variable_with_property()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addid('ppal');
        $element->addClass('position-(position.direction)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementVariable()}->addid(\"ppal\");" . PHP_EOL;

            $expectedCompiledElement .= 
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{\$position->direction}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_simple_variable_with_a_formatter()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction | inUppercase)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_simple_variable_with_2_formatter()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction | inUppercase | nowhitespace)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase, nowhitespace]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_variable_with_property_and_method_calls_with_a_formatter()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction.position.first() | inUppercase)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction->position->first());". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_dynamic_class_2_variables_with_a_formatter_each()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('position-(direction | inUppercase) float-(float.position | horizontal)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;

        $expectedCompiledElement .= " (object) {$EOMElementCompiler->formattersHandlerVariable()[1]} = new FormattersHandler(\$float->position);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[1]}->setFormatterNames([horizontal]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()} float-{{$EOMElementCompiler->formattersHandlerVariable()[1]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

     }

    public function test_compiles_an_element_with_a_dynamic_class_2_variables_with_2_formatters_each()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass(
            'position-(direction | inUppercase | nowhitespace) float-(float.position | horizontal | alphabetic)'
        );

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase, nowhitespace]);" . PHP_EOL;

        $expectedCompiledElement .= " (object) {$EOMElementCompiler->formattersHandlerVariable()[1]} = new FormattersHandler(\$float->position);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[1]}->setFormatterNames([horizontal, alphabetic]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()} float-{{$EOMElementCompiler->formattersHandlerVariable()[1]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_a_static_id_and_a_dynamic_class_2_variables_with_a_formatter_each()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addId('main');
        $element->addClass('position-(direction | inUppercase) float-(float.position | horizontal)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addid(\"main\");" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;

        $expectedCompiledElement .= " (object) {$EOMElementCompiler->formattersHandlerVariable()[1]} = new FormattersHandler(\$float->position);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[1]}->setFormatterNames([horizontal]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()} float-{{$EOMElementCompiler->formattersHandlerVariable()[1]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

     }

    public function test_compiles_an_element_with_a_dynamic_id_with_formatter_and_a_dynamic_class_2_variables_with_a_formatter_each()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addId('main-(header.name | inLowerCase)');
        $element->addClass('position-(direction | inUppercase) float-(float.position | horizontal)');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]} = new FormattersHandler(\$header->name);". PHP_EOL .
            "{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->setFormatterNames([inLowerCase]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addid(\"main-{{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$direction);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;

        $expectedCompiledElement .= " (object) {$EOMElementCompiler->formattersHandlerVariable()[1]} = new FormattersHandler(\$float->position);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[1]}->setFormatterNames([horizontal]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"position-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()} float-{{$EOMElementCompiler->formattersHandlerVariable()[1]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

     }

     public function test_compiles_an_element_with_a_manager_task()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addshowif('text');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Prebuilt\Manager\VisibilityManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('showif');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"text\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);
    
    }

     public function test_compiles_an_element_with_a_custom__registered_manager_task()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('text');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"text\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_2_manager_tasks()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
        
        $element->addshowif('true');
        $element->addtesttask('text');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Prebuilt\Manager\VisibilityManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('showif');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"true\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;

            $expectedCompiledElement .=
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[1]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTaskArgument(\"text\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[1]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->removeAll();
        
    
    }

    public function test_compiles_an_element_with_a_custom_registered_manager_task_with_variable()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('(header.options)');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\$header->options);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_an_id_and_a_custom_registered_manager_task_with_variable()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('(header.options)');
        $element->addid('main');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\$header->options);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;

            $expectedCompiledElement .= 
                                        "{$EOMElementCompiler->elementVariable()}->addid(\"main\");" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_2_manager_tasks_with_variables()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addshowif('(options)');
        $element->addtesttask('(header.options)');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Prebuilt\Manager\VisibilityManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('showif');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\$options);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;

            $expectedCompiledElement .=
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[1]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTaskArgument(\$header->options);" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[1]});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;


        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_a_custom_registered_manager_task_with_variable_with_text()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('numberOfOptions: (header.options.count())');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"numberOfOptions: {\$header->options->count()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_a_custom_registered_manager_task_with_variable_with_text_And_a_formatter()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('numberOfOptions: (header.options.count() | inUppercase)');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$header->options->count());". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"numberOfOptions: {{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_2_manager_tasks_with_variables_and_a_formatter_for_each_variable()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addshowif('(options | nowhitespace)');
        $element->addtesttask('(header.options | inUppercase)');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Prebuilt\Manager\VisibilityManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('showif');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]} = new FormattersHandler(\$options);". PHP_EOL .
            "{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->setFormatterNames([nowhitespace]);" . PHP_EOL . PHP_EOL .  PHP_EOL;

            $expectedCompiledElement .= 
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"{{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;

            $expectedCompiledElement .=
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[1]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$header->options);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL . PHP_EOL . PHP_EOL;

            $expectedCompiledElement .=
                                        "{$EOMElementCompiler->elementManagerVariables()[1]}->setTaskArgument(\"{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[1]});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;


        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_a_custom_registered_manager_task_with_2_variables_one_formater_for_each()
    {
        (object) $managerRegistrator = new ManagerTaskRegistrator('StratumTestManager');
        $managerRegistrator->setTask('test-task');
        $managerRegistrator->register();

        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);
    
        $element->addtesttask('numberOfOptions: (header.options.count() | inUppercase)  (header.name | nowhitespace)');
    
        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .
                                        "(object) {$EOMElementCompiler->elementManagerVariables()[0]} = new Stratum\Custom\Manager\StratumTestManager({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTask('testtask');" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$header->options->count());". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;

            $expectedCompiledElement .= " (object) {$EOMElementCompiler->formattersHandlerVariable()[1]} = new FormattersHandler(\$header->name);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[1]}->setFormatterNames([nowhitespace]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL . PHP_EOL . 
                                        "{$EOMElementCompiler->elementManagerVariables()[0]}->setTaskArgument(\"numberOfOptions: {{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}  {{$EOMElementCompiler->formattersHandlerVariable()[1]}->formatText()}\");" . PHP_EOL;

            $expectedCompiledElement .= PHP_EOL .
                                        "\$elementManagersQueue->addManagerToQueue({$EOMElementCompiler->elementManagerVariables()[0]});" . PHP_EOL;
    
            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL;

        $this->assertRegexp('/\$element[0-9]{7}/', $EOMElementCompiler->elementVariable());
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

        $managerRegistrator->remove('testtask');
    
    }

    public function test_compiles_an_element_with_1_child()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $element->addChild($elementChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_2_children()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";


            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);
        $element->addChild($elementThirdChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";


            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children_and_1_grandChildren()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $elementChildChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);
        $element->addChild($elementThirdChild);

        $elementChild->addChild($elementChildChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]} = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addChild({$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";


            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_text_child()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $Text = new Text;
        $Text->addContent('Hello, there!');

        $element->addChild($Text);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"Hello, there!\");";

            $expectedCompiledElement .=  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children_elements_4_text_children_and_1_grandChildren()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $elementChildChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        $textBeforeP = new Text;
        $textBeforeP->addContent('first text');

        $textBeforeB = new Text;
        $textBeforeB->addContent('second text');

        $textBeforeSection = new Text;
        $textBeforeSection->addContent('third text');

        $textAfterSection = new Text;
        $textAfterSection->addContent('fourth text');

        $element->addChild($textBeforeP);
        $element->addChild($elementChild);
        $element->addChild($textBeforeB);
        $element->addChild($elementSecondChild);
        $element->addChild($textBeforeSection);
        $element->addChild($elementThirdChild);
        $element->addChild($textAfterSection);

        $elementChild->addChild($elementChildChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"first text\");";

        $expectedCompiledElement .=  PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]} = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addChild({$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=   PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"second text\");";

        $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"third text\");";

        $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";

        $expectedCompiledElement .=   PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"fourth text\");";


            $expectedCompiledElement .= PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_text_child_text_with_variable()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $Text = new Text;
        $Text->addContent('Hello, (user.name)!');

        $element->addChild($Text);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"Hello, {\$user->name}!\");";

            $expectedCompiledElement .=  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_text_child_with_variable()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $Text = new Text;
        $Text->addContent('(user.name)');

        $element->addChild($Text);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        
        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\$user->name);";

            $expectedCompiledElement .=  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_text_child_text_with_variable_and_a_formatter()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $Text = new Text;
        $Text->addContent('Hello, (user.name | inUppercase)!');

        $element->addChild($Text);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]} = new FormattersHandler(\$user->name);". PHP_EOL .
            "{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;

        
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addContent(\"Hello, {{$EOMElementCompiler->variableResolvers()[0]->formattersHandlerVariable()[0]}->formatText()}!\");";

            $expectedCompiledElement .=  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;
                                        
        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_child_with_class_in_top_level_element()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $element->addClass('container');
        $element->addChild($elementChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addclass(\"container\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_child_with_a_class_for_each_element()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $element->addClass('container');
        $element->addChild($elementChild);

        $elementChild->addClass('child-container');

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addclass(\"container\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addclass(\"child-container\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children_and_1_grandChildren_with_a_class_and_an_id_for_each_element()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $elementChildChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        $element->addClass('div-class');
        $element->addid('div-id');

        $elementChild->addClass('p-class');
        $elementChild->addid('p-id');

        $elementSecondChild->addClass('b-class');
        $elementSecondChild->addid('b-id');

        $elementThirdChild->addClass('section-class');
        $elementThirdChild->addid('section-id');

        $elementChildChild->addClass('span-class');
        $elementChildChild->addid('span-id');

        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);
        $element->addChild($elementThirdChild);

        $elementChild->addChild($elementChildChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addclass(\"div-class\");";
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addid(\"div-id\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addclass(\"p-class\");";
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addid(\"p-id\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]} = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]}->addclass(\"span-class\");";
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]}->addid(\"span-id\");";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addChild({$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[1]}->addclass(\"b-class\");";
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[1]}->addid(\"b-id\");";


        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[2]}->addclass(\"section-class\");";
        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[2]}->addid(\"section-id\");";


        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";


            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_component()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $component = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => false
        ]);

        $component->addname('Header');

        $element->addChild($component);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Stratum\Custom\Component\Header(\$this->variables);";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChildren({$EOMElementCompiler->descendantNodesVariables()[0]}->elements());";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_component_with_binded_value()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $component = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => false
        ]);

        $component->adduse('(header.options)');
        $component->addname('Header');

        $element->addChild($component);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Stratum\Custom\Component\Header(\$this->variables);";

        $expectedCompiledElement.= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->use(\$header->options);" ;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChildren({$EOMElementCompiler->descendantNodesVariables()[0]}->elements());";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children_and_1_grandChildren_inside_a_foreach_loop_all_descendants_use_the_aliased_variable()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        $element->addClass('div-(post.id | inUppercase)');
        $element->addCreateForEach('post in posts');

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        $elementChild->addClass('p-(post)');

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        $elementSecondChild->addClass('b-(post.slug)');

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        $elementThirdChild->addClass('section-(post:0)');

        (object) $elementChildChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        $elementChildChild->addClass('span-(post.meta.data())');

        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);
        $element->addChild($elementThirdChild);

        $elementChild->addChild($elementChildChild);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = "foreach (\$posts as \$post) {" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->formattersHandlerVariable()[0]} = new FormattersHandler(\$post->id);". PHP_EOL .
            "{$EOMElementCompiler->formattersHandlerVariable()[0]}->setFormatterNames([inUppercase]);" . PHP_EOL;


            $expectedCompiledElement .= PHP_EOL .
        "{$EOMElementCompiler->elementVariable()}->addclass(\"div-{{$EOMElementCompiler->formattersHandlerVariable()[0]}->formatText()}\");" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->descendantNodesVariables()[0]}->addclass(\"p-{\$post}\");" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]} = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]}->addclass(\"span-{\$post->meta->data()}\");" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addChild({$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->descendantNodesVariables()[1]}->addclass(\"b-{\$post->slug}\");";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .
                                        "{$EOMElementCompiler->descendantNodesVariables()[2]}->addclass(\"section-{\$post[0]}\");";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";


        $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $expectedCompiledElement .= PHP_EOL . "}" . PHP_EOL;                                  


        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_3_children_and_1_grandChildren_with_text_with_variable_inside_foreach()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $elementChild = new Element([
            'type' => 'p',
            'isVoid' => false
        ]);

        (object) $elementSecondChild = new Element([
            'type' => 'b',
            'isVoid' => false
        ]);

        (object) $elementThirdChild = new Element([
            'type' => 'section',
            'isVoid' => false
        ]);

        (object) $elementChildChild = new Element([
            'type' => 'span',
            'isVoid' => false
        ]);

        (object) $Text = new Text;
        $Text->addContent('(post.title)');

        $element->addCreateForEach('post in posts');
        $element->addChild($elementChild);
        $element->addChild($elementSecondChild);
        $element->addChild($elementThirdChild);

        $elementChild->addChild($elementChildChild);

        $elementChildChild->addChild($Text);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = "foreach (\$posts as \$post) {" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .  "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'p',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]} = new Element([
                'type' => 'span',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]}->addContent(\$post->title);";

        $expectedCompiledElement .= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->addChild({$EOMElementCompiler->childrenCompilers()[0]->descendantNodesVariables()[0]});";

        $expectedCompiledElement .=  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[1]} = new Element([
                'type' => 'b',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[1]});";

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[2]} = new Element([
                'type' => 'section',
                'isVoid' => false
            ]);" . PHP_EOL . PHP_EOL . PHP_EOL ;


        $expectedCompiledElement .=  PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[2]});";


            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $expectedCompiledElement .= PHP_EOL . "}" . PHP_EOL;   

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_component_with_binded_value_inside_foreach_loop()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $component = new Element([
            'type' => 'stratumcomponent',
            'isVoid' => false
        ]);

        $component->adduse('(post.options)');
        $component->addname('Header');

        $element->addCreateForEach('post in posts');

        $element->addChild($component);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();
        
        (string) $expectedCompiledElement = "foreach (\$posts as \$post) {" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .  "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Stratum\Custom\Component\Header(\$this->variables);";

        $expectedCompiledElement.= PHP_EOL . "{$EOMElementCompiler->descendantNodesVariables()[0]}->use(\$post->options);" ;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChildren({$EOMElementCompiler->descendantNodesVariables()[0]}->elements());";

            $expectedCompiledElement .= PHP_EOL .  PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

        $expectedCompiledElement .= PHP_EOL . "}" . PHP_EOL;   

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }

    public function test_compiles_an_element_with_1_child_with_foreach()
    {
        (object) $element = new Element([
            'type' => 'div',
            'isVoid' => false
        ]);

        (object) $child = new Element([
            'type' => 'li',
            'isVoid' => false
        ]);

        $child->addCreateForEach('post in posts');

        $element->addChild($child);

        (object) $EOMElementCompiler = new EOMElementCompiler($element);
        
        (string) $actualCompiledElement = $EOMElementCompiler->compiledElement();

        (string) $expectedCompiledElement = PHP_EOL .  "(object) {$EOMElementCompiler->elementVariable()} = new Element([
                'type' => 'div',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL . PHP_EOL . "foreach (\$posts as \$post) {" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .  "(object) {$EOMElementCompiler->descendantNodesVariables()[0]} = new Element([
                'type' => 'li',
                'isVoid' => false
            ]);" . PHP_EOL;

        $expectedCompiledElement .= PHP_EOL .  PHP_EOL .  PHP_EOL . PHP_EOL . "{$EOMElementCompiler->elementVariable()}->addChild({$EOMElementCompiler->descendantNodesVariables()[0]});";
        
        $expectedCompiledElement .= PHP_EOL . "}";  

            $expectedCompiledElement .= PHP_EOL .
                                        "\$groupOfNodes->add({$EOMElementCompiler->elementVariable()});" . PHP_EOL ;

         

        $this->assertEquals($expectedCompiledElement, $actualCompiledElement);

    }
    
}