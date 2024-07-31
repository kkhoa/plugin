<?php

class Parent_Plugin_Security {
    public static function add_security_measures() {
        add_filter('rest_pre_dispatch', array(__CLASS__, 'validate_api_key'), 10, 3);
    }

    public static function validate_api_key($result, $server, $request) {
        $route = $request->get_route();
        if (strpos($route, '/parent-plugin/v1') !== false) {
            $api_key = $request->get_param('api_key');
            $options = get_option('parent_plugin_options');
            if ($api_key !== $options['api_key']) {
                return new WP_Error('invalid_api_key', __('Invalid API Key', 'parent-plugin'), array('status' => 403));
            }
        }
        return $result;
    }
}
