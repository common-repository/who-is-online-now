<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
add_shortcode( 'who-is-online-now', 'wpmwo_get_visitor' );
function wpmwo_get_visitor($atts, $content=null){
		$defaults = array(
			"theme"						=> "1"
		);
		$params 					= shortcode_atts($defaults, $atts);
		$theme						= $params['theme'];
?>
<div class="wpmwo_online_visitors" id='wpmwo_show_result'>
	<?php echo wpmwo_get_who_is_online(); ?>
</div>
<?php
}