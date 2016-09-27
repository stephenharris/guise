<?php
namespace StephenHarris\Guise\Settings\Views;


/**
 * A class which decorates a Section view to provide a method to print the view.
 *
 * WordPress' settings API requires we provide a callback which prints the content of the section. Rather than require
 * that client code provides such a print method, we can just use the render() method and print it ourselves by decorating
 * the passed view.
 *
 * @private
 */
class SectionPrintDecorator implements Section {

    protected $section;

    function __construct( Section $section ) {
        $this->section = $section;
    }

    public function label() {
        return $this->section->label();
    }

    public function render() {
        return $this->section->render();
    }

    public function _print() {
        echo $this->render();
    }

}