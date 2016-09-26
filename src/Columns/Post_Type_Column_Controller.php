<?php

namespace StephenHarris\Guise\Columns;

class Post_Type_Column_Controller {

	private $column_view_store = null;

	function __construct() {
		$this->column_view_store = new Column_View_Store();
	}

	public function register( Post_Type_Column_View $column_view, $post_type, $index = -1 ) {
		$this->column_view_store->store( $column_view, $post_type, $index );
		add_filter( "manage_{$post_type}_posts_columns", array( $this, '_maybe_add_column' ) );
		add_action( "manage_{$post_type}_posts_custom_column", array( $this, '_maybe_print_column_cell' ), 10, 3 );
		if ( $column_view instanceof Sortable_Column_View ) {
			add_filter( "manage_{$post_type}_posts_sortable_columns", array( $this, '_maybe_add_sortable_column' ) );
		}
	}

	/**
	 * @private
	 */
	public function _maybe_add_column( $columns ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_([a-z_-]{1,32})_posts_columns/', $hook, $matches ) ) {
			return $columns;
		}

		$post_type = $matches[1];
		$columns = $this->column_view_store->insert_columns_for_object( $post_type, $columns );

		return $columns;
	}

	/**
	 * @private
	 */
	public function _maybe_add_sortable_column( $columns ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_([a-z_-]{1,32})_posts_sortable_columns/', $hook, $matches ) ) {
			return $columns;
		}

		$post_type = $matches[1];
		$columns = $this->column_view_store->insert_sortable_columns_for_object( $post_type, $columns );

		return $columns;
	}

	/**
	 * @private
	 */
	public function _maybe_print_column_cell( $column_name, $post_id ) {
		$hook = current_filter();
		if ( ! preg_match( '/manage_([a-z_-]{1,32})_posts_custom_column/', $hook, $matches ) ) {
			return;
		}

		$post_type = $matches[1];
		$column_view = $this->column_view_store->get_column_view( $post_type, $column_name );

		echo $column_view->render( \get_post( $post_id ) );
	}

}
