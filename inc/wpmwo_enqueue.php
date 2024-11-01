<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Enqueue Scripts for front-end
add_action('wp_enqueue_scripts', 'wpmwo_scripts');
function wpmwo_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('wpmwo-script',plugin_dir_url( __DIR__ ).'js/wpmwo_script.js',array('jquery'),null,false);
    wp_enqueue_style('wpmwo-style',plugin_dir_url( __DIR__ ).'css/wpmwo_style.css',array());
}

function wpmwo_ajax_call_url(){
    wp_localize_script('jquery', 'wpmwo_ajax', array( 'wpmwo_ajaxurl' => admin_url( 'admin-ajax.php')));
}
add_action('admin_enqueue_scripts','wpmwo_ajax_call_url');
add_action('wp_enqueue_scripts','wpmwo_ajax_call_url');
