<?php
/**
 * PHPUnit bootstrap file
 */

$_tests_dir = getenv('WP_TESTS_DIR');

if (! $_tests_dir) {
    $_tests_dir = rtrim(sys_get_temp_dir(), '/\\') . '/wordpress-tests-lib';
}

if (! file_exists($_tests_dir . '/includes/functions.php')) {
    echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; 
    exit(1);
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

// Custom constants.
define('DIR_DATA', dirname(__FILE__) . '/data');
define('WP_HOME', 'http://example.org');

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin()
{
    /**
     * selectively use ACF in plugins directory or in current directory,
     * depending on GITHUB CI or regular PHPUNIT
     */
    $path = '';
    if (!is_dir(dirname(dirname(__FILE__)) . '/second-plugin-to-add'))
    {
        $path = '../';
    }
    require dirname(dirname(__FILE__)) . '/'.$path.'second-plugin-to-add/my_second_plugin.php';		// ACF
    require dirname(dirname(__FILE__)) . '/myplugin.php';										    // this plugin.
}

tests_add_filter('muplugins_loaded', '_manually_load_plugin');

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';