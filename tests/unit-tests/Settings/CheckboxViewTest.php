<?php

use StephenHarris\Guise\Settings\Views\Checkbox;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class CheckboxViewTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();
        Functions::when('esc_html')->returnArg();
        Functions::when('esc_attr')->returnArg();
    }

    function testGetLabel() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );
        $this->assertEquals( 'My label', $view->label() );
    }

    function testRender() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );
        $view->set_attribute( 'foo', 'bar' );

        $this->assertEquals( '<input name="field-name" value="hello" type="checkbox" foo="bar"/>', $view->render() );
    }

    function testRenderChecked() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );
        $view->set_attribute( 'foo', 'bar' );
        $view->check();

        $this->assertTrue( $view->is_checked() );
        $this->assertEquals( '<input name="field-name" value="hello" type="checkbox" foo="bar" checked="checked"/>', $view->render() );
    }

    function testRenderUnchecked() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );
        $view->set_attribute( 'foo', 'bar' );
        $view->check();
        $view->uncheck();

        $this->assertFalse( $view->is_checked() );
        $this->assertEquals( '<input name="field-name" value="hello" type="checkbox" foo="bar"/>', $view->render() );
    }

    function testParseInput() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );

        $this->assertNull( $view->get_attribute( 'checked' ) );

        $view->parse_stored_value( 'hello' );

        $this->assertEquals( 'checked', $view->get_attribute( 'checked' ), 'Checkbox should have been checked' );
    }

    function testParseInputNotValid() {
        $view = new Checkbox( 'field-name', 'My label', 'hello' );

        $view->check();

        $view->parse_stored_value( 'not-the-right-value' );

        $this->assertNull( $view->get_attribute( 'checked' ), 'Checkbox should have been unchecked' );
    }

}