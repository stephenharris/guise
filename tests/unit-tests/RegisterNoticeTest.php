<?php
use StephenHarris\Guise\Notice_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Actions;

class RegisterNoticeTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();
        $this->controller = new Notice_Controller();

    }

    function testRegisterNotices() {
        $mockNoticeView = $this->getMockBuilder('\StephenHarris\Guise\Notice_View')->getMock();

        Actions::expectAdded('admin_notices')->once()->with(array( $this->controller, '_print_notices' ));

        $this->controller->register( 'my-notice', $mockNoticeView );
    }

    function testRenderNotices() {
        $mockNoticeView = $this->getMockBuilder('\StephenHarris\Guise\Notice_View')->getMock();
        $mockNoticeView->expects($this->once())->method('render')->will( $this->returnValue('<div class="notice notice-info">notice</div>') );;

        $this->expectOutputString( '<div class="notice notice-info">notice</div>' );


        $this->controller->register( 'my-notice', $mockNoticeView );
        $this->controller->_print_notices();
    }

    function testRegisteringSameNoticeRendersOnlyOnceNotice() {
        $mockNoticeView = $this->getMockBuilder('\StephenHarris\Guise\Notice_View')->getMock();
        $mockNoticeView->expects($this->once())->method('render')->will( $this->returnValue('<div class="notice notice-info">notice</div>') );

        $this->expectOutputString( '<div class="notice notice-info">notice</div>' );

        $this->controller->register( 'my-notice', $mockNoticeView );
        $this->controller->register( 'my-notice', $mockNoticeView );
        $this->controller->_print_notices();

    }

    function testRenderingMultipleNotices() {
        $mockNoticeView = $this->getMockBuilder('\StephenHarris\Guise\Notice_View')->getMock();
        $mockNoticeView->expects($this->once())->method('render')->will( $this->returnValue('<div class="notice notice-info">notice1</div>') );

        $mockNoticeView2 = $this->getMockBuilder('\StephenHarris\Guise\Notice_View')->getMock();
        $mockNoticeView2->expects($this->once())->method('render')->will( $this->returnValue('<div class="notice notice-info">notice2</div>') );

        $this->expectOutputString( '<div class="notice notice-info">notice1</div><div class="notice notice-info">notice2</div>' );

        $this->controller->register( 'my-notice', $mockNoticeView );
        $this->controller->register( 'my-second-notice', $mockNoticeView2 );
        $this->controller->_print_notices();
    }

}