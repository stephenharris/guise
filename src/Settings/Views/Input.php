<?php

namespace StephenHarris\Guise\Settings\Views;

class Input extends Element {

    public function __construct( $name, $label ) {
        $this->label = $label;
        $this->set_name( $name );
        $this->set_attribute( 'type', 'text' );
    }

    public function label() {
        return $this->label;
    }

    function set_value( $value ) {
        $this->set_attribute( 'value', $value );
    }

    function get_value() {
        return $this->get_attribute( 'value' );
    }

    public function parse_stored_value( $stored_value ) {
        $this->set_value( $stored_value );
    }

    public function render() {
        return sprintf( '<input %s/>', trim( $this->render_attributes() ) );
    }

}