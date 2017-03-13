<?php
// Options page init
function prb_settings_init() {

    // Register Setting
    register_setting('prb', 'prb_options');

    // Section -> Recurly Settings
    add_settings_section(
        'prb_section_recurly',
        __('Recurly Settings', 'prb'),
        'prb_section_empty_cb',
        'prb'
    );

    // Section -> Post Affiliate Pro Settings
    add_settings_section(
        'prb_section_pap',
        __('Post Affiliate Pro Settings', 'prb'),
        'prb_section_empty_cb',
        'prb'
    );


    // Field -> Recurly Private API Key
    add_settings_field(
        'prb_setting_recurly_api_key_private',
        __('Recurly Private API Key', 'prb'),
        'prb_setting_field_text',
        'prb',
        'prb_section_recurly',
        [
            'label_for'         => 'prb_setting_recurly_api_key_private',
            'description'       => 'You can find this key in your Recurly dashboard: Developers > API Credentials',
            'is_valid_info'     => prb_is_valid_recurly_config()
        ]
    );

    // Field -> Recurly Subdomain
    add_settings_field(
        'prb_setting_recurly_subdomain',
        __('Recurly Subdomain', 'prb'),
        'prb_setting_field_text',
        'prb',
        'prb_section_recurly',
        [
            'label_for'         => 'prb_setting_recurly_subdomain',
            'description'       => 'For https://my-company.recurly.com your subdomain is my-company',
            'is_valid_info'     => prb_is_valid_recurly_config()
        ]
    );

    // Field -> Checkout confirmation page
    add_settings_field(
        'prb_setting_confirmation_page',
        __('Checkout Confirmation Page(s)', 'prb'),
        'prb_setting_field_select',
        'prb',
        'prb_section_pap',
        [
            'label_for'         => 'prb_setting_confirmation_page',
            'description'       => 'The page(s) where you will redirect users after a successful Recurly payment',
            'options'           => get_pages()
        ]
    );

    // Field -> Post Affiliate Pro URL
    add_settings_field(
        'prb_setting_pap_url',
        __('Post Affiliate Pro Account URL', 'prb'),
        'prb_setting_field_fake_papurl',
        'prb',
        'prb_section_pap',
        [
            'label_for'         => 'prb_setting_pap_url',
            'description'       => 'This field is configured from the Post Affiliate Pro options.',
            'general_url'       => admin_url(). '?page=pap-top-level-options-handle'
        ]
    );

    // Field -> Recurly Subdomain
    add_settings_field(
        'prb_setting_account_code_query_var',
        __('URL query variable: Account Code', 'prb'),
        'prb_setting_field_text',
        'prb',
        'prb_section_pap',
        [
            'label_for'         => 'prb_setting_account_code_query_var',
            'description'       => 'Query variable key for account code in Confirmation page redirect URL.',
            'default_value'     => 'account_code'
        ]
    );

        // Field -> Recurly Subdomain
    add_settings_field(
        'prb_setting_plan_code_query_var',
        __('URL query variable: Plan Code', 'prb'),
        'prb_setting_field_text',
        'prb',
        'prb_section_pap',
        [
            'label_for'         => 'prb_setting_plan_code_query_var',
            'description'       => 'Query variable key for plan code in Confirmation page redirect URL.',
            'default_value'     => 'plan'
        ]
    );
}
add_action('admin_init', 'prb_settings_init');

// Empty section callback
function prb_section_empty_cb($args) {

}

// Field: Text
function prb_setting_field_text($args) {

    $options = get_option('prb_options');
    $value   = ( $options[$args['label_for']] ) ? $options[$args['label_for']] : $args['default_value'];
    ?>
    <input type="text" class="regular-text" name="prb_options[<?php echo esc_attr($args['label_for']); ?>]" value="<?php echo esc_attr($value);?>">
    <?php if (isset($args['is_valid_info'])) { 
        echo ($args['is_valid_info']) ? '<span class="dashicons dashicons-yes"></span>' : '<span class="dashicons dashicons-no"></span>';
    } ?>
    <?php if ( isset($args['description']) ) : ?>
    <p class="description">
        <?php echo esc_html($args['description'], 'prb'); ?>
    </p>
    <?php endif;?>

    <?php
}

// Field: Select
function prb_setting_field_select($args) {
    $options = get_option('prb_options');
    $pages = $args['options'];
    ?>

    <select id="<?php echo esc_attr($args['label_for']); ?>" name="prb_options[<?php echo esc_attr($args['label_for']); ?>][]" multiple>
        <?php foreach ( $pages as $page ) { ?>
            <option
                value="<?php echo $page->ID;?>"
                <?php echo ( is_array($options[$args['label_for']]) && in_array( $page->ID, $options[$args['label_for']]) ) ? 'selected' : '';?>
            >
                <?php echo esc_html($page->post_title, 'prb'); ?>
            </option>
            <?php
        }?>
    </select>
    <?php if ( $args['description'] ) : ?>
    <p class="description">
        <?php echo esc_html($args['description'], 'prb'); ?>
    </p>
    <?php endif;?>
    <?php
}

function prb_setting_field_fake_papurl($args) {

    $pap_url = get_option('pap-url');
    ?>
    <input type="text" class="regular-text" value="<?php echo esc_attr($pap_url);?>" disabled>
    <?php if ( isset($args['description']) ) : ?>
    <p class="description">
        <?php echo esc_html($args['description'], 'prb'); ?> <a href="<?php echo esc_url($args['general_url']);?>" target="_blank">Click here to update</a>
    </p>
    <?php endif;?>
    <?php
}

/**
 * top level menu:
 * callback functions
 */
function prb_options_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('prb_messages', 'prb_message', __('Settings Saved', 'prb'), 'updated');

        // Get saved settings
        $private_key = prb_get_option('prb_setting_recurly_api_key_private');
        $sub_domain = prb_get_option('prb_setting_recurly_subdomain');

        // Revalidate settings
        prb_validate_info($private_key, $sub_domain, false);
    }

    settings_errors( 'prb_messages' );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "prb"
            settings_fields('prb');
            // output setting sections and their fields
            // (sections are registered for "prb", each field is registered to a specific section)
            do_settings_sections('prb');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
    <?php
}
