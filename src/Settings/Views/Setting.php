<?php

namespace StephenHarris\Guise\Settings\Views;

interface Setting extends View {

    public function parse_stored_value( $stored_value );

}