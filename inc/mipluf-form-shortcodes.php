<?php
/*
Class: MIPLUF Shortcodes
*/

class MIPLUF_Shortcodes{

    //Login Button
    function mipluf_login_button_shortcode($atts = [], $content = null, $tag = ''){

        $attrs = array_change_key_case( (array) $atts, CASE_LOWER );
        $wporg_atts = shortcode_atts(
            array(
                'user_role_for_social_media'=> '',
                'redirect_url' => ''
            ), $atts, $tag
        );

        $_SESSION['mipluf_social_media_data'] = $wporg_atts;

        ob_start();
        ?>
        <a href="#login" class="mipluf_toggal_modal" data-modal="#mipluf_login_modal"><?php esc_html_e('User Login', 'mipl-wp-user-forms') ?></a>
        <?php
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }


    //Login Registration Button
    function mipluf_login_registeration_button_shortcode($atts = [], $content = null, $tag = ''){

        ob_start();
        $args = shortcode_atts(
            array(
               'form_id'=> '',
               'redirect_url' => ''
            ), $atts, $tag
        );

        $form_id = $args['form_id'];

        if( !isset($_SESSION['mipluf_reg_login_forms']) || !in_array($form_id,$_SESSION['mipluf_reg_login_forms']) ){
            $_SESSION['mipluf_reg_login_forms'][] = $form_id;
        }

        $_SESSION['mipluf_redirect_after_login'] = $args['redirect_url'];
        ?>
        <a href="#register-login" class="mipluf_toggal_modal" data-modal="#mipluf_registration_login_modal_<?php echo esc_html($form_id);?>"><?php esc_html_e('Login & Registration', 'mipl-wp-user-forms') ?></a>
        <?php
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }


    // Login Links
    function mipluf_user_login_links($atts = [], $content = null, $tag = ''){

        $args = shortcode_atts(
            array(
                'form_id'=>'',
            ), $atts, $tag
        );

        $form_id = $args['form_id'];
        
        if( !isset($_SESSION['mipluf_reg_forms']) || !in_array($form_id,$_SESSION['mipluf_reg_forms']) ){
            $_SESSION['mipluf_reg_forms'][] = $form_id;
        }
        
        $current_user = wp_get_current_user();
        
        $logged_in = 0;
        if($current_user->ID != 0){ $logged_in = 1; }
        ob_start();
        
        if( isset($_SESSION['alert_success']) ){
            ?>
            <p><div class="mipluf_alert mipluf_alert-success"><?php esc_html_e($_SESSION['alert_success'])?></div></p>;
            <?php
            unset($_SESSION['alert_success']);
        }

        if( isset($_SESSION['alert_error']) ){
            ?>
            <p><div class="mipluf_alert mipluf_alert-error"><?php esc_html_e($_SESSION['alert_error'])?></div></p>
            <?php
            unset($_SESSION['alert_error']);
        }
        ?>
        <p class="user_logged_in" style="text-align: center; display:<?php echo esc_html($logged_in?'block':'none')?>">
            <?php esc_html_e('Welcome', 'mipl-wp-user-forms');?>, <span class="user_display_name"><?php echo esc_html($current_user->display_name);?></span> &nbsp; |
            <a href="#update" class="mipluf_toggal_modal"  data-modal="#mipluf_registration_modal_<?php echo esc_html($form_id);?>"><?php esc_html_e('Update User Details', 'mipl-wp-user-forms') ?></a> | &nbsp;
            <a href="<?php echo esc_url(wp_logout_url( get_permalink() )); ?>"><?php esc_html_e('Logout', 'mipl-wp-user-forms') ?></a>
        </p>

        <p class="user_logged_out" style="text-align: center; display:<?php echo esc_html($logged_in?'none':'block')?>">
            <a href="#login" class="mipluf_toggal_modal"  data-modal="#mipluf_login_modal"><?php esc_html_e('User Login', 'mipl-wp-user-forms'); ?></a> &nbsp; | &nbsp;
            <a href="#register" class="mipluf_toggal_modal"  data-modal="#mipluf_registration_modal_<?php echo esc_html($form_id);?>"><?php esc_html_e('Register', 'mipl-wp-user-forms') ?></a>
        </p><?php
        
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }


