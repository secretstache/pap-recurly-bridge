<?php
/*
Plugin Name: PAP Recurly Bridge
Plugin URI: https://www.secretstache.com/
Description: This plugin integrated Post Affiliate Pro and Recurly.
Version: 0.1.0
Author: Secret Stache Media
Author URI: https://www.secretstache.com/
Text Domain: pap-recurly-bridge
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
define( 'PAP_RECURLY_VERSION', '0.1.0' );
define( 'PAP_RECURLY_URL', plugin_dir_url( __FILE__ ) );
define( 'PAP_RECURLY_DIR', plugin_dir_path( __FILE__ ) );

define( 'PAP_RECURLY_DIR_INC', trailingslashit ( PAP_RECURLY_DIR . 'inc' ) );
define( 'PAP_RECURLY_DIR_ADMIN', trailingslashit ( PAP_RECURLY_DIR . 'admin' ) );

// Grab other files
require_once ( PAP_RECURLY_DIR_INC . 'PapApi.class.php' );
