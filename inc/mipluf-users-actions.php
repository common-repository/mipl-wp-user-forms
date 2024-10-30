<?php
/* 
Class: MIPLUF Users
---------------
*/

class MIPLUF_Users {

    public $user_role = '';

    function mipluf_register_sub_menu(){

        add_submenu_page('edit.php?post_type=registration_forms', __('Login Form', 'mipl-wp-user-forms'), __('Login Form', 'mipl-wp-user-forms'), 'manage_options', 'mipluf-login-form' , array($this,'mipluf_login_form'));
        add_submenu_page('edit.php?post_type=registration_forms', __('Settings', 'mipl-wp-user-forms'), __('Settings', 'mipl-wp-user-forms'), 'manage_options', 'mipluf-login-registration-forms-settings', array($this,'mipluf_email_setting_form') );
        
    }


    //Users Form Settings
    function mipluf_email_setting_form(){

        global $post;
        include_once MIPLUF_PLUGIN_PATH.'/views/mipluf-forms-setting.php';

    }

    // Login Form
    function mipluf_login_form(){

        include_once MIPLUF_PLUGIN_PATH.'/views/mipluf-login-form.php';

    }


    // Redirect after Login
    function mipluf_redirect_after_login($user){

        if( in_array($this->user_role, $user->roles)){
            wp_safe_redirect( get_page_link(get_option('mipluf_login_redirect_page')) );
            exit;
        }

    }


    // Admin Bar remove 
    function mipluf_remove_admin_bar(){

        if (!current_user_can('administrator') && !is_admin()) {
            show_admin_bar(false);
        }

    }


    // Fill Current User Details
    function mipluf_fill_user_details_function(){

        if(is_user_logged_in()){
            echo '<script> jQuery(document).ready(function(){ mipluf_user_fill_details(); }); </script>';
        }

    }


    // Get User Details
    function mipluf_get_user_details(){
       
        $current_user = wp_get_current_user();
        $form_id = get_user_meta($current_user->ID,'_mipluf_register_form_id',true);
        $user_meta_fields = array('first_name', 'last_name');
        $current_user_data = array();
        $current_user_fields = array();
        $type_count = 0;

        if ( $current_user->ID != 0 ) {

            $user_id = $current_user->ID;
            $current_user_data['display_name'] =  $current_user->display_name;
            $current_user_fields['display_name'] =  'text';
            $current_user_data['user_email']   =  $current_user->user_email;
            $current_user_fields['user_email'] =  'text';
        
            $user_data   =  get_user_meta($user_id);
            $extra_form_fields = get_post_meta($form_id, '_mipluf_reg_custom_field', true);
            
            foreach($user_data as $key => $value){
                
                $field_value = get_user_meta($user_id, $key, true);
                if(stripos($key,'_mipluf_') === 0){
                    $key = str_replace('_mipluf_','',$key);
                }

                if(in_array($key, $user_meta_fields)){
                    $current_user_data[$key] = $field_value;
                    $current_user_fields[$key] =  'text';
                }

                if(isset($extra_form_fields["field_name"])){
                    if( in_array($key, $extra_form_fields["field_name"]) ){

                        $field_index = array_search($key, $extra_form_fields["field_name"]);
                        $current_user_data[$key] = $field_value;
                        $current_user_fields[$key] =  isset($extra_form_fields["field_type"][$field_index]) ? $extra_form_fields["field_type"][$field_index] : '';

                    }
                }
        
            }
            
        }
        
        $data['field_type'] = $current_user_fields;
        $data['user_data'] = $current_user_data;
        $data['form_id'] = $form_id;
        echo wp_json_encode($data);
        die();

    }