    // Login Form
    function mipluf_login_form_field($atts = [], $content = null, $tag = ''){

        $attrs = array_change_key_case( (array) $atts, CASE_LOWER );
        $wporg_atts = shortcode_atts(
            array(
                'user_role_for_social_media'=> '',
                'redirect_url' => ''
            ), $atts, $tag
        );
      
        $redirect_url = $wporg_atts['redirect_url'];
        $user_role = $wporg_atts['user_role_for_social_media'];
        
        global $post;
        $current_user = wp_get_current_user();
        $login_form_body = stripcslashes(get_option("_mipluf_user_login_form"));
        $enable_shortcode = get_option('_mipluf_disable_login_recaptcha');
        $social_media_logins = array('mipluf_google_login_button', 'mipluf_sm_facebook_login_button', 'mipluf_sm_linkedin_login_button', 'mipluf_sm_microsoft_login_button',
        'mipluf_sm_github_login_button', 'mipluf_sm_instagram_login_button', 'mipluf_sm_amazon_login_button', 'mipluf_sm_yahoo_login_button',
        'mipluf_sm_slack_login_button', 'mipluf_sm_twitter_login_button', 'mipluf_sm_apple_login_button');

        foreach($social_media_logins as $social_login){
            $rep_data = "[$social_login user_role_for_social_media=$user_role redirect_url=$redirect_url]";
            $login_form_body = str_replace("[$social_login]", $rep_data, $login_form_body);
        }

        $login_form_body = str_ireplace('[field ', '[mipluf_form_field ', $login_form_body);
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');

        if($enable_recaptcha != 'enable'){
            $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
        }

        if($enable_shortcode != 'enable'){
            $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
        }

        $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');

        ob_start();
        ?>
        <form method="POST" action="" id="mipluf_user_login_page_form" class="mipluf_user_login_page_form">

            <?php
            if($current_user->ID != 0){
                ?>
                <p><?php esc_html_e('Welcome', 'mipl-wp-user-forms');?>, <?php echo esc_html($current_user->display_name);?>,
                <a href="<?php echo esc_url(wp_logout_url( get_permalink() )); ?>"><?php esc_html_e('Logout', 'mipl-wp-user-forms'); ?></a></p>

                <p>&nbsp;</p>
                <p>&nbsp;</p>
                <?php

            }else{?>
           
                <?php echo apply_filters('the_content', $login_form_body ); 

                if($mipluf_recaptcha_v3 == "v3" && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($login_form_body, '[mipluf_recaptcha]') !== false ){?>
                    <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                    <?php
                }
                ?>

                <div class="mipluf_p_wrap">
                    <p><a href="#forgot-password" onclick="jQuery('#mipluf_user_login_page_form').hide(0); jQuery('#mipluf_forgotpassword_pageform').show(0); return false;"><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms') ?></a></p>
                </div>

                <div class="">
                    <div class="" style="width:48%; float:right; text-align:right;">
                        <input type="hidden" name="custom_login" value="true">
                        <input type="hidden" name="redirect_page" value="<?php echo esc_html(get_option('_mipluf_login_redirect_page')); ?>">
                    </div>
                </div>
                
                <div class="mipluf_alert"></div>
                <?php
            }
            ?>

            <?php wp_nonce_field( 'mipluf_user_login_form', 'mipluf_user_login_form_nonce' ); ?>

        </form>

        <?php do_action('mipluf_user_otp');
        
        if($mipluf_recaptcha_v3 == 'v3'  && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable'){
            $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
            $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
            if(!empty($recaptchav3)){
                ?>
                <script>
                    jQuery(document).ready(function(){
                        grecaptcha.ready(function() {
                            grecaptcha.execute('<?php echo esc_html($recaptchav3); ?>', {action: 'submit'}).then(function(token) {
                                jQuery('.g-recaptcha-response').val(token);
                            });
                        });
                    });
                </script>
                <?php
            }
        }
        ?>

        <form id="mipluf_forgotpassword_pageform" method="POST" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword'));?>" class="mipluf_forgotpassword_pageform" style="display: none;">
            
            <h2><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms')?>:</h2>
            <label><?php esc_html_e('Email', 'mipl-wp-user-forms');?>*:<br><input type="email" name="user_login" placeholder=<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms');?> required/></label><br>
            <span class="-message" style="font-size:12px;"><?php esc_html_e('Note: Check your email for the confirmation link, after click on "Get New Password"!', 'mipl-wp-user-forms'); ?></span>
           
            <div class="mipluf_p_wrap" style="padding: 15px 0;">
                <p><a href="#login" onclick="jQuery('#mipluf_user_login_page_form').show(0); jQuery('#mipluf_forgotpassword_pageform').hide(0); return false;"><?php esc_html_e('Login', 'mipl-wp-user-forms') ?></a></p>
            </div>
            <div class="">
                <div class="">
                    <button type="submit" class="mipluf_button_small"><?php esc_html_e('Get New Password', 'mipl-wp-user-forms') ?></button>
                </div>
            </div>

            <div class="mipluf_alert"></div>

        </form>

        <?php
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }
    

