<?php
/**
 * Hook tracking code
 *
 * Hooks tracking script to header(for
 * reliable tracking).
 *
 * @since   PAP Recurly Bridge  0.1.0
**/
function prb_hook_tracking_code() {
    if ( prb_maybe_load_script() ) {
        prb_get_script();
    }
}
add_action('wp_head', 'prb_hook_tracking_code');
