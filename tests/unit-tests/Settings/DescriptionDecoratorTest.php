<?php

use StephenHarris\Guise\Settings\Views\Setting;
use StephenHarris\Guise\Settings\Views\DescriptionDecorator;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class DescriptionDecoratorTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();

        Functions::when('esc_html')->returnArg();
        Functions::when('esc_attr')->returnArg();

        $this->originalSettingView = $this->getMockBuilder( 'StephenHarris\Guise\Settings\Views\Setting' )->getMock();
        $this->decoratedSettingView = new DescriptionDecorator( $this->originalSettingView, 'Foobar' );
    }

    function testGetLabel() {
        $this->originalSettingView->expects($this->once())->method( 'label' )->willReturn( 'setting label' );
        $this->assertEquals( 'setting label', $this->decoratedSettingView->label() );
    }

    function testRender() {
        $this->originalSettingView->expects($this->once())->method( 'render' )->willReturn( '<html setting>' );
        $this->assertEquals( '<html setting><p class="description">Foobar</p>', $this->decoratedSettingView->render() );
    }

    function testParseInputValue() {
        $this->originalSettingView->expects($this->once())->method( 'parse_stored_value' )->with('hello world');
        $this->decoratedSettingView->parse_stored_value('hello world');
    }

}