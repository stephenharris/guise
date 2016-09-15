<?php
use stephenharris\guise\Taxonomy_Column_Controller;
use MonkeryTestCase\BrainMonkeyWpTestCase;
use Brain\Monkey\Functions;

class RenderingTaxonomyColumnTest extends BrainMonkeyWpTestCase {

    private $columnName;
    private $anotherClass;
    private $controller;

    function setUp(){
        parent::setUp();

        $mockView = $this->getMockBuilder('\stephenharris\guise\Taxonomy_Column_View')->getMock();
        $mockView->method('render')->will($this->returnCallback(function( $term ){
            return 'Term ID: ' . $term->term_id;
        }));
        $this->columnName = spl_object_hash( $mockView );

        $anotherMockView = $this->getMockBuilder('\stephenharris\guise\Taxonomy_Column_View')->getMock();
        $anotherMockView->method('render')->will($this->returnValue('notthis'));
        $this->anotherColumnName = spl_object_hash( $anotherMockView );

        Functions::when('get_term')->alias( function( $id, $taxonomy ) {
            $mockTerm = $this->getMockBuilder('\WP_Term')->getMock();
            $mockTerm->term_id = $id;
            $mockTerm->taxonomy = $taxonomy;
            return $mockTerm;
        } );


        $this->controller = new Taxonomy_Column_Controller();
        $this->controller->register( $mockView, 'my-taxonomy', -1 );
        $this->controller->register( $anotherMockView, 'another-taxonomy', -1 );

    }

    function testRenderTaxonomyColumn() {

        Functions::expect('current_filter')->andReturn('manage_my-taxonomy_custom_column');

        $this->assertEquals( 'Term ID: 123', $this->controller->_maybe_render_column_cell( '', $this->columnName, 123 ) );
        $this->assertEquals( 'Term ID: 456', $this->controller->_maybe_render_column_cell( '', $this->columnName, 456 ) );

    }

    function testIgnoreUnknownColumn() {

        Functions::expect('current_filter')->andReturn('manage_my-taxonomy_custom_column');

        $this->assertEquals( '', $this->controller->_maybe_render_column_cell( '', $this->anotherColumnName, 456 ) );
        $this->assertEquals( 'foobar', $this->controller->_maybe_render_column_cell( 'foobar', $this->anotherColumnName, 456 ) );

    }


}