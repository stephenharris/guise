<?php

namespace StephenHarris\Guise\Settings\Views;

abstract class Element implements Setting {

    protected $attributes;

    public function get_id() {
        $id = $this->get_attribute('id');
        if ( is_null( $id ) ) {
            $name = $this->get_name();
            $id = preg_replace( '/[^0-9a-z-_]/i', '', $name );
        }
        return $id;
    }

    public function set_id( $id ) {
        $this->set_attribute( 'id', $id );
    }

    public function get_name() {
        return $this->get_attribute('name');
    }

    public function set_name( $name ) {
        $this->set_attribute( 'name', $name );
    }

    protected function get_attributes() {
        return $this->attributes;
    }

    public function set_attribute( $key, $value ) {
        $this->attributes[$key] = $value;
    }

    public function get_attribute( $key ) {
        return isset( $this->attributes[$key] ) ? $this->attributes[$key] : null;
    }

    public function remove_attribute( $key ) {
        unset( $this->attributes[$key] );
    }

    protected function render_attributes() {
        $attributes = array_filter( $this->get_attributes(), function($var){return !is_null($var);} );
        $rendered_attributes = '';
        foreach( $attributes as $key => $value ) {
            $rendered_attributes .= sprintf( ' %s="%s"', esc_attr( $key ), esc_attr( $value ) );
        }
        return $rendered_attributes;
    }

}