<?php
namespace StephenHarris\Guise\Settings\Views;

class SimpleSection implements Section {

    protected $title;
    protected $content;

    function __construct( $title, $content = null ) {
        $this->title = $title;
        $this->content = $content;
    }

    public function label() {
        return $this->title;
    }

    public function render() {
        return $this->content;
    }

}