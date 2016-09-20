<?php
use stephenharris\PHPUnit\PHPUnit_Framework_Constraint_HTML_Equals;
use stephenharris\guise\Info_Notice_View;
use stephenharris\guise\Error_Notice_View;
use stephenharris\guise\Success_Notice_View;
use stephenharris\guise\Dismissible_Notice_Decorator;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Actions;

class RenderNoticeTest extends BrainMonkeyWpTestCase {

    public function assertHTMLEquals( $expected_string, $actual_string, $message = '' )
    {
        $this->assertXmlStringEqualsXmlString( $expected_string, $actual_string, $message );
    }

    function testRenderInfoNotice() {
        $base_notice = new Info_Notice_View( '<p>This is a notification message</p>' );
        $actual = $base_notice->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/info-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

    function testRenderErrorNotice() {
        $base_notice = new Error_Notice_View( '<p>This is an error message</p>' );
        $actual = $base_notice->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/error-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

    function testRenderSuccessNotice() {
        $base_notice = new Success_Notice_View( '<p>This is a success message</p>' );
        $actual = $base_notice->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/success-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

    function testRenderDismissableInfoNotice() {
        $base_notice = new Info_Notice_View( '<p>This is a dismissible notification message</p>' );
        $dismissable = new Dismissible_Notice_Decorator( $base_notice );
        $actual = $dismissable->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/dismissible-info-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

    function testRenderDismissableErrorNotice() {
        $base_notice = new Error_Notice_View( '<p>This is a dismissible error message</p>' );
        $dismissable = new Dismissible_Notice_Decorator( $base_notice );
        $actual = $dismissable->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/dismissible-error-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

    function testRenderDismissableSuccessNotice() {
        $base_notice = new Success_Notice_View( '<p>This is a dismissible success message</p>' );
        $dismissable = new Dismissible_Notice_Decorator( $base_notice );
        $actual = $dismissable->render();
        $expected =  file_get_contents( TEST_DATA . '/notices/dismissible-success-notice.html' );
        $this->assertHTMLEquals( $expected, $actual, true );
    }

}