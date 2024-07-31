<?php

class Parent_Plugin_Rate_Limiter {
    private $limit = 100;
    private $period = 3600; // 1 hour

    public function __construct() {
        add_action('rest_api_init', array($this, 'rate_limit'));
    }

    public function rate_limit() {
        add_filter('rest_pre_dispatch', array($this, 'check_rate_limit'), 10, 3);
    }

    public function check_rate_limit($result, $server, $request) {
        $route = $request->get_route();
        if (strpos($route, '/parent-plugin/v1') !== false) {
            $ip_address = $this->get_ip_address();
            $transient_key = 'rate_limit_' . $ip_address;

            $request_count = get_transient($transient_key);

            if ($request_count === false) {
                set_transient($transient_key, 1, $this->period);
            } elseif ($request_count >= $this->limit) {
                return new WP_Error('rate_limit_exceeded', __('Rate limit exceeded', 'parent-plugin'), array('status' => 429));
            } else {
                set_transient($transient_key, $request_count + 1, $this->period);
            }
        }
        return $result;
    }

    private function get_ip_address() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
