<?php

namespace StephenHarris\Guise\Settings\Views;

class DescriptionDecorator implements Setting {

    function __construct( Setting $settingView, $description ) {
        $this->decoratedSetting = $settingView;
        $this->description = $description;
    }

    function label() {
        return $this->decoratedSetting->label();
    }

    function render() {
        return $this->decoratedSetting->render() . sprintf(
            '<p class="description">%s</p>',
            $this->description
        );
    }

    function parse_stored_value( $stored_value ) {
        return $this->decoratedSetting->parse_stored_value( $stored_value );
    }

    public function __call($method, $args)
    {
        if (!method_exists($this->decoratedSetting, $method)) {
            throw new Exception("Undefined method $method.");
        }
        return call_user_func_array(array($this->decoratedSetting, $method), $args);
    }

}