<?php

namespace Wpapi\Admin;

use Wpapi\Helper;

class Assets {
    public function enqueue() {
        add_action( 'admin_enqueue_scripts', function ($hook) {
            if ( 'toplevel_page_wpai_settings' != $hook ) {
                return;
            }
            wp_enqueue_style( 'wp-ai-admin-styles', WP_AI_POSTS_URL. 'assets/css/admin.css', [], Helper::get_version());
            wp_enqueue_script( 'wp-ai-admin-script', WP_AI_POSTS_URL. 'assets/js/main.js', [], Helper::get_version());
            wp_localize_script( 'wp-ai-admin-script', 'wpApiSettings', array(
                'root' => esc_url_raw( rest_url() ),
                'nonce' => wp_create_nonce( 'wp_rest' )
            ) );
        });
    }
}
