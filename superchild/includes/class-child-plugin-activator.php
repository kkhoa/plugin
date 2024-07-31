<?php

class Child_Plugin_Activator {

    public static function activate() {
        // Thiết lập các tùy chọn mặc định nếu chưa có
        if ( false === get_option( 'child_plugin_options' ) ) {
            add_option( 'child_plugin_options', array(
                'sync_interval' => 1, // Khoảng thời gian đồng bộ (giờ)
                'api_url'        => 'http://localhost/dm_kt/wp-admin/admin-ajax.php?action=parent_get_products_public',
            ));
        }

        // Cấu hình cơ sở dữ liệu
        self::create_plugin_table();

        // Đăng ký cron job nếu chưa có
        if ( ! wp_next_scheduled( 'child_plugin_scheduled_sync' ) ) {
            wp_schedule_event( time(), 'custom_interval', 'child_plugin_scheduled_sync' );
        }
    }

    private static function create_plugin_table() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'child_plugin_data';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(255) DEFAULT '' NOT NULL,
            value text NOT NULL,
            UNIQUE KEY id (id)
        ) $charset_collate;";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }
}
