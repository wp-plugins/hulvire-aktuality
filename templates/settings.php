<div class="wrap">
    <h2><img src="<?php echo plugin_dir_url( __FILE__ ). '../images/aktuality-icon-s.png'; ?>" /aktuality> Hulvire Aktuality</h2>
    <form method="post" action="options.php"> 
        <?php @settings_fields('wp_hulvire_aktuality-group'); ?>
        <?php @do_settings_fields('wp_hulvire_aktuality-group'); ?>

        <?php do_settings_sections('wp_hulvire_aktuality'); ?>

        <?php @submit_button(); ?>
    </form>
</div>