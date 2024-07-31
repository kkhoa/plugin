<?php

class Parent_Plugin_Api {
    public function register_api_routes() {
        register_rest_route('parent-plugin/v1', '/products', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_products'),
            'permission_callback' => array($this, 'permission_check')
        ));
    }

    public function get_products($request) {
        $products = $this->fetch_products();
        return new WP_REST_Response($products, 200);
    }

    private function fetch_products() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1
        );
        $products = get_posts($args);
        $product_data = array();

        foreach ($products as $product) {
            $product_data[] = array(
                'id' => $product->ID,
                'name' => $product->post_title,
                'price' => get_post_meta($product->ID, '_price', true)
            );
        }

        return $product_data;
    }

    public function permission_check() {
        return true;
    }
}
