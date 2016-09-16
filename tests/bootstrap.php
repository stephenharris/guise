<?php

require_once 'vendor/autoload.php';

require_once 'assertions/PHPUnit_Framework_Constraint_NthElementOfArrayHasValue.php';

//Load WP class references for typehinting
require_once 'WP/WP_Post.php';
require_once 'WP/WP_Term.php';

//Stubs
require_once 'stubs/Sortable_Post_Type_Column_View.php';
require_once 'stubs/Sortable_Taxonomy_Column_View.php';