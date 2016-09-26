<?php

namespace StephenHarris\Guise\Settings\Views;

class Select extends Element {

    protected $checkbox_views = array();

    protected $options = array();

    protected $selected = array();

    public function __construct( $name, $label, $options ) {
        $this->label = $label;
        $this->set_name( $name );
        $this->set_options( $options );
    }

    public function label() {
        return $this->label;
    }

    protected function set_options( $options ) {
        $this->options = $options;
    }

    protected function get_options() {
        return $this->options;
    }

    public function select( $option_value ) {
        $options = $this->get_options();
        if ( ! array_key_exists( (string) $option_value, $options ) ) {
            throw new \InvalidArgumentException( sprintf( "Option \"%s\" does not exist", $option_value ) );
        }
        $this->selected = $option_value;
    }

    public function deselect() {
        $this->selected = null;
    }

    public function is_selected( $option_value ) {
        return ( $this->selected == $option_value );
    }

    public function parse_stored_value( $stored_value ) {
        try {
            $this->select( $stored_value );
        } catch ( \Exception $e ) {
            $this->deselect();
        }
    }

    public function render() {
        $html = sprintf( '<select %s>', $this->render_attributes() );

        $options = $this->get_options();
        if ( $options ) {
            foreach ( $options as $value => $label ) {
                $html .= $this->render_option( $value, $label );
            }
        }

        $html .= '</select>';
        return $html;
    }

    protected function render_option( $value, $label ) {
        return sprintf(
            '<option value="%s" %s >%s</option>',
            esc_attr( $value ),
            $this->is_selected( $value ) ? 'selected="selected"' : '',
            esc_attr( $label )
        );
    }

}