<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <?php
    // Get all WooCommerce products once
    $all_products = wc_get_products(array(
        'limit' => -1,
        'status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC'
    ));
    ?>

    <form method="post" action="options.php">
        <?php settings_fields('gobac_options'); ?>
        
        <!-- Boxes Section -->
        <h2>Moving Boxes</h2>
        <table class="widefat" id="boxes-table">
            <thead>
                <tr>
                    <th>WooCommerce Product</th>
                    <th>Box Type</th>
                    <th>Weekly Price</th>
                    <th>Dimensions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $box_products = get_option('gobac_box_products', array());
                if (empty($box_products)) {
                    $box_products = array(array());
                }
                foreach ($box_products as $index => $box):
                ?>
                <tr class="product-row">
                    <td>
                        <select name="gobac_box_products[<?php echo $index; ?>][product_id]">
                            <option value="">Select Box</option>
                            <?php 
                            if ($all_products) {
                                foreach ($all_products as $product): 
                            ?>
                                <option value="<?php echo $product->get_id(); ?>"
                                    <?php selected(isset($box['product_id']) ? $box['product_id'] : '', $product->get_id()); ?>>
                                    <?php echo $product->get_name() . ' (#' . $product->get_id() . ')'; ?>
                                </option>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_box_products[<?php echo $index; ?>][type]"
                               value="<?php echo isset($box['type']) ? esc_attr($box['type']) : ''; ?>"
                               placeholder="e.g., Small, Medium, Large">
                    </td>
                    <td>
                        <input type="number" 
                               name="gobac_box_products[<?php echo $index; ?>][weekly_price]"
                               value="<?php echo isset($box['weekly_price']) ? esc_attr($box['weekly_price']) : ''; ?>"
                               step="0.01">
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_box_products[<?php echo $index; ?>][dimensions]"
                               value="<?php echo isset($box['dimensions']) ? esc_attr($box['dimensions']) : ''; ?>"
                               placeholder="e.g., 12x12x12">
                    </td>
                    <td>
                        <button type="button" class="button remove-row">Remove</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="button add-row" data-table="boxes-table" data-type="box">Add Box Product</button>

        <!-- Packing Supplies Section -->
        <h2 style="margin-top: 30px;">Packing Supplies</h2>
        <table class="widefat" id="supplies-table">
            <thead>
                <tr>
                    <th>WooCommerce Product</th>
                    <th>Supply Type</th>
                    <th>Weekly Price</th>
                    <th>Unit</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $packing_supplies = get_option('gobac_packing_supplies', array());
                if (empty($packing_supplies)) {
                    $packing_supplies = array(array());
                }
                foreach ($packing_supplies as $index => $supply):
                ?>
                <tr class="product-row">
                    <td>
                        <select name="gobac_packing_supplies[<?php echo $index; ?>][product_id]">
                            <option value="">Select Supply</option>
                            <?php 
                            if ($all_products) {
                                foreach ($all_products as $product): 
                            ?>
                                <option value="<?php echo $product->get_id(); ?>"
                                    <?php selected(isset($supply['product_id']) ? $supply['product_id'] : '', $product->get_id()); ?>>
                                    <?php echo $product->get_name() . ' (#' . $product->get_id() . ')'; ?>
                                </option>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_packing_supplies[<?php echo $index; ?>][type]"
                               value="<?php echo isset($supply['type']) ? esc_attr($supply['type']) : ''; ?>"
                               placeholder="e.g., Tape, Bubble Wrap">
                    </td>
                    <td>
                        <input type="number" 
                               name="gobac_packing_supplies[<?php echo $index; ?>][weekly_price]"
                               value="<?php echo isset($supply['weekly_price']) ? esc_attr($supply['weekly_price']) : ''; ?>"
                               step="0.01">
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_packing_supplies[<?php echo $index; ?>][unit]"
                               value="<?php echo isset($supply['unit']) ? esc_attr($supply['unit']) : ''; ?>"
                               placeholder="e.g., per roll, per foot">
                    </td>
                    <td>
                        <button type="button" class="button remove-row">Remove</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="button add-row" data-table="supplies-table" data-type="supply">Add Supply Product</button>

        <!-- Accessories Section -->
        <h2 style="margin-top: 30px;">Accessories</h2>
        <table class="widefat" id="accessories-table">
            <thead>
                <tr>
                    <th>WooCommerce Product</th>
                    <th>Accessory Type</th>
                    <th>One-Time Price</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $accessories = get_option('gobac_accessories', array());
                if (empty($accessories)) {
                    $accessories = array(array());
                }
                foreach ($accessories as $index => $accessory):
                ?>
                <tr class="product-row">
                    <td>
                        <select name="gobac_accessories[<?php echo $index; ?>][product_id]">
                            <option value="">Select Accessory</option>
                            <?php 
                            if ($all_products) {
                                foreach ($all_products as $product): 
                            ?>
                                <option value="<?php echo $product->get_id(); ?>"
                                    <?php selected(isset($accessory['product_id']) ? $accessory['product_id'] : '', $product->get_id()); ?>>
                                    <?php echo $product->get_name() . ' (#' . $product->get_id() . ')'; ?>
                                </option>
                            <?php 
                                endforeach;
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_accessories[<?php echo $index; ?>][type]"
                               value="<?php echo isset($accessory['type']) ? esc_attr($accessory['type']) : ''; ?>"
                               placeholder="e.g., Dolly, Hand Truck">
                    </td>
                    <td>
                        <input type="number" 
                               name="gobac_accessories[<?php echo $index; ?>][price]"
                               value="<?php echo isset($accessory['price']) ? esc_attr($accessory['price']) : ''; ?>"
                               step="0.01"
                               placeholder="One-time purchase price">
                    </td>
                    <td>
                        <input type="text" 
                               name="gobac_accessories[<?php echo $index; ?>][description]"
                               value="<?php echo isset($accessory['description']) ? esc_attr($accessory['description']) : ''; ?>"
                               placeholder="Brief description">
                    </td>
                    <td>
                        <button type="button" class="button remove-row">Remove</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="button add-row" data-table="accessories-table" data-type="accessory">Add Accessory Product</button>

        <?php submit_button('Save All Products'); ?>
    </form>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    // Add row functionality
    $('.add-row').on('click', function() {
        var tableId = $(this).data('table');
        var tbody = $('#' + tableId + ' tbody');
        var rowCount = tbody.children('.product-row').length;
        var template = tbody.children('.product-row').first().clone();
        
        // Clear values
        template.find('input').val('');
        template.find('select').val('');
        
        // Update names with new index
        template.find('input, select').each(function() {
            var name = $(this).attr('name');
            name = name.replace(/\[\d+\]/, '[' + rowCount + ']');
            $(this).attr('name', name);
        });
        
        tbody.append(template);
    });

    // Remove row functionality
    $(document).on('click', '.remove-row', function() {
        var tbody = $(this).closest('tbody');
        if (tbody.children('.product-row').length > 1) {
            $(this).closest('tr').remove();
        } else {
            alert('You must keep at least one row.');
        }
    });
});
</script>

<style>
.product-row td {
    padding: 10px;
}
.add-row {
    margin-top: 10px !important;
    margin-bottom: 30px !important;
}
.remove-row {
    color: #a00 !important;
}
.remove-row:hover {
    color: #dc3232 !important;
}
select {
    min-width: 200px;
}
</style> 