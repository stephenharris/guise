<?php

use StephenHarris\Guise\Settings\Views\Section;
use MonkeryTestCase\BrainMonkeyWpTestCase;

class SectionViewTest extends BrainMonkeyWpTestCase {

    function testGetLabel() {
        $view = new Section( 'My label', 'My content' );
        $this->assertEquals( 'My label', $view->label() );
    }

    function testGetContent() {
        $view = new Section( 'My label', 'My content' );
        $this->assertEquals( 'My content', $view->render() );
    }

    function testPrint() {
        $view = new Section( 'My label', 'My content' );
        $this->expectOutputString( 'My content' );
        $view->_print();
    }

}