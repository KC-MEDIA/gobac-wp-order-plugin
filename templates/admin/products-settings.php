<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <form method="post" action="options.php">
        <?php settings_fields('gobac_options'); ?>
        
        <h2>Box Products</h2>
        <table class="widefat">
            <thead>
                <tr>
                    <th>WooCommerce Product</th>
                    <th>Box Type</th>
                    <th>Weekly Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $box_products = get_option('gobac_box_products', array());
                // Get all WooCommerce products
                $products = wc_get_products(array(
                    'limit' => -1,
                    'status' => 'publish',
                ));
                
                for ($i = 0; $i < 4; $i++): // For your 4 box types
                ?>
                <tr>
                    <td>
                        <select name="gobac_box_products[<?php echo $i; ?>][product_id]">
                            <option value="">Select Product</option>
                            <?php foreach ($products as $product): ?>
                            <option value="<?php echo $product->get_id(); ?>"
                                <?php selected(isset($box_products[$i]['product_id']) ? $box_products[$i]['product_id'] : '', $product->get_id()); ?>>
                                <?php echo $product->get_name(); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_box_products[<?php echo $i; ?>][type]"
                               value="<?php echo isset($box_products[$i]['type']) ? esc_attr($box_products[$i]['type']) : ''; ?>"
                               placeholder="Box Type Name">
                    </td>
                    <td>
                        <input type="number" 
                               name="gobac_box_products[<?php echo $i; ?>][weekly_price]"
                               value="<?php echo isset($box_products[$i]['weekly_price']) ? esc_attr($box_products[$i]['weekly_price']) : ''; ?>"
                               step="0.01">
                    </td>
                </tr>
                <?php endfor; ?>
            </tbody>
        </table>

        <!-- Similar tables for Location Products and Accessories -->
        
        <?php submit_button(); ?>
    </form>
</div> 