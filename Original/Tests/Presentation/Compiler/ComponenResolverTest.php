<?php


use PHPUnit\Framework\TestCase;
use Stratum\Original\Presentation\Compiler\ComponentResolver;

Class ComponentResolverTest extends TestCase
{
    public function test_html_tags_get_ignored()
    {
        (string) $html = '<div></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_html_tags_get_ignored_with_chidlren()
    {
        (string) $html = '<div><p></p></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_html_tags_get_ignored_void()
    {
        (string) $html = '<img />';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_html_tags_get_ignored_with_void_chidlren()
    {
        (string) $html = '<div><img /></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_missing_opening_angled_brackets_get_ignored()
    {
        (string) $html = 'header>>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_missing_closing_angled_brackets_get_ignored()
    {
        (string) $html = '<<header';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_missing_one_closing_angled_brackets_get_ignored()
    {
        (string) $html = '<<header>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_missing_one_opening_angled_brackets_get_ignored()
    {
        (string) $html = '<header>>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($html, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag()
    {
        (string) $html = '<<header>>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_uppercase()
    {
        (string) $html = '<<Header>>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_alphanumeric()
    {
        (string) $html = '<<Header1>>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_string()
    {
        (string) $html = '<<Header1 use="string">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="string"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_integer()
    {
        (string) $html = '<<Header1 use="70">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="70"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_variable()
    {
        (string) $html = '<<Header1 use="(headerdata)">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="(headerdata)"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_special_content_component()
    {
        (string) $html = '<<Content>>';
        (string) $preCompiledHTML = '<stratumcomponent name="Content" use="(stratumPartialView)"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_variable_property()
    {
        (string) $html = '<<Header1 use="(headerdata.ad)">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="(headerdata.ad)"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_variable_method_call()
    {
        (string) $html = '<<Header1 use="(headerdata.ad())">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="(headerdata.ad())"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_variable_array()
    {

        (string) $html = '<<Header1 use="(headerdata:0)">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="(headerdata:0)"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_with_attribute_variable_property_array_method()
    {
        (string) $html = '<<Header1 use="(headerdata.ad.options:0.boolean())">>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header1" use="(headerdata.ad.options:0.boolean())"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_next_to_html_element()
    {
        (string) $html = '<div></div><<header>>';
        (string) $preCompiledHTML = '<div></div><stratumcomponent name="Header"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_before_html_element()
    {
        (string) $html = '<<header>><div></div>';
        (string) $preCompiledHTML = '<stratumcomponent name="Header"></stratumcomponent><div></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_surrounded_by_html_elements()
    {
        (string) $html = '<div></div><<header>><div></div>';
        (string) $preCompiledHTML = '<div></div><stratumcomponent name="Header"></stratumcomponent><div></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_inside_html_element()
    {
        (string) $html = '<div><<header>></div>';
        (string) $preCompiledHTML = '<div><stratumcomponent name="Header"></stratumcomponent></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_inside_html_element_2_levels()
    {
        (string) $html = '<div><section><<header>></section></div>';
        (string) $preCompiledHTML = '<div><section><stratumcomponent name="Header"></stratumcomponent></section></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_component_definition_into_a_component_tag_inside_html_element_2_levels_with_text()
    {
        (string) $html = '<div>text text<section><<header>>text</section></div>';
        (string) $preCompiledHTML = '<div>text text<section><stratumcomponent name="Header"></stratumcomponent>text</section></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_2_component_definitions_first_outside_and_second_one_as_a_chidlren()
    {
        (string) $html = '<<header>><div><<posts>></div>';
        (string) $preCompiledHTML = 
        '<stratumcomponent name="Header"></stratumcomponent><div><stratumcomponent name="Posts"></stratumcomponent></div>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }

    public function test_converts_2_component_definitions()
    {
        (string) $html = '<<header>> <<posts>>';
        (string) $preCompiledHTML = 
        '<stratumcomponent name="Header"></stratumcomponent> <stratumcomponent name="Posts"></stratumcomponent>';
        (object) $ComponentResolver = new ComponentResolver($html);

        $this->assertEquals($preCompiledHTML, $ComponentResolver->preCompiledHTML());
    }









}
