<?php

namespace Wpapi\Admin;

class Menu
{
    public static function register_menu() {
        global $menu, $submenu;
        add_menu_page(
            __('WP Ai Posts', 'wpai'),
            __('WP Ai Posts', 'wpai'),
            'manage_options',
            'wpai_settings',
            'Wpapi\Admin\Menu::draw_settings_screen',
            'data:image/svg+xml;base64,' . base64_encode(file_get_contents(WP_AI_POSTS_PATH .'/assets/img/artificial-intelligence-svgrepo-com.svg')),
            16
        );
    }

    public static function draw_settings_screen() {
        ob_start();
        require_once WP_AI_POSTS_PATH . '/templates/settings.php';
        echo ob_get_clean();
    }
}