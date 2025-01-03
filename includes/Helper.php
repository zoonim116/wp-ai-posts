<?php

namespace Wpapi;

use Wpapi\Core\KeyStatus;

class Helper
{
    public static function is_request(string $type) {
        switch ($type) {
            case 'admin' :
                return is_admin();
            case 'ajax' :
                return defined('DOING_AJAX');
            case 'frontend' :
                return (!is_admin() || defined('DOING_AJAX')) && ! defined('DOING_CRON');
        }
    }

    public static function get_version(): string {
        return get_plugin_data(WP_AI_POSTS_PATH . '/wp-ai-posts.php')['Version'] ?? '1.0.0';
    }

    public static function getKeyStatus(int|string $value): string {
        return match ((int)$value) {
            KeyStatus::VALID->value => 'Valid',
            KeyStatus::INVALID->value => 'Invalid',
            default => 'Unknown',
        };
    }

}