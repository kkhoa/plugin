<?php

class Test_Settings extends WP_UnitTestCase {
    public function test_settings_registration() {
        $settings = get_option('parent_plugin_options');
        $this->assertNotFalse($settings);
    }
}
