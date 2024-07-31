<?php
global $wpdb;
$user_id = get_current_user_id();
$table_name = $wpdb->prefix . 'warranties';
$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE user_id = %d AND status IN ('pending', 'approved')", $user_id));
?>

<h3><?php _e('Your Warranties', 'woocommerce'); ?></h3>

<table>
    <thead>
        <tr>
            <th><?php _e('Warranty ID', 'woocommerce'); ?></th>
            <th><?php _e('Start Date', 'woocommerce'); ?></th>
            <th><?php _e('End Date', 'woocommerce'); ?></th>
            <th><?php _e('Order ID', 'woocommerce'); ?></th>
            <th><?php _e('Product Name', 'woocommerce'); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $result) : ?>
            <tr>
                <td><?php echo esc_html($result->warranty_id); ?></td>
                <td><?php echo esc_html($result->start_date); ?></td>
                <td><?php echo esc_html($result->end_date); ?></td>
                <td><?php echo esc_html($result->order_id); ?></td>
                <td><?php
                    $product = wc_get_product($result->product_id);
                    echo esc_html($product->get_name());
                ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
