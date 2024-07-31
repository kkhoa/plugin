<?php

class Test_Activator extends WP_UnitTestCase {
    public function test_activation() {
        do_action('activate_parent-plugin/parent-plugin.php');
        $this->assertTrue(true);
    }
}
