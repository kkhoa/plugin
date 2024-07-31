<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

// Xóa cài đặt plugin
delete_option('parent_plugin_options');

// Xóa logs
$log_file = plugin_dir_path(__FILE__) . 'logs/plugin-log.txt';
if (file_exists($log_file)) {
    unlink($log_file);
}
