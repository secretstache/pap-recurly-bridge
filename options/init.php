<?php
// Options page init
function prb_settings_init() {

    // Register Setting
    register_setting('prb', 'prb_options');

    // Recurly Options Section
    add_settings_section(
        'prb_section_recurly',
        __('Recurly Options', 'prb'),
        'prb_section_recurly_cb',
        'prb'
    );

        // Add Setting
        add_settings_field(
            'prb_setting_recurly_api_key_private',
            __('Recurly Private API Key', 'prb'),
            'prb_setting_recurly_api_key_private_cb',
            'prb',
            'prb_section_recurly',
            [
                'label_for'         => 'prb_setting_recurly_api_key_private',
                'class'             => 'prb_row',
                'prb_custom_data'   => 'custom',
            ]
        );

        // Add Setting
        add_settings_field(
            'prb_setting_recurly_api_key_public',
            __('Recurly Public API Key', 'prb'),
            'prb_setting_recurly_api_key_public_cb',
            'prb',
            'prb_section_recurly',
            [
                'label_for'         => 'prb_setting_recurly_api_key_public',
                'class'             => 'prb_row',
                'prb_custom_data'   => 'custom',
            ]
        );

    // Recurly Options Section
    add_settings_section(
        'prb_section_pap',
        __('Post Affiliate Pro Options.', 'prb'),
        'prb_section_pap_cb',
        'prb'
    );

        // Add Setting
        add_settings_field(
            'prb_setting_pap_pages',
            __('Recurly Public API Key', 'prb'),
            'prb_setting_pap_pages_cb',
            'prb',
            'prb_section_pap',
            [
                'label_for'         => 'prb_setting_pap_pages',
                'class'             => 'prb_row',
                'prb_custom_data'   => 'custom',
            ]
        );
}
add_action('admin_init', 'prb_settings_init');

// Recurly Options Section Output
function prb_section_recurly_cb($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html__('', 'prb'); ?></p>
    <?php
}

// Recurly Private API Key Output
function prb_setting_recurly_api_key_private_cb($args) {

    $options = get_option('prb_options');
    ?>
    <input type="text" name="prb_options[<?php echo esc_attr($args['label_for']); ?>]">

    <?php
}

// Recurly Public API Key Output
function prb_setting_recurly_api_key_public_cb($args) {

    $options = get_option('prb_options');
    ?>
    <input type="text" name="prb_options[<?php echo esc_attr($args['label_for']); ?>]">

    <?php
}

// Recurly Options Section Output
function prb_section_pap_cb($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php echo esc_html__('', 'prb'); ?></p>
    <?php
}

// Recurly Public API Key Output
function prb_setting_pap_pages_cb($args) {

    $options = get_option('prb_options');
    $pages_args = array(
        'sort_order' => 'asc',
        'sort_column' => 'post_title',
        'hierarchical' => 1,
        'exclude' => '',
        'include' => '',
        'meta_key' => '',
        'meta_value' => '',
        'authors' => '',
        'child_of' => 0,
        'parent' => -1,
        'exclude_tree' => '',
        'number' => '',
        'offset' => 0,
        'post_type' => 'page',
        'post_status' => 'publish'
    );
    $pages = get_pages();
    ?>

    <select multiple size="6">
        <?php foreach ( $pages as $page ) {
            echo "<option value=$page->ID>$page->post_title</option>";
        }?>

    </select>
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
    }

    // show error/update messages
    settings_errors('prb_messages');
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
