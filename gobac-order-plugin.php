<?php
/*
Plugin Name: Gobac Moving Boxes Wizard
Description: Multi-step form for box rental service
Version: 1.0
Author: @ferpk
*/

// Prevent direct access
if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-gobac-settings.php';

class GobacMovingBoxes {
    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        // Enhanced WooCommerce check
        $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';
        
        if (
            in_array($plugin_path, wp_get_active_and_valid_plugins())
            || in_array($plugin_path, wp_get_active_network_plugins())
        ) {
            // WooCommerce is active, initialize plugin
            $settings = new Gobac_Settings();
            add_action('admin_menu', array($this, 'add_admin_menu'));
        } else {
            // WooCommerce not active
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
        }
    }

    public function add_admin_menu() {
        // Add main menu
        add_menu_page(
            'Gobac Moving Boxes',          // Page title
            'Gobac Boxes',                 // Menu title
            'manage_options',              // Capability
            'gobac-moving-boxes',          // Menu slug
            array($this, 'render_settings_page'), // Callback
            'dashicons-archive',           // Icon
            30                            // Position
        );

        // Add submenus
        add_submenu_page(
            'gobac-moving-boxes',         // Parent slug
            'Products Config',            // Page title
            'Products Config',            // Menu title
            'manage_options',             // Capability
            'gobac-products',             // Menu slug
            array($this, 'render_products_page')
        );

        add_submenu_page(
            'gobac-moving-boxes',         // Parent slug
            'Delivery Zones',             // Page title
            'Delivery Zones',             // Menu title
            'manage_options',             // Capability
            'gobac-zones',                // Menu slug
            array($this, 'render_zones_page')
        );
    }

    public function render_settings_page() {
        include plugin_dir_path(__FILE__) . 'templates/admin/settings-page.php';
    }

    public function render_products_page() {
        include plugin_dir_path(__FILE__) . 'templates/admin/products-settings.php';
    }

    public function render_zones_page() {
        include plugin_dir_path(__FILE__) . 'templates/admin/zones-settings.php';
    }

    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('Gobac Moving Boxes requires WooCommerce to be installed and active.', 'gobac-moving-boxes'); ?></p>
        </div>
        <?php
    }
}

// Initialize the plugin
GobacMovingBoxes::get_instance();