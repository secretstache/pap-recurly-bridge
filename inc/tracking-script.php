<?php
// No direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Check if the script needs to load
function prb_maybe_load_script() {

    // Get current post ID
    global $post;
    $post_id = $post->ID;

    $confirmation_page = prb_get_option('prb_setting_confirmation_page');

    // Check if current page selected
    if ( in_array($post_id, $confirmation_page) ) {

        // Check if account_code & plan provided
        if ( isset($_GET['account_code']) && !empty($_GET['account_code']) && isset($_GET['plan']) && !empty($_GET['plan']) ) {
            return true;
        }

    }
}

/**
 * Get tracking script
 *
 * http://addons.qualityunit.com/PostAffiliatePro/integration-methods/_irecurly/
**/
function prb_get_script() {

    // Get url stored in Post Affiliate Pro options
    $pap_url = get_option('pap-url');

    Recurly_Client::$apiKey = prb_get_option('prb_setting_recurly_api_key_private');
    Recurly_Client::$subdomain = prb_get_option('prb_setting_recurly_subdomain');

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
    var sale = PostAffTracker.createSale();
    sale.setTotalCost('<?php echo $total;?>');
    sale.setOrderID('<?php echo $_GET['account_code'];?>');
    sale.setProductID('<?php echo $_GET['plan'];?>');
    sale.setData1('<?php echo $orderID;?>');

    PostAffTracker.register();
    </script>
    <?php
}
