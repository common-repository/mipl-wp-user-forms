<?php
    $data = $user_role = $redirect_after_login = "";
    if(!empty($_SESSION['mipluf_social_media_data'])){
        $data = $_SESSION['mipluf_social_media_data'];
        $user_role = $data['user_role_for_social_media'];
        $redirect_after_login = $data['redirect_url'];
    }
?>

<div class="mipluf_popup_modal" id="mipluf_login_modal">
    <div class="mipluf_popup_dialog mipluf_popup_small">
        <a href="#" class="mipluf_close_modal mipluf_close_modal_button">
            &times;
        </a>
        <div class="mipluf_popup_content">
            <?php 
            $current_user = wp_get_current_user();
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
            <form method="POST" action="" id="mipluf_user_login_form_modal" class="mipluf_user_login_form_modal">
                <div class="mipluf_login_popup">
                <?php
                if($current_user->ID != 0){?>
                   <p><?php echo esc_html('Welcome');?>, <?php echo esc_html($current_user->display_name);?>,
                    <a href="<?php echo esc_url(wp_logout_url( get_permalink() )); ?>"><?php esc_html_e('Logout', 'mipl-wp-user-forms'); ?></a></p>
                    <?php
                    
                }else{?>
               
                    <?php echo apply_filters('the_content', $login_form_body); 
                    if($mipluf_recaptcha_v3 == "v3" && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($login_form_body, '[mipluf_recaptcha]') !== false ){?>
                        <input type="hidden" class="g-recaptcha-response" name="g-recaptcha-response">
                        <?php
                    }
                    ?>

                    <div class="mipluf_p_wrap" style="float: right;">
                        <p><a href="#forgot-password" onclick="jQuery('#mipluf_user_login_form_modal').hide(0); jQuery('#mipluf_login_modal_forgotpassword_pageform').show(0); return false;"><?php esc_html_e('Forgot Password', 'mipl-wp-user-forms'); ?></a></p>
                    </div>

                    <div class="" style="width:48%; float:right; text-align:right;">
                        <div class="">
                            <input type="hidden" name="mipluf_action" value="mipluf_login_user">
                            <input type="hidden" name="redirect_page" value="<?php echo esc_html(get_option('_mipluf_login_redirect_page')); ?>">
                        </div>
                    </div>
                    <script>
                        jQuery(document).ready(function(){
                            jQuery('#mipluf_user_login_form_modal br').remove();
                        })
                    </script>
                    <div class="mipluf_alert"></div>
                    <?php 
                }?>
                </div>

                <?php wp_nonce_field( 'mipluf_user_login_form', 'mipluf_user_login_form_nonce' ); ?>
                
            
            </form>

            <?php do_action('mipluf_user_otp');
            
            if($mipluf_recaptcha_v3 == 'v3' && $enable_shortcode == 'enable' && $enable_recaptcha == 'enable'){
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

            <form id="mipluf_login_modal_forgotpassword_pageform" method="POST" action="<?php echo esc_url(site_url('wp-login.php?action=lostpassword'));?>" class="mipluf_login_modal_forgotpassword_pageform" style="display: none;">
        
                <h2><?php echo esc_html_e('Forgot Password', 'mipl-wp-user-forms')?>:</h2>
                <label><?php esc_html_e('Email', 'mipl-wp-user-forms');?>*:<br><input type="email" name="user_login" placeholder="<?php esc_attr_e('Enter Email', 'mipl-wp-user-forms');?>" required/></label><br>
                <span class="-message" style="font-size:12px;"><?php esc_html_e('Note: Check your email for the confirmation link, after click on "Get New Password"!', 'mipl-wp-user-forms'); ?></span>
                
                <div class="mipluf_p_wrap" style="padding: 15px 0;">
                    <p><a href="#login" onclick="jQuery('#mipluf_user_login_form_modal').show(0); jQuery('#mipluf_login_modal_forgotpassword_pageform').hide(0); return false;"><?php esc_html_e('Login', 'mipl-wp-user-forms'); ?></a></p>
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