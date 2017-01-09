<?php
/*
Plugin Name: PAP Recurly Bridge
Plugin URI: https://www.secretstache.com/
Description: This plugin integrated Post Affiliate Pro and Recurly.
Version: 0.1.0
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
define( 'PRB_RECURLY_VERSION', '0.1.0' );
define( 'PRB_RECURLY_URL', plugin_dir_url( __FILE__ ) );
define( 'PRB_RECURLY_DIR', plugin_dir_path( __FILE__ ) );

define( 'PRB_RECURLY_DIR_INC', trailingslashit ( PRB_RECURLY_DIR . 'inc' ) );
define( 'PRB_RECURLY_DIR_LIB', trailingslashit ( PRB_RECURLY_DIR . 'lib' ) );
define( 'PRB_RECURLY_DIR_OPTIONS', trailingslashit ( PRB_RECURLY_DIR . 'options' ) );

// Grab other files
require_once( PRB_RECURLY_DIR_INC . 'hooks.php' );
require_once( PRB_RECURLY_DIR_OPTIONS . 'init.php' );

// External libraries
require_once( PRB_RECURLY_DIR_LIB . 'recurly.php');
require_once( PRB_RECURLY_DIR_LIB . 'PapApi.class.php' );

// Get prb option value
function prb_get_option( $option_name ) {
    return get_option('prb_options')[$option_name];
}

// Add plugin options submenu page
function prb_options_page() {
    add_submenu_page(
        'options-general.php',
        'PAP Recurly Options',
        'PAP Recurly Options',
        'manage_options',
        'pap-recurly-bridge',
        'prb_options_page_html'
    );
}
add_action('admin_menu', 'prb_options_page');

// Check if PAP active
function prb_check_if_pap_active() {
  if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'postaffiliatepro/postaffiliatepro.php' ) ) {
    add_action( 'admin_notices', 'prb_check_if_pap_active_notice' );

    deactivate_plugins( plugin_basename( __FILE__ ) );

    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }
  }
}
add_action( 'admin_init', 'prb_check_if_pap_active' );

// Dependency missing notice
function prb_check_if_pap_active_notice(){
  ?><div class="error"><p>PAP Recurly Bridge has been deactivated as requires the plugin 'postaffiliatepro' to be installed and activated.</p></div><?php
}
