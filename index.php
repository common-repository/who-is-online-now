<?php
/**
* Plugin Name: Who Is Online Now
* Plugin URI: https://wpmonkeys.com/
* Description: A Ajax Live Online Visitor Counter for WordPress website.
* Version: 1.0.2
* Author: WP Monkey's
* Author URI: https://wpmonkeys.com
* Text Domain: who-is-online-now
* Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once( ABSPATH . "wp-includes/pluggable.php" );
// function to create the DB / Options / Defaults         
function wpmwo_visitor_db_table() {
global $wpdb;
  $charset_collate = $wpdb->get_charset_collate();
  $table_name = $wpdb->prefix . 'online_visitors';
  $sql = "CREATE TABLE $table_name (
    visitor_id int(15) NOT NULL AUTO_INCREMENT,
    timestamp int(15) NOT NULL,
    user_id int(9) NOT NULL,  
    ip varchar(55) NOT NULL,
    mobile int(1) NOT NULL,  
    files varchar(255) DEFAULT '' NOT NULL,
    PRIMARY KEY  (visitor_id)
  ) $charset_collate;";
  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql );
}
// run the install scripts upon plugin activation
register_activation_hook(__FILE__,'wpmwo_visitor_db_table');




require_once(dirname(__FILE__) . "/inc/class/settings.php");
require_once(dirname(__FILE__) . "/inc/wpmwo_settings.php");
require_once(dirname(__FILE__) . "/inc/wpmwo_enqueue.php");
if( !is_admin()){ 
  require_once(dirname(__FILE__) . "/inc/wpmwo_visitor.php");
  require_once(dirname(__FILE__) . "/inc/wpmwo_shortcode.php");
}







function wpmwo_get_who_is_online(){
  global $wpdb;
  $table_name         = $wpdb->prefix . 'online_visitors';
  $total_ip           = $wpdb->get_var("SELECT COUNT(DISTINCT ip) FROM $table_name");
  $total_users        = $wpdb->get_var( "SELECT COUNT(DISTINCT user_id) FROM $table_name WHERE user_id!=0" );
  $total_mobile_users = $wpdb->get_var( "SELECT  COUNT(DISTINCT ip) FROM $table_name WHERE mobile=1" );
  $member_q           = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id>0 GROUP BY user_id") or die(mysql_error());
  $string_1           = wpmwo_get_option('wpmwp_string_1', 'wpmwo_string_setting_sec','Total');
  $string_2           = wpmwo_get_option('wpmwp_string_2', 'wpmwo_string_setting_sec','visitors are online');
  $string_3           = wpmwo_get_option('wpmwp_string_3', 'wpmwo_string_setting_sec','&');
  $string_4           = wpmwo_get_option('wpmwp_string_4', 'wpmwo_string_setting_sec','registered member');
  $string_5           = wpmwo_get_option('wpmwp_string_5', 'wpmwo_string_setting_sec','from mobile');

  $avatar_size        = wpmwo_get_option('wpmwo_avatar_size', 'wpmwo_general_setting_sec','32');
  $show_member        = wpmwo_get_option('wpmwo_show_user_list', 'wpmwo_general_setting_sec','yes');
  $member_style       = wpmwo_get_option('wpmwo_user_list_style', 'wpmwo_general_setting_sec','avatar');
  $hide_admin       = wpmwo_get_option('wpmwo_hide_admin_users', 'wpmwo_general_setting_sec','no');

  ob_start();

  if ($total_ip>=0){
  echo "$string_1 <span id='mvtotalss'>".$total_ip."</span> $string_2";
  }
  if($total_users>0){
  echo "<span id='mvreguserss'>, $string_3 ".$total_users." "." $string_4 </span>";
  }

  if($total_mobile_users>0){
  echo "<span id='mvmbuserss'> ( ".$total_mobile_users." $string_5 ) </span>";
  }

  if($show_member=='yes'){
    if($total_users>0){
      echo '<ul>';
        foreach( $member_q as $member ) {
          $user_info = get_userdata($member->user_id);
          $user_roles=$user_info->roles;



if($hide_admin=='yes'){


          if($member_style=='avatar'){
            if($user_roles[0]!='administrator'){
          ?>
         <li class="wpmwo_member_avatar"> <img src="<?php echo get_avatar_url($member->user_id,array('size'=>$avatar_size)); ?>" alt="<?php echo $user_info->display_name; ?>" title='<?php echo $user_info->display_name; ?>'></li>
       <?php } } elseif($member_style=='name_list'){ if($user_roles[0]!='administrator'){ ?>
          <li class="wpmwo_member_name"><?php echo $user_info->display_name; ?></li>
         <?php
       }
}





}else{



          if($member_style=='avatar'){
          ?>
         <li class="wpmwo_member_avatar"> <img src="<?php echo get_avatar_url($member->user_id,array('size'=>$avatar_size)); ?>" alt="<?php echo $user_info->display_name; ?>" title='<?php echo $user_info->display_name; ?>'></li>
       <?php } elseif($member_style=='name_list'){ ?>
          <li class="wpmwo_member_name"><?php echo $user_info->display_name; ?></li>
         <?php
       }

}



       }
        echo '</ul>';
      }
     
    }
  $content = ob_get_clean();
  return $content;
}


add_action('wp_footer','wpmwo_ajax_script');
function wpmwo_ajax_script(){
  $refresh_time = wpmwo_get_option('wpmwo_ajax_refresh_time', 'wpmwo_general_setting_sec','5')*1000;
  ?>
  <script>
    setInterval(wpmwo_get_online_user_ajax,<?php echo $refresh_time; ?>);
  </script>
<?php
}

function wpmwo_member_ajax_search(){
  echo wpmwo_get_who_is_online();
die();
}
add_action('wp_ajax_wpmwo_member_ajax_search', 'wpmwo_member_ajax_search');
add_action('wp_ajax_nopriv_wpmwo_member_ajax_search', 'wpmwo_member_ajax_search');