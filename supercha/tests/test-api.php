<?php

class Test_Api extends WP_UnitTestCase {
    public function test_api_route() {
        $request = new WP_REST_Request('GET', '/parent-plugin/v1/products');
        $response = rest_do_request($request);
        $this->assertEquals(200, $response->get_status());
    }
}
