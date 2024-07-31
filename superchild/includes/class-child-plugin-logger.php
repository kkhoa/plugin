<?php

class Child_Plugin_Logger {

    public static function log($message) {
        $log_file = plugin_dir_path( __FILE__ ) . '../logs/plugin-log.txt';
        $timestamp = date("Y-m-d H:i:s");
        $log_message = "[$timestamp] $message\n";

        file_put_contents($log_file, $log_message, FILE_APPEND);
    }
}
