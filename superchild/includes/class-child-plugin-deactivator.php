<?php

class Child_Plugin_Deactivator {

    public static function deactivate() {
        // Xóa cron job đã đăng ký
        wp_clear_scheduled_hook( 'child_plugin_scheduled_sync' );

        // Xóa bảng dữ liệu nếu cần
        self::drop_plugin_table();
    }

    private static function drop_plugin_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'child_plugin_data';

        $sql = "DROP TABLE IF EXISTS $table_name;";
        $wpdb->query( $sql );
    }
}
