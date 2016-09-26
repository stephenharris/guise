<?php

namespace StephenHarris\Guise\Settings;
use Respect\Validation\Validatable;
use Respect\Validation\Exceptions\AbstractNestedException;
use Respect\Validation\Exceptions\ValidationExceptionInterface;

class Page {

    private $page;

    private $sections;

    private $settings;

    function __construct( $page ) {
        $this->page = $page;
    }

    public function register_setting( $setting_id, $section_id, Views\Setting $view_class, Validatable $validator ) {
        $this->settings[$setting_id] = array(
            'id'         => $setting_id,
            'section_id' => $section_id,
            'view'       => $view_class,
            'validator'  => $validator
        );
        add_action( 'admin_init', array( $this, '_settings_init' ) );
    }

    public function deregister_setting( $setting_id ) {
        unset( $this->settings[$setting_id] );
        unregister_setting( $this->page, $setting_id );
        remove_filter( "sanitize_option_{$setting_id}", array( $this, '_validate' ), 10, 3 );
    }

    public function register_section( $section_id, Views\Section $section_view ) {
        $this->sections[$section_id] = array(
            'id'   => $section_id,
            'view' => $section_view,
        );
        add_action( 'admin_init', array( $this, '_settings_init' ) );
    }

    public function _settings_init() {
        $this->register_settings_with_wp();
        $this->register_sections_with_wp();
    }

    private function register_settings_with_wp() {
        if ( $this->settings ) {
            foreach ( $this->settings as $setting_id => $setting ) {
                register_setting( $this->page, $setting_id );
                add_filter( "sanitize_option_{$setting_id}", array( $this, '_validate' ), 10, 2 );
                add_settings_field(
                    $setting_id,
                    $setting['view']->label(),
                    array( $this, '_render_setting_proxy' ),
                    $this->page,
                    $setting['section_id'],
                    array( 'id' => $setting_id, 'view' => $setting['view'], 'label_for' => $setting_id )
                );
            }
        }
    }

    private function register_sections_with_wp() {
        if ( $this->sections ) {
            foreach ( $this->sections as $id => $section ) {
               add_settings_section( $id, $section['view']->label(), array( $section['view'], '_print' ), $this->page );
            }
        }
    }

    public function _render_setting_proxy( $args ) {
        $value = get_option( $args['id'] );
        $args['view']->parse_stored_value( $value );
        echo $args['view']->render();
    }

    public function _validate( $value, $option_id ) {
        $setting = $this->settings[$option_id];
        if (!isset($this->settings[$option_id])) {
            return $value;
        }

        try {
            $setting['validator']->assert($value);
        } catch ( AbstractNestedException $e) {
            $e->setName( $setting['view']->label() );
            $messages = $this->nested_exception_to_array( $e );
            $message = implode( '<br>', $messages );
            add_settings_error($this->page, $option_id, $message);
            $value = get_option( $option_id );
        } catch( ValidationExceptionInterface $e ) {
            $e->setName( $setting['view']->label() );
            add_settings_error( $this->page, $option_id, $e->getMainMessage() );
            $value = get_option( $option_id );
        } catch( \Exception $e ) {
            add_settings_error( $this->page, $option_id, $e->getMessage() );
            $value = get_option( $option_id );
        }

        return $value;
    }

    private function nested_exception_to_array( AbstractNestedException $e ) {
        $iterator = $e->getIterator(false, AbstractNestedException::ITERATE_TREE);
        return iterator_to_array( $iterator );
    }

}