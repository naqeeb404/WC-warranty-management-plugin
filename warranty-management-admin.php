<?php

// Functionality to handle admin actions.

// Update warranty status.
add_action('admin_post_update_warranty_status', 'update_warranty_status');
function update_warranty_status()
{
    // Update warranty status in the database.
    wp_redirect(admin_url('admin.php?page=warranty-management'));
    exit;
}

// Delete warranty submission.
add_action('admin_post_delete_warranty', 'delete_warranty');
function delete_warranty()
{
    // Delete warranty from the database.
    wp_redirect(admin_url('admin.php?page=warranty-management'));
    exit;
}