    // Recaptcha
    function mipluf_recaptcha_shortcode(){

        ob_start();
        
        $recaptchav2_site_key = get_option("_mipluf_recaptchaV2_site_key");
        $recaptcha_rand_id = mipluf_rand();

        if(!empty($recaptchav2_site_key)){

            $mipluf_recaptcha_v2=get_option('_mipluf_recaptcha');
        
            if($mipluf_recaptcha_v2 == 'v2'){
                ?>
                <div class="g-recaptcha" id="g-recaptcha_<?php echo esc_html($recaptcha_rand_id)?>" data-sitekey="<?php echo esc_html($recaptchav2_site_key);?>" style="margin-top:10px; width:100%; float: left;"></div>
                <?php 
            }
        }
        
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }


    // Registration Form 
    function mipluf_registration_form($atts = [], $content = null, $tag = ''){

        global $post;
        $current_user = wp_get_current_user();
        $attrs = array_change_key_case( (array) $atts, CASE_LOWER );
        $wporg_atts = shortcode_atts(
            array(
                'id' => '',
            ), $atts, $tag
        );
        
        $form_body = "";
        $form_post = get_post($wporg_atts['id']);
        $enable_shortcode = get_post_meta($wporg_atts['id'],'_mipluf_disable_recaptcha',true);

        if(!empty($form_post)){

            $form_body = $form_post->post_content;
            $form_body = str_ireplace('[field ', '[mipluf_form_field ', $form_body);
            $form_body = str_ireplace('[mipluf_form_field ', '[mipluf_form_field form_id="'.$form_post->ID.'" ', $form_body);

            $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
            if($enable_recaptcha != 'enable'){
                $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
            }

            if($enable_shortcode != 'enable'){
                $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
            }
            
        }

        ob_start();
        if(!empty($form_post) && $form_post->post_status == 'publish'){

            $mipfuf_user_role = get_post_meta( $form_post->ID, '_mipluf_form_user_role', true);
            $mipluf_reg_page_url = get_post_meta( $form_post->ID, '_mipluf_reg_page_url', true);
            $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
            ?>
            <div class="mipluf_registrations_forms">
                <form method="POST" action="" id="mipluf_user_register_pageform" class="mipluf_user_register_pageform">
                
                    <span class="mipluf_error"></span>
                    
                    <?php echo apply_filters('the_content', $form_body );?>
    
                    <div class="">
                        <?php
                        $user_action = 'mipluf_register_user';
                        if( $current_user->ID != 0  && $mipfuf_user_role == $current_user->roles[0]){
                            $user_action = 'mipluf_update_user';
                        }
                        if($mipluf_recaptcha_v3 == "v3" && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($form_body, '[mipluf_recaptcha]') !== false ){?>
                            <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                            <?php
                        }
                        ?>

                        <input type="hidden" name="mipluf_action" value="<?php echo esc_html($user_action); ?>">
                        <input type="hidden" name="mipluf_register_form_id" value="<?php echo esc_html($form_post->ID); ?>">
                        
                    </div>
                    <div class="mipluf_error_alert"></div>

                    <?php wp_nonce_field( 'mipluf_user_register_form', 'mipluf_user_register_form_nonce' ); ?>

                </form>
             </div>
            <?php

            if($mipluf_recaptcha_v3 == 'v3' && $enable_shortcode == 'enable'  && $enable_recaptcha == 'enable'  && strpos($form_body, '[mipluf_recaptcha]') !== false  ){
                $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
                $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
                if(!empty($recaptchav3)){
                    ?>
                    <script>
                        jQuery(document).ready(function(){
                            grecaptcha.ready(function() {
                                grecaptcha.execute('<?php echo esc_html($recaptchav3); ?>', {action: 'submit'}).then(function(token) {
                                    jQuery('.g-recaptcha-response').val(token);
                                });
                            });
                        });
                    </script>
                    <?php
                }
            }
            
        }

        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }

    
    // Custom Registration Form Fields
    function mipluf_form_field($atts = [], $content = null, $tag = ''){

        global $post;
        $current_user = wp_get_current_user();
        $attrs = array_change_key_case( (array) $atts, CASE_LOWER );
        $wporg_atts = shortcode_atts(
            array(
                'name'=>'',
                'form_id'=>'',
                'type'=>'',
                'label'=>'',
                'id'=>'',
                'title'=>'',
                'class'=>'',
            ), $atts, $tag
        );

        $fields = get_post_meta( $wporg_atts['form_id'], '_mipluf_reg_custom_field', true);
        $current_user = wp_get_current_user();
        $user_role = get_post_meta( $wporg_atts['form_id'], '_mipluf_form_user_role', true);

        $error_keys = array();
        if (isset($fields['errors'])) {
            $array_values = array_values($fields['errors']);
            
            if (is_array($array_values)) {
                foreach ($array_values as $subArray) {
                    if (is_array($subArray)) {
                        foreach ($subArray as $key => $value) {
                            $error_keys[$key] = $key;
                        }
                    }
                }
            }
            
        }
        
        ob_start();


        if($wporg_atts['name'] == 'user_login'){?>
            <input type="text" name="user_login" placeholder="<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms');?>"  autocomplete="off">
            <?php
        }
        
        if($wporg_atts['name'] == 'user_password'){?>
            <input type="password" name="user_password" placeholder="<?php esc_attr_e('Example@1234', 'mipl-wp-user-forms');?>"  autocomplete="off">
            <?php
        }

        if($wporg_atts['name'] == 'rememberme'){?>
            <div class="remember">
                <label><input type="checkbox" name="rememberme" value="forever"><?php esc_html_e('Remember Me', 'mipl-wp-user-forms') ?></label>
            </div>
            <?php
        }

        if($wporg_atts['name']=='user_email'){?>
            <input type="text" name="user_email" placeholder="<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms');?>"  autocomplete="off">
            <?php
        }
        
        if($wporg_atts['name']=='user_pass'){
            if($current_user->ID != 0 && $current_user->roles[0] == $user_role){
                ?>
                <a href="#mipluf_update_user_modal" style="width: 97%; margin-bottom: 15px; display:inline-block;" class="mipluf_update_user_modal" data-modal="#mipluf_update_user_modal"><?php esc_html_e('Update Password', 'mipl-wp-user-forms'); ?></a>
                <?php
            }else{
                ?>
                <input type="password" name="user_pass" placeholder="<?php esc_attr_e('Example@1234', 'mipl-wp-user-forms'); ?>"  autocomplete="off">
                <?php
            }
        }

        if($wporg_atts['name']=='first_name'){?>
            <input type="text" name="first_name" placeholder="<?php esc_attr_e('Enter First Name', 'mipl-wp-user-forms');?>">
            <?php
        }

        if($wporg_atts['name']=='last_name'){?>
            <input type="text" name="last_name"  placeholder="<?php esc_attr_e('Enter Last Name', 'mipl-wp-user-forms');?>">
            <?php
        }

        if($wporg_atts['name']=='authentication_enable_setting'){
            do_action('mipluf_form_field');
        }
        
        if($current_user->ID != 0 && $current_user->roles[0] == $user_role){
            $wporg_atts['title'] = esc_html('Update');
        }

        if($wporg_atts['type']=='submit'){?>
            <div><button type="submit" name="submit" class="btn button submit_btn"><?php echo esc_html($wporg_atts['title']) ?></button></div>
            <?php
        }
        
        if(!empty($fields)){
            if(isset($fields['field_type'])){
                foreach($fields['field_type'] as $field_key => $field_val){
                    
                    if(in_array($field_key, $error_keys)){
                        continue;
                    }
                    
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
                    
                    if(isset($fields['field_name'][$field_key])){
                        if($fields['field_name'][$field_key] == $wporg_atts['name']){
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
                                foreach($field_options as $key => $value){
                                    ?><span style="width:100%; display: -webkit-box;">
                                    <input type="<?php echo esc_html($field_type)?>" name="<?php echo esc_html($field_name);?>[]" <?php echo esc_attr($field_class);?> <?php echo esc_attr($field_id);?>  value="<?php echo esc_html($key);?>"/>
                                    <?php echo esc_html($value);?>
                                    </span><?php
                                }
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
                                <select name="<?php echo esc_html($field_name);?>"  <?php echo esc_attr($field_id);?> <?php echo esc_attr($field_class);?>>
                                    <?php
                                    foreach($field_options as $key => $value){
                                        ?>
                                        <option value="<?php echo esc_html($key)?>"><?php echo esc_html($value)?></option>
                                        <?php
                                    } ?>
                                </select>
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
                                foreach($field_options as $key => $value){
                                    ?><span style="width:100%; display: -webkit-box;">
                                    <input type="<?php echo esc_html($field_type)?>" name="<?php echo esc_html($field_name);?>" <?php echo esc_attr($field_class);?>  <?php echo esc_attr($field_id);?> value="<?php echo esc_html($key);?>"/>
                                    <?php echo esc_html($value);?>
                                    </span><?php 
                                }
                            }elseif($fields['field_type'][$field_key] == "textarea"){
                                ?>
                                <textarea name="<?php echo esc_html($field_name);?>" <?php echo esc_attr($field_class);?>  <?php echo esc_attr($field_id); if(!empty($field_placeholder)){ ?> placeholder="<?php echo esc_attr($field_placeholder); ?>"<?php } ?>></textarea>
                                <?php
                            }elseif(!empty($fields['field_type'][$field_key])){
                                ?>
                                <input type="<?php echo esc_html($field_type);?>" name="<?php echo esc_html($field_name);?>" <?php echo esc_attr($field_class);?>  <?php echo esc_attr($field_id); if(!empty($field_placeholder)){ ?> placeholder="<?php echo esc_attr($field_placeholder); ?>"<?php } ?>>
                                <?php
                            }
                        }
                    }
                    
                }
            }
           
        }

        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }


    // Registration and login form shortcode
    function mipluf_registration_login_form($atts = [], $content = null, $tag = ''){
        global $post;
        ob_start();
       
        ?>
        <div class="mipluf_registration_login_form">
            <div class="mipluf_reg">
            <?php
            $wporg_atts = shortcode_atts(
                array(
                    'id'=>'',
                    'redirect_url' => ''
                ), $atts, $tag
            );

            $user_role = get_post_meta($wporg_atts['id'],'_mipluf_form_user_role',true);
            $redirect_after_login = $wporg_atts['redirect_url'];
            $current_user = wp_get_current_user();
            
            $form_body = "";
            $form_post = get_post($wporg_atts['id']);
            $enable_shortcode = get_post_meta($wporg_atts['id'],'_mipluf_disable_recaptcha',true);
            
            if(!empty($form_post) && $form_post->post_status == 'publish'){

                $form_body = $form_post->post_content;
                $form_body = str_ireplace('[field ', '[mipluf_form_field ', $form_body);
                $form_body = str_ireplace('[mipluf_form_field ', '[mipluf_form_field form_id="'.$form_post->ID.'" ', $form_body);

                $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
                if($enable_recaptcha != 'enable'){
                    $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                }

                if($enable_shortcode != 'enable'){
                    $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                }

                $mipluf_reg_page_url = get_post_meta( $form_post->ID, '_mipluf_reg_page_url', true);
                $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
                ?>
                <div class="mipluf_registrations_forms">
                    <form method="POST" action="" id="mipluf_user_register_login_page_form" class="mipluf_user_register_login_page_form">
                        <?php 
                        if(isset($form_body)){
                            echo apply_filters('the_content', $form_body );
                        }
                        ?>
                        <div class="">
                            <?php
                            $mipfuf_user_role = get_post_meta( $form_post->ID, '_mipluf_form_user_role', true);
                                $user_action = 'mipluf_register_user';
                                if( $current_user->ID != 0 && $mipfuf_user_role == $current_user->roles[0]){
                                    $user_action = 'mipluf_update_user';
                                }

                                if($mipluf_recaptcha_v3 == "v3" && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($form_body, '[mipluf_recaptcha]') !== false ){ ?>
                                    <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                                    <?php
                                }
                            ?>
        
                            <input type="hidden" name="mipluf_action" value="<?php echo esc_html($user_action); ?>">
                            <input type="hidden" name="mipluf_register_form_id" value="<?php echo esc_html($form_post->ID) ?>">
                            
                        </div>
                        <div class="mipluf_error_alert"></div>

                        <?php wp_nonce_field( 'mipluf_user_register_form', 'mipluf_user_register_form_nonce' ); ?>

                    </form>
                </div>
                <?php
                
                if($mipluf_recaptcha_v3 == 'v3' && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable'){
                    $mipluf_recaptcha_v3  = get_option('_mipluf_recaptcha');
                    $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
                    if(!empty($recaptchav3)){
                        ?>
                        <script>
                            jQuery(document).ready(function(){
                                grecaptcha.ready(function() {
                                    grecaptcha.execute('<?php echo esc_html($recaptchav3); ?>', {action: 'submit'}).then(function(token) {
                                        jQuery('.g-recaptcha-response').val(token);
                                    });
                                });
                            });
                        </script>
                        <?php
                    }
                }
                
            }
            ?>
            </div>
            <div class="mipl_uf_login_form">
            <?php
            $login_form_body = stripcslashes(get_option("_mipluf_user_login_form")); 

            $social_media_logins = array('mipluf_google_login_button', 'mipluf_sm_facebook_login_button', 'mipluf_sm_linkedin_login_button', 'mipluf_sm_microsoft_login_button',
            'mipluf_sm_github_login_button', 'mipluf_sm_instagram_login_button', 'mipluf_sm_amazon_login_button', 'mipluf_sm_yahoo_login_button',
            'mipluf_sm_slack_login_button', 'mipluf_sm_twitter_login_button', 'mipluf_sm_apple_login_button');
    
            foreach($social_media_logins as $social_login){
                $rep_data = "[$social_login user_role_for_social_media=$user_role redirect_url=$redirect_after_login]";
                $login_form_body = str_replace("[$social_login]", $rep_data, $login_form_body);
            }
            $enable_shortcode = get_option('_mipluf_disable_login_recaptcha');
            $login_form_body = str_ireplace('[field ', '[mipluf_form_field ', $login_form_body);

            $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
            if($enable_recaptcha != 'enable'){
                $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
            }
            if($enable_shortcode != 'enable'){
                $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
            }
            $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
            ?>
            <form method="POST" action="" id="mipluf_user_register_login_form" class="mipluf_user_register_login_form">
               
                <?php $current_user = wp_get_current_user();
                if($current_user->ID != 0){
                    ?>
                    <p><?php esc_html_e('Welcome', 'mipl-wp-user-forms');?>, <?php echo esc_html($current_user->display_name);?>,
                    <a href="<?php echo esc_url(wp_logout_url( get_permalink() )); ?>"><?php esc_html_e('Logout', 'mipl-wp-user-forms') ?></a></p>
                    <?php
                }else{?>
                <?php echo apply_filters('the_content', $login_form_body );
                
                if($mipluf_recaptcha_v3 == "v3"  && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($login_form_body, '[mipluf_recaptcha]') !== false  ){?>
                    <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                    <?php
                }
                ?>
                
                <div class="mipluf_p_wrap" style="float: right;">
                    <p><a href="#forgot-password" onclick="jQuery('#mipluf_user_register_login_form').hide(0); jQuery('#mipluf_reg_login_forgotpassword_pageform').show(0); return false;"><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms') ?></a></p>
                </div>

                <div class="" style="width:48%; float:right; text-align:right;">
                    <div class="">
                        <input type="hidden" name="mipluf_action" value="mipluf_login_user">
                        <input type="hidden" name="redirect_page" value="<?php echo esc_html(get_option('_mipluf_login_redirect_page')); ?>">
                    </div>
                </div>
                
                <div class="mipluf_alert"></div>
                
                <?php
                }?>

                <?php wp_nonce_field( 'mipluf_user_login_form', 'mipluf_user_login_form_nonce' ); ?>

            </form>
            <?php do_action('mipluf_user_otp'); ?>
            <form id="mipluf_reg_login_forgotpassword_pageform" method="POST" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword'));?>" class="mipluf_reg_login_forgotpassword_pageform" style="display: none;">
               
                <h2><?php echo esc_html('Forgot Password:')?></h2>
                <label><?php esc_html_e('Email', 'mipl-wp-user-forms');?>*:<br><input type="email" name="user_login" placeholder="<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms'); ?>" required/></label><br>
                <span class="-message" style="font-size:12px;"><?php esc_html_e('Note: Check your email for the confirmation link, after click on "Get New Password"!', 'mipl-wp-user-forms'); ?></span>

                <div class="mipluf_p_wrap" style="padding: 15px 0;">
                    <p><a href="#login" onclick="jQuery('#mipluf_user_register_login_form').show(0); jQuery('#mipluf_reg_login_forgotpassword_pageform').hide(0); return false;"><?php esc_html_e('Login', 'mipl-wp-user-forms') ?></a></p>
                </div>
                <div class="">
                    <div class="">                        
                        <button type="submit" class="mipluf_button_small"><?php esc_html_e('Get New Password', 'mipl-wp-user-forms') ?></button>
                    </div>
                </div>
                <script>
                    jQuery(document).ready(function(){
                        jQuery('#mipluf_user_register_login_form br').remove();
                    })
                </script>
                <div class="mipluf_alert"></div>

            </form>
        </div>
        </div>
        <?php

        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;

    }
   
}
