<?php

class Parent_Plugin_Admin {
    public function __construct() {
        add_action('admin_menu', array($this, 'add_menu'));
        add_action('admin_init', array($this, 'settings_init'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function add_menu() {
        add_menu_page('Parent Plugin', 'Parent Plugin', 'manage_options', 'parent-plugin', array($this, 'admin_page'));
        add_submenu_page('parent-plugin', 'Settings', 'Settings', 'manage_options', 'parent-plugin-settings', array($this, 'settings_page'));
    }

    public function admin_page() {
        echo '<div class="wrap">';
        echo '<h1>Parent Plugin Dashboard</h1>';
        echo '<p>Welcome to the Parent Plugin dashboard.</p>';
        echo '</div>';
    }

    public function settings_page() {
        echo '<div class="wrap">';
        echo '<h1>Parent Plugin Settings</h1>';
        echo '<form method="POST" action="options.php">';
        settings_fields('parent_plugin_settings');
        do_settings_sections('parent_plugin_settings');
        submit_button();
        echo '</form>';
        echo '</div>';
    }

    public function settings_init() {
        register_setting('parent_plugin_settings', 'parent_plugin_options');
        add_settings_section('parent_plugin_section', __('API Settings', 'parent-plugin'), array($this, 'settings_section_callback'), 'parent_plugin_settings');
        add_settings_field('parent_plugin_api_url', __('API URL', 'parent-plugin'), array($this, 'settings_field_callback'), 'parent_plugin_settings', 'parent_plugin_section', array('field' => 'api_url'));
        add_settings_field('parent_plugin_api_key', __('API Key', 'parent-plugin'), array($this, 'settings_field_callback'), 'parent_plugin_settings', 'parent_plugin_section', array('field' => 'api_key'));
    }

    public function settings_section_callback() {
        echo __('Enter your API settings below:', 'parent-plugin');
    }

    public function settings_field_callback($args) {
        $options = get_option('parent_plugin_options');
        $field = $args['field'];
        echo '<input type="text" name="parent_plugin_options[' . $field . ']" value="' . esc_attr($options[$field]) . '">';
    }

    public function enqueue_styles() {
        wp_enqueue_style('parent-plugin-admin-css', plugin_dir_url(__FILE__) . '../../assets/css/admin.css', array(), '1.0.0', 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script('parent-plugin-admin-js', plugin_dir_url(__FILE__) . '../../assets/js/admin.js', array('jquery'), '1.0.0', true);
    }
}
