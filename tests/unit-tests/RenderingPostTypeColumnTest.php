<?php
use StephenHarris\Guise\Post_Type_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class RenderingPostTypeColumnTest extends BrainMonkeyWpTestCase {

    private $columnName;
    private $anotherClass;
    private $controller;

    function setUp(){
        parent::setUp();

        $mockView = $this->getMockBuilder('\StephenHarris\Guise\Post_Type_Column_View')->getMock();
        $mockView->method('render')->will($this->returnCallback(function( $post ){
            return 'Post ID: ' . $post->ID;
        }));
        $this->columnName = spl_object_hash( $mockView );

        Functions::when('get_post')->alias( function( $id ) {
            $mockPost = $this->getMockBuilder('\WP_post')->getMock();
            $mockPost->ID = $id;
            return $mockPost;
        } );

        $anotherMockView = $this->getMockBuilder('\StephenHarris\Guise\Post_Type_Column_View')->getMock();
        $anotherMockView->method('render')->will($this->returnValue('notthis'));
        $this->anotherColumnName = spl_object_hash( $anotherMockView );


        $this->controller = new Post_Type_Column_Controller();
        $this->controller->register( $mockView, 'my-post-type', -1 );
        $this->controller->register( $anotherMockView, 'another-post-type', -1 );

    }

    function testRenderTaxonomyColumn() {

        Functions::expect('current_filter')->andReturn('manage_my-post-type_posts_custom_column');

        $this->expectOutputString( "Post ID: 123\nPost ID: 456" );

        $this->controller->_maybe_print_column_cell( $this->columnName, 123 );
        echo "\n";
        $this->controller->_maybe_print_column_cell( $this->columnName, 456 );

    }

    function testIgnoreUnknownColumn() {

        Functions::expect('current_filter')->andReturn('manage_my-post-type_posts_custom_column');

        $this->expectOutputString( '' );

        $this->controller->_maybe_print_column_cell( $this->anotherColumnName, 123 );
    }


}