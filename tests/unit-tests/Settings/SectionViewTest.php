<?php

use StephenHarris\Guise\Settings\Views\SimpleSection;
use StephenHarris\Guise\Settings\Views\SectionPrintDecorator;
use MonkeryTestCase\BrainMonkeyWpTestCase;

class SectionViewTest extends BrainMonkeyWpTestCase {

    function testGetLabel() {
        $view = new SimpleSection( 'My label', 'My content' );
        $this->assertEquals( 'My label', $view->label() );
    }

    function testGetContent() {
        $view = new SimpleSection( 'My label', 'My content' );
        $this->assertEquals( 'My content', $view->render() );
    }

    function testPrint() {
        $view = new SimpleSection( 'My label', 'My content' );
        $printable_section_view = new SectionPrintDecorator( $view );
        $this->expectOutputString( 'My content' );
        $printable_section_view->_print();
    }

}