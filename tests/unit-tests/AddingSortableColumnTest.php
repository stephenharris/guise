<?php
use StephenHarris\Guise\Post_Type_Column_Controller;
use StephenHarris\Guise\Taxonomy_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class AddingSortableTypeColumnTest extends BrainMonkeyWpTestCase {

    function testSortablePostTypeColumnAdded(){

        $this->mockView = $this->getMockBuilder('\StephenHarris\Guise\Sortable_Post_Type_Column_View')->getMock();
        $this->mockView->method('sort_by')->willReturn( 'some_query_variable' );

        $this->controller = new Post_Type_Column_Controller();
        $this->controller->register( $this->mockView, 'my-post-type' );

        $this->existing_columns = array(
            'first-header'  => 'date',
            'second-header' => 'title',
            'third-header'  => 'author',
        );

        Functions::expect('current_filter')->once()->andReturn('manage_my-post-type_posts_sortable_columns');
        $this->assertContains( 'some_query_variable', $this->controller->_maybe_add_sortable_column( $this->existing_columns ) );
    }

    function testSortableTaxonomyColumnAdded(){

        $this->mockView = $this->getMockBuilder('\StephenHarris\Guise\Sortable_Taxonomy_Column_View')->getMock();
        $this->mockView->method('sort_by')->willReturn( 'some_query_variable' );

        $this->controller = new Taxonomy_Column_Controller();
        $this->controller->register( $this->mockView, 'my-taxonomy' );

        $this->existing_columns = array(
            'first-header'  => 'date',
            'second-header' => 'title',
            'third-header'  => 'author',
        );

        Functions::expect('current_filter')->once()->andReturn('manage_edit-my-taxonomy_sortable_columns');
        $this->assertContains( 'some_query_variable', $this->controller->_maybe_add_sortable_column( $this->existing_columns ) );
    }
}