<?php
use StephenHarris\Guise\Settings\Page;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Filters;
use Brain\Monkey\WP\Actions;
use Brain\Monkey\Functions;

class RegisterSettingTest extends BrainMonkeyWpTestCase {

    function testRegisterSetting()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockSettingView->method('label')->will($this->returnValue('Setting label'));
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();

        $page = new Page('page-id');

        Actions::expectAdded('admin_init')->once()->with(array($page, '_settings_init'));

        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator);
    }


    function testRegisterSection()
    {
        $mockSectionView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Section')->disableOriginalConstructor()->getMock();
        $mockSectionView->method('label')->will($this->returnValue('Section label'));

        $page = new Page('page-id');

        Actions::expectAdded('admin_init')->once()->with(array($page, '_settings_init'));

        $page->register_section('section-id', $mockSectionView );
    }

    function testAddingSettingsField()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockSettingView->method('label')->will($this->returnValue('Setting label'));
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        Functions::expect('register_setting')->once()->with( 'page-id', 'setting-id' );
        Filters::expectAdded('sanitize_option_setting-id')->once()->with( array( $page, '_validate' ), 10, 2 );

        Functions::expect('add_settings_field')->once()->with(
            'setting-id',
            'Setting label',
            array( $page, '_render_setting_proxy' ),
            'page-id',
            'section-id',
            array(
                'id' => 'setting-id',
                'view' => $mockSettingView,
                'label_for' => 'setting-id'
            )
        );

        $page->_settings_init();
    }

    function testAddingSection()
    {
        $mockSectionView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Section')->disableOriginalConstructor()->getMock();
        $mockSectionView->method('label')->will($this->returnValue('Section label'));

        $page = new Page('page-id');
        $page->register_section('section-id', $mockSectionView );

        Functions::expect('add_settings_section')->once()->with(
            'section-id',
            'Section label',
            array( $mockSectionView, '_print' ),
            'page-id'
        );

        $page->_settings_init();
    }


    function testDeregisterSetting()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockSettingView->method('label')->will($this->returnValue('Setting label'));
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();

        Actions::expectAdded('admin_init');
        Functions::expect('register_setting');
        Functions::expect('add_settings_field');
        Filters::expectAdded('sanitize_option_setting-id');

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator);
        $page->_settings_init();

        Functions::expect( 'unregister_setting')->once()->with( 'page-id', 'setting-id' );

        $page->deregister_setting( 'setting-id' );

        $this->assertFalse( has_filter( "sanitize_option_setting-id", array( $page, '_validate' ) ) );

    }

    function testRenderingSettingsField()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        Functions::expect('get_option')->once()->with( 'setting-id' )->andReturn( 'foobar' );
        $mockSettingView->expects($this->once())->method('parse_stored_value')->with( 'foobar' );
        $mockSettingView->expects($this->once())->method('render')->will($this->returnValue('Setting content'));
        $this->expectOutputString( 'Setting content' );

        $page->_render_setting_proxy( array(
            'id' => 'setting-id',
            'view' => $mockSettingView,
            'label_for' => 'setting-id'
        ));
    }

}