<?php
/*
Plugin Name: WooCommerce Warranty Management
Description: Adds warranty management functionality to WooCommerce.
Version: 1.0
Author: Naqeeb
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Include admin functionalities.
include_once plugin_dir_path(__FILE__) . 'warranty-management-admin.php';

// Add 'Add Warranty' tab in WooCommerce My Account.
add_filter('woocommerce_account_menu_items', 'add_warranty_tab', 40);
function add_warranty_tab($items) {
    $items['add-warranty'] = __('Add Warranty', 'woocommerce');
    return $items;
}

add_action('init', 'add_warranty_endpoint');
function add_warranty_endpoint() {
    add_rewrite_endpoint('add-warranty', EP_ROOT | EP_PAGES);
}

add_action('woocommerce_account_add-warranty_endpoint', 'warranty_content');
function warranty_content() {
    wc_get_template('warranty-form.php', array(), '', plugin_dir_path(__FILE__) . 'template/');
}

// Add 'Warranty' tab to show user submissions.
add_filter('woocommerce_account_menu_items', 'add_user_warranty_tab', 50);
function add_user_warranty_tab($items) {
    $items['user-warranty'] = __('Warranty', 'woocommerce');
    return $items;
}

add_action('init', 'add_user_warranty_endpoint');
function add_user_warranty_endpoint() {
    add_rewrite_endpoint('user-warranty', EP_ROOT | EP_PAGES);
}

add_action('woocommerce_account_user-warranty_endpoint', 'user_warranty_content');
function user_warranty_content() {
    wc_get_template('user-warranty.php', array(), '', plugin_dir_path(__FILE__) . 'template/');
}

// Handle form submission.
add_action('admin_post_nopriv_submit_warranty_form', 'handle_warranty_form');
add_action('admin_post_submit_warranty_form', 'handle_warranty_form');

function handle_warranty_form() {
    if (!is_user_logged_in()) {
        wp_redirect(home_url());
        exit;
    }

    $user_id = get_current_user_id();
    $name = sanitize_text_field($_POST['name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $product_id = intval($_POST['product']);
    $order_id = sanitize_text_field($_POST['order_id']);
    $store_name = sanitize_text_field($_POST['store_name']);
    $purchase_date = sanitize_text_field($_POST['purchase_date']);
    $start_date = date('Y-m-d', strtotime($purchase_date));
    
    // Get the product warranty and extended warranty
    $product_warranty = 365; // Assuming the base product warranty is 365 days
    $extended_warranty = get_field('_extended_warranty', $product_id); // Using ACF to get the extended warranty days
    
    // Calculate the end date
    $end_date = date('Y-m-d', strtotime($start_date . " + $product_warranty days + $extended_warranty days"));
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'warranties';
    
    // Generate a unique 5-digit warranty ID
    $warranty_id = sprintf("%05d", mt_rand(1, 99999));
    
    $wpdb->insert($table_name, array(
        'user_id' => $user_id,
        'warranty_id' => $warranty_id,
        'order_id' => $order_id,
        'product_id' => $product_id,
        'start_date' => $start_date,
        'end_date' => $end_date,
        'status' => 'pending'
    ));
    
    wp_redirect(wc_get_account_endpoint_url('user-warranty'));
    exit;
}

// Create custom database table on plugin activation.
register_activation_hook(__FILE__, 'create_warranty_table');
function create_warranty_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'warranties';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        warranty_id varchar(5) NOT NULL,
        order_id varchar(20) NOT NULL,
        product_id bigint(20) NOT NULL,
        start_date date NOT NULL,
        end_date date NOT NULL,
        status varchar(20) NOT NULL DEFAULT 'pending',
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Register custom endpoints for admin.
function add_admin_warranty_menu() {
    add_menu_page('Warranty Management', 'Warranty', 'manage_options', 'warranty-management', 'admin_warranty_page', 'dashicons-shield', 56);
}
add_action('admin_menu', 'add_admin_warranty_menu');

function admin_warranty_page() {
    include plugin_dir_path(__FILE__) . 'admin/admin-warranty-page.php';
}
