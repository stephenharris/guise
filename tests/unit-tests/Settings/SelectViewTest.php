<?php

use StephenHarris\Guise\Settings\Views\Select;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class SelectViewTest extends BrainMonkeyWpTestCase {

    function setUp() {
        parent::setUp();
        Functions::when('esc_html')->returnArg();
        Functions::when('esc_attr')->returnArg();

        $this->view = new Select( 'field-name', 'Pick one of:', array(
            'tea' => 'Tea',
            'cake' => 'Cake',
            'death' => 'Death',
        ) );
        $this->view->set_attribute( 'foo', 'bar' );
    }

    public function assertHTMLEquals( $expected_string, $actual_string, $message = '' ) {
        $this->assertXmlStringEqualsXmlString( $expected_string, $actual_string, $message );
    }

    function testGetLabel() {
        $this->assertEquals( 'Pick one of:', $this->view->label() );
    }

    function testRender() {
        $expected =  file_get_contents( TEST_DATA . '/settings/select.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );
    }

    function testRenderChecked() {
        $this->view->select( 'death' );

        $expected = file_get_contents( TEST_DATA . '/settings/select-selected.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );
    }

    function testRenderUnchecked() {
        $this->view->select( 'death' );
        $this->view->deselect( 'death' );

        $expected =  file_get_contents( TEST_DATA . '/settings/select.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );
    }

    function testRenderOnlyLastOneIsChecked() {
        $this->view->select( 'cake' );
        $this->view->select( 'death' );

        $expected =  file_get_contents( TEST_DATA . '/settings/select-selected.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );
    }


    function testSelect() {
        $this->view->select( 'cake' );

        $this->assertFalse( $this->view->is_selected( 'tea' ) );
        $this->assertTrue( $this->view->is_selected( 'cake' ) );
        $this->assertFalse( $this->view->is_selected( 'death' ) );
    }

    /**
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Option "not-a-valid-option" does not exist
     */
    function testSelectInvalid() {
        $this->view->select( 'not-a-valid-option' );
    }

    function testDeselect() {
        $this->view->select( 'cake' );
        $this->view->deselect();

        $this->assertFalse( $this->view->is_selected( 'cake' ) );
    }


    function testParseInput() {
        $this->view->parse_stored_value( 'death' );

        $this->assertFalse( $this->view->is_selected( 'tea' ) );
        $this->assertFalse( $this->view->is_selected( 'cake' ) );
        $this->assertTrue( $this->view->is_selected( 'death' ) );
        $expected =  file_get_contents( TEST_DATA . '/settings/select-selected.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );
    }

    function testParseInputNotValid() {
        $this->view->parse_stored_value( 'not-a-valid-option' );

        $this->assertFalse( $this->view->is_selected( 'tea' ) );
        $this->assertFalse( $this->view->is_selected( 'cake' ) );
        $this->assertFalse( $this->view->is_selected( 'death' ) );

        $expected =  file_get_contents( TEST_DATA . '/settings/select.html' );
        $this->assertHTMLEquals( $expected, $this->view->render() );;
    }

}