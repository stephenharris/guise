<?php
use stephenharris\PHPUnit\PHPUnit_Framework_Constraint_NthElementOfArrayHasValue;
use StephenHarris\Guise\Taxonomy_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class AddingTaxonomyColumnTest extends BrainMonkeyWpTestCase {

    function setUp()
    {
        parent::setUp();
        $this->mockView = $this->getMockBuilder('\StephenHarris\Guise\Taxonomy_Column_View')->getMock();
        $this->mockView->method('label')->willReturn( 'Column label' );

        $this->existing_columns = array(
            'first-header'  => 'My first header',
            'second-header' => 'My second header',
            'third-header'  => 'My third header',
        );

        $this->controller = new Taxonomy_Column_Controller();

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

    function testAddTaxonomyColumnHeaderAtEnd() {

        $this->controller->register( $this->mockView, 'my-taxonomy', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 4, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddTaxonomyColumnHeaderAtBeginning() {

        $this->controller->register( $this->mockView, 'my-taxonomy', 0 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 1, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddTaxonomyColumnHeaderInMiddle() {

        $this->controller->register( $this->mockView, 'my-taxonomy', 2 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 3, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddTaxonomyColumnHeaderAtLargeIndex() {

        $this->controller->register( $this->mockView, 'my-taxonomy', 999 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $this->assertNthValueOfArrayEquals( 'Column label', 4, $this->controller->_maybe_add_column( $this->existing_columns ) );

    }

    function testAddOnlyExpectedTaxonomyColumnHeader() {

        $anotherMockView = $this->getMockBuilder('\StephenHarris\Guise\Taxonomy_Column_View')->getMock();
        $anotherMockView->method('label')->willReturn( 'This should not appear' );

        $this->controller->register( $this->mockView, 'my-taxonomy', -1 );
        $this->controller->register( $anotherMockView, 'another-taxonomy', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertContains( 'Column label', $actual );
        $this->assertNotContains( 'This should not appear', $actual);

    }

    function testSameTaxonomyColumnAddedAppearsOnlyOnce() {

        $this->controller->register( $this->mockView, 'my-taxonomy', -1 );
        $this->controller->register( $this->mockView, 'my-taxonomy', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertContains( 'Column label', $actual );
        $this->assertCount( 4, $actual ); //column should only be added once
    }

    function testAddMultipleColumns() {

        $anotherMockView = $this->getMockBuilder('\StephenHarris\Guise\Taxonomy_Column_View')->getMock();
        $anotherMockView->method('label')->willReturn( 'another column' );

        $this->controller->register( $this->mockView, 'my-taxonomy', -1 );
        $this->controller->register( $anotherMockView, 'my-taxonomy', -1 );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_columns');

        $actual = $this->controller->_maybe_add_column( $this->existing_columns );
        $this->assertNthValueOfArrayEquals( 'Column label', 4, $actual );
        $this->assertNthValueOfArrayEquals( 'another column', 5, $actual );

    }

}