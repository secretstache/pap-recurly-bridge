<?php 
/**
 * Validate Recurly Info
 *
 * @since  1.0.0
 * @access public
 *
 * @param string $private_key   Recurly private Key
 * @param string $sub_domain    Recurly Subdomain
 * @param bool $cache           Validates recurly info again if false
 *
 * @return bool
 */
function prb_validate_info( $private_key = null, $sub_domain = null, $cache = true ) {
    $recurly_key_info = get_transient( 'prb_recurly_key_info' );
    if ( ! $cache ) {
        $recurly_key_info = null;
        // Flush cache
        delete_transient( 'prb_recurly_key_info' );
    }
    // Check if already validated
    if ( $recurly_key_info ) {
        return true;
    }
    // Recurly_Client config
    Recurly_Client::$subdomain = $sub_domain;
    Recurly_Client::$apiKey = $private_key;
    // Instantiate recurly client
    $recurly_client = new Recurly_Client();
    // Try to get accounts
    try {
        $get = $recurly_client->request('GET', '/accounts');
        if ( $get->statusCode == '200' ) {
            $recurly_key_info = array( 'is_valid_key' => '1' );
            // Caching response
            set_transient( 'prb_recurly_key_info', $recurly_key_info, 86400 ); //caching for 24 hours
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        // Get rid of error for now
        unset($e);
        return false;
    }
}

/**
 * Verify if recurly configs correct
 *
 * @since  1.0.0
 * @access public
 *
 * @return bool
 */
function prb_is_valid_recurly_config() {

    // Get saved settings
    $private_key = prb_get_option('prb_setting_recurly_api_key_private');
    $sub_domain = prb_get_option('prb_setting_recurly_subdomain');

    // Make sure subdomain and API key are not empty
    if ( $private_key != '' && $sub_domain != '' ) {

        // Validate info
        $validate_info = prb_validate_info($private_key, $sub_domain);
        return $validate_info;
    } else {
        return false;
    }
}