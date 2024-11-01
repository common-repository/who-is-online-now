<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( !class_exists('WPM_WO_Setting_Controls' ) ):
class WPM_WO_Setting_Controls {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WPM_Setting_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'Who Is Online', 'Who Is Online', 'delete_posts', 'wpmwo_settings_page', array($this, 'plugin_page') );


        // add_submenu_page('edit.php?post_type=wpmlimo_fleet', __('Map Settings','wbtm-menu'), __('Map Settings','wbtm-menu'), 'manage_options', 'wpmlimo_settings', array($this, 'plugin_page'));
    }

    function get_settings_sections() {

        $sections = array(
            array(
                'id' => 'wpmwo_general_setting_sec',
                'title' => __( 'General Settings', 'who-is-online-now' )
            ),
            array(
                'id' => 'wpmwo_string_setting_sec',
                'title' => __( 'String Settings', 'who-is-online-now' )
            )            
        );



        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {

        $settings_fields = array(
            'wpmwo_general_setting_sec' => array(
                array(
                    'name' => 'wpmwo_ajax_refresh_time',
                    'label' => __( 'Ajax Refresh Interval', 'who-is-online-now' ),
                    'desc' => __( 'Please Enter Ajax Refresh Interval time in second', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => '5'
                ),
                array(
                    'name' => 'wpmwo_avatar_size',
                    'label' => __( 'Avatar Image Size', 'who-is-online-now' ),
                    'desc' => __( 'Select Member Image Size', 'who-is-online-now' ),
                    'type' => 'select',
                    'default' => '32',
                    'options' =>  array(
                        '32' => 32,
                        '48' => 48,
                        '96' => 96
                    )
                ),                
                array(
                    'name' => 'wpmwo_show_user_list',
                    'label' => __( 'Show Member List', 'who-is-online-now' ),
                    'desc' => __( 'Do you want to show member list?', 'who-is-online-now' ),
                    'type' => 'select',
                    'default' => 'yes',
                    'options' =>  array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),                 
                array(
                    'name' => 'wpmwo_hide_admin_users',
                    'label' => __( 'Hide Admin Users?', 'who-is-online-now' ),
                    'desc' => __( 'Do you want to hide admin users from the online list?', 'who-is-online-now' ),
                    'type' => 'select',
                    'default' => 'no',
                    'options' =>  array(
                        'yes' => 'Yes',
                        'no' => 'No'
                    )
                ),                 
                array(
                    'name' => 'wpmwo_user_list_style',
                    'label' => __( 'Member List Style', 'who-is-online-now' ),
                    'desc' => __( 'Please Select How you want to display Member List', 'who-is-online-now' ),
                    'type' => 'select',
                    'default' => 'avatar',
                    'options' =>  array(
                        'avatar' => 'Member Image',
                        'name_list' => 'Member Name'
                    )
                ),                
            ),            
            'wpmwo_string_setting_sec' => array(
                array(
                    'name' => 'wpmwp_string_1',
                    'label' => __( 'Total', 'who-is-online-now' ),
                    // 'desc' => __( '', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => 'Total'
                ),
                array(
                    'name' => 'wpmwp_string_2',
                    'label' => __( 'visitors are online', 'who-is-online-now' ),
                    // 'desc' => __( '', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => 'visitors are online'
                ),
                array(
                    'name' => 'wpmwp_string_3',
                    'label' => __( '&', 'who-is-online-now' ),
                    // 'desc' => __( '', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => '&'
                ),
                array(
                    'name' => 'wpmwp_string_4',
                    'label' => __( 'registered member', 'who-is-online-now' ),
                    // 'desc' => __( '', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => 'registered member'
                ),
                array(
                    'name' => 'wpmwp_string_5',
                    'label' => __( 'from mobile', 'who-is-online-now' ),
                    // 'desc' => __( '', 'who-is-online-now' ),
                    'type' => 'text',
                    'default' => 'from mobile'
                ),                                                                
            )
        );

        return $settings_fields;
    }

    function plugin_page() {
        echo '<div class="wrap">';

        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }

}
endif;

$settings = new WPM_WO_Setting_Controls();


function wpmwo_get_option( $option, $section, $default = '' ) {
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}