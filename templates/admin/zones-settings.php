<div class="wrap">
    <h1><?php _e('Delivery Zones Settings', 'gobac-moving-boxes'); ?></h1>
    <form method="post" action="options.php">
        <?php settings_fields('gobac_options'); ?>
        <p><?php _e('Configure delivery zones here.', 'gobac-moving-boxes'); ?></p>
        <?php submit_button(); ?>
    </form>
</div> 