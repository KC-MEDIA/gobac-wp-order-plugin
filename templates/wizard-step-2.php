<div class="step-2">
    <h2><?php _e('Choose Your Boxes', 'gobac-moving-boxes'); ?></h2>
    
    <div class="box-products">
        <?php
        $boxes = Gobac_Products::get_box_products();
        foreach ($boxes as $box):
        ?>
        <div class="box-product">
            <img src="<?php echo esc_url($box['image']); ?>" alt="<?php echo esc_attr($box['name']); ?>">
            <h3><?php echo esc_html($box['name']); ?></h3>
            <p class="description"><?php echo esc_html($box['description']); ?></p>
            <p class="price"><?php echo sprintf(__('$%s per week', 'gobac-moving-boxes'), number_format($box['weekly_price'], 2)); ?></p>
            <input type="number" 
                   name="box_quantity[<?php echo $box['id']; ?>]" 
                   min="0" 
                   value="0" 
                   class="box-quantity">
        </div>
        <?php endforeach; ?>
    </div>
</div> 