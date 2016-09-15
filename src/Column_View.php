<?php

namespace stephenharris\guise;


interface Column_View {

	public function label();

	public function render( $object );

}
