<?php

class Child_Plugin_Admin {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        add_action( 'child_plugin_scheduled_sync', array( $this, 'scheduled_sync' ) );
        add_action( 'admin_post_fetch_parent_data', array( $this, 'handle_fetch_parent_data' ) );
        add_action( 'admin_post_sync_now', array( $this, 'handle_sync_now' ) );
        $this->setup_cron_job();
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'Child Plugin Dashboard',
            'Child Plugin',
            'manage_options',
            'child-plugin',
            array( $this, 'display_plugin_admin_page' ),
            'dashicons-admin-generic',
            26
        );
    }

    public function display_plugin_admin_page() {
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'Child Plugin Dashboard', 'child-plugin' ); ?></h1>
            <button id="fetch-data-button" class="button button-primary">Fetch Data from Parent Plugin</button>
            <button id="sync-now-button" class="button button-secondary">Sync Now</button>
            <div id="fetch-data-result"></div>
            <form method="post" action="options.php">
                <?php
                    settings_fields( 'child_plugin_options' );
                    do_settings_sections( 'child-plugin' );
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function enqueue_admin_scripts() {
        wp_enqueue_script(
            'child-plugin-admin',
            plugin_dir_url( __FILE__ ) . '../../assets/js/admin.js',
            array( 'jquery' ),
            null,
            true
        );

        wp_localize_script( 'child-plugin-admin', 'childPlugin', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce'    => wp_create_nonce( 'child_plugin_nonce' ),
        ));
    }

    public function scheduled_sync() {
        $api = new Child_Plugin_API();
        $api->fetch_parent_data();
        Child_Plugin_Logger::log('Scheduled sync executed.');
    }

    public function handle_fetch_parent_data() {
        if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'child_plugin_nonce', 'nonce' ) ) {
            wp_die( 'Unauthorized' );
        }

        $api = new Child_Plugin_API();
        $result = $api->fetch_parent_data();
        if ( $result ) {
            wp_send_json_success( 'Data fetched successfully.' );
        } else {
            wp_send_json_error( 'Failed to fetch data.' );
        }
    }

    public function handle_sync_now() {
        if ( ! current_user_can( 'manage_options' ) || ! check_ajax_referer( 'child_plugin_nonce', 'nonce' ) ) {
            wp_die( 'Unauthorized' );
        }

        $api = new Child_Plugin_API();
        $result = $api->fetch_parent_data();
        if ( $result ) {
            wp_redirect( admin_url( 'admin.php?page=child-plugin' ) );
            exit;
        } else {
            wp_redirect( admin_url( 'admin.php?page=child-plugin' ) );
            exit;
        }
    }

    public function setup_cron_job() {
        if ( ! wp_next_scheduled( 'child_plugin_scheduled_sync' ) ) {
            $interval_in_hours = get_option('child_plugin_options')['sync_interval'] ?: 1;
            $interval_in_seconds = $interval_in_hours * 3600;
            wp_schedule_event( time(), 'custom_interval', 'child_plugin_scheduled_sync' );
        }

        add_filter( 'cron_schedules', function( $schedules ) {
            $options = get_option( 'child_plugin_options' );
            $interval_in_hours = isset( $options['sync_interval'] ) ? $options['sync_interval'] : 1;
            $interval_in_seconds = $interval_in_hours * 3600;

            $schedules['custom_interval'] = array(
                'interval' => $interval_in_seconds,
                'display'  => __( 'Custom Interval', 'child-plugin' ),
            );

            return $schedules;
        });
    }

    public function register_settings() {
        register_setting( 'child_plugin_options', 'child_plugin_options', array( $this, 'validate_options' ) );

        add_settings_section(
            'child_plugin_main',
            'Child Plugin Settings',
            array( $this, 'section_text' ),
            'child-plugin'
        );

        add_settings_field(
            'child_plugin_sync_interval',
            'Sync Interval (in hours)',
            array( $this, 'sync_interval_field' ),
            'child-plugin',
            'child_plugin_main'
        );

        add_settings_field(
            'child_plugin_api_url',
            'API URL',
            array( $this, 'api_url_field' ),
            'child-plugin',
            'child_plugin_main'
        );
    }

    public function section_text() {
        echo '<p>Enter your settings below:</p>';
    }

    public function sync_interval_field() {
        $options = get_option( 'child_plugin_options' );
        echo "<input id='child_plugin_sync_interval' name='child_plugin_options[sync_interval]' size='40' type='number' value='{$options['sync_interval']}' />";
    }

    public function api_url_field() {
        $options = get_option( 'child_plugin_options' );
        echo "<input id='child_plugin_api_url' name='child_plugin_options[api_url]' size='40' type='text' value='{$options['api_url']}' />";
    }

    public function validate_options( $input ) {
        $new_input = array();
        $new_input['sync_interval'] = absint( $input['sync_interval'] );
        $new_input['api_url'] = esc_url_raw( $input['api_url'] );

        if ( $new_input['sync_interval'] > 0 ) {
            if ( ! wp_next_scheduled( 'child_plugin_scheduled_sync' ) ) {
                wp_schedule_event( time(), 'custom_interval', 'child_plugin_scheduled_sync' );
            } else {
                $timestamp = wp_next_scheduled( 'child_plugin_scheduled_sync' );
                wp_unschedule_event( $timestamp, 'child_plugin_scheduled_sync' );
                wp_schedule_event( time(), 'custom_interval', 'child_plugin_scheduled_sync' );
            }
        } else {
            wp_clear_scheduled_hook( 'child_plugin_scheduled_sync' );
        }

        return $new_input;
    }
}
