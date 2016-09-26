<?php
use StephenHarris\Guise\Settings\Page;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Filters;
use Brain\Monkey\WP\Actions;
use Brain\Monkey\Functions;

class ValidateSettingTest extends BrainMonkeyWpTestCase {

    function testValidateSetting()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        //The validator should be instructed to validate the value
        $mockValidator->expects($this->once())->method('assert')->with( 'value-to-validate' );

        $validated = $page->_validate( 'value-to-validate', 'setting-id' );

        //The value is returned since its valid
        $this->assertEquals( 'value-to-validate', $validated );
    }


    function testValidateSettingNotExist()
    {
        $page = new Page('page-id');

        $validated = $page->_validate( 'value-to-validate', 'setting-id-not-exit' );

        //We the value should just pass through since we don't know how to validate it.
        $this->assertEquals( 'value-to-validate', $validated );
    }


    /**
     * Testing when a AbstractNestedException is encounted when validating
     */
    function testValidateInvalidSettingMultipleConstraints()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();
        $mockException = $this->getMockBuilder('\Respect\Validation\Exceptions\AbstractNestedException')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        //The validator should be instructed to validate the value and it should through an exception
        $errors = new \ArrayIterator(array('The value was invalid because of X', 'The value was invalid because of Y' ));
        $mockException->method('getIterator')->will($this->returnValue($errors));
        $mockValidator->expects($this->once())->method('assert')->with( 'an invalid value' )->will(
            $this->throwException( $mockException )
        );

        //Since validation fails, we obtain the current value to return that instead
        Functions::expect('get_option')->once()->with( 'setting-id' )->andReturn( 'current value' );

        //We expect WordPress to be notified of the error
        Functions::expect('add_settings_error')->once()->with( 'page-id', 'setting-id', 'The value was invalid because of X<br>The value was invalid because of Y' );


        $validated = $page->_validate( 'an invalid value', 'setting-id' );

        //The old value is returned since it is not valid
        $this->assertEquals( 'current value', $validated );
    }


    /**
     * Testing when a ValidationException is encounted when validating
     */
    function testValidateInvalidSettingSingleConstraint()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();
        $mockException = $this->getMockBuilder('\Respect\Validation\Exceptions\ValidationException')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        //The validator should be instructed to validate the value and it should through an exception
        $mockException->method('getMainMessage')->will($this->returnValue('The value was invalid because of X'));
        $mockValidator->expects($this->once())->method('assert')->with( 'an invalid value' )->will(
            $this->throwException( $mockException )
        );

        //Since validation fails, we obtain the current value to return that instead
        Functions::expect('get_option')->once()->with( 'setting-id' )->andReturn( 'current value' );

        //We expect WordPress to be notified of the error
        Functions::expect('add_settings_error')->once()->with( 'page-id', 'setting-id', 'The value was invalid because of X' );


        $validated = $page->_validate( 'an invalid value', 'setting-id' );

        //The old value is returned since it is not valid
        $this->assertEquals( 'current value', $validated );
    }

    /**
     * Testing when any other Exception is encounted when validating
     */
    function testValidateInvalidSettingUnknownException()
    {
        $mockSettingView = $this->getMockBuilder('\StephenHarris\Guise\Settings\Views\Setting')->getMock();
        $mockValidator = $this->getMockBuilder('\Respect\Validation\Validatable')->getMock();
        $mockException = $this->getMockBuilder('\Exception')->getMock();

        $page = new Page('page-id');
        $page->register_setting('setting-id', 'section-id', $mockSettingView, $mockValidator );

        //The validator should be instructed to validate the value and it should through an exception
        $mockValidator->expects($this->once())->method('assert')->with( 'an invalid value' )->will(
            $this->throwException( new \Exception( 'Unknown error' ) )
        );

        //Since validation fails, we obtain the current value to return that instead
        Functions::expect('get_option')->once()->with( 'setting-id' )->andReturn( 'current value' );

        //We expect WordPress to be notified of the error
        Functions::expect('add_settings_error')->once()->with( 'page-id', 'setting-id', 'Unknown error' );


        $validated = $page->_validate( 'an invalid value', 'setting-id' );

        //The old value is returned since it is not valid
        $this->assertEquals( 'current value', $validated );
    }



}