    // User Login
    function mipluf_login_user(){
                
        if ( isset( $_POST['mipluf_user_login_form_nonce'] ) && !wp_verify_nonce( $_POST['mipluf_user_login_form_nonce'], 'mipluf_user_login_form' ) ) {
            echo wp_json_encode( array( 'status' => "error", 'message' => __('Invalid request!', 'mipl-wp-user-forms') ) );
            die();
        }

        $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
        $mipluf_recaptcha = get_option('_mipluf_recaptcha');        
        $disable_recaptcha = get_option('_mipluf_disable_login_recaptcha');
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
        if($enable_recaptcha == 'enable' && $disable_recaptcha != 'disable' && isset($_POST['g-recaptcha-response'])){
            if( !$this->mipluf_verify_recaptcha()){

                $update_errors = array( 'status' => 'error', 'message' => __('Recaptcha Failed', 'mipl-wp-user-forms'), 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);
                if($mipluf_recaptcha == 'v3'){
                    $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
                }
                echo wp_json_encode( $update_errors );
                die();

            }
        }
       
        if(isset($_POST['user_login'])){
            $user_obj = get_user_by('email', sanitize_text_field($_POST['user_login']));
            if(isset($user_obj->ID)){
                $user_id = $user_obj->ID;
            }
        }

        if(isset($user_id)){
            $verify_email_status = get_user_meta($user_id, '_mipluf_verify_email_status', true);
            if( $verify_email_status != 'verified' ){

                $update_errors = array( 'status' => "error", 'message' => __("Email is not verified, Please check verification email!", 'mipl-wp-user-forms' ), 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);
                if($mipluf_recaptcha == 'v3'){
                    $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
                }
                echo wp_json_encode( $update_errors );
                die();

            }

            $activation_status = get_user_meta($user_id, '_mipluf_activation_status', true);
            if( $activation_status != 'approve' ){

                $update_errors = array( 'status' => "error", 'message' => __("Account is not approved!", 'mipl-wp-user-forms' ), 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);
                if($mipluf_recaptcha == 'v3'){
                    $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
                }
                echo wp_json_encode( $update_errors );
                die();

            }
        }
        
        $creds = array();
        $creds['user_login'] = sanitize_text_field($_POST['user_login']);
        $creds['user_password'] = sanitize_text_field($_POST['user_password']);
        $creds['remember'] = true;
        $user = wp_signon( $creds, false );

        if ( !is_wp_error($user) ){

            do_action('enable_otp_field_to_loginform');

            $login_redirection_url = get_option('_mipluf_login_redirect_pages', true);
            
            if(empty($login_redirection_url) || $login_redirection_url == true){
                $login_redirection_url = home_url();
            }
            
            echo wp_json_encode( array( 'status' => "success", 'message' => __('Successfully Login!', 'mipl-wp-user-forms'),'redirection_url' => $login_redirection_url ) );
            die();

        }else{

            $update_errors = array( 'status' => "error", 'message' => $user->get_error_message(), 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }
            echo wp_json_encode( $update_errors );
            die();

        }
        
    }
   

    //Verify recaptcha
    function mipluf_verify_recaptcha(){

        $recaptcha = get_option('_mipluf_recaptcha');

        if($recaptcha == 'v3'){
            $secret_key = get_option('_mipluf_recaptchaV3_secrate_key');
        }

        if($recaptcha == 'v2'){
            $secret_key = get_option('_mipluf_recaptchaV2_secrate_key');
        }

        if(empty($secret_key)){
            return true;
        }

        $response = "";
        if(isset($_POST['g-recaptcha-response']) && is_string($_POST['g-recaptcha-response'])){
            $response = sanitize_text_field($_POST['g-recaptcha-response']);
        }
        
        $url = "https://www.google.com/recaptcha/api/siteverify";

        $args = array(
            'data' => array(
                'secret'   => $secret_key,
                "response" =>  $response,
                "remoteip" => $_SERVER['REMOTE_ADDR']
            ),
            'method' => 'POST'
        );
      
        $rs = mipluf_curl_request($url,$args);
        $resp = '';
        if(!empty($rs['body'])){
            $resp = json_decode( $rs['body'] );
        }

        return $resp->success;

    }


    // Reset v2 keys
    function mipluf_reset_recaptchav2_keys(){
        
        $keys = array('_mipluf_recaptchaV2_site_key','_mipluf_recaptchaV2_secrate_key');
        foreach ($keys as $key){
            $response = update_option( $key, '');
        }

        if($response){
            echo wp_json_encode(['status'=>'success','success_message' => __('Succssefully deleted', 'mipl-wp-user-forms')]);
            die();
        }else{
            echo wp_json_encode(['status'=>'error','error_message' => __('Something wrong', 'mipl-wp-user-forms')]);
            die();
        }
        
    }


    // Reset v3 keys
    function mipluf_reset_recaptchav3_keys(){
        
        $keys = array('_mipluf_recaptchaV3_site_key','_mipluf_recaptchaV3_secrate_key');
        foreach ($keys as $key){
            $response = update_option($key, '');
        }

        if($response){
            echo wp_json_encode(['status' => 'success', 'success_message' => __('Successfully deleted', 'mipl-wp-user-forms')]);
            die();
        }else{
            echo wp_json_encode(['status' => 'error', 'error_message' => __('Something wrong', 'mipl-wp-user-forms')]);
            die();
        }

    }


