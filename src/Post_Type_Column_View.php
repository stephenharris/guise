<?php

namespace stephenharris\guise;


interface Post_Type_Column_View {

	public function label();

	public function render( \WP_Post $post_type );

}
