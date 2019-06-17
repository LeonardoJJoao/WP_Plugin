
<?php
function load_scripts()
{
    $pluginPath = plugin_dir_url(__FILE__);

    wp_enqueue_script('phaser_min_js', $pluginPath . 'js/phaser.min.js'); // Game Engine
    wp_enqueue_script('pong_js', $pluginPath . 'js/pong.js', array('phaser_min_js'), '1.0.0' , true); // Game

    wp_register_style('style_css', $pluginPath . 'css/style.css');
    wp_enqueue_style('style_css');
}
?>
