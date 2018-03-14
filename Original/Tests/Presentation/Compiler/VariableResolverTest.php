<?php


use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Compiler\VariableResolver;

Class VariableResolverTest extends TestCase
{
    public function test_compiles_no_variable_when_is_not_surrounded_by_parenthesis()
    {
        (object) $VariableResolver = new VariableResolver('user');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"user"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_opening_parenthesis()
    {
        (object) $VariableResolver = new VariableResolver('user)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"user)"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_closing_parenthesis()
    {
        (object) $VariableResolver = new VariableResolver('(user');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"(user"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_not_surrounded_by_parenthesis_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_opening_parenthesis_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user)"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_closing_parenthesis_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, (user"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_not_surrounded_by_parenthesis_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.name');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.name"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_opening_parenthesis_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.name)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.name)"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_closing_parenthesis_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, (user.name"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_not_surrounded_by_parenthesis_method_call_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.inUppercase()');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.inUppercase()"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_opening_parenthesis_method_call_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.inUppercase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.inUppercase())"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_closing_parenthesis_method_call_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.inUppercase()');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, (user.inUppercase()"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_not_surrounded_by_parenthesis_method_call_2_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.inUppercase().noWhiteSpace()');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.inUppercase().noWhiteSpace()"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_opening_parenthesis_method_call_2_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, user.inUppercase().noWhiteSpace())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, user.inUppercase().noWhiteSpace())"', $VariableResolver->compiledString());
    }

    public function test_compiles_no_variable_when_is_missing_closing_parenthesis_method_call_2_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.inUppercase().noWhiteSpace()');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, (user.inUppercase().noWhiteSpace()"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_single_variable_with_no_text_no_double_quotes_nor_curly_braces()
    {
        (object) $VariableResolver = new VariableResolver('(user)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('$user', $VariableResolver->compiledString());
    }

    public function test_compiles_a_single_variable_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_2_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name.first)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name->first}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_3_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name.first.inUpperCase)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name->first->inUpperCase}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_method_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.fullName())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->fullName()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_method_2_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.fullName().inUppercase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->fullName()->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_method_3_levels_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.fullName().inUppercase().noWhiteSpace())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->fullName()->inUppercase()->noWhiteSpace()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_2_levels_and_a_method_call_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name.first.inUpperCase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name->first->inUpperCase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_method_call_2_levels_and_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name().first().inUpperCase)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name()->first()->inUpperCase}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_and_a_method_call_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name.inUppercase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_a_method_call_and_property_access_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.random().name)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->random()->name}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_property_access_a_method_call_and_property_access_again_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name.inUppercase().last)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name->inUppercase()->last}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_a_method_call_property_access_and_method_call_again_with_text()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.random().name.inUppercase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->random()->name->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_integer()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:0)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[0]}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name\']}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string_underscore()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name_full)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name_full\']}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string_and_property_access()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name.first)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name\']->first}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string_and_method_call()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name.inUppercase())');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name\']->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_property_access_and_array_access_string()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name:first)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name[\'first\']}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_property_access_and_array_access_integer()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name:0)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name[0]}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_method_call_and_array_access_string()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name():first)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name()[\'first\']}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_method_call_and_array_access_integer()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name():0)');

            $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user->name()[0]}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string_property_access_and_method_call()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name.first.inUppercase())');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name\']->first->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_string_method_call_and_property_access()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:name.first().inUppercase)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[\'name\']->first()->inUppercase}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_integer_property_access_and_method_call()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:0.first.inUppercase())');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[0]->first->inUppercase()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_element_access_integer_method_call_and_property_access()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:0.first().inUppercase)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[0]->first()->inUppercase}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_method_array()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:1.inUppercase():0)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[1]->inUppercase()[0]}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_property_array()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:1.names:0)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[1]->names[0]}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_array()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:1:name)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[1][\'name\']}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_array_property()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:1:name.first)');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[1][\'name\']->first}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_variable_with_array_array_method()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:1:name.first())');

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals('"Hello, {$user[1][\'name\']->first()}"', $VariableResolver->compiledString());
    }

    public function test_compiles_a_string_two_variables()
    {
        (object) $VariableResolver = new VariableResolver(
            'Hello, (user.name)! You have (user.messages.new.count()) new messages'
        );

        $this->assertFalse($VariableResolver->expressionHasFormatters());
        $this->assertEquals(
            '"Hello, {$user->name}! You have {$user->messages->new->count()} new messages"',
            $VariableResolver->compiledString()
        );
    }

    public function test_compiles_a_variable_with_formatters()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name | inUppercase)');

        (string) $formattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler'), strlen('formattersHandler') + 4);

        $this->assertTrue($VariableResolver->expressionHasFormatters());
        $this->assertRegExp('/"Hello\, \{\$formattersHandler([0-9]{4})->formatText\(\)\}"/', $VariableResolver->compiledString());
        $this->assertEquals("\"Hello, {\${$formattersHandlerName}->formatText()}\"", $VariableResolver->compiledString());

        $this->assertEquals(1, count($VariableResolver->formattersHandlers()));

        $this->assertRegExp('/\$formattersHandler[0-9]{4}/', $VariableResolver->formattersHandlerVariable()[0]);
        $this->assertEquals(
            "(object) \${$formattersHandlerName} = new FormattersHandler(\$user->name);". PHP_EOL .
            "\${$formattersHandlerName}->setFormatterNames([inUppercase]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[0]
        );
    }

    public function test_compiles_a_variable_with_2_formatters()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name | inUppercase | noWhiteSpace)');

        (string) $formattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler'), strlen('formattersHandler') + 4);

        $this->assertTrue($VariableResolver->expressionHasFormatters());
        $this->assertRegExp('/"Hello\, \{\$formattersHandler([0-9]{4})->formatText\(\)\}"/', $VariableResolver->compiledString());
        $this->assertEquals("\"Hello, {\${$formattersHandlerName}->formatText()}\"", $VariableResolver->compiledString());

        $this->assertEquals(1, count($VariableResolver->formattersHandlers()));

        $this->assertEquals(
            "(object) \${$formattersHandlerName} = new FormattersHandler(\$user->name);". PHP_EOL .
            "\${$formattersHandlerName}->setFormatterNames([inUppercase, noWhiteSpace]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[0]
        );
    }

    public function test_compiles_a_variable_array_access_with_formatters()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user:0 | inUppercase)');

        (string) $formattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler'), strlen('formattersHandler') + 4);

        $this->assertTrue($VariableResolver->expressionHasFormatters());
        $this->assertRegExp('/"Hello\, \{\$formattersHandler([0-9]{4})->formatText\(\)\}"/', $VariableResolver->compiledString());
        $this->assertEquals("\"Hello, {\${$formattersHandlerName}->formatText()}\"", $VariableResolver->compiledString());

        $this->assertEquals(1, count($VariableResolver->formattersHandlers()));

        $this->assertEquals(
            "(object) \${$formattersHandlerName} = new FormattersHandler(\$user[0]);". PHP_EOL .
            "\${$formattersHandlerName}->setFormatterNames([inUppercase]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[0]
        );
    }

    public function test_compiles_a_variable_method_call_with_formatters()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name() | inUppercase)');

        (string) $formattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler'), strlen('formattersHandler') + 4);

        $this->assertTrue($VariableResolver->expressionHasFormatters());
        $this->assertRegExp('/"Hello\, \{\$formattersHandler([0-9]{4})->formatText\(\)\}"/', $VariableResolver->compiledString());
        $this->assertEquals("\"Hello, {\${$formattersHandlerName}->formatText()}\"", $VariableResolver->compiledString());

        $this->assertEquals(1, count($VariableResolver->formattersHandlers()));

        $this->assertEquals(
            "(object) \${$formattersHandlerName} = new FormattersHandler(\$user->name());". PHP_EOL .
            "\${$formattersHandlerName}->setFormatterNames([inUppercase]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[0]
        );
    }

    public function test_compiles_two_variables_with_formatters()
    {
        (object) $VariableResolver = new VariableResolver('Hello, (user.name | inUppercase). You have (user.comments | twoDigits)');

        (string) $firstFormattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler'), strlen('formattersHandler') + 4);

        (string) $secondFormattersHandlerName = substr($VariableResolver->compiledString(), strpos($VariableResolver->compiledString(), 'formattersHandler', strpos($VariableResolver->compiledString(), 'formattersHandler') + 4), strlen('formattersHandler') + 4);
    
        $this->assertRegExp('/\$formattersHandler[0-9]{4}/', $VariableResolver->formattersHandlerVariable()[0]);
        $this->assertRegExp('/\$formattersHandler[0-9]{4}/', $VariableResolver->formattersHandlerVariable()[1]);
        $this->assertNotEquals($VariableResolver->formattersHandlerVariable()[0],$VariableResolver->formattersHandlerVariable()[1]);
        $this->assertTrue($VariableResolver->expressionHasFormatters());
        $this->assertRegExp(
            '/"Hello\, \{\$formattersHandler([0-9]{4})->formatText\(\)\}\. ' .
            'You have \{\$formattersHandler([0-9]{4})->formatText\(\)\}' .
            '"/',
            $VariableResolver->compiledString()
        );

        $this->assertEquals("\"Hello, {\${$firstFormattersHandlerName}->formatText()}. You have {\${$secondFormattersHandlerName}->formatText()}\"", $VariableResolver->compiledString());

        $this->assertEquals(2, count($VariableResolver->formattersHandlers()));

        $this->assertEquals(
            "(object) \${$firstFormattersHandlerName} = new FormattersHandler(\$user->name);". PHP_EOL .
            "\${$firstFormattersHandlerName}->setFormatterNames([inUppercase]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[0]
        );

        $this->assertEquals(
            "(object) \${$secondFormattersHandlerName} = new FormattersHandler(\$user->comments);". PHP_EOL .
            "\${$secondFormattersHandlerName}->setFormatterNames([twoDigits]);" . PHP_EOL,
             $VariableResolver->formattersHandlers()[1]
        );
    }


}