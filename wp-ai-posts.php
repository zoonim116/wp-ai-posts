<?php
/**
 * Wp Ai Posts
 *
 * @package   Wp Ai Posts
 * @author    Maxim Nepomniashchiy
 * @link      https://github.com/zoonim116/Pushify
 *
 * @wordpress-plugin
 * Plugin Name: Wp Ai Posts - Generate posts using AI
 * Plugin URI:  https://github.com/zoonim116/Pushify
 * Description: Generate posts using AI
 * Version:     1.0.0
 * Author:      Maxim Nepomniashchiy
 * License: GPLv3 or later
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: wp-ai
 * Domain Path: /i18n/
 */

use Wpapi\Admin\Assets;
use Wpapi\Admin\Rest;
use Wpapi\Admin\Settings;

if (!defined('ABSPATH')) {
    exit;
}

define('WP_AI_POSTS_PATH', plugin_dir_path(__FILE__));
define('WP_AI_POSTS_URL', plugin_dir_url(__FILE__));

require_once plugin_dir_path(__FILE__) . './vendor/autoload.php';

class WP_AI_Posts {
    private Rest $rest;
    private Settings $settings;
    private Assets $assets;
    public function __construct() {
        $this->rest = new Rest();
        $this->settings = new Settings();
        $this->assets = new Assets();

        $this->init();
    }

    private function init() {
        $this->assets->enqueue();
        add_action('rest_api_init', function () {
            $this->rest->init();
        });
    }
}

new WP_AI_Posts();