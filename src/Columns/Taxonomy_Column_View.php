<?php

namespace StephenHarris\Guise\Columns;


interface Taxonomy_Column_View extends Column_View {

	public function render( \WP_Term $term );

}
