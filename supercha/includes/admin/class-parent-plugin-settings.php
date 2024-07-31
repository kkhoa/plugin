<?php

class Parent_Plugin_Settings {
    public static function init() {
        register_setting(
            'parent_plugin_settings',
            'parent_plugin_options',
            array(__CLASS__, 'sanitize')
        );

        add_settings_section(
            'setting_section_id',
            __('Settings', 'parent-plugin'),
            array(__CLASS__, 'print_section_info'),
            'parent_plugin_settings'
        );

        add_settings_field(
            'api_key',
            __('API Key', 'parent-plugin'),
            array(__CLASS__, 'settings_field_callback'),
            'parent_plugin_settings',
            'setting_section_id',
            array('field' => 'api_key')
        );
    }

    public static function sanitize($input) {
        $new_input = array();
        if (isset($input['api_key'])) {
            $new_input['api_key'] = sanitize_text_field($input['api_key']);
        }
        return $new_input;
    }

    public static function print_section_info() {
        print __('Enter your settings below:', 'parent-plugin');
    }

    public static function settings_field_callback($args) {
        $options = get_option('parent_plugin_options');
        $field = $args['field'];
        $value = isset($options[$field]) ? esc_attr($options[$field]) : '';
        echo "<input type='text' name='parent_plugin_options[$field]' value='$value' />";
    }
}
