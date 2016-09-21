<?php

namespace StephenHarris\Guise;


interface Taxonomy_Column_View extends Column_View {

	public function render( \WP_Term $term );

}
