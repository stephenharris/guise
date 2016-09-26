<?php
use stephenharris\PHPUnit\PHPUnit_Framework_Constraint_NthElementOfArrayHasValue;
use StephenHarris\Guise\Columns\Post_Type_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class AddingPostTypeColumnTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();
        $this->mockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();
        $this->mockView->method('label')->willReturn( 'Column label' );

        $this->existing_columns = array(
            'first-header'  => 'My first header',
            'second-header' => 'My second header',
            'third-header'  => 'My third header',
        );

        $this->controller = new Post_Type_Column_Controller();

    }

    public function assertNthValueOfArrayEquals( $expected, $ordinal, $array, $message = '' )
    {
        if (! is_integer($ordinal) ) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory( 1, 'integer' );
        }
        if (!(is_array($array) || $array instanceof ArrayAccess)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory( 1, 'array or ArrayAccess' );
        }
        $constraint = new PHPUnit_Framework_Constraint_NthElementOfArrayHasValue($ordinal,$expected);
        \PHPUnit_Framework_Assert::assertThat($array, $constraint, $message);
    }



    function testAddPostTypeColumnHeaderAtEnd() {

        $this->mockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();
        $this->mockView->method('label')->willReturn( 'Column label' );

        $this->controller = new Post_Type_Column_Controller();
        $this->controller->register( $this->mockView, 'my-post-type', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 4, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddPostTypeColumnHeaderAtBeginning() {
        
        $this->controller->register( $this->mockView, 'my-post-type', 0 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 1, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddPostTypeColumnHeaderInMiddle() {
        
        $this->controller->register( $this->mockView, 'my-post-type', 2 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 3, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddPostTypeColumnHeaderAtLargeIndex() {

        $this->controller->register( $this->mockView, 'my-post-type', 999 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 4, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddOnlyExpectedPost_TypeColumnHeader() {

        $anotherMockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();
        $anotherMockView->method('label')->willReturn( 'This should not appear' );

        $this->controller->register( $this->mockView, 'my-post-type', -1 );
        $this->controller->register( $anotherMockView, 'another-post-type', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertContains( 'Column label', $actual );
        $this->assertNotContains( 'This should not appear', $actual);

    }

    function testSamePostTypeColumnAddedAppearsOnlyOnce() {

        $this->controller->register( $this->mockView, 'my-post-type', -1 );
        $this->controller->register( $this->mockView, 'my-post-type', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertContains( 'Column label', $actual );
        $this->assertCount( 4, $actual ); //column should only be added once
    }

    function testAddMultipleColumns() {

        $anotherMockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();
        $anotherMockView->method('label')->willReturn( 'another column' );

        $this->controller->register( $this->mockView, 'my-post-type', -1 );
        $this->controller->register( $anotherMockView, 'my-post-type', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertNthValueOfArrayEquals( 'Column label', 4, $actual );
        $this->assertNthValueOfArrayEquals( 'another column', 5, $actual );

    }
}