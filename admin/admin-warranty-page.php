<?php
global $wpdb;
$table_name = $wpdb->prefix . 'warranties';
$results = $wpdb->get_results("SELECT * FROM $table_name");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['status'])) {
        $status = sanitize_text_field($_POST['status']);
        $warranty_id = intval($_POST['warranty_id']);
        $wpdb->update($table_name, array('status' => $status), array('id' => $warranty_id));
    } elseif (isset($_POST['delete'])) {
        $warranty_id = intval($_POST['warranty_id']);
        $wpdb->delete($table_name, array('id' => $warranty_id));
    }
}
?>

<h1><?php _e('Warranty Management', 'woocommerce'); ?></h1>

<form method="get" action="">
    <input type="hidden" name="page" value="warranty-management">
    <p>
        <label for="search"><?php _e('Search by Warranty ID, Order ID, or Order Date', 'woocommerce'); ?></label>
        <input type="text" name="search" id="search" value="<?php echo isset($_GET['search']) ? esc_attr($_GET['search']) : ''; ?>">
        <button type="submit"><?php _e('Search', 'woocommerce'); ?></button>
    </p>
</form>

<table class="widefat fixed">
    <thead>
        <tr>
            <th><?php _e('Warranty ID', 'woocommerce'); ?></th>
            <th><?php _e('User ID', 'woocommerce'); ?></th>
            <th><?php _e('Order ID', 'woocommerce'); ?></th>
            <th><?php _e('Product ID', 'woocommerce'); ?></th>
            <th><?php _e('Start Date', 'woocommerce'); ?></th>
            <th><?php _e('End Date', 'woocommerce'); ?></th>
            <th><?php _e('Status', 'woocommerce'); ?></th>
            <th><?php _e('Actions', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result) : ?>
            <tr>
                <td><?php echo esc_html($result->warranty_id); ?></td>
                <td><?php echo esc_html($result->user_id); ?></td>
                <td><?php echo esc_html($result->order_id); ?></td>
                <td><?php echo esc_html($result->product_id); ?></td>
                <td><?php echo esc_html($result->start_date); ?></td>
                <td><?php echo esc_html($result->end_date); ?></td>
                <td><?php echo esc_html($result->status); ?></td>
                <td>
                    <form method="post" action="">
                        <input type="hidden" name="warranty_id" value="<?php echo esc_attr($result->id); ?>">
                        <select name="status">
                            <option value="pending" <?php selected($result->status, 'pending'); ?>><?php _e('Pending', 'woocommerce'); ?></option>
                            <option value="approved" <?php selected($result->status, 'approved'); ?>><?php _e('Approved', 'woocommerce'); ?></option>
                            <option value="rejected" <?php selected($result->status, 'rejected'); ?>><?php _e('Rejected', 'woocommerce'); ?></option>
                        </select>
                        <button type="submit"><?php _e('Update', 'woocommerce'); ?></button>
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="warranty_id" value="<?php echo esc_attr($result->id); ?>">
                        <button type="submit" name="delete" value="1"><?php _e('Delete', 'woocommerce'); ?></button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
