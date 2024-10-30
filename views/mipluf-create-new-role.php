<div id="mipluf_modal" class="mipluf_popup_modal mipluf_users_role">
    <div class="mipluf_popup_dialog mipluf_popup_small">
        <a href="#" class="mipluf_close_modal mipluf_close_modal_button">×</a>
        <div class="mipluf_popup_content">
            <form method="post" action="" id="mipluf_add_role_setting" style="width:100%;">
                <h2 style="margin:0; margin-left: 15px; line-height: 2;"><?php esc_html_e('Add New Role', 'mipl-wp-user-forms'); ?></h2>
                <div class="mipluf_role">
                <strong><?php esc_html_e('Display Name', 'mipl-wp-user-forms'); ?>:</strong>
                <input type="text" name="mipluf_role_name" id="mipluf_role_name" style="width:100%;margin-bottom: 10px;" />
                <span style="color:red;" class="mipluf_err"></span>
                </div>
                <div class="mipluf_role">
                <strong><?php esc_html_e('Role Slug', 'mipl-wp-user-forms'); ?>:</strong>
                <input type="text" name="mipluf_role_slug" id="mipluf_role_slug" style="width:100%;margin-bottom: 10px;" />
                <span style="color:red;" class="mipluf_err"></span>
                </div>
                <div class="mipluf_role">
                <strong><?php esc_html_e('Capabilities of', 'mipl-wp-user-forms'); ?>:</strong>
                <select name="mipluf_copy_role_capabilities" id="mipluf_copy_role_capabilities" style="margin-bottom: 10px;width:100%;max-width:100%">
                    <option value=""><?php esc_html_e('Select Role', 'mipl-wp-user-forms');?></option>
                    <?php wp_dropdown_roles(); ?>
                </select>
                </div>
                <span style="color:red;" class="mipluf_err"></span>
                <div class="mipluf_add_role">
                    <button type="button" class="mipluf_add_Role"><?php esc_attr_e('Add Role', 'mipl-wp-user-forms') ?></button>
                </div>

                <?php wp_nonce_field( 'mipluf_add_role_setting', 'mipluf_add_role_setting_nonce' ); ?>
                
            </form>
        </div>
    </div>
</div>
