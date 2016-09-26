<?php

namespace StephenHarris\Guise\Settings\Views;

class MultipleCheckbox extends Element {

    protected $checkbox_views = array();

    public function __construct( $name, $label, $options ) {
        $this->label = $label;
        $this->set_name( $name );

        foreach( $options as $value => $label ) {
            $checkbox_view = new Checkbox( $name.'[]', $label, $value );
            $checkbox_view->set_id( $this->get_id() . '_' . $value );
            $this->checkbox_views[$value] = $checkbox_view;
        }
    }

    public function label() {
        return $this->label;
    }

    public function parse_stored_value( $stored_value ) {
        $stored_value = (array) $stored_value;
        foreach ( $this->checkbox_views as $checkbox_view ) {
            $checkbox_view->uncheck();
            if ( in_array( $checkbox_view->get_value(), $stored_value) ) {
                $checkbox_view->check();
            }
        }
    }

    public function check( $option_value ) {
        if ( ! array_key_exists( (string) $option_value, $this->checkbox_views ) ) {
            throw new \InvalidArgumentException( sprintf( "Checkbox \"%s\" does not exist", $option_value ) );
        }
        $this->checkbox_views[$option_value]->check();
    }

    public function uncheck( $option_value ) {
        if ( ! array_key_exists( (string) $option_value, $this->checkbox_views ) ) {
            throw new \InvalidArgumentException( sprintf( "Checkbox \"%s\" does not exist", $option_value ) );
        }
        $this->checkbox_views[$option_value]->uncheck();
    }

    public function render() {
        $html = '<ul>';
        foreach ( $this->checkbox_views as $checkbox_view ) {
            $html .= sprintf(
                '<li> %s<label for="%s">%s</label></li>',
                $checkbox_view->render(),
                $checkbox_view->get_id(),
                $checkbox_view->label()
            );
        }
        $html .= '</ul>';
        return $html;
    }

}