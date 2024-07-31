<?php

class Parent_Plugin_Logger {
    public static function log($message) {
        $file = plugin_dir_path(__FILE__) . '../../logs/sync.log';
        file_put_contents($file, date('Y-m-d H:i:s') . ' - ' . $message . PHP_EOL, FILE_APPEND);
    }
}
