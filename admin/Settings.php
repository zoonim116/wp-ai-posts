<?php

namespace Wpapi\Admin;

use Wpapi\Core\KeyStatus;
use Wpapi\Helper;
use Wpapi\Admin\Menu;

class Settings
{
    public function __construct() {
        if (Helper::is_request('admin')) {
            register_setting('wpai_settings', 'wpai_key', [
                'sanitize_callback' => [$this, 'sanitize_key'],
                'default' => '',
            ]);
            register_setting('wpai_settings', 'wpai_key_valid');
            add_action('admin_menu', [Menu::class, 'register_menu']);
        }
    }

    public function sanitize_key($setting): string {
        if (get_option('wpai_key') !== $setting) {
            update_option('wpai_key_valid', KeyStatus::UNDEFINED->value);
        }
        return trim(sanitize_text_field($setting));
    }

    public function get_status(int $status): string {
        return \KeyStatus::from($status);
    }
}