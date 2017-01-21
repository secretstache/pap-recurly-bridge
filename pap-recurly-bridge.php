<?php
/*
Plugin Name: PAP Recurly Bridge
Plugin URI: https://www.secretstache.com/
Description: This plugin integrates Post Affiliate Pro and Recurly.
Version: 0.1.1
Author: Secret Stache Media
Author URI: https://www.secretstache.com/
Text Domain: prb
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Define Constants
 *
 * @since   PAP Recurly Bridge  0.1.0
 */
define( 'PRB_RECURLY_VERSION', '0.1.1' );
define( 'PRB_RECURLY_URL', plugin_dir_url( __FILE__ ) );
define( 'PRB_RECURLY_DIR', plugin_dir_path( __FILE__ ) );
define( 'PRB_RECURLY_BASENAME', plugin_basename( __FILE__ ) );

define( 'PRB_RECURLY_DIR_INC', trailingslashit ( PRB_RECURLY_DIR . 'inc' ) );
define( 'PRB_RECURLY_DIR_LIB', trailingslashit ( PRB_RECURLY_DIR . 'lib' ) );
define( 'PRB_RECURLY_DIR_OPTIONS', trailingslashit ( PRB_RECURLY_DIR . 'options' ) );

// Grab other files

require_once( PRB_RECURLY_DIR_INC . 'dependency-check.php' );
require_once( PRB_RECURLY_DIR_INC . 'tracking-script.php' );
require_once( PRB_RECURLY_DIR_OPTIONS . 'init.php' );

// External libraries
if ( ! class_exists('Recurly_Base') )
    require_once( PRB_RECURLY_DIR_LIB . 'recurly.php');
require_once( PRB_RECURLY_DIR_LIB . 'PapApi.class.php' );
require PRB_RECURLY_DIR_LIB . 'plugin_update_check.php';
$MyUpdateChecker = new PluginUpdateChecker_2_0 (
    'https://kernl.us/api/v1/updates/58811a47198a095fb1f3936f/',
    __FILE__,
    'pap-recurly-bridge',
    1
);
// Get prb option value
function prb_get_option( $option_name ) {
    return get_option('prb_options')[$option_name];
}

// Add plugin options submenu page
function prb_options_page() {
    add_submenu_page(
        'pap-top-level-options-handle',
        'Recurly Bridge to Post Affiliate Pro',
        'Recurly Integration',
        'manage_options',
        'pap-recurly-bridge',
        'prb_options_page_html'
    );
}
add_action('admin_menu', 'prb_options_page', 99);
