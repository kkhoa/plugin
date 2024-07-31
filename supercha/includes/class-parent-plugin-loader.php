<?php

class Parent_Plugin_Loader {
    protected $admin;
    protected $public;

    public function __construct() {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'class-parent-plugin-i18n.php';
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-parent-plugin-admin.php';
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-parent-plugin-settings.php';
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-parent-plugin-logger.php';
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-parent-plugin-security.php';
        require_once plugin_dir_path( __FILE__ ) . 'admin/class-parent-plugin-dashboard.php';
        require_once plugin_dir_path( __FILE__ ) . 'public/class-parent-plugin-public.php';
        require_once plugin_dir_path( __FILE__ ) . 'public/class-parent-plugin-api.php';
        require_once plugin_dir_path( __FILE__ ) . 'public/class-parent-plugin-rate-limiter.php';
    }

    private function set_locale() {
        $plugin_i18n = new Parent_Plugin_i18n();
        add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );
    }

    private function define_admin_hooks() {
        $plugin_admin = new Parent_Plugin_Admin();
        add_action( 'admin_menu', array( $plugin_admin, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $plugin_admin, 'register_settings' ) );
    }

    private function define_public_hooks() {
        $plugin_public = new Parent_Plugin_Public();
        add_action( 'rest_api_init', array( $plugin_public, 'register_api_routes' ) );
    }

    public function run() {
        // Run the loader
    }
}
