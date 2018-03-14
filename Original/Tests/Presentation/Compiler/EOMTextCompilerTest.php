<?php

use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Compiler\EOMTextCompiler;
use Stratum\Original\Presentation\EOM\Text;


Class EOMTextCompilerTest extends TestCase
{
    public function test_compiles_node_with_a_variable_as_content()
    {
        (object) $text = new Text;

        $text->addContent('(post.content)');

        (object) $EOMTextCompiler = new EOMTextCompiler($text);

        $this->assertEquals('$post->content', $EOMTextCompiler->compiledNode());
    }

    public function test_compiles_node_with_a_variable_and_text_as_content()
    {
        (object) $text = new Text;

        $text->addContent('Read: (post.content)');

        (object) $EOMTextCompiler = new EOMTextCompiler($text);

        $this->assertEquals('"Read: {$post->content}"', $EOMTextCompiler->compiledNode());
    }

    public function test_compiles_text_node()
    {
        (object) $text = new Text;

        $text->addContent('the content');

        (object) $EOMTextCompiler = new EOMTextCompiler($text);

        (string) $actualCompiledText = $EOMTextCompiler->compiledType();

        (string) $expectedCompiledText = "{$EOMTextCompiler->textVariable()} = new Text;" . PHP_EOL. PHP_EOL;

        $expectedCompiledText.= "{$EOMTextCompiler->textVariable()}->addContent(\"the content\");" . PHP_EOL;
        $expectedCompiledText.= "\$groupOfNodes->add({$EOMTextCompiler->textVariable()});" . PHP_EOL;

        $this->assertEquals($expectedCompiledText, $actualCompiledText);
    }

    public function test_compiles_text_node_with_variable()
    {
        (object) $text = new Text;

        $text->addContent('(post.content)');

        (object) $EOMTextCompiler = new EOMTextCompiler($text);

        (string) $actualCompiledText = $EOMTextCompiler->compiledType();

        (string) $expectedCompiledText = "{$EOMTextCompiler->textVariable()} = new Text;" . PHP_EOL . PHP_EOL;

        $expectedCompiledText.= "{$EOMTextCompiler->textVariable()}->addContent(\$post->content);" . PHP_EOL;
        $expectedCompiledText.= "\$groupOfNodes->add({$EOMTextCompiler->textVariable()});" . PHP_EOL;

        $this->assertEquals($expectedCompiledText, $actualCompiledText);
    }







}