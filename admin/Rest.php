<?php

namespace Wpapi\Admin;

use WP_REST_Request;
use WP_REST_Server;
use Wpapi\Core\KeyStatus;

class Rest
{
    protected $client;

    public function init() {
        $api_key = get_option('wpai_key');
        if (!$api_key) {
            wp_send_json_error('invalid_api_key', 400);
        }
        $this->client = new AiRequest($api_key);
        register_rest_route('wp-api/v1', '/hello', [
            'methods' => WP_REST_Server::READABLE,
            'permission_callback' => function (\WP_REST_Request $request) {
				return is_user_logged_in() && current_user_can( 'manage_options' );
			},
            'callback' => [$this, 'say_hello'],
        ]);

        register_rest_route('wp-api/v1', '/generate', [
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => function (\WP_REST_Request $request) {
                return is_user_logged_in() && current_user_can( 'manage_options' );
            },
            'callback' => [$this, 'generate_post'],
        ]);
    }

    public function say_hello() {
        try {
            $this->client->test_connection();
            update_option('wpai_key_valid', KeyStatus::VALID->value);
            wp_send_json_success(['status' => 'success']);
        } catch (\Exception $e) {
            update_option('wpai_key_valid', KeyStatus::INVALID->value);
            wp_send_json_error($e->getMessage(), 400);
        }
    }

    public function generate_post(WP_REST_Request $request): string {
        if ($request->get_param('prompt') && !empty($request->get_param('prompt'))) {
            try {
                $response = $this->client->generate_post($request->get_param('prompt'));
                if (is_object($response)) {
                    $post = wp_insert_post([
                        'post_title' => wp_strip_all_tags($response->title),
                        'post_content' => $response->content,
                        'post_status'   => 'publish',
                        'post_author'   => get_current_user_id(),
                    ], true);
                    if (is_wp_error($post)) {
                        throw new \Exception($post->get_error_message());
                    } else {
                        $url = get_permalink($post);
                        wp_send_json_success(compact('url'));
                    }
                }
            } catch (\Exception $e) {
                wp_send_json_error($e->getMessage(), 400);
            }
        }
    }
}