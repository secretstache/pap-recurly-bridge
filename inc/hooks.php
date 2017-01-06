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

    // Get current post ID
    global $post;
    $post_id = $post->ID;

    // Our plugin's options
    $public_key = prb_get_option('prb_setting_recurly_api_key_public');
    $confirmation_page = prb_get_option('prb_setting_confirmation_page');
    $private_key = prb_get_option('prb_setting_recurly_api_key_private');
    $recurly_subdomain = prb_get_option('prb_setting_recurly_subdomain');
    echo $recurly_subdomain;
    // Get url stored in Post Affiliate Pro options
    $pap_url = get_option('pap-url');

    // If current page is the confiration page
    if ( $post_id == $confirmation_page ) {
        if ( !empty($_GET['account_code']) ) {

            Recurly_Client::$apiKey = $private_key; // set your API key here
            Recurly_Client::$subdomain = $recurly_subdomain;

            $invoices = Recurly_InvoiceList::getForAccount($_GET['account_code']);

            foreach ($invoices as $invoice) {
              $inv = explode(", ","{$invoice}");
              foreach ($inv as $key=>$value){
                $res = explode("=",$value);
                if ($res[0] == "invoice_number")
                  $orderID=$res[1];
                if ($res[0] == "subtotal_in_cents")
                  $total=$res[1]/100;
              }
              break;
            }
            ?>

            <script id="pap_x2s6df8d" src="<?php echo esc_url($pap_url);?>/scripts/salejs.php" type="text/javascript">
            </script>
            <script type="text/javascript">
            alert('test');
            var sale = PostAffTracker.createSale();
            sale.setTotalCost('<?php echo $total;?>');
            sale.setOrderID('<?php echo $_GET['account_code'];?>');
            sale.setProductID('<?php echo $_GET['plan'];?>');
            sale.setData1('<?php echo $orderID;?>');

            PostAffTracker.register();
            </script>
            <?php
        }
    }
}
add_action('wp_head', 'prb_hook_tracking_code');
