<?php
use stephenharris\PHPUnit\PHPUnit_Framework_Constraint_NthElementOfArrayHasValue;
use stephenharris\guise\Column_View_Store;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class ColumnViewStoreTest extends BrainMonkeyWpTestCase {

    function setUp(){
        $this->stubView = $this->getMockBuilder('stephenharris\guise\Column_View')->getMock();
        $this->stubView->method('label')->willReturn('Column label');

        $this->store = new Column_View_Store();
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

    /**
     * @dataProvider columnPositionProvider
     */
    public function testInsertColumns( $index, $ordinal ) {

        $this->store->store( $this->stubView, 'object-type', $index );

        $columns = array(
            'first' => 'First',
            'second' => 'Second',
            'third' => 'Third'
        );

        $actual = $this->store->insert_columns_for_object( 'object-type', $columns );

        $this->assertNthValueOfArrayEquals( 'Column label', $ordinal, $actual );
    }

    public function columnPositionProvider() {
        return array(
            array( -1, 4 ),
            array( 0, 1 ),
            array( 1, 2 ),
            array( 3, 4 ),
            array( 999, 4 ),
        );
    }

    public function testInsertColumnsObjectNotExist() {

        $this->store->store( $this->stubView, 'object-type', -1 );

        $columns = array(
            'first' => 'First',
            'second' => 'Second',
            'third' => 'Third'
        );

        $actual = $this->store->insert_columns_for_object( 'object-does-not-exit', $columns );

        $this->assertEquals( $columns, $actual );
    }

    public function testGetColumnView() {
        $this->store->store( $this->stubView, 'object-type', -1 );
        $key = spl_object_hash( $this->stubView );

        $this->assertSame( $this->stubView , $this->store->get_column_view( 'object-type', $key ) );
    }

    public function testColumnKeyNotExistView() {
        $this->store->store( $this->stubView, 'object-type', -1 );
        $this->assertInstanceOf( 'stephenharris\guise\Null_Column_View' , $this->store->get_column_view( 'object-type', 'key-does-not-exist' ) );
    }
    public function testObjectTypeNotExistView() {
        $this->store->store( $this->stubView, 'object-type', -1 );
        $key = spl_object_hash( $this->stubView );
        $this->assertInstanceOf( 'stephenharris\guise\Null_Column_View' , $this->store->get_column_view( 'object-does-not-exit', $key ) );
    }
}