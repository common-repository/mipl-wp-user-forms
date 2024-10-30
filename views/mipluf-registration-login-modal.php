<?php
$current_user = wp_get_current_user();

if($mipluf_recaptcha_v3 == 'v3'){
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

if(!empty($_SESSION['mipluf_reg_login_forms'])){
    $reg_forms = mipluf_sanitize_numric_array($_SESSION['mipluf_reg_login_forms']);
}

if(!empty($_SESSION['mipluf_redirect_after_login'])){
    $redirect_after_login = $_SESSION['mipluf_redirect_after_login'];
}

if(isset($reg_forms)){
    foreach($reg_forms as $reg_form){

        $form_post = get_post($reg_form);
        $user_role = get_post_meta($form_post->ID,'_mipluf_form_user_role',true);
        ?>
        <div class="mipluf_popup_modal" id="mipluf_registration_login_modal_<?php echo esc_html($form_post->ID);?>">
            <div class="mipluf_popup_dialog mipluf_popup_small mipluf_registration_login">
                <a href="#" class="mipluf_close_modal mipluf_close_modal_button">
                    &times;
                </a>
                <div class="mipluf_popup_content">
                    <div class="mipluf_reg_modal" id="mipluf_reg_modal">
                        <?php
                        $current_user = wp_get_current_user();
                        
                        $form_body = "";
                        if(!empty($form_post) && $form_post->post_status == 'publish'){
                            $form_body = $form_post->post_content;
                            $form_body = str_ireplace('[field ', '[mipluf_form_field ', $form_body);
                            $form_body = str_ireplace('[mipluf_form_field ', '[mipluf_form_field form_id="'.$form_post->ID.'" ', $form_body);

                            $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
                            if($enable_recaptcha != 'enable'){
                                $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                            }

                            $enable_shortcode = get_post_meta($form_post->ID,'_mipluf_disable_recaptcha',true);
                            if($enable_shortcode != 'enable'){
                                $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                            }

                        }
                        ?>
                        <div class="mipluf_registrations_forms">
                            <form method="POST" action="" id="mipluf_user_register_login_page_modal" class="mipluf_user_register_login_page_modal">
                                <?php echo apply_filters( 'the_content', $form_body ); ?>
                                <div class="">
                                    <?php
                                    $mipfuf_user_role = get_post_meta( $form_post->ID, '_mipluf_form_user_role', true);
                                    $user_action = 'mipluf_register_user';
                                    if( $current_user->ID != 0 && $mipfuf_user_role == $current_user->roles[0]){
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
                    </div>
                    
                    <div class="mipluf_login_form_modal">
                        <?php
                        $login_form_body = stripcslashes(get_option("_mipluf_user_login_form"));
                        $social_media_logins = array('mipluf_google_login_button', 'mipluf_sm_facebook_login_button', 'mipluf_sm_linkedin_login_button', 'mipluf_sm_microsoft_login_button',
                        'mipluf_sm_github_login_button', 'mipluf_sm_instagram_login_button', 'mipluf_sm_amazon_login_button', 'mipluf_sm_yahoo_login_button',
                        'mipluf_sm_slack_login_button', 'mipluf_sm_twitter_login_button', 'mipluf_sm_apple_login_button');

                        foreach($social_media_logins as $social_login){
                            $rep_data = "[$social_login user_role_for_social_media=$user_role redirect_url=$redirect_after_login]";
                            $login_form_body = str_replace("[$social_login]", $rep_data, $login_form_body);
                        }

                        $login_form_body = str_ireplace('[field ', '[mipluf_form_field ', $login_form_body);

                        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
                        if($enable_recaptcha != 'enable'){
                            $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
                        }

                        $enable_shortcode = get_option('_mipluf_disable_login_recaptcha');
                        if($enable_shortcode != 'enable'){
                            $login_form_body = str_ireplace('[mipluf_recaptcha]', '', $login_form_body);
                        }

                        $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
                        ?>
                        <form method="POST" action="" id="mipluf_user_register_login_form_modal" class="mipluf_user_register_login_form_modal">
                        
                            <?php
                            if($current_user->ID != 0){?>
                                <p><?php echo __('Welcome', 'mipl-wp-user-forms');?>, <?php echo esc_html($current_user->display_name);?>,
                                <a href="<?php echo esc_url(wp_logout_url( get_permalink() )); ?>"><?php esc_html_e('Logout', 'mipl-wp-user-forms'); ?></a></p>
                                <?php
                            }else{?>
                                <?php echo apply_filters('the_content', $login_form_body ); 
                                if($mipluf_recaptcha_v3 == "v3"  && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($login_form_body, '[mipluf_recaptcha]') !== false  ){?>
                                    <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                                    <?php
                                }
                                ?>
                                <div class="mipluf_p_wrap" style="float: right;">
                                <p><a href="#forgot-password" onclick="jQuery('.mipluf_user_register_login_form_modal').hide(0); jQuery('.mipluf_reg_login_modal_forgotpassword_pageform').show(0); return false;"><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms'); ?></a></p>
                                </div>

                                <div class="" style="width:48%; float:right; text-align:right;">
                                    <div class="">
                                        <input type="hidden" name="mipluf_action" value="mipluf_login_user">
                                        <input type="hidden" name="redirect_page" value="<?php echo esc_html(get_option('_mipluf_login_redirect_page')); ?>">
                                    </div>
                                </div>
                                <script>
                                    jQuery(document).ready(function(){
                                        jQuery('#mipluf_user_register_login_form_modal br').remove();
                                    });
                                </script>
                                <div class="mipluf_alert"></div>
                                <?php do_action('mipluf_user_otp');
                            }?>

                            <?php wp_nonce_field( 'mipluf_user_login_form', 'mipluf_user_login_form_nonce' ); ?>

                        </form>
    
                        <form id="mipluf_reg_login_modal_forgotpassword_pageform" method="POST" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword'));?>" class="mipluf_reg_login_modal_forgotpassword_pageform" style="display: none;">
                            
                            <h2><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms')?>:</h2>
                            <label><?php esc_html_e('Email', 'mipl-wp-user-forms');?>*:<br><input type="email" name="user_login" placeholder="<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms');?>" required/></label><br>
                            <span class="-message" style="font-size:12px;"><?php esc_html_e('Note: Check your email for the confirmation link, after click on "Get New Password"!', 'mipl-wp-user-forms'); ?></span>
                            
                            <div class="mipluf_p_wrap" style="padding: 15px 0;">
                                <p><a href="#login" onclick="jQuery('#mipluf_user_register_login_form_modal').show(0); jQuery('#mipluf_reg_login_modal_forgotpassword_pageform').hide(0); return false;"><?php esc_html_e('Login', 'mipl-wp-user-forms'); ?></a></p>
                            </div>
                            <div class="">
                                <div class="">
                                    <button type="submit" class="mipluf_button_small"><?php esc_html_e('Get New Password', 'mipl-wp-user-forms'); ?></button>
                                </div>
                            </div>
    
                            <div class="mipluf_alert"></div>
    
                        </form>
    
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
