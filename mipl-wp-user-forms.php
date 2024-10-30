<?php
/**
* Plugin Name:       MIPL WP User Forms
* Plugin URI:
* Description:       Simple Login And Registration Forms, Users=>Login & Registration"
* Version:           1.0.2
* Requires at least: 4.7
* Requires PHP:      7.2
* Author:            Mulika Team
* Author URI:        https://www.mulikainfotech.com/
* Text Domain:       mipl-wp-user-forms
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Define const
define("MIPLUF_PLUGIN_URL", plugin_dir_url( __FILE__ ) );
define("MIPLUF_PLUGIN_PATH", plugin_dir_path( __FILE__ ) );
define("MIPLUF_HOME_URL", home_url() );
define("MIPLUF_SITE_URL", site_url() );
define("MIPLUF_TEMPLATE_URL", get_template_directory_uri() );


// Include lib
include_once MIPLUF_PLUGIN_PATH.'/inc/libs/mipluf_curl_helper.php';
include_once MIPLUF_PLUGIN_PATH.'/inc/mipluf-class-input-validation.php';
 
// Include classes
include_once MIPLUF_PLUGIN_PATH.'/inc/lib-mipluf-common.php';
include_once MIPLUF_PLUGIN_PATH.'/inc/mipluf-users-actions.php';
include_once MIPLUF_PLUGIN_PATH.'/inc/mipluf-class-common.php';
include_once MIPLUF_PLUGIN_PATH.'/inc/mipluf-form-shortcodes.php';

$mipluf_user_obj = new MIPLUF_Users();
$mipluf_shortcodes_obj = new MIPLUF_Shortcodes();
$mipluf_common_obj = new MIPLUF_Common();


// Register Post type
add_action('init', array( $mipluf_common_obj,'mipluf_register_custom_post_type') );


// Activation hooks
register_activation_hook( __FILE__, array( $mipluf_common_obj, 'mipluf_post_type_rewrite_flush' ) );
register_activation_hook( __FILE__, array($mipluf_common_obj, 'mipluf_default_mail_settings'));


// Actions
add_action('wp_head', array($mipluf_common_obj, 'mipluf_wp_urls'));
add_action('admin_head', array($mipluf_common_obj, 'mipluf_wp_urls'));
add_action('wp_enqueue_scripts', array($mipluf_common_obj,'mipluf_user_scripts'));
// add_action('admin_init', array($mipluf_common_obj,'mipluf_admin_enqueue_scripts'));


// Shortcodes
add_shortcode('mipluf_login_button', array($mipluf_shortcodes_obj, 'mipluf_login_button_shortcode') );
add_shortcode('mipluf_login_registration_button', array($mipluf_shortcodes_obj, 'mipluf_login_registeration_button_shortcode') );
add_shortcode('mipluf_login_form', array($mipluf_shortcodes_obj,'mipluf_login_form_field') );
add_shortcode('mipluf_user_login_links', array($mipluf_shortcodes_obj, 'mipluf_user_login_links'));
add_shortcode('mipluf_registration_form', array($mipluf_shortcodes_obj,'mipluf_registration_form') );
add_shortcode('mipluf_login_registration_form', array($mipluf_shortcodes_obj,'mipluf_registration_login_form') );
add_shortcode('mipluf_form_field', array($mipluf_shortcodes_obj,'mipluf_form_field') );
add_shortcode('mipluf_recaptcha', array($mipluf_shortcodes_obj,'mipluf_recaptcha_shortcode') );


// Session
if(!wp_is_json_request() ){
    add_action('init', array($mipluf_common_obj, 'mipluf_session'));
}


// Admin Actions 
if(is_admin()){

    add_action('admin_menu', array($mipluf_user_obj, 'mipluf_register_sub_menu'));
    add_action('add_meta_boxes', array( $mipluf_common_obj,'mipluf_add_custom_meta_box') );
    add_action('manage_registration_forms_posts_columns', array( $mipluf_common_obj,'mipluf_add_forms_posts_columns') );
    add_action('manage_registration_forms_posts_custom_column', array( $mipluf_common_obj,'mipluf_registration_form_shortcode'),10,2);
    add_action('save_post',  array($mipluf_common_obj, 'mipluf_save_registration_form'),10, 3);
    add_action('edit_form_after_title',  array($mipluf_common_obj, 'mipluf_registration_form_field'));
    add_filter('wp_insert_post_data', array($mipluf_common_obj,'mipluf_filter_post_name'),10,3);
    add_action('admin_footer',  array($mipluf_common_obj, 'mipluf_create_role_for_regform'));
    add_action('edit_user_profile', array($mipluf_user_obj, 'mipluf_custom_user_profile_fields' ));
    add_action('edit_user_profile_update',array($mipluf_user_obj, 'mipluf_update_extra_profile_fields'),10,1);
    add_action('admin_notices', array( $mipluf_user_obj, 'mipluf_admin_notices' ) );

    add_action( 'admin_enqueue_scripts', array($mipluf_common_obj, 'mipluf_admin_enqueue_scripts'), 9 );
   
    if( isset( $_REQUEST['mipluf_action']) ){

        // Save Users Settings
        if( $_REQUEST['mipluf_action'] == 'mipluf_save_all_setting' ){
            add_action('init', array( $mipluf_common_obj, 'mipluf_save_all_setting'));
        }

        // Save Login Form
        if( $_REQUEST['mipluf_action'] == 'mipluf_save_login_form_settings' ){
            add_action('init', array( $mipluf_common_obj, 'mipluf_save_login_form'));
        }
        
    }


    // Add registration form role
    if(isset($_REQUEST['mipluf_action']) && $_REQUEST['mipluf_action'] == 'mipluf_add_Role' ){
        add_action('init', array( $mipluf_common_obj, 'mipluf_add_role'));
    }


}


//client Actions
if(!is_admin()){
  
    if( isset($_REQUEST['mipluf_action']) ){

        // Login user
        if($_REQUEST['mipluf_action'] == 'mipluf_login_user' ){
            add_action('init', array($mipluf_user_obj,'mipluf_login_user'));
        }

        // Register user
        if($_REQUEST['mipluf_action'] == 'mipluf_register_user' ){
            add_action('init', array($mipluf_user_obj,'mipluf_register_user'));
        }
       
        // Update user
        if($_REQUEST['mipluf_action'] == 'mipluf_update_user' ){
            add_action('init', array($mipluf_user_obj,'mipluf_update_user'));
        }
       
        // Fill user details
        if($_REQUEST['mipluf_action'] == 'mipluf_get_user_details' ){
            add_action('init', array($mipluf_user_obj,'mipluf_get_user_details'));
        }
    
    }

    // wp-admin login form field
    $enable_social_media_logins = get_option('_mipluf_enable_google_login_in_wp_login');
    if(!empty($enable_social_media_logins) && $enable_social_media_logins == 'enable'){
        add_action('login_form', array($mipluf_common_obj, 'mipluf_wp_login_form_field'));
    }


    if( isset($_REQUEST['mipluf_action']) && $_REQUEST['mipluf_action'] == 'mipluf_update_user_setting' ){
        add_action('init', array($mipluf_user_obj, 'mipluf_update_user_setting'));
    }
    

}


// Add User Role
if( isset($_GET['mipluf_action']) && $_GET['mipluf_action'] == 'add_role' ){
    add_action('init', array($mipluf_user_obj, 'mipluf_add_user_role'));
}


// Verify Email
if( isset($_GET['verify-email']) ){
    add_action('init', array($mipluf_user_obj, 'mipluf_verify_email'));
}


// Activate User
if( isset($_GET['activate-user']) ){
    add_action('init', array($mipluf_user_obj, 'mipluf_activate_user'));
}


// Redirect after reset password
add_action('after_password_reset', array($mipluf_user_obj, 'mipluf_redirect_after_login'));


// Remove admin bar for Custom User
add_action('init', array($mipluf_user_obj, 'mipluf_remove_admin_bar'));


// Reset social media and recaptcha keys
if(isset($_REQUEST['mipluf_action'])) {
    
    if($_REQUEST['mipluf_action'] == 'mipluf_reset_recaptchav2_keys' ){
        add_action('init', array($mipluf_user_obj,'mipluf_reset_recaptchav2_keys'));
    }
    
    if($_REQUEST['mipluf_action'] == 'mipluf_reset_recaptchav3_keys' ){
        add_action('init', array($mipluf_user_obj,'mipluf_reset_recaptchav3_keys'));
    }

}


// Add Login, Registration, Update Details Modals
add_action('wp_footer', array($mipluf_user_obj, 'mipluf_add_footer_modals'));


// Fill user details
add_action('wp_footer', array($mipluf_user_obj, 'mipluf_fill_user_details_function'));


// Page Restriction
add_action('wp', array( $mipluf_common_obj,'mipluf_page_restriction') );