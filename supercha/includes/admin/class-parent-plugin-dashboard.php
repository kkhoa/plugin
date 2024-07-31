<?php

class Parent_Plugin_Dashboard {
    public static function render_dashboard() {
        // Display dashboard with logs and stats
        echo '<h2>Parent Plugin Dashboard</h2>';
        echo '<pre>';
        echo file_get_contents(plugin_dir_path(__FILE__) . '../../logs/plugin-log.txt');
        echo '</pre>';
    }
}
