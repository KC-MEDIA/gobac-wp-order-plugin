<?php
/*
Plugin Name: Gobac Moving Boxes - custom plugin
Description: Multi-step form for box rental service. Requires WooCommerce to be installed and activated.
Version: 1.0
Author: @Ferpk
WC requires at least: 5.0
WC tested up to: 8.5
*/

// Prevent direct access
if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-gobac-products.php';
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
        // Enhanced WooCommerce dependency check
        $plugin_path = trailingslashit(WP_PLUGIN_DIR) . 'woocommerce/woocommerce.php';
        
        if (
            !in_array($plugin_path, wp_get_active_and_valid_plugins())
            && !in_array($plugin_path, wp_get_active_network_plugins())
        ) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            add_action('admin_init', array($this, 'deactivate_self'));
            return;
        }

        // Wait for WooCommerce to fully initialize
        add_action('woocommerce_init', array($this, 'init'));
    }

    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php _e('Gobac Moving Boxes requires WooCommerce to be installed and active. Please install and activate WooCommerce before using this plugin.', 'gobac-moving-boxes'); ?></p>
        </div>
        <?php
    }

    public function deactivate_self() {
        deactivate_plugins(plugin_basename(__FILE__));
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }
    }

    // Check minimum WooCommerce version
    private function check_wc_version() {
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '5.0', '<')) {
            add_action('admin_notices', function() {
                ?>
                <div class="error">
                    <p><?php _e('Gobac Moving Boxes requires WooCommerce version 5.0 or higher.', 'gobac-moving-boxes'); ?></p>
                </div>
                <?php
            });
            return false;
        }
        return true;
    }

    private function init() {
        if (!$this->check_wc_version()) {
            return;
        }

        // Add settings class
        $settings = new Gobac_Settings();
        $settings->register_settings();

        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add shortcode for the wizard
        add_shortcode('gobac_moving_wizard', array($this, 'render_wizard'));
        
        // Ajax handlers
        add_action('wp_ajax_validate_postal_code', array($this, 'validate_postal_code'));
        add_action('wp_ajax_nopriv_validate_postal_code', array($this, 'validate_postal_code'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function enqueue_scripts() {
        wp_enqueue_style('gobac-wizard', plugin_dir_url(__FILE__) . 'assets/css/gobac-wizard.css');
        wp_enqueue_script('gobac-wizard', plugin_dir_url(__FILE__) . 'assets/js/gobac-wizard.js', array('jquery'), '1.0', true);
        
        // Add localized script data
        wp_localize_script('gobac-wizard', 'gobacWizard', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('gobac_wizard_nonce')
        ));
    }

    public function render_wizard() {
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/wizard-step-1.php';
        return ob_get_clean();
    }

    public function validate_postal_code() {
        check_ajax_referer('gobac_wizard_nonce', 'nonce');
        
        $postal_code = sanitize_text_field($_POST['postal_code']);
        // Add Quebec postal code validation logic here
        
        wp_send_json_success(array('valid' => true));
    }

    public function add_admin_menu() {
        add_menu_page(
            'Gobac Moving Boxes', 
            'Gobac Boxes', 
            'manage_options', 
            'gobac-settings',
            array($this, 'render_settings_page'),
            'dashicons-archive',
            30
        );

        // Add submenus
        add_submenu_page(
            'gobac-settings',
            'Product Settings',
            'Products',
            'manage_options',
            'gobac-products',
            array($this, 'render_products_page')
        );

        add_submenu_page(
            'gobac-settings',
            'Delivery Zones',
            'Delivery Zones',
            'manage_options',
            'gobac-zones',
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

    public function register_settings() {
        // General Settings
        register_setting('gobac_options', 'gobac_delivery_zones');
        register_setting('gobac_options', 'gobac_min_rental_weeks');
        register_setting('gobac_options', 'gobac_km_extra_fee');

        // Product Mappings
        register_setting('gobac_options', 'gobac_box_products');
        register_setting('gobac_options', 'gobac_location_products');
        register_setting('gobac_options', 'gobac_accessory_products');
    }
}

// Initialize the plugin
GobacMovingBoxes::get_instance(); 