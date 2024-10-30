<div class="wrap">
    <h2 class="wp-heading-inline"><?php esc_html_e('User Forms Settings', 'mipl-wp-user-forms')?></h2>
    <form method="post" action="" id="mipluf_form_setting" class="mipluf_form_setting" style="width:100%;">
    <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
        <div id="postbox-container-1" class="postbox-container">
        <div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
        <div id="submitdiv" class="postbox ">
            <div class="postbox-header">
                <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Save User Settings', 'mipl-wp-user-forms')?></h2>
            </div>
            <div class="inside">
            <div class="submitbox" id="submitpost">
            <div id="minor-publishing">
            <div id="misc-publishing-actions">
            <div class="misc-pub-section curtime misc-pub-curtime">
                <input type='hidden' name='mipluf_action' value='mipluf_save_all_setting' />
                <input type="button" name="mipluf_save_setting" id="mipluf_save_setting" class="mipluf_save_setting button-primary" value="<?php esc_attr_e('Save Settings', 'mipl-wp-user-forms') ?>" />
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
        <div id="submitdiv" class="postbox">
        <div class="postbox-header">
            <h2 class="hndle ui-sortable-handle"><?php esc_html_e('Users Form Settings', 'mipl-wp-user-forms')?></h2>
        </div>
        <div id="mipluf_setting_tabs" class="mipluf_users_forms_tabs">
            <ul class="label">
                <li><a href="#email-tab" class="mipl_uf_setting"><?php esc_html_e('Email', 'mipl-wp-user-forms'); ?></a></li>
                <li><a href="#restriction-tab" class="mipl_uf_setting"><?php esc_html_e('Page Restrictions', 'mipl-wp-user-forms'); ?></a></li>
                <li><a href="#recaptcha-tab" class="mipl_uf_setting"><?php esc_html_e('reCAPTCHA', 'mipl-wp-user-forms'); ?></a></li>
                <?php do_action('mipluf_settings_tabs_after'); ?>
                <?php do_action('mipluf_sm_settings_tabs_after'); ?>
                <li><a href="#help-tab" class="mipl_uf_setting"><?php esc_html_e('Help', 'mipl-wp-user-forms'); ?></a></li>
            </ul>
            
            <div id="email-tab" class="mipluf_setting_general_tabs" style="display: none;">
                <div class="mipluf_general_tab_urls_content">
                    <strong><?php esc_html_e('Mail Settings', 'mipl-wp-user-forms')?></strong>
                    <strong><?php esc_html_e('User Activation Email', 'mipl-wp-user-forms'); ?>:</strong>
                    <span style="font-size:11px; "><?php esc_html_e('Tags: [email], [activation_link], [first_name], [last_name]'); ?></span><br>
                    <div>
                        <?php
                        $content = stripcslashes(get_option("_mipluf_supplier_activation_email"));
                        $editor_id = 'mipluf_supplier_activation_email';
                        ?>
                        <textarea name="mipluf_supplier_activation_email" id="mipluf_supplier_activation_email"><?php echo esc_textarea($content);?></textarea>
                    </div>
                </div>
                <div class="mipluf_general_tab_urls_content">
                    <strong><?php esc_html_e('Admin mail to', 'mipl-wp-user-forms'); ?>:</strong>
                    <input type="text" name="mipluf_admin_mail_to"  value="<?php echo esc_html(get_option("_mipluf_admin_mail_to")); ?>" />
                </div>
                <strong style="margin-left:20px; margin-top: 8px;"><?php esc_html_e('Admin Notify Email', 'mipl-wp-user-forms'); ?>:</strong>
                <span style="font-size:11px;margin-left:20px;"><?php esc_html_e('Tags: [email], [first_name], [last_name], [approve_link], [reject_link]'); ?></span>
                <div style="padding:0px 20px;padding-bottom: 10px;">
                    <?php
                    $content = stripcslashes(get_option("_mipluf_supplier_notify_email"));
                    $editor_id = 'mipluf_supplier_notify_email';
                    ?>
                    <textarea name="mipluf_supplier_notify_email" id="mipluf_supplier_notify_email"><?php echo esc_textarea($content);?></textarea>
                </div>
            </div>

            <div id="restriction-tab" class="mipluf_setting_general_tabs" style="display: none;">
                <div class="restriction_tab_content">
                    <?php $mipluf_pages_restriction = get_option('_mipluf_page_restriction'); ?>
                    <div class="restriction">
                        <div class="mipluf_edit_restriction_form_row">
                            <div class="mipluf_edit_field" style="width:40%;display: inline-block;padding-left: 5px;"><strong><?php esc_html_e('Role', 'mipl-wp-user-forms'); ?></strong></div>
                            <div class="mipluf_edit_field" style="width:35%;display: inline-block;text-align: left;"><strong><?php esc_html_e('Page', 'mipl-wp-user-forms'); ?></strong></div>
                            <div class="mipluf_edit_field" style="width:18%;display: inline-block;text-align: right;"><strong><?php esc_html_e('Action', 'mipl-wp-user-forms'); ?></strong></div>
                        </div>
                        <?php 
                        
                        if(isset($mipluf_pages_restriction['mipluf_default_pages'])){

                            foreach($mipluf_pages_restriction['mipluf_default_pages'] as $key => $value ){

                                $error = "";
                                $style = "";

                                $error_index = array();
                                $user_role_error_massage = $user_page_error_massage = "";
                                $user_role_error_class = $user_page_error_class =  "";

                                if(isset($mipluf_pages_restriction['errors']['mipluf_default_user_role'][$key])){
                                    $error_index[] = $key;
                                    $user_role_error_class = "border:1px solid red";
                                    $user_role_error_massage = $mipluf_pages_restriction['errors']['mipluf_default_user_role'][$key];
                                }
        
                                if(isset($mipluf_pages_restriction['errors']['mipluf_default_pages'][$key])){                                    
                                    $error_index[] = $key;
                                    $user_page_error_class = "border:1px solid red";
                                    $user_page_error_massage =  $mipluf_pages_restriction['errors']['mipluf_default_pages'][$key];
                                }

                                $error_style = "";
                                if(in_array($key, $error_index)){
                                    $error_style = "border-left:3px solid red";
                                }
                                ?>
                                
                                <div class="mipluf_edit_restriction_form_row" style="<?php echo esc_attr($error_style); ?>">
                                    <div class="mipluf_field_data">
                                        
                                        <div class="mipluf_edit_field" style="width:40%;;display: inline-block;"><span class="mipluf_restriction_span"><?php echo esc_html(ucfirst($mipluf_pages_restriction['mipluf_default_user_role'][$key]))?></span></div>
                                        <div class="mipluf_edit_field" style="width:40%;display: inline-block;text-align: left;"><span class="mipluf_restriction_span"><?php echo esc_html(get_the_title($mipluf_pages_restriction['mipluf_default_pages'][$key]))?></span></div>
                                        <div class="mipluf_edit_field"style="width:18%;display: inline-block;text-align: right;">
                                            <div style="">
                                                <a href="#" class="mipluf_edit_page_restriction_field " style="margin-right:30px;color: black;"><?php esc_html_e('Edit', 'mipl-wp-user-forms'); ?></a>
                                                <a href="#" class="mipluf_delete_page_restriction_field " style="color: black;"><?php esc_html_e('Delete', 'mipl-wp-user-forms'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mipluf_edit_form_row" style="display:none;">
                                        <div class="mipluf_add_dropdown" style="">
                                        
                                            <strong><?php esc_html_e('User Role', 'mipl-wp-user-forms'); ?>:</strong>
                                            <select name="mipluf_page_restriction[mipluf_default_user_role][]" id="mipluf_page_restriction" class="mipluf_user_role" style="<?php echo esc_attr($user_role_error_class);?>">
                                            <option><?php esc_html_e('Select Role', 'mipl-wp-user-forms');?></option>
                                                <?php
                                                    $roles = get_option('wp_user_roles');
                                                    foreach($roles as $role => $values){
                                                        $selected = "";
                                                        if($mipluf_pages_restriction['mipluf_default_user_role'][$key] == $role){
                                                        $selected = 'selected';
                                                        }?>
                                                        <option value="<?php echo esc_html($role);?>" <?php echo esc_html($selected) ?>><?php echo esc_html($values['name']);?></option><?php
                                                    }
                                                ?>
                                            </select>

                                            <span class="mipluf_restriction_error"><?php echo esc_html($user_role_error_massage); ?></span>

                                            <strong><?php esc_html_e('User Page', 'mipl-wp-user-forms'); ?>:</strong><?php
                                            $args = array(
                                                'post_type' => 'page',
                                                'posts_per_page' => -1
                                            );
                                            $info = get_posts($args);?>
                                            <select name="mipluf_page_restriction[mipluf_default_pages][]" class="mipluf_user_pages" style="<?php echo esc_attr($user_page_error_class);?>">
                                            <option><?php esc_html_e('Select Page', 'mipl-wp-user-forms');?></option>
                                            <?php
                                                foreach($info as $page){
                                                    $selected = "";
                                                    if($mipluf_pages_restriction['mipluf_default_pages'][$key] == $page->ID){
                                                        $selected = 'selected';
                                                    }
                                                    ?>
                                                    <option value="<?php echo esc_html($page->ID)?>" <?php echo esc_html($selected) ?>><?php echo esc_html($page->post_title);?></option>
                                                    <?php
                                                }?>
                                            </select>
                                            <span class="mipluf_restriction_error"><?php echo esc_html($user_page_error_massage); ?></span>
                                            
                                            <div style="margin-top:20px;margin-bottom:20px;">
                                                <a class="mipluf_close_edit_button button"><?php esc_html_e('Done', 'mipl-wp-user-forms'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div><?php
                            }

                        }?>
                    </div>
                    <div style="margin-left:17px;margin-top:15px;margin-bottom: 15px;">
                        <a class="mipluf_resriction button"><?php esc_html_e('Add More', 'mipl-wp-user-forms'); ?></a>
                    </div>
                </div>
            </div>

            <script>
                jQuery('.mipluf_resriction').on('click', function() {
                    var $append_data = `<div class="mipluf_edit_restriction_form_row">
                        <div class="mipluf_field_data">
                            <div class="mipluf_edit_field" style="width:40%;display: inline-block;"><span class="mipluf_restriction_span"><?php esc_html_e('(Role)', 'mipl-wp-user-forms');?></span></div>
                            <div class="mipluf_edit_field" style="width:37%;display: inline-block;"><span class="mipluf_restriction_span"><?php esc_html_e('(Page)', 'mipl-wp-user-forms');?></span></div>
                            <div class="mipluf_edit_field"style="width:21%;display: inline-block;text-align: right;">
                                <div style="">
                                    <a href="#" class="mipluf_edit_page_restriction_field " style="margin-right:30px;color: black;"><?php esc_html_e('Edit', 'mipl-wp-user-forms');?></a>
                                    <a href="#" class="mipluf_delete_page_restriction_field " style="color: black;"><?php esc_html_e('Delete', 'mipl-wp-user-forms');?></a>
                                </div>
                            </div>
                        </div>
                        <div class="mipluf_edit_form_row"
                            <div class="mipluf_add_dropdown">
                                <strong><?php esc_html_e('User Role', 'mipl-wp-user-forms');?>:</strong>
                                <select name="mipluf_page_restriction[mipluf_default_user_role][]" class="mipluf_user_role">
                                    <option><?php esc_html_e('select Role', 'mipl-wp-user-forms');?></option><?php
                                    $roles = get_option('wp_user_roles');
                                    foreach($roles as $role => $values ){
                                        $user_roles = $values['name'];?>
                                        <option value="<?php echo esc_html($role);?>"><?php echo esc_html($user_roles);?></option><?php
                                    }?>
                                </select>
                                <strong><?php esc_html_e('User Page', 'mipl-wp-user-forms');?>:</strong><?php
                                $args = array(
                                    'post_type' => 'page',
                                    'posts_per_page' => -1
                                );
                                $info = get_posts($args);?>
                                <select name="mipluf_page_restriction[mipluf_default_pages][]" class="mipluf_user_pages" >
                                    <option><?php esc_html_e('select page', 'mipl-wp-user-forms');?></option><?php
                                    foreach($info as $page){
                                        ?>
                                        <option value="<?php echo esc_html($page->ID);?>"><?php echo esc_html($page->post_title);?></option><?php
                                    }?>
                                </select>
                                <div style="margin-top:20px;padding-bottom: 20px;">
                                    <a class="mipluf_remove_button button"><?php esc_html_e('Remove', 'mipl-wp-user-forms');?></a>
                                    <a class="mipluf_close_button button"><?php esc_html_e('Done', 'mipl-wp-user-forms');?></a>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    jQuery(document).find('.restriction').append($append_data);
                });
                jQuery('.restriction').on('click', 'a.mipluf_edit_page_restriction_field', function() {
                    jQuery(this).parents('.mipluf_edit_restriction_form_row').find('.mipluf_edit_form_row').slideToggle(500);
                    return false;
                });

                jQuery('.restriction').on('click', 'a.mipluf_remove_button', function() {
                    jQuery(this).parents('.mipluf_edit_restriction_form_row').remove();
                    return false;
                });

                jQuery('.restriction').on('click', 'a.mipluf_delete_page_restriction_field', function(){
                    var cnfrm = confirm("Are you sure to delete this field.");
                    if(cnfrm){
                        jQuery(this).parents(".mipluf_edit_restriction_form_row").remove();
                    }
                    return false;
                });

                jQuery('.restriction').on('click', '.mipluf_close_button', function() {
                    jQuery(this).parents('.mipluf_edit_form_row').slideToggle();
                    return false;
                });

                jQuery('.restriction').on('click', '.mipluf_close_edit_button', function() {
                    jQuery(this).parents('.mipluf_edit_form_row').slideToggle();
                    return false;
                });

            </script>
            <script>

                jQuery('body').on('focusout','select[name="mipluf_page_restriction[mipluf_default_user_role][]"]',function(){
                    var $name = jQuery(this).find('option:selected').text();
                    jQuery(this).parents('.mipluf_edit_restriction_form_row').find('.mipluf_edit_field:nth-child(1) .mipluf_restriction_span').text($name);
                });

                jQuery('body').on('change','select[name="mipluf_page_restriction[mipluf_default_pages][]"]',function(){ 
                    var $label = jQuery(this).find('option:selected').text();
                    var $parent = jQuery(this).parents('.mipluf_edit_restriction_form_row').find('.mipluf_edit_field:nth-child(2) .mipluf_restriction_span').text($label);
                });

            </script>
            <div id="recaptcha-tab" class="mipluf_setting_general_tabs mipluf_reset_recaptcha_keys" style="display: none;">
                <div class="mipluf_enable_recaptcha">
                    <?php
                    $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
                    $checked = "";
                    if($enable_recaptcha == 'enable'){
                        $checked = "checked";
                    }
                    ?>
                    <input type="checkbox" <?php echo esc_html($checked) ?> name="mipluf_enable_recaptcha" id="mipluf_enable_recaptcha" value="enable" style="width:0px; margin-top: 11px;">
                    <label for="mipluf_enable_recaptcha"><?php esc_html_e('Enable Recaptcha', 'mipl-wp-user-forms'); ?></label>
                </div>
                <div class="mipluf_general_tab_urls_content">
                    <div class="recaptcha_type">
                    <strong class="mipluf_required_field"><?php esc_html_e('reCAPTCHA Type', 'mipl-wp-user-forms'); ?></strong>
                    <select class="mipluf_recaptcha" name="mipluf_recaptcha" style="min-width: 100%;">
                        <?php 
                        $recaptcha = get_option('_mipluf_recaptcha');
                        $option_array = array( 'select' => 'Select type', 'v2'=>'Recaptcha v2','v3'=>'Recaptcha v3');
                        
                        foreach($option_array as $value => $title){
                            $selected = "";
                            if($recaptcha == $value){
                                $selected = 'selected';
                            }
                            ?>
                            <option value="<?php echo esc_html($value) ?>" <?php echo esc_html($selected) ?>><?php echo esc_html($title)?></option>
                            <?php
                        }
                        ?>
                    </select>
                    </div><br>
                </div>
                <div class="mipluf_recaptcha_keys">
                    <div class="recaptcha_keyV2" style="display:none;">
                        <div class="mipluf_general_tab_urls_content">
                            <?php
                            $recaptchaV2_site_key=get_option("_mipluf_recaptchaV2_site_key");
                            $v2_site_key_name = 'mipluf_recaptchaV2_site_key';
                            $v2_site_key_val = '';
                            $v2_site_readonly = '';
                            if( !empty($recaptchaV2_site_key) ){
                                $v2_site_key_val = substr($recaptchaV2_site_key,0,4).'********************'.substr($recaptchaV2_site_key,-4,4);
                                $v2_site_readonly = 'readonly="readonly"';
                            }
                            ?>
                            <strong class="mipluf_required_field"><?php esc_html_e('Site Key V2 ', 'mipl-wp-user-forms'); ?>:</strong>
                            <input name="<?php echo esc_html($v2_site_key_name);?>" id="mipluf_recaptchaV2_site_key" type="text"  value="<?php echo esc_html($v2_site_key_val);?>" <?php echo esc_html($v2_site_readonly);?> />
                            
                        </div>
                        <div class="mipluf_general_tab_urls_content">
                            <?php
                            $recaptchaV2_secrate_key = get_option("_mipluf_recaptchaV2_secrate_key");
                            $v2_secret_key_name = 'mipluf_recaptchaV2_secrate_key';
                            $v2_secret_key_val = '';
                            $v2_secret_readonly = '';
                            if( !empty($recaptchaV2_secrate_key) ){
                                $v2_secret_key_val = substr($recaptchaV2_secrate_key,0,4).'********************'.substr($recaptchaV2_secrate_key,-4,4);
                                $v2_secret_readonly = 'readonly="readonly"';
                            }
                            ?>
                            <strong class="mipluf_required_field"><?php esc_html_e('Secrate Key V2 ', 'mipl-wp-user-forms'); ?>:</strong>
                            <input name="<?php echo esc_html($v2_secret_key_name);?>" id="mipluf_recaptchaV2_secrate_key" type="text"  value="<?php echo esc_html($v2_secret_key_val);?>" <?php echo esc_html($v2_secret_readonly);?> />
                        </div>
                        <?php
                        $display = "display:none;";
                        if(!empty($recaptchaV2_secrate_key) || !empty($recaptchaV2_site_key)){
                            $display = "display:block;";
                        }
                        ?>
                        <div class="mipluf_v2_reset_button" style="<?php echo esc_attr($display); ?>">
                            <button type="button" class="reset_recaptchav2_keys button" style="margin: 14px 20px; "><?php esc_html_e('Reset', 'mipl-wp-user-forms'); ?></button>
                        </div>
                    </div>

                    <div class="recaptcha_keyV3" style="display:none;">
                        <div class="mipluf_general_tab_urls_content">
                            <?php
                            $recaptchaV3_site_key = get_option("_mipluf_recaptchaV3_site_key");
                            $v3_site_key_name = 'mipluf_recaptchaV3_site_key';
                            $v3_site_key_val = '';
                            $v3_site_readonly = '';
                            if( !empty($recaptchaV3_site_key) ){
                                $v3_site_key_val = substr($recaptchaV3_site_key,0,4).'********************'.substr($recaptchaV3_site_key,-4,4);
                                $v3_site_readonly = 'readonly="readonly"';
                            }
                            ?>
                            <strong class="mipluf_required_field"><?php esc_html_e('Site Key V3', 'mipl-wp-user-forms'); ?>:</strong>
                            <input name="<?php echo esc_html($v3_site_key_name);?>" id="mipluf_recaptchaV3_site_key" type="text"  value="<?php echo esc_html($v3_site_key_val);?>" <?php echo esc_html($v3_site_readonly);?> />
                            
                        </div>
                        <div class="mipluf_general_tab_urls_content">
                            <?php
                            $recaptchaV3_secrate_key = get_option("_mipluf_recaptchaV3_secrate_key");
                            $v3_secret_key_name = 'mipluf_recaptchaV3_secrate_key';
                            $v3_secret_key_val = '';
                            $v3_secret_readonly = '';
                            if( !empty($recaptchaV3_secrate_key) ){
                                $v3_secret_key_val = substr($recaptchaV3_secrate_key,0,4).'********************'.substr($recaptchaV3_secrate_key,-4,4);
                                $v3_secret_readonly = 'readonly="readonly"';
                            }
                            ?>
                            <strong class="mipluf_required_field"><?php esc_html_e('Secrate Key V3', 'mipl-wp-user-forms'); ?>: </strong>
                            <input name="<?php echo esc_html($v3_secret_key_name);?>" id="mipluf_recaptchaV3_secrate_key" type="text"  value="<?php echo esc_html($v3_secret_key_val);?>" <?php echo esc_html($v3_secret_readonly);?> />
                        </div>
                        <?php
                        $display = "display:none;";
                        if(!empty($recaptchaV3_secrate_key) || !empty($recaptchaV3_site_key)){
                            $display = "display:block;";
                        }
                        ?>
                        <div class="mipluf_v3_reset_button" style="<?php echo esc_attr($display); ?>">
                        <button type="button" class="mipl_uf_reset_recaptchav3_keys button" style="margin: 14px 22px;"><?php esc_html_e('Reset', 'mipl-wp-user-forms'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
           
            <?php do_action('mipluf_settings_tabs_content_after'); ?>
            <?php do_action('mipluf_sm_settings_tabs_content_after'); ?>

            <div id="help-tab" class="mipluf_setting_general_tabs" style="display: none;">
                <div class="help_content" style="line-height: 2; padding-top: 9px;">
                    <strong style="margin-left: 20px;"><?php esc_html_e('Shortcodes', 'mipl-wp-user-forms'); ?></strong>
                    <div class="mipluf_help_content">
                        <span>1) <b><?php esc_html_e('Login Button Shortcode', 'mipl-wp-user-forms'); ?>:</b><br><?php echo esc_html('[mipluf_login_button]'); ?></span> <br />
                    </div>
                    <div class="mipluf_help_content">
                        <span>2) <b><?php esc_html_e('Login and Registration Button Shortcode', 'mipl-wp-user-forms'); ?>: </b><br><?php esc_html_e('[mipluf_login_registration_button]'); ?></span> <br />e.g: <?php echo esc_html('[mipluf_login_registration_button form_id="form_id"]'); ?>
                    </div>
                    <div class="mipluf_help_content">
                        <span>3) <b><?php esc_html_e('Login Links Shortcode', 'mipl-wp-user-forms'); ?>:</b><br><?php esc_html_e('[mipluf_user_login_links]'); ?></span> <br />e.g: <?php echo esc_html('[mipluf_user_login_links form_id="form_id"]'); ?>
                    </div>
                    <div class="mipluf_help_content">
                        <span>4) <b><?php esc_html_e('Login Form Shortcode', 'mipl-wp-user-forms'); ?>:</b><br><?php echo esc_html('[mipluf_login_form]'); ?></span> <br />
                    </div>
                    <div class="mipluf_help_content">
                        <span>5) <b><?php esc_html_e('Registration Form Shortcode', 'mipl-wp-user-forms'); ?>:</b><br><?php esc_html_e('[mipluf_registration_form]'); ?></span> <br />e.g: <?php echo esc_html('[mipluf_registration_form id="form_id"]'); ?>
                    </div>
                    <div style="padding: 8px 20px;">
                        <span>6) <b><?php esc_html_e('Login and Registration Form Shortcode', 'mipl-wp-user-forms'); ?>:</b><br><?php esc_html_e('[mipluf_login_registration_form]'); ?></span> <br />e.g: <?php echo esc_html('[mipluf_login_registration_form id="form_id"]'); ?>
                    </div>
                </div>
            </div>
            
        </div>
        </div>
        </div>
    </div>
    </div>

    </form>
</div>

<!-- recaptcha -->
<script>
    
    jQuery(document).ready(function(){
        var recaptcha = jQuery(".mipluf_recaptcha").val();
        if(recaptcha == 'v2'){
            jQuery('.recaptcha_keyV2').css('display','block');
            jQuery('.recaptcha_keyV3').css('display','none');
        }
        if(recaptcha == 'v3'){
            jQuery('.recaptcha_keyV3').css('display','block');
            jQuery('.recaptcha_keyV2').css('display','none');
        }
    });

    jQuery(".mipluf_recaptcha").on('change',function(){
        var reCAPTCHA = jQuery(this).val();
        if(reCAPTCHA =='v2'){
            jQuery('.recaptcha_keyV2').css('display','block');
            jQuery('.recaptcha_keyV3').css('display','none');
        }
        if(reCAPTCHA =='v3'){
            jQuery('.recaptcha_keyV3').css('display','block');
            jQuery('.recaptcha_keyV2').css('display','none');
        }
    });

    // social media buttons
    jQuery(document).ready(function(){
        var button_type = jQuery(".mipluf_button_type").val();
        if(button_type == 'default'){
            jQuery('.default_buttons').css('display','block');
            jQuery('.custom_buttons').css('display','none');
            jQuery('.icon').css('display','none');
        }
        if(button_type == 'custom'){
            jQuery('.custom_buttons').css('display','block');
            jQuery('.default_buttons').css('display','none');
            jQuery('.icon').css('display','none');
        }
        if(button_type == 'icon'){
            jQuery('.icon').css('display','block');
            jQuery('.default_buttons').css('display','none');
            jQuery('.custom_buttons').css('display','none');
        }
    });

    jQuery(".mipluf_button_type").on('change',function(){
        var button_type = jQuery(this).val();
        if(button_type == 'default'){
            jQuery('.default_buttons').css('display','block');
            jQuery('.custom_buttons').css('display','none');
            jQuery('.icon').css('display','none');
        }
        if(button_type == 'custom'){
            jQuery('.custom_buttons').css('display','block');
            jQuery('.default_buttons').css('display','none');
            jQuery('.icon').css('display','none');
        }
        if(button_type == 'icon'){
            jQuery('.icon').css('display','block');
            jQuery('.default_buttons').css('display','none');
            jQuery('.custom_buttons').css('display','none');
        }
    });
</script>


<script>
    jQuery(document).ready(function(){
        
        var $active_tab = localStorage.getItem('mipluf_setting_active_tab');
        if( $active_tab == null ){
            $active_tab = '#email-tab';
        }
        mipluf_change_tab($active_tab);
        
        jQuery('.label li a').click(function(){
            var $tab = jQuery(this).attr('href');
            return mipluf_change_tab($tab);
        });
        
        function mipluf_change_tab($tab){
            
            $tab = $tab.replaceAll('#','');
            
            if( jQuery('#'+$tab).length <= 0 && jQuery('#email-tab').length <= 0 ){
                return false;
            }else if(jQuery('#'+$tab).length <= 0 && jQuery('#email-tab').length != 0){
                $tab = 'email-tab';
            }
            
            jQuery('.label li').removeClass('mipluf_tab_active');
            jQuery('.label li a[href=#'+$tab+']').parent('li').addClass('mipluf_tab_active');
            
            jQuery('.mipluf_users_forms_tabs .mipluf_setting_general_tabs').hide(0);
            jQuery('.mipluf_users_forms_tabs .mipluf_setting_general_tabs#'+$tab).show(0);
            
            localStorage.setItem('mipluf_setting_active_tab',$tab);
            return false;
            
        }
        
    });
</script>