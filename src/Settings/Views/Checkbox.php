<?php

namespace StephenHarris\Guise\Settings\Views;

class Checkbox extends Input {

    protected $label;

    protected $attributes;

    public function __construct( $name, $label, $value ) {
        $this->label = $label;
        $this->set_name( $name );
        $this->set_value( $value );
        $this->set_attribute( 'type', 'checkbox' );
    }

    public function parse_stored_value( $stored_value ) {
        $this->uncheck();
        if ( $stored_value == $this->get_value() ) {
            $this->check();
        }
    }

    public function check() {
        $this->set_attribute( 'checked', 'checked' );
    }

    public function uncheck() {
        $this->remove_attribute( 'checked' );
    }

    public function is_checked() {
        return ( $this->get_attribute( 'checked' ) == 'checked' );
    }

}