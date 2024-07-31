<?php
$user_id = get_current_user_id();
?>

<h3><?php _e('Add Warranty', 'woocommerce'); ?></h3>

<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
    <input type="hidden" name="action" value="submit_warranty_form">
    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

    <p class="formin">
        <label for="name"><?php _e('Name', 'woocommerce'); ?></label>
        <input type="text" name="name" id="name" required>
    </p>

    <p class="formin">
        <label for="email"><?php _e('Email', 'woocommerce'); ?></label>
        <input type="email" name="email" id="email" required>
    </p>

    <p class="formin">
        <label for="phone"><?php _e('Phone', 'woocommerce'); ?></label>
        <input type="text" name="phone" id="phone" required>
    </p>

    <p class="formin">
        <label for="product"><?php _e('Product', 'woocommerce'); ?></label>
        <select name="product" id="product" required>
            <option value=""><?php _e('Select a product', 'woocommerce'); ?></option>
            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_extended_warranty',
                        'compare' => 'EXISTS'
                    )
                )
            );
            $products = new WP_Query($args);
            if ($products->have_posts()) {
                while ($products->have_posts()) {
                    $products->the_post();
                    $product_id = get_the_ID();
                    $product_name = get_the_title();
                    echo '<option value="' . esc_attr($product_id) . '">' . esc_html($product_name) . '</option>';
                }
                wp_reset_postdata();
            } else {
                echo '<option value="">' . __('No products with extended warranty found', 'woocommerce') . '</option>';
            }
            ?>
        </select>
    </p>

    <p class="formin">
        <label for="order_id"><?php _e('Order ID', 'woocommerce'); ?></label>
        <input type="text" name="order_id" id="order_id" required>
    </p>

    <p class="formin">
        <label for="store_name"><?php _e('Store Name', 'woocommerce'); ?></label>
        <input type="text" name="store_name" id="store_name" required>
    </p>

    <p class="formin">
        <label for="purchase_date"><?php _e('Purchase Date', 'woocommerce'); ?></label>
        <input type="date" name="purchase_date" id="purchase_date" required>
    </p>

    <p>
        <button type="submit"><?php _e('Submit', 'woocommerce'); ?></button>
    </p>
</form>
