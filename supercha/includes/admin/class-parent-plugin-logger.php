<?php

class Parent_Plugin_Logger {
    private static $log_file = __DIR__ . '/../../logs/plugin-log.txt';

    public static function log($message) {
        if (!file_exists(self::$log_file)) {
            file_put_contents(self::$log_file, '');
        }

        $current_time = date('Y-m-d H:i:s');
        $formatted_message = "[{$current_time}] {$message}\n";
        file_put_contents(self::$log_file, $formatted_message, FILE_APPEND);
    }
}
