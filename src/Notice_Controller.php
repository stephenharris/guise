<?php

namespace stephenharris\guise;

class Notice_Controller {

    private $notices = array();

    public function register( $id, Notice_View $notice_View ) {
        $this->notices[$id] = $notice_View;
        add_action( 'admin_notices', array( $this, '_print_notices' ) );
    }

    /**
     * @private
     * @hooked admin_notices
     */
    public function _print_notices() {
        if ( $this->notices ) {
            foreach ( $this->notices as $notice ) {
                echo $notice->render();
            }
        }
    }

}
