<?php
use stephenharris\guise\Taxonomy_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Filters;

class RegisterTaxonomyColumnTest extends BrainMonkeyWpTestCase {

    function testRegisterTaxonomyColumn() {

        $mockView = $this->getMockBuilder('\stephenharris\guise\Taxonomy_Column_View')->getMock();
        $mockView->method('label')->willReturn( 'Column label' );

        $controller = new Taxonomy_Column_Controller();

        Filters::expectAdded('manage_edit-my-taxonomy_columns')
            ->once()
            ->with(array( $controller, '_maybe_add_column' ));

        Filters::expectAdded('manage_my-taxonomy_custom_column')
            ->once()
            ->with(array( $controller, '_maybe_render_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-taxonomy', -1 );

    }

    function testRegisterTaxonomyColumnMultipleTimes() {

        $mockView = $this->getMockBuilder('\stephenharris\guise\Taxonomy_Column_View')->getMock();
        $mockView->method('label')->willReturn( 'Column label' );

        $controller = new Taxonomy_Column_Controller();

        Filters::expectAdded('manage_edit-my-taxonomy_columns')
            ->twice()
            ->with(array( $controller, '_maybe_add_column' ));

        Filters::expectAdded('manage_my-taxonomy_custom_column')
            ->twice()
            ->with(array( $controller, '_maybe_render_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-taxonomy', -1 );
        $controller->register( $mockView, 'my-taxonomy', -1 );

    }

    function testRegisterTaxonomyColumnMultipleTimesDifferentTaxononmy() {

        $mockView = $this->getMockBuilder('\stephenharris\guise\Taxonomy_Column_View')->getMock();
        $mockView->method('label')->willReturn( 'Column label' );

        $controller = new Taxonomy_Column_Controller();

        Filters::expectAdded('manage_edit-my-taxonomy_columns')
            ->once()
            ->with(array( $controller, '_maybe_add_column' ));

        Filters::expectAdded('manage_my-taxonomy_custom_column')
            ->once()
            ->with(array( $controller, '_maybe_render_column_cell' ), 10, 3 );

        Filters::expectAdded('manage_edit-another-taxonomy_columns')
            ->once()
            ->with(array( $controller, '_maybe_add_column' ));

        Filters::expectAdded('manage_another-taxonomy_custom_column')
            ->once()
            ->with(array( $controller, '_maybe_render_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-taxonomy', -1 );
        $controller->register( $mockView, 'another-taxonomy', -1 );

    }


}