<div id="mipluf_update_user_modal" class="mipluf_toggal_role_modal mipluf_popup_modal mipluf_update_user_">
    <div class="mipluf_popup_dialog mipluf_popup_small">
        <a href="#" class="mipluf_close_modal mipluf_close_modal_button">×</a>
        <div class="mipluf_popup_content" style="padding:0;">
            <form method="post" action="" id="mipluf_update_user_setting" style="width:100%;">
                <h2 style="margin: 0; font-size: 22px; padding-top: 10px; padding-left: 10px;"><?php esc_html_e('Update Password', 'mipl-wp-user-forms'); ?></h2>
                
                <div class="mipluf_role" style="padding:5px 15px">
                    <label><strong><?php esc_html_e('Password', 'mipl-wp-user-forms');?>:</strong>
                    <input type="password" name="user_pass" id="user_pass" style="width: 97%; padding: 5px;" placeholder="<?php esc_attr_e('Example@1234', 'mipl-wp-user-forms');?>" autocomplete="off"></label>
                </div>
                <div class="mipluf_role" style="padding:5px 15px">
                    <label><strong><?php esc_html_e('New Password', 'mipl-wp-user-forms');?>:</strong>
                    <input type="password" name="new_password" id="new_password" style="width: 97%; padding: 5px;" placeholder="<?php esc_attr_e('Example@1234', 'mipl-wp-user-forms');?>" autocomplete="off"></label>
                </div>

                <div class="mipluf_role" style="padding:5px 15px">
                    <label><strong><?php esc_html_e('Confirm Password', 'mipl-wp-user-forms');?>:</strong>
                    <input type="password" name="confirm_password" id="confirm_password" style="width: 97%; padding: 5px;"  placeholder="<?php esc_attr_e('Example@1234', 'mipl-wp-user-forms');?>" autocomplete="off"></label>
                </div>
                
                <div class="mipluf_role" style="padding:0 15px">
                    <button type="submit" style="padding:5px 15px; margin-bottom:10px; margin-top:10px" class="button button-primary"><?php esc_html_e('Submit', 'mipl-wp-user-forms');?></button>
                </div>

                <div class="mipluf_update_user_error_massage mipluf_error" style="padding:0 15px; margin-bottom:15px;">
                </div>

                <?php wp_nonce_field( 'mipluf_update_user_setting', 'mipluf_update_user_setting_nonce' ); ?>

            </form>
        </div>
    </div>
</div>
