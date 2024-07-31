<?php
/**
 * Plugin Name: Parent Plugin
 * Description: A parent plugin to manage products and synchronize with child sites.
 * Version: 2.0.0
 * Author: Your Name
 * Text Domain: parent-plugin
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once plugin_dir_path(__FILE__) . 'includes/admin/class-parent-plugin-admin.php';
require_once plugin_dir_path(__FILE__) . 'includes/api/class-parent-plugin-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/sync/class-parent-plugin-sync.php';
require_once plugin_dir_path(__FILE__) . 'includes/logger/class-parent-plugin-logger.php';
require_once plugin_dir_path(__FILE__) . 'includes/security/class-parent-plugin-security.php';
require_once plugin_dir_path(__FILE__) . 'includes/i18n/class-parent-plugin-i18n.php';

class ParentPlugin {
    public function __construct() {
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
        $this->load_textdomain();
    }

    private function load_dependencies() {
        new Parent_Plugin_Admin();
        new Parent_Plugin_API();
        new Parent_Plugin_Sync();
        new Parent_Plugin_Logger();
        new Parent_Plugin_Security();
        new Parent_Plugin_i18n();
    }

    private function define_admin_hooks() {
        add_action('admin_menu', array('Parent_Plugin_Admin', 'add_menu'));
    }

    private function define_public_hooks() {
        add_action('wp_ajax_nopriv_parent_plugin_sync', array('Parent_Plugin_Sync', 'sync_data'));
        add_action('wp_ajax_parent_plugin_sync', array('Parent_Plugin_Sync', 'sync_data'));
    }

    private function load_textdomain() {
        $plugin_i18n = new Parent_Plugin_i18n();
        $plugin_i18n->load_plugin_textdomain();
    }
}

new ParentPlugin();
