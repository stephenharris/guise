<?php

namespace stephenharris\guise;


interface Taxonomy_Column_View {

	public function label();

	public function render( \WP_Term $term );

}
