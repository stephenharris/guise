<?php
use StephenHarris\Guise\Columns\Post_Type_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\WP\Actions;
use Brain\Monkey\WP\Filters;

class RegisterPostColumnTest extends BrainMonkeyWpTestCase {

    function testRegisterPostTypeColumn() {

        $mockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();

        $controller = new Post_Type_Column_Controller();

        Filters::expectAdded('manage_my-post-type_posts_columns')->once()
            ->with(array( $controller, '_maybe_add_column' ));
        Actions::expectAdded('manage_my-post-type_posts_custom_column')->once()
            ->with(array( $controller, '_maybe_print_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-post-type', -1 );

    }

    function testRegisterPostTypeColumnMultipleTimes() {

        $mockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();

        $controller = new Post_Type_Column_Controller();

        Filters::expectAdded('manage_my-post-type_posts_columns')->twice()
            ->with(array( $controller, '_maybe_add_column' ));

        Actions::expectAdded('manage_my-post-type_posts_custom_column')->twice()
            ->with(array( $controller, '_maybe_print_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-post-type', -1 );
        $controller->register( $mockView, 'my-post-type', -1 );

    }

    function testRegisterPostTypeColumnMultipleTimesDifferentPostType() {

        $mockView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Post_Type_Column_View')->getMock();

        $controller = new Post_Type_Column_Controller();

        Filters::expectAdded('manage_my-post-type_posts_columns')->once()
            ->with(array( $controller, '_maybe_add_column' ));
        Actions::expectAdded('manage_my-post-type_posts_custom_column')->once()
            ->with(array( $controller, '_maybe_print_column_cell' ), 10, 3 );

        Filters::expectAdded('manage_another-post-type_posts_columns')->once()
            ->with(array( $controller, '_maybe_add_column' ));
        Actions::expectAdded('manage_another-post-type_posts_custom_column')->once()
            ->with(array( $controller, '_maybe_print_column_cell' ), 10, 3 );

        $controller->register( $mockView, 'my-post-type', -1 );
        $controller->register( $mockView, 'another-post-type', -1 );

    }

    function testRegisterSortablePostTypeColumn() {

        $sortablePostView = $this->getMockBuilder('\StephenHarris\Guise\Columns\Sortable_Post_Type_Column_View')->getMock();

        $controller = new Post_Type_Column_Controller();

        Filters::expectAdded('manage_my-post-type_posts_columns')->once()
            ->with(array( $controller, '_maybe_add_column' ));
        Filters::expectAdded('manage_my-post-type_posts_sortable_columns')->once()
            ->with(array( $controller, '_maybe_add_sortable_column' ));
        Actions::expectAdded('manage_my-post-type_posts_custom_column')->once()
            ->with(array( $controller, '_maybe_print_column_cell' ), 10, 3 );

        $controller->register( $sortablePostView, 'my-post-type', -1 );

    }


}