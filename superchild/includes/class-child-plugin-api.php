<?php

class Child_Plugin_API {

    public function __construct() {
        add_action( 'wp_ajax_fetch_parent_data', array( $this, 'fetch_parent_data' ) );
        add_action( 'wp_ajax_sync_now', array( $this, 'sync_now' ) );
    }

    public function fetch_parent_data() {
        check_ajax_referer( 'child_plugin_nonce', 'nonce' );

        $options = get_option( 'child_plugin_options' );
        $api_url = isset( $options['api_url'] ) ? esc_url_raw( $options['api_url'] ) : '';

        if ( empty( $api_url ) ) {
            Child_Plugin_Logger::log( 'API URL is not set.' );
            wp_send_json_error( 'API URL is not set.' );
        }

        $response = wp_remote_get( $api_url );

        if ( is_wp_error( $response ) ) {
            Child_Plugin_Logger::log( 'Error fetching data from parent plugin' );
            wp_send_json_error( 'Error fetching data from parent plugin' );
        }

        $body = wp_remote_retrieve_body( $response );
        Child_Plugin_Logger::log( 'Data fetched successfully from parent plugin' );

        wp_send_json_success( json_decode( $body ) );
    }

    public function sync_now() {
        $this->fetch_parent_data();
        wp_send_json_success( 'Manual sync executed successfully' );
        Child_Plugin_Logger::log( 'Manual sync executed successfully' );
    }

    public function run() {
        // Run additional initialization if needed
    }
}
