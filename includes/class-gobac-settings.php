<?php
class Gobac_Settings {
    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
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