    // Register User
    function mipluf_register_user(){

        if ( isset( $_POST['mipluf_user_register_form_nonce'] ) && !wp_verify_nonce( $_POST['mipluf_user_register_form_nonce'], 'mipluf_user_register_form' ) ) {
            echo wp_json_encode( array( 'status' => "error", 'message' => __('Invalid request!', 'mipl-wp-user-forms') ) );
            die();
        }

        $meta_fields = array();

        if(isset($_POST['mipluf_register_form_id']) && is_string($_POST['mipluf_register_form_id'])){
            $form_id = sanitize_text_field($_POST['mipluf_register_form_id']);
        }
        
        $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
        $mipluf_recaptcha = get_option('_mipluf_recaptcha');
        $disable_recaptcha = get_post_meta($form_id,'_mipluf_disable_recaptcha',true);
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
        if($enable_recaptcha == 'enable' && $disable_recaptcha != 'disable' && isset($_POST['g-recaptcha-response'])){
            if( !$this->mipluf_verify_recaptcha()){
                
                $update_errors = array( 'status' => 'error', 'message' => __('Recaptcha Failed', 'mipl-wp-user-forms'), 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);

                if($mipluf_recaptcha == 'v3'){
                    $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
                }

                echo wp_json_encode( $update_errors );
                die();

            }
        }

        $mipluf_common_obj = new MIPLUF_Common();
        $field = get_post($form_id);
        $meta_fields = get_post_meta($field->ID,'_mipluf_reg_custom_field',true);
        $mipfuf_user_role = get_post_meta( $field->ID, '_mipluf_form_user_role', true);
        $mipluf_field = $mipluf_common_obj->mipluf_get_custom_fields($meta_fields, $mipfuf_user_role);
        $mipluf_validation_obj  =new MIPLUF_Input_Validation($mipluf_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $userdata = $mipluf_validation_obj->get_valid_data();
        
        if( !empty($errors) ){

            $update_errors = array( 'status' => "error", 'errors' => $errors, 'recaptcha' => $mipluf_recaptcha, 'disable_recaptcha' => $disable_recaptcha);

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }

            echo wp_json_encode( $update_errors );
            die();

        }
        
        $user_email       =  $userdata['user_email'];
        $first_name       =  $userdata['first_name'];
        $last_name        =  $userdata['last_name'];
        $user_pass        =  $userdata['user_pass'];
        
        if(isset($form_id)){
            $userdata['_user_role'] = get_post_meta($form_id, '_mipluf_form_user_role', true);
        }
        
        $user_role = $userdata['_user_role'] ? $userdata['_user_role'] : get_option('_mipluf_login_user_role');
        
        $user_data = array(
            'first_name'            => $first_name,
            'last_name'             => $last_name,
            'user_login'            => $user_email,
            'user_pass'             => $user_pass,
            'user_email'            => $user_email,
            'role'                  => $user_role,
            'show_admin_bar_front'  => false
        );

        $user_id = "";
        $user_id = wp_insert_user($user_data);
        
        if( !$user_id ){

            $update_errors = array( 'status' => "error", 'message' => __('The password you entered is incorrect!', 'mipl-wp-user-forms'), 'disable_recaptcha' => $disable_recaptcha );

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }

            echo wp_json_encode( $update_errors );
            die();

        }
        
        $args = array(
            'post_type'      => 'registration_forms',
            'posts_per_page' => -1
        );

        $info = get_posts($args);
       
        foreach($info as $finfo){
            $form_id = $finfo->ID;
            $fields = get_post_meta($form_id,'_mipluf_reg_custom_field',true);
            if(isset($fields['field_name'])){
                foreach($fields['field_name'] as $k => $val){
                    if(isset($userdata[$val])){
                        update_user_meta($user_id,'_mipluf_'.$fields['field_name'][$k], $userdata[$val]);
                    }
                }
            }
        }
        
        if(isset($_POST['mipluf_register_form_id']) && is_string($_POST['mipluf_register_form_id'])){
            $mipluf_register_form_id = sanitize_text_field($_POST['mipluf_register_form_id']);
            update_user_meta($user_id, '_mipluf_register_form_id', $mipluf_register_form_id);
        }

        if(isset($_POST['authentication_enable_setting']) && is_string($_POST['authentication_enable_setting'])){
            $mipluf_authentication_enable_setting = sanitize_text_field($_POST['authentication_enable_setting']);
            update_user_meta($user_id, '_mipluf_authentication_enable_setting', $mipluf_authentication_enable_setting);
        }else{
            update_user_meta($user_id, '_mipluf_authentication_enable_setting','disable');
        }

        if ( !is_wp_error( $user_id ) ) {
            
            $this->mipluf_send_verify_email($user_id, $first_name, $last_name, $user_email);

            do_action('mipluf_save_user_field');

            $form_id = get_user_meta($user_id, '_mipluf_register_form_id', true);
            $redirection_url = get_post_meta($form_id, '_mipluf_reg_page', true);

            echo wp_json_encode( array( 'status' => "success", 'message' => __('Successfully registered, Please Login!', 'mipl-wp-user-forms'), 'redirection_url' => $redirection_url ) );
            die();

        }else{
            
            $update_errors = array( 'status' => "error", 'message' => $user_id->get_error_message(), 'disable_recaptcha' => $disable_recaptcha );

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }

            echo wp_json_encode( $update_errors );
            die();

        }

    }

    
    // Update User
    function mipluf_update_user(){

        if ( isset( $_POST['mipluf_user_register_form_nonce'] ) && !wp_verify_nonce( $_POST['mipluf_user_register_form_nonce'], 'mipluf_user_register_form' ) ) {
            echo wp_json_encode( array( 'status' => "error", 'message' => __('Invalid request!', 'mipl-wp-user-forms') ) );
            die();
        }
        
        $meta_fields = array();
        $form_id = "";
        if(isset($_POST['mipluf_register_form_id']) && is_string($_POST['mipluf_register_form_id'])){
            $form_id = sanitize_text_field($_POST['mipluf_register_form_id']);
        }

        $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
        $mipluf_recaptcha = get_option('_mipluf_recaptcha');
        $disable_recaptcha = get_post_meta($form_id,'_mipluf_disable_recaptcha',true);
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
        if($enable_recaptcha == 'enable' && $disable_recaptcha == 'enable' && isset($_POST['g-recaptcha-response'])){
            if( !$this->mipluf_verify_recaptcha()){

                $update_errors = array( 'status' => 'error', 'message' => __('Recaptcha Failed', 'mipl-wp-user-forms'), 'recaptcha' => $mipluf_recaptcha);

                if($mipluf_recaptcha == 'v3'){
                    $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
                }

                echo wp_json_encode( $update_errors );
                die();

            }
        }

        $current_user = wp_get_current_user();
        
        if ( $current_user->ID == 0 ) {
            die();
        }

        $mipluf_common_obj = new MIPLUF_Common();
        $field = get_post($form_id);
        $meta_fields =  get_post_meta($field->ID,'_mipluf_reg_custom_field',true);
        $mipfuf_user_role = get_post_meta( $field->ID, '_mipluf_form_user_role', true);
        $mipluf_field = $mipluf_common_obj->mipluf_get_custom_fields($meta_fields, $mipfuf_user_role);
        $mipluf_validation_obj = new MIPLUF_Input_Validation($mipluf_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $userdata = $mipluf_validation_obj->get_valid_data();
        if( !empty($errors) ){

            $update_errors = array( 'status' => "error", 'errors' => $errors, 'recaptcha' => $mipluf_recaptcha);

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }

            echo wp_json_encode( $update_errors );
            die();

        }
        
        $first_name       = isset($userdata['first_name']) ? $userdata['first_name'] : '';
        $last_name        = isset($userdata['last_name']) ? $userdata['last_name'] : '';
        $user_email       = isset($userdata['user_email']) ? $userdata['user_email'] : '';
        $display_name     = $first_name.' '.$last_name;
        $new_password     = $userdata['new_password'];
        
        $user_data = array(
            'ID'           => $current_user->ID,
            'nickname'     => $display_name,
            'display_name' => $display_name,
            'first_name'   => $first_name,
            'last_name'    => $last_name
        );

        $user_id = wp_update_user($user_data);
        $form_id = get_user_meta($user_id,'_mipluf_register_form_id', true);
        $fields = get_post_meta($form_id,'_mipluf_reg_custom_field',true);

        if(isset($fields['field_name'])){
            foreach($fields['field_name'] as $k => $val){
                
                if(isset($userdata[$val])){
                    update_user_meta($user_id, '_mipluf_'.$fields['field_name'][$k], $userdata[$val]);
                }else{
                    update_user_meta($user_id, '_mipluf_'.$fields['field_name'][$k], '');
                }
            }
        }

        if ( !is_wp_error($user_id) ){

            do_action('mipluf_save_update_user_field');
            $redirection_url = get_post_meta($form_id, '_mipluf_reg_page', true);
            echo wp_json_encode( array( 'status' => "success", 'message' => __('Successfully Updated!', 'mipl-wp-user-forms') ) );
            die();

        }else{

            $update_errors = array( 'status' => "error", 'message' => $user_id->get_error_message() );

            if($mipluf_recaptcha == 'v3'){
                $update_errors = array_merge($update_errors, array('recaptcha_v3' => base64_encode($recaptchav3)));
            }

            echo wp_json_encode( $update_errors );
            die();

        }

    }

    
    // Verification Users
    function mipluf_send_verify_email($user_id, $first_name, $last_name, $email, $email_template=''){
        
        if(empty($email_template)){
            $email_template = get_option('_mipluf_supplier_activation_email');
        }

        $verify_key = mipluf_rand(20);
        update_user_meta($user_id, '_mipluf_verify_key', $verify_key);
        update_user_meta($user_id, '_mipluf_verify_email_status', '');
        update_user_meta($user_id, '_mipluf_activation_status', '');
        
        $key = base64_encode(wp_json_encode(array('user_id' => $user_id, 'key' => $verify_key)));
        $link_href = home_url('/?verify-email='.$key);
        $verify_link = '<a href="'.$link_href.'">'.$link_href.'</a>';
        $tags = array('[email]', '[first_name]','[last_name]', '[activation_link]');
        $tags_val = array($email, $first_name, $last_name,  $verify_link);
       
        // Send mail
        $admin_email = get_option('admin_email');
        $subject     = esc_html__("Verify Email", 'mipl-wp-user-forms');
        $mail_body   = str_ireplace($tags, $tags_val, $email_template);
        $headers     = array();
        $headers[]   = 'Content-Type: text/html; charset=UTF-8';
        $headers[]   = "From: $admin_email";
        wp_mail($email, $subject, $mail_body, $headers);
        
        return;

    }

    
    // Activate Users
    function mipluf_send_activation_email($user_id, $first_name, $last_name, $email, $email_template=''){
        
        if(empty($email_template)){
            $email_template = get_option('_mipluf_supplier_notify_email');
        }
        
        $activation_key = mipluf_rand(20);
        update_user_meta($user_id, '_mipluf_activation_key', $activation_key);
        
        $key = base64_encode(wp_json_encode(array('user_id' => $user_id, 'key' => $activation_key)));
        $approve_link_href = home_url('/?activate-user='.$key.'&status=approve');
        $approve_link = '<a href="'.$approve_link_href.'">'.$approve_link_href.'</a>';
        $reject_link_href = home_url('/?activate-user='.$key.'&status=reject');
        $reject_link = '<a href="'.$reject_link_href.'">'.$reject_link_href.'</a>';
        $tags = array('[email]', '[first_name]','[last_name]', '[approve_link]', '[reject_link]');
        $tags_val = array($email, $first_name, $last_name, $approve_link, $reject_link);
        
        // Send mail
        $admin_email = get_option('admin_email');
        $mipluf_email_addiional_headers = get_option('_mipluf_email_addiional_headers');
        $subject = esc_html__("New User Registered, Please Approve/Reject!", 'mipl-wp-user-forms');
        $mail_body = str_ireplace($tags, $tags_val, $email_template);
        $headers = array();
        $headers[] = 'Content-Type: text/html; charset=UTF-8';
        $headers[] = "From:  $admin_email";
        $headers[] = "$mipluf_email_addiional_headers";
        wp_mail($admin_email, $subject, $mail_body, $headers);
        
        return;

    }
    
    
    // Email Verification
    function mipluf_verify_email(){

        if( !isset($_GET['verify-email']) ){ return false; }
        
        // $verify_email = $_GET['verify-email'];
        $activation_data = json_decode(base64_decode($_GET['verify-email']));
        $user_id = $activation_data->user_id;
        $activation_key = $activation_data->key;
        $user_key = get_user_meta($user_id, '_mipluf_verify_key', true);

        if( $user_key == $activation_key ){
            update_user_meta($user_id, '_mipluf_verify_email_status', 'verified');
            $user_obj = get_user_by('id', $user_id);
            $this->mipluf_send_activation_email($user_id, $user_obj->first_name, $user_obj->last_name, $user_obj->user_email);
            $_SESSION['alert_success'] = esc_html__('Your account is successfully verified!, It will activate after admin approve!', 'mipl-wp-user-forms');
        }else{
            $_SESSION['alert_error'] = esc_html__('Verification details are wrong!', 'mipl-wp-user-forms');
        }
        
        header('Location: '.home_url('/?mi-login-modal=true'));
        exit();
        
    }
    
    
    // User Activate
    function mipluf_activate_user(){
        
        if( !isset($_GET['activate-user']) ){ return false; }
        
        $activation_data = json_decode(base64_decode($_GET['activate-user']));
        $user_id         = $activation_data->user_id;
        $activation_key  = $activation_data->key;
        $status          = sanitize_text_field($_GET['status']);
        $user_key        = get_user_meta($user_id, '_mipluf_activation_key', true);
      
        if($user_key == $activation_key){
            update_user_meta($user_id, '_mipluf_activation_status', $status);
            $_SESSION['alert_success'] = esc_html__('User sucessfully activated!', 'mipl-wp-user-forms');
        }else{
            $_SESSION['alert_error'] = esc_html__('Activation details are wrong!', 'mipl-wp-user-forms');
        }
        
        header('Location: '.home_url('/?mi-login-modal=true'));
        exit();
        
    }

    
    // Footer modals
    function mipluf_add_footer_modals(){

        $current_user = wp_get_current_user();
        $form_id = get_user_meta($current_user->ID,'_mipluf_register_form_id',true);
        
        ob_start();

        include_once MIPLUF_PLUGIN_PATH.'/views/mipluf-login-modal.php';
        include_once MIPLUF_PLUGIN_PATH.'/views/mipluf-registration-modal.php';
        include_once MIPLUF_PLUGIN_PATH.'/views/mipluf-registration-login-modal.php';
        include_once MIPLUF_PLUGIN_PATH.'views/mipluf-update-user-pass.php';
        
        $contents = ob_get_contents();
        ob_end_clean();
        echo $contents;

    }


    // Show Custom Fields to User Edit Profile
    function mipluf_custom_user_profile_fields(){

        $user_id = sanitize_text_field($_GET['user_id']);
        $form_id = get_user_meta($user_id,'_mipluf_register_form_id',true);
        $form_obj = get_post($form_id);
        $fields = get_post_meta($form_id,'_mipluf_reg_custom_field',true);
        
        $array_keys = array();
        if(isset($fields['errors'])){
            foreach($fields['errors'] as $key => $values){
                foreach($values as $key1 => $value){
                    $array_keys[$key1] = $value;
                }
            }
        }?>
        <table class="form-table">
            <?php
            if(!empty($fields)){
                if(isset($fields['field_type'])){
                    foreach($fields['field_type'] as $field_key => $field_val){
                        
                        if(in_array($field_key, array_keys($array_keys))){
                            continue;
                        }
                        $edit_user_req_field = "";
                        $field_type = $field_label = $field_name = $field_class = $field_id = $field_option = $field_placeholder = $field_required = "";

                        if(isset($fields['field_type'][$field_key])){
                            $field_type = $fields['field_type'][$field_key];
                        }

                        if(isset($fields['field_label'][$field_key])){
                            $field_label = $fields['field_label'][$field_key];
                        }

                        if(isset($fields['field_name'][$field_key])){
                            $field_name = $fields['field_name'][$field_key];
                        }

                        if(isset($fields['field_class'][$field_key])){
                            $field_class = !empty($fields['field_class'][$field_key]) ? "class=".$fields['field_class'][$field_key]."" : '';
                        }
                        
                        if(isset($fields['field_id'][$field_key])){
                            $field_id = !empty($fields['field_id'][$field_key]) ? "id=".$fields['field_id'][$field_key]."" : '';
                        }

                        if(isset($fields['field_option'][$field_key])){
                            $field_option = $fields['field_option'][$field_key];
                        }

                        if(isset($fields['field_placeholder'][$field_key])){
                            $field_placeholder = !empty($fields['field_placeholder'][$field_key]) ? sprintf( __( '%s', 'mipl-wp-user-forms' ), $fields['field_placeholder'][$field_key] ) : '';
                        }

                        if(isset($fields['field_required'][$field_key])){
                            $field_required = $fields['field_required'][$field_key];
                        }
                        
                        $mipluf_user = get_user_meta($user_id, '_mipluf_'.$field_name,true);
                        
                        if(isset($fields['field_required']) && in_array($field_name, $fields['field_required'])){
                            $edit_user_req_field = 'mipluf_user_edit_req_field';
                        }

                        if(isset($fields['field_name'][$field_key])){
                            
                            $fields['field_name'] = array_unique($fields['field_name']);
                            if($fields['field_type'][$field_key] == "checkbox" ){
                                $label_value = preg_split("/[\n]+/", trim($field_option));
                                $explode_array = array();
                                foreach($label_value as $option_key => $option_value){
                                    if(strpos($option_value,":")){
                                        $tmp = explode(':',trim($option_value));
                                        if(!empty($tmp[0] && !empty($tmp[1]))){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[1]));
                                        }
                                    }else{
                                        $tmp = explode('/n',trim($option_value));
                                        if(!empty($tmp[0])){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[0]));
                                        }
                                    }
                                }
                                
                                $field_options = $explode_array;
                                ?>
                                <tr class="user-first-name-wrap mipluf_fields_label">
                                    <th class="<?php echo esc_html($edit_user_req_field)?>"><label><?php echo esc_html($fields['field_label'][$field_key]);?></label></th>
                                    <td>
                                <?php
                                foreach($field_options as $key => $value){
                                    $checked = "";
                                    
                                    if( is_array($mipluf_user) && in_array($key, $mipluf_user)){
                                        $checked = "checked";
                                    }
                                    ?>
                                    <input type="<?php echo esc_html($field_type)?>" name="<?php echo esc_html($field_name);?>[]" class="regular-text" value="<?php echo esc_html($key);?>" <?php echo esc_attr($checked); ?> />
                                    <?php echo esc_html($value);
                                }
                                ?>
                                </td>
                                </tr>
                                <?php
                            }elseif($fields['field_type'][$field_key] == "select"){
                                $label_value = preg_split("/[\n]+/", trim($field_option));
                                $explode_array = array();
                                foreach($label_value as $option_key => $option_value){
                                    if(strpos($option_value,":")){
                                        $tmp = explode(':',trim($option_value));
                                        if(!empty($tmp[0] && !empty($tmp[1]))){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[1]));
                                        }
                                    }else{
                                        $tmp = explode('/n',trim($option_value));
                                        if(!empty($tmp[0])){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[0]));
                                        }
                                    }
                                }
                                
                                $field_options = $explode_array;                                    
                                ?>
                                <tr class="user-first-name-wrap mipluf_fields_label">
                                    <th class="<?php echo esc_html($edit_user_req_field)?>"><label><?php echo esc_html($fields['field_label'][$field_key]);?></label></th>
                                    <td>
                                    <select name="<?php echo esc_html($field_name);?>"  class="regular-text" >
                                        <?php
                                        foreach($field_options as $key => $value){
                                            $selected = "";
                                            if($key == $mipluf_user){
                                                $selected = "selected";
                                            }
                                            ?>
                                            <option value="<?php echo esc_html($key)?>" <?php echo esc_attr($selected); ?>><?php echo esc_html($value)?></option>
                                            <?php
                                        } ?>
                                    </select>
                                    </td>
                                </tr>
                                <?php

                            }elseif($fields['field_type'][$field_key] == "radio"){
                                $label_value = preg_split("/[\n]+/", trim($field_option));
                                $explode_array = array();
                                foreach($label_value as $option_key => $option_value){
                                    if(strpos($option_value,":")){
                                        $tmp = explode(':',trim($option_value));
                                        if(!empty($tmp[0] && !empty($tmp[1]))){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[1]));
                                        }
                                    }else{
                                        $tmp = explode('/n',trim($option_value));
                                        if(!empty($tmp[0])){
                                            $explode_array[strtolower(trim($tmp[0]))] = ucfirst(trim($tmp[0]));
                                        }
                                    }
                                }
                                $field_options = $explode_array;
                                
                                ?>
                                <tr class="user-first-name-wrap mipluf_fields_label">
                                    <th class="<?php echo esc_html($edit_user_req_field)?>"><label><?php echo esc_html($fields['field_label'][$field_key]);?></label></th>
                                    <td>
                                    <?php
                                    foreach($field_options as $key => $value){
                                        $checked = "";
                                        if($key == $mipluf_user){
                                            $checked = "checked";
                                        }
                                        ?>
                                        <input type="<?php echo esc_html($field_type)?>" name="<?php echo esc_html($field_name);?>" class="regular-text" value="<?php echo esc_html($key);?>" <?php echo esc_attr($checked); ?>/>
                                        <?php echo esc_html($value);
                                    }
                                    ?>
                                    </td>
                                </tr>
                                <?php
                            }elseif($fields['field_type'][$field_key] == "textarea"){
                                ?>
                                <tr class="user-first-name-wrap mipluf_fields_label">
                                    <th class="<?php echo esc_html($edit_user_req_field)?>"><label><?php echo esc_html($fields['field_label'][$field_key]);?></label></th>
                                    <td>
                                        <textarea name="<?php echo esc_html($field_name);?>" class="regular-text" ><?php echo esc_html($mipluf_user);?></textarea>
                                    </td>
                                </tr>
                                <?php
                            }elseif(!empty($fields['field_type'][$field_key])){
                                ?>
                                <tr class="user-first-name-wrap mipluf_fields_label">
                                    <th class="<?php echo esc_html($edit_user_req_field)?>"><label><?php echo esc_html($fields['field_label'][$field_key]);?></label></th>
                                    <td><input type="<?php echo esc_html($field_type);?>" name="<?php echo esc_html($field_name);?>" class="regular-text" value="<?php echo esc_html($mipluf_user);?>"></td>
                                </tr>
                                <?php
                            }
                            
                        }
                        
                    }
                }
            
            }
            ?>

        </table>
        <?php
    }


    // Update Fields of User Edit Profile
    function mipluf_update_extra_profile_fields($user_id){

        $form_id = get_user_meta($user_id,'_mipluf_register_form_id',true);
        $fields = get_post_meta($form_id,'_mipluf_reg_custom_field',true);

        $mipluf_common_obj = new MIPLUF_Common();
        $mipluf_field = $mipluf_common_obj->mipluf_get_user_custom_fields($fields);        
        $mipluf_validation_obj = new MIPLUF_Input_Validation($mipluf_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $user_data = $mipluf_validation_obj->get_valid_data();

        $_SESSION['mipluf_admin_notices']['error'] = implode('<br>', $errors);

        if(isset($fields['field_name'])){
            foreach($fields['field_name'] as $key => $value){
                if(isset($user_data[$value])&& !empty($user_data[$value])){
                    update_user_meta($user_id,'_mipluf_'.$value,$user_data[$value]);
                }
            }
        }
        
    }


    // Show Validation Messages
    function mipluf_admin_notices(){

        $message_type = array('error', 'success', 'warning', 'info');

        foreach( $message_type as $type ){
            
            $class = 'notice is-dismissible ';
            if(!empty($_SESSION['field_position'])){
                printf( '<div class="notice is-dismissible  notice-error"><p>%s</p></div>', esc_html__("Please solve custom fields errors!") );
            }
            if( isset($_SESSION['mipluf_admin_notices'][ $type ]) && trim( $_SESSION['mipluf_admin_notices'][ $type ]) != '' ){
                $class = $class.' notice-'.$type;
                $message = wp_kses_post($_SESSION['mipluf_admin_notices'][ $type ]);
                printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
                unset( $_SESSION['mipluf_admin_notices'][ $type ] );
            }

            unset($_SESSION['field_position']);
        }
    }


    function mipluf_update_user_setting(){

        if ( isset( $_POST['mipluf_update_user_setting_nonce'] ) && !wp_verify_nonce( $_POST['mipluf_update_user_setting_nonce'], 'mipluf_update_user_setting' ) ) {
            echo wp_json_encode( array( 'status' => "error", 'message' => __('Invalid request!', 'mipl-wp-user-forms') ) );
            die();
        }
        
        $current_user = wp_get_current_user();
        if ( $current_user->ID == 0 ) {
            echo wp_json_encode( array('status' => "error", 'message' => __('The user is not logged in!', 'mipl-wp-user-forms')) );
            die();
        }

        $user_pass = sanitize_text_field($_POST['user_pass']);
        $new_password = sanitize_text_field($_POST['new_password']);
        $confirm_password = sanitize_text_field($_POST['confirm_password']);

        $update_field['user_pass'] = array(
            'label'      => 'User Pass',
            'type'       => 'password',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('Password is required', 'mipl-wp-user-forms'),
                'regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
                'regex_msg' => __('Password should be valid!', 'mipl-wp-user-forms')
            ),
        );
        $update_field['new_password'] = array(
            'label'      => 'New Password',
            'type'       => 'password',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('New Password is required', 'mipl-wp-user-forms'),
                'regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
                'regex_msg' => __('New Password should be valid!', 'mipl-wp-user-forms')
            ),
        );
        $update_field['confirm_password'] = array(
            'label'      => 'Confirm Password',
            'type'       => 'password',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('Confirm Password is required', 'mipl-wp-user-forms'),
                'regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
                'regex_msg' => __('Confirm Password should be valid!', 'mipl-wp-user-forms')
            ),
        );
        
        $mipluf_validation_obj = new MIPLUF_Input_Validation($update_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $userdata = $mipluf_validation_obj->get_valid_data();

        if( !empty($errors) ){
            echo wp_json_encode( array( 'status' => "error", 'errors' => $errors) );
            die();
        }
        
        if( wp_check_password($user_pass, $current_user->user_pass, $current_user->ID) ){
            if( !empty($new_password) && $new_password === $confirm_password){
                wp_set_password($new_password, $current_user->ID);
                echo wp_json_encode( array( 'status' => "success", 'message' => __("Successfully updated!", 'mipl-wp-user-forms') ) );
                die();
            }else{
                echo wp_json_encode( array( 'status' => "error", 'message' => __('The confirmation password is incorrect!', 'mipl-wp-user-forms') ) );
                die();
            }
        }else{
            echo wp_json_encode( array( 'status' => "error", 'message' => __('The password you entered is incorrect!', 'mipl-wp-user-forms') ) );
            die();
        }

    }

}