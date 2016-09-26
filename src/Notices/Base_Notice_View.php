<?php

namespace StephenHarris\Guise\Notices;

abstract class Base_Notice_View implements Notice_View {

    protected $message;

    protected $classes = array();

    public function __construct( $message ) {
        $this->message = $message;
    }

    public function get_classes() {
        return $this->classes;
    }

    public function get_message() {
        return $this->message;
    }

    protected function sanitize_class_attribute( $attribute_value ) {

        //Strip out any % encoded octets
        $sanitized_attribute_value = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $attribute_value );

        //Limit to a-z,numbers, dashes, underscores and spaces
        $sanitized_attribute_value = preg_replace( '/[^0-9a-z\s-_]/i', '', $sanitized_attribute_value );

        //This is not necessarily a **valid** class name, but it is safe
        //@see https://www.w3.org/TR/CSS21/grammar.html#scanner

        return $sanitized_attribute_value;
    }

    function render() {
        return sprintf( '<div class="notice %s">%s</div>',
            $this->sanitize_class_attribute( implode( ' ', $this->get_classes() ) ),
            $this->get_message()
        );
    }

}
