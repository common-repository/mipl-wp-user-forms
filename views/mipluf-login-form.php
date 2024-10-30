<div class="wrap">
    <h2 class="wp-heading-inline"><?php esc_html_e('Login Form', 'mipl-wp-user-forms')?></h2>
    <form method="post" action="" id="mipluf_login_form" style="width:99%;">
        <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
            <div id="postbox-container-1" class="postbox-container">
                <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
                    <div id="submitdiv" class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Save Login Form', 'mipl-wp-user-forms'); ?></h2>
                        </div>
                        <div class="inside">
                        <div class="submitbox" id="submitpost">
                        <div id="minor-publishing">
                        <div id="misc-publishing-actions">
                            <p style="padding:0px 10px">
                                <b><?php esc_html_e('Add the Shortcode in page', 'mipl-wp-user-forms'); ?>:</b><br><?php echo esc_html('[mipluf_login_form]'); ?>
                            </p>
                        <div class="misc-pub-section curtime misc-pub-curtime">
                            <input type='hidden' name='mipluf_action' value='mipluf_save_login_form_settings' />
                            <input type="button" name="mipluf_save_login_form" id="mipluf_save_login_form" class="mipluf_save_login_form button-primary" value="<?php esc_attr_e('Save Settings', 'mipl-wp-user-forms') ?>" />
                        </div>
                        </div>
                        </div>
                        </div>
                        </div>
                    </div>
                    <div id="side-sortables" class="meta-box-sortables ui-sortable">
                        <div id="submitdiv" class="postbox ">
                        <div class="inside">
                        <div class="mipluf_help_box">
                            <p><strong class="help_box"><?php esc_html_e('Do you need help?', 'mipl-wp-user-forms'); ?></strong></p>
                            <ol>
                                <li>
                                    <b><a href=" https://developers.google.com/recaptcha/intro" target="_blank" style="background: ghostwhite;"><?php esc_html_e('How to get recaptcha API Keys?', 'mipl-wp-user-forms'); ?></a></b>
                                </li>
                            </ol>
                        </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="postbox-container-2" class="postbox-container mi-setting-box-1">
                <div id="user-login-tab" class="mipluf_login_form_settings">
                    <div class="mipluf_login_buttons" style="width:99%;">
                        <strong><?php esc_html_e('Fields', 'mipl-wp-user-forms'); ?>:</strong>
                        <div style="color: #646970;"><?php esc_html_e('Click the fields and add these fields in Editor.', 'mipl-wp-user-forms'); ?></div>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button" data-id=''  data-shortcode="[field name='user_login']"><?php esc_html_e('User Login', 'mipl-wp-user-forms'); ?></a>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button " data-id='' data-shortcode="[field name='user_password']"><?php esc_html_e('Password', 'mipl-wp-user-forms'); ?></a>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button " data-id='' data-shortcode="[mipluf_recaptcha]"><?php esc_html_e('reCAPTCHA', 'mipl-wp-user-forms'); ?></a>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button " data-id='' data-shortcode="[field name='rememberme']"><?php esc_html_e('RememberMe', 'mipl-wp-user-forms'); ?></a>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button " data-id='' data-shortcode='[field type="submit" title="Login"]'><?php esc_html_e('Login Button', 'mipl-wp-user-forms'); ?></a>
                        <a href="#" class="mipluf_add_default_login_fields mipluf_button " data-id='' data-shortcode="[mipluf_google_login_button]"><?php esc_html_e('Login by Google', 'mipl-wp-user-forms'); ?></a>
                        <?php do_action('mipluf_sm_logins'); ?>
                        <?php $content = stripcslashes(get_option("_mipluf_user_login_form"));?>

                        <textarea id="mipluf_user_login_form" name="mipluf_user_login_form"  style="display:none;"><?php echo esc_textarea( $content ); ?></textarea>
                    </div>
                    
                    <div id="submitdiv" class="postbox" style="margin-top: 20px;">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Settings', 'mipl-wp-user-forms')?></h2>
                        </div>
                        <?php
                        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
                        if($enable_recaptcha == 'enable'){
                            ?>
                            <div class="mipluf_disable_login_page_recaptcha">
                                <?php
                                $disable_recaptcha = get_option('_mipluf_disable_login_recaptcha');
                                $checked = "";
                                if($disable_recaptcha == 'disable'){
                                    $checked = "checked";
                                }
                                ?>
                                <strong><?php esc_html_e('Disable Recaptcha', 'mipl-wp-user-forms'); ?>:</strong><br>
                                <label><input type="checkbox" <?php echo esc_html($checked)?> name="mipluf_disable_login_recaptcha" value="disable" ><?php esc_html_e('Disable Recaptcha', 'mipl-wp-user-forms'); ?></label>
                            </div>
                            <?php
                        }
                        ?>

                        <div class="mipluf_login_redirection">
                            <strong class="mipluf_required_field"><?php esc_html_e('Default Redirection URL', 'mipl-wp-user-forms'); ?>:</strong><br>
                            <input type="text" name="mipluf_login_redirect_page" class="mipluf_login_redirect_page" style="width:97%;" value="<?php echo esc_html(get_option("_mipluf_login_redirect_page")); ?>" />
                        </div>
                        
                    </div>
                   
                </div><br>
                <input type='hidden' name='mipluf_action' value='mipluf_save_login_form_settings' />
                <input type="button" name="mipluf_save_login_form" id="mipluf_save_login_form" class="mipluf_save_login_form button-primary" value="<?php esc_attr_e('Save Settings', 'mipl-wp-user-forms') ?>" />
            </div>
        </div>
        </div>

    </form>
</div>
