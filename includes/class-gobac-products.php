<?php
class Gobac_Products {
    public static function get_box_products() {
        // Ensure WooCommerce is active
        if (!function_exists('wc_get_products')) {
            return array();
        }

        $box_products = get_option('gobac_box_products', array());
        $products = array();
        
        foreach ($box_products as $box) {
            if (empty($box['product_id'])) continue;
            
            // Using WooCommerce functions
            $product = wc_get_product($box['product_id']);
            if (!$product) continue;
            
            $products[] = array(
                'id' => $box['product_id'],
                'name' => $product->get_name(),
                'type' => $box['type'],
                'weekly_price' => $box['weekly_price'],
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                'description' => $product->get_description()
            );
        }
        
        return $products;
    }

    public static function get_accessory_products() {
        $accessory_products = get_option('gobac_accessory_products', array());
        $products = array();
        
        foreach ($accessory_products as $accessory) {
            if (empty($accessory['product_id'])) continue;
            
            $product = wc_get_product($accessory['product_id']);
            if (!$product) continue;
            
            $products[] = array(
                'id' => $accessory['product_id'],
                'name' => $product->get_name(),
                'price' => $product->get_price(),
                'has_quantity' => $accessory['has_quantity'] ?? false,
                'options' => $accessory['options'] ?? array(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
                'description' => $product->get_description()
            );
        }
        
        return $products;
    }
} 