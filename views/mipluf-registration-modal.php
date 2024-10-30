<?php
$current_user = wp_get_current_user();
$mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
$enable_recaptcha = get_option('_mipluf_enable_recaptcha');

if(!empty($_SESSION['mipluf_reg_forms'])){
    $reg_forms = mipluf_sanitize_numric_array($_SESSION['mipluf_reg_forms']);
}
$enable_login_shortcode = "";
if(isset($reg_forms)){
    foreach($reg_forms as $reg_form){
        $form_post = get_post($reg_form);
        $enable_login_shortcode = "";
        ?>
        <div class="mipluf_popup_modal" id="mipluf_registration_modal_<?php echo esc_html($form_post->ID);?>">
            <div class="mipluf_popup_dialog mipluf_popup_small">
                <a href="#" class="mipluf_close_modal mipluf_close_modal_button">
                    &times;
                </a>
                <div class="mipluf_popup_content">
                    <?php
                    $current_user = wp_get_current_user();
                    
                    $form_body = "";
                    if(!empty($form_post) && $form_post->post_status == 'publish'){
                        $form_body = $form_post->post_content;
                        $form_body = str_ireplace('[field ', '[mipluf_form_field ', $form_body);
                        $form_body = str_ireplace('[mipluf_form_field ', '[mipluf_form_field form_id="'.$form_post->ID.'" ', $form_body);
                        
                        if($enable_recaptcha != 'enable'){
                            $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                        }

                        $enable_login_shortcode = get_post_meta($form_post->ID,'_mipluf_disable_recaptcha',true);
                        if($enable_login_shortcode != 'enable'){
                            $form_body = str_ireplace('[mipluf_recaptcha]', '', $form_body);
                        }

                    }
                    ?>
                    
                    <div class="mipluf_registrations_forms">
                        <form method="POST" action="" id="mipluf_user_register_page_form" class="mipluf_user_register_page_form">
                            <?php
                            echo apply_filters( 'the_content', $form_body ); ?>
                            <div class="">
                                <?php
                                $mipfuf_user_role = get_post_meta( $form_post->ID, '_mipluf_form_user_role', true);

                                $user_action = 'mipluf_register_user';
                                if( $current_user->ID != 0 && $mipfuf_user_role == $current_user->roles[0]){
                                    $user_action = 'mipluf_update_user';
                                }
                                if($mipluf_recaptcha_v3 == "v3" && $enable_login_shortcode == 'enable' && $enable_recaptcha == 'enable' && strpos($form_body, '[mipluf_recaptcha]') !== false ){?>
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
            </div>
        </div>
        <?php
    }
}

if($mipluf_recaptcha_v3 == 'v3' && $enable_login_shortcode == 'enable' && $enable_recaptcha == 'enable' ){
    $mipluf_recaptcha_v3 = get_option('_mipluf_recaptcha');
    $recaptchav3 = get_option("_mipluf_recaptchaV3_site_key");
    if(!empty($recaptchav3)){
        ?>
        <script>
            jQuery(document).ready(function(){
                grecaptcha.ready(function() {
                    grecaptcha.execute('<?php echo esc_html($recaptchav3); ?>', {action: 'submit'}).then(function(token) {
                        jQuery('.register-page-form-g-recaptcha-response').val(token);
                    });
                });
            });
        </script>
        <?php
    }
}