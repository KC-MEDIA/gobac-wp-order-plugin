<?php
class Gobac_Settings {
    public function __construct() {
        add_action('admin_init', array($this, 'register_settings'));
        // Add initialization of default values if none exist
        add_action('admin_init', array($this, 'maybe_initialize_settings'));
    }
    
    public function register_settings() {
        // Box Products (weekly rental)
        register_setting('gobac_options', 'gobac_box_products', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_products')
        ));
        
        // Packing Supplies (one-time purchase)
        register_setting('gobac_options', 'gobac_packing_supplies', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_products')
        ));
        
        // Accessories (one-time purchase)
        register_setting('gobac_options', 'gobac_accessories', array(
            'type' => 'array',
            'sanitize_callback' => array($this, 'sanitize_products')
        ));
    }

    public function maybe_initialize_settings() {
        // Initialize settings if they don't exist
        if (false === get_option('gobac_box_products')) {
            update_option('gobac_box_products', array());
        }
        if (false === get_option('gobac_packing_supplies')) {
            update_option('gobac_packing_supplies', array());
        }
        if (false === get_option('gobac_accessories')) {
            update_option('gobac_accessories', array());
        }
    }

    public function sanitize_products($input) {
        if (!is_array($input)) {
            return array();
        }

        $sanitized = array();
        foreach ($input as $item) {
            if (!empty($item['product_id'])) {
                $sanitized[] = array(
                    'product_id' => absint($item['product_id']),
                    'type' => sanitize_text_field($item['type']),
                    'price' => isset($item['price']) ? (float) $item['price'] : 0,
                    'weekly_price' => isset($item['weekly_price']) ? (float) $item['weekly_price'] : 0,
                    'dimensions' => isset($item['dimensions']) ? sanitize_text_field($item['dimensions']) : '',
                    'unit' => isset($item['unit']) ? sanitize_text_field($item['unit']) : '',
                    'description' => isset($item['description']) ? sanitize_text_field($item['description']) : ''
                );
            }
        }
        return array_values($sanitized); // Reindex array
    }
} 