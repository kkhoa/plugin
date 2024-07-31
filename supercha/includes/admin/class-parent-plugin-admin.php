<?php

class Parent_Plugin_Admin {
    public function add_admin_menu() {
        add_menu_page(
            'Parent Plugin',
            'Parent Plugin',
            'manage_options',
            'parent-plugin',
            array( $this, 'create_admin_page' ),
            'dashicons-admin-generic',
            90
        );
    }

    public function create_admin_page() {
        require_once plugin_dir_path( __FILE__ ) . 'partials/parent-plugin-admin-display.php';
    }

    public function register_settings() {
        register_setting( 'parent_plugin_options', 'parent_plugin_options' );
        add_settings_section(
            'parent_plugin_settings_section',
            __( 'Settings', 'parent-plugin' ),
            null,
            'parent-plugin'
        );

        add_settings_field(
            'api_key',
            __( 'API Key', 'parent-plugin' ),
            array( $this, 'api_key_callback' ),
            'parent-plugin',
            'parent_plugin_settings_section'
        );
    }

    public function api_key_callback() {
        $options = get_option( 'parent_plugin_options' );
        echo '<input type="text" id="api_key" name="parent_plugin_options[api_key]" value="' . esc_attr( $options['api_key'] ) . '">';
    }
}
