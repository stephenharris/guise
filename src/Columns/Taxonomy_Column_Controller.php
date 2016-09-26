<?php

namespace StephenHarris\Guise\Columns;

class Taxonomy_Column_Controller {

	private $column_view_store = null;

	function __construct() {
		$this->column_view_store = new Column_View_Store();
	}

	public function register( Taxonomy_Column_View $column_view, $taxonomy, $index = -1 ) {
		$this->column_view_store->store( $column_view, $taxonomy, $index );
		add_filter( "manage_edit-{$taxonomy}_columns", array( $this, '_maybe_add_column' ) );
		add_filter( "manage_{$taxonomy}_custom_column", array( $this, '_maybe_render_column_cell' ), 10, 3 );
		if ( $column_view instanceof Sortable_Column_View ) {
			add_filter( "manage_edit-{$taxonomy}_sortable_columns", array( $this, '_maybe_add_sortable_column' ) );
		}
	}

	/**
	 * @private
	 */
	public function _maybe_add_column( $columns ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_edit-([a-z_-]{1,32})_columns/', $hook, $matches ) ) {
			return $columns;
		}

		$taxonomy = $matches[1];
		$columns = $this->column_view_store->insert_columns_for_object( $taxonomy, $columns );

		return $columns;
	}

	/**
	 * @private
	 */
	public function _maybe_add_sortable_column( $columns ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_edit-([a-z_-]{1,32})_sortable_columns/', $hook, $matches ) ) {
			return $columns;
		}

		$taxonomy = $matches[1];
		$columns = $this->column_view_store->insert_sortable_columns_for_object( $taxonomy, $columns );

		return $columns;
	}

	/**
	 * @private
	 */
	public function _maybe_render_column_cell( $content, $column_name, $term_id ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_([a-z_-]{1,32})_custom_column/', $hook, $matches ) ) {
			return $content;
		}

		$taxonomy = $matches[1];
		$column_view = $this->column_view_store->get_column_view( $taxonomy, $column_name );

		return ( $column_view instanceof Null_Column_View ) ? $content : $column_view->render( \get_term( $term_id, $taxonomy ) );
	}

}
