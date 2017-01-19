<?php
// No direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if PAP active
function prb_check_if_pap_active() {
  if ( is_admin() && current_user_can( 'activate_plugins' ) &&  !is_plugin_active( 'postaffiliatepro/postaffiliatepro.php' ) ) {
    add_action( 'admin_notices', 'prb_check_if_pap_active_notice' );

    deactivate_plugins( PRB_RECURLY_BASENAME );

    if ( isset( $_GET['activate'] ) ) {
      unset( $_GET['activate'] );
    }
  }
}
add_action( 'admin_init', 'prb_check_if_pap_active' );

// Dependency missing notice
function prb_check_if_pap_active_notice(){ ?>
    <div class="error"><p>PAP Recurly Bridge has been deactivated as requires the plugin 'postaffiliatepro' to be installed and activated.</p></div>
    <?php
}
