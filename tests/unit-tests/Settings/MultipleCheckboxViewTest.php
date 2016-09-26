<?php

use StephenHarris\Guise\Settings\Views\MultipleCheckbox;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class MultipleCheckboxViewTest extends BrainMonkeyWpTestCase {

    function setUp() {
        parent::setUp();
        Functions::when('esc_html')->returnArg();
        Functions::when('esc_attr')->returnArg();
    }

    public function assertHTMLEquals( $expected_string, $actual_string, $message = '' ) {
        $this->assertXmlStringEqualsXmlString( $expected_string, $actual_string, $message );
    }


    function testGetLabel() {
        $view = new MultipleCheckbox( 'field-name', 'Pick any of:', array(
            'hello' => 'world',
            'foo'   => 'bar',
        ) );
        $this->assertEquals( 'Pick any of:', $view->label() );
    }

    function testRender() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox.html' );
        $this->assertHTMLEquals( $expected, $view->render() );
    }

    function testRenderChecked() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );
        $view->check( 'foo' );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox-checked.html' );
        $this->assertHTMLEquals( $expected, $view->render() );
    }

    function testRenderUnchecked() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );
        $view->check( 'foo' );
        $view->uncheck( 'foo' );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox.html' );
        $this->assertHTMLEquals( $expected, $view->render() );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage heckbox "not-a-valid-option" does not exist
     */
    function testCheckedInvalid() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );
        $view->check( 'not-a-valid-option' );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage heckbox "not-a-valid-option" does not exist
     */
    function testUnCheckedInvalid() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );
        $view->uncheck( 'not-a-valid-option' );
    }

    function testParseInput() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );

        $view->parse_stored_value( array( 'foo' ) );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox-checked.html' );
        $this->assertHTMLEquals( $expected, $view->render() );
    }

    function testParseInputNotValid() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );

        $view->parse_stored_value( array( 'not-a-valid-value' ) );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox.html' );
        $this->assertHTMLEquals( $expected, $view->render() );;
    }

    function testParseInputPartiallyValid() {
        $view = new MultipleCheckbox( 'field-name', 'My label', array(
            'hello' => 'World',
            'foo'   => 'Bar',
        ) );

        $view->parse_stored_value( array( 'boo', 'foo' ) );

        $expected =  file_get_contents( TEST_DATA . '/settings/multiple-checkbox-checked.html' );
        $this->assertHTMLEquals( $expected, $view->render() );
    }

}