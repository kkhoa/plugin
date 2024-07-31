<?php

class Parent_Plugin_Activator {
    public static function activate() {
        if (!get_option('parent_plugin_options')) {
            add_option('parent_plugin_options', array('api_key' => ''));
        }

        if (!file_exists(plugin_dir_path(__FILE__) . '../../logs')) {
            mkdir(plugin_dir_path(__FILE__) . '../../logs', 0755, true);
        }
    }
}
