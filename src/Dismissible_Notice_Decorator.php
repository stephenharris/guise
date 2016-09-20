<?php

namespace stephenharris\guise;

class Dismissible_Notice_Decorator extends Base_Notice_View {

    public function __construct( Base_Notice_View $decorated_notice_view ) {
        $this->decorated_notice_view = $decorated_notice_view;
    }

    public function get_classes() {
        $classes = $this->decorated_notice_view->get_classes();
        $classes[] = 'is-dismissible';
        return $classes;
    }

    public function get_message() {
        return $this->decorated_notice_view->get_message();
    }


}
