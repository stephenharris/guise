<?php

use StephenHarris\Guise\Settings\Views\Input;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class InputViewTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();

        Functions::when('esc_html')->returnArg();
        Functions::when('esc_attr')->returnArg();
    }

    function testGetLabel() {
        $view = new Input( 'field-name', 'My label' );
        $this->assertEquals( 'My label', $view->label() );
    }

    function testRender() {
        $view = new Input( 'field-name', 'My label' );
        $view->set_attribute( 'foo', 'bar' );
        $view->set_value( 'hello-world' );

        $this->assertEquals( '<input name="field-name" type="text" foo="bar" value="hello-world"/>', $view->render() );
    }

    function testParseInputValue() {
        $view = new Input( 'field-name', 'My label' );
        $view->set_attribute( 'foo', 'bar' );

        $view->parse_stored_value( 'foo-bar' );

        $this->assertEquals( '<input name="field-name" type="text" foo="bar" value="foo-bar"/>', $view->render() );
    }

}