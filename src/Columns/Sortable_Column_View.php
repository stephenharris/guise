<?php

namespace StephenHarris\Guise\Columns;


interface Sortable_Column_View {
	/**
	 * @return string The value of the orderby parameter
	 */
	function sort_by();
}
