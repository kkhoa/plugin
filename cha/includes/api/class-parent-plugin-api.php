<?php

class Parent_Plugin_API {
    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('parent-plugin/v1', '/products', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_products'),
            'permission_callback' => array('Parent_Plugin_Security', 'check_oauth_permission')
        ));
    }

    public function get_products() {
        $args = array('post_type' => 'product', 'posts_per_page' => -1);
        $products = get_posts($args);
        $products_data = array();

        foreach ($products as $product) {
            $products_data[] = array(
                'id' => $product->ID,
                'name' => $product->post_title,
                'price' => get_post_meta($product->ID, '_price', true)
            );
        }

        return $products_data;
    }
}
