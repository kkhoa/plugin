<?php

class Parent_Plugin_Security {
    public static function check_oauth_permission() {
        // Check OAuth2 token
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            return new WP_Error('rest_forbidden', __('You do not have permission to access this resource.', 'parent-plugin'), array('status' => 403));
        }

        $token = str_replace('Bearer ', '', $headers['Authorization']);
        if (!self::validate_token($token)) {
            return new WP_Error('rest_forbidden', __('Invalid token.', 'parent-plugin'), array('status' => 403));
        }

        return true;
    }

    private static function validate_token($token) {
        // Validate token logic
        return true;
    }
}
