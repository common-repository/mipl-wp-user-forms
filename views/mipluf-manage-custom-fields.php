<?php $mipluf_custom_field = get_post_meta($post->ID,'_mipluf_reg_custom_field',true); ?>

<div class="mipluf_field_table">
    
    <div class="mipluf_edit_row">
        <div class="mipluf_extra_custom_fields"><strong><?php esc_html_e('Type', 'mipl-wp-user-forms'); ?></strong></div>
        <div class="mipluf_extra_custom_fields"><strong><?php esc_html_e('Label', 'mipl-wp-user-forms'); ?></strong></div>
        <div class="mipluf_extra_custom_fields"><strong><?php esc_html_e('Name', 'mipl-wp-user-forms'); ?></strong></div>
        <div class="mipluf_extra_custom_fields"><strong><?php esc_html_e('Shortcode', 'mipl-wp-user-forms'); ?></strong></div>
        <div class="mipluf_extra_custom_fields" style="margin-left:-24px; text-align: right;"><strong><?php esc_html_e('Actions', 'mipl-wp-user-forms'); ?></strong></div>
    </div>
    <?php 
    
    if(isset($mipluf_custom_field['field_label'])){

        foreach($mipluf_custom_field['field_label'] as $key=>$value ){

            $label_field_error = isset($mipluf_custom_field['errors']['field_label'][$key])?$mipluf_custom_field['errors']['field_label'][$key]:"";
            $name_field_error = isset($mipluf_custom_field['errors']['field_name'][$key])?$mipluf_custom_field['errors']['field_name'][$key]:"";
            $type_field_error = isset($mipluf_custom_field['errors']['field_type'][$key])?$mipluf_custom_field['errors']['field_type'][$key]:"";
            $id_field_error = isset($mipluf_custom_field['errors']['field_id'][$key])?$mipluf_custom_field['errors']['field_id'][$key]:"";
            $class_field_error = isset($mipluf_custom_field['errors']['field_class'][$key])?$mipluf_custom_field['errors']['field_class'][$key]:"";
            $placeholder_field_error = isset($mipluf_custom_field['errors']['field_placeholder'][$key]) ? $mipluf_custom_field['errors']['field_placeholder'][$key]:"";
            $option_field_error = isset($mipluf_custom_field['errors']['field_option'][$key]) ? $mipluf_custom_field['errors']['field_option'][$key]:"";

            $error_mark = "";
            $label_error = "";
            if(isset($mipluf_custom_field['errors']['field_label']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_label']))){
                $label_error = "mipluf_label_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $name_error="";
            if(isset($mipluf_custom_field['errors']['field_name']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_name']))){
                $name_error = "mipluf_name_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $type_error="";
            if(isset($mipluf_custom_field['errors']['field_type']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_type']))){
                $type_error = "mipluf_type_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $id_error="";
            if(isset($mipluf_custom_field['errors']['field_id']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_id']))){
                $id_error = "mipluf_id_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $class_error="";
            if(isset($mipluf_custom_field['errors']['field_class']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_class']))){
                $class_error = "mipluf_class_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $placeholder_error="";
            if(isset($mipluf_custom_field['errors']['field_placeholder']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_placeholder']))){
                $placeholder_error = "mipluf_placeholder_error";
                $error_mark = "mipluf_custom_field_error";
            }

            $option_error="";
            if(isset($mipluf_custom_field['errors']['field_option']) && in_array($key,array_keys($mipluf_custom_field['errors']['field_option']))){
                $option_error = "mipluf_option_error";
                $error_mark = "mipluf_custom_field_error";
            }
            ?>
            <div class="mipluf_edit_row <?php echo esc_html($error_mark); ?>">
                <div class="mipluf_field_data">
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php if(isset($mipluf_custom_field['field_type'][$key])){ echo esc_attr(ucfirst($mipluf_custom_field['field_type'][$key]));}?></span></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_edit_field"><?php if(isset($mipluf_custom_field['field_label'][$key])){ echo esc_attr($mipluf_custom_field['field_label'][$key]);}?></span></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php if(isset($mipluf_custom_field['field_name'][$key])){ echo esc_attr(esc_attr($mipluf_custom_field['field_name'][$key]));}?></span></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php if(isset($mipluf_custom_field['field_name'][$key])){ echo esc_attr("[field name='".$mipluf_custom_field['field_name'][$key]."']");}?></span></div>
                    <div class="mipluf_extra_custom_fields">
                        <a href="#" class="mipluf_delete_field mipl_uf_edit_field"><?php esc_html_e('Delete', 'mipl-wp-user-forms'); ?></a> 
                        <a href="#" class="mipluf_edit_field mipl_uf_edit_field"><?php esc_html_e('Edit', 'mipl-wp-user-forms'); ?></a>
                    </div>
                </div>
                <div class="mipluf_edit_registration_form_row" style="display:none; margin-bottom:20px;">
                    <div class="mipluf_required_form_field">
                        <label for="mipluf_field_type"><?php esc_html_e('Field Type', 'mipl-wp-user-forms'); ?>:</label><br>
                        <select id="mipluf_field_type" name="mipluf_field[field_type][]" class="<?php echo esc_html($type_error) ?>">
                            <option><?php esc_html_e('Select Type', 'mipl-wp-user-forms');?></option>
                            <?php $options = array('text','tel','number','textarea','checkbox','radio','select');
                                foreach($options as $option){
                                    $selected = "";
                                    if($mipluf_custom_field['field_type'][$key] == $option){
                                        $selected = 'selected';
                                    }
                                    ?>
                                    <option value="<?php echo esc_html($option);?>" <?php echo esc_html($selected) ?>><?php echo esc_html(ucfirst($option))?></option>
                                    <?php
                                }
                            ?>
                        </select>
                        <span class="mipluf_reg_form_error"><?php echo esc_html($type_field_error);?></span>
                    </div>
                    
                    <div class="mipluf_required_form_field">
                        <label><?php esc_html_e('Field Label', 'mipl-wp-user-forms'); ?>:</label><input type="text" name="mipluf_field[field_label][]" id="mipluf_field_label" class="mipluf_field_input <?php echo esc_html($label_error) ?>" value="<?php if(isset($mipluf_custom_field['field_label'][$key])){ echo esc_html($mipluf_custom_field['field_label'][$key]);}?>" />
                        <span class="mipluf_reg_form_error"><?php echo esc_html($label_field_error);?></span>
                    </div>
                    <div class="mipluf_required_form_field">
                        <label><?php esc_html_e('Field Name', 'mipl-wp-user-forms'); ?>:</label><input type="text" name="mipluf_field[field_name][]" id="mipluf_field_name" class="mipluf_field_name <?php echo esc_html($name_error) ?>" value="<?php if(isset($mipluf_custom_field['field_name'][$key])){ echo esc_html($mipluf_custom_field['field_name'][$key]);}?>" />
                        <span class="mipluf_reg_form_error"><?php echo esc_html($name_field_error);?></span>
                    </div>

                    <?php
                        if(isset($mipluf_custom_field['field_type'][$key])){
                            $type = $mipluf_custom_field['field_type'][$key];
                        }
                        if($type == "select" || $type == "radio" || $type == "checkbox"){?>
                            <div class="mipluf_field_options">
                                <div class="mipluf_required_form_field">
                                    <label><?php esc_html_e('Field Options', 'mipl-wp-user-forms'); ?>:</label><br><?php esc_html_e('[Note: One option per line.]', 'mipl-wp-user-forms'); ?><br><textarea id="mipluf_field_option" name="mipluf_field[field_option][]" class="mipluf_field_option <?php echo esc_html($option_error) ?>" ><?php if(isset($mipluf_custom_field['field_option'][$key])){ echo esc_html($mipluf_custom_field['field_option'][$key]);}?></textarea>
                                    <span class="mipluf_reg_form_error"><?php echo esc_html($option_field_error);?></span>
                                </div>
                            </div><?php
                        }else{
                            ?>
                            <div class="mipluf_field_options" style="display:none">
                                <div class="mipluf_required_form_field">
                                    <label><?php esc_html_e('Field Options', 'mipl-wp-user-forms'); ?>:</label><br><?php esc_html_e('[Note: One option per line.]', 'mipl-wp-user-forms'); ?><br><textarea id="mipluf_field_option" name="mipluf_field[field_option][]" class="mipluf_field_option <?php echo esc_html($option_error) ?>" ></textarea>
                                    <span class="mipluf_reg_form_error"><?php echo esc_html($option_field_error);?></span>
                                </div>
                            </div><?php
                        }
                        
                    ?>

                    <div class="mipluf_optional_form_field">
                        <label><?php esc_html_e('Field Id', 'mipl-wp-user-forms'); ?>:<input type="text" name="mipluf_field[field_id][]" id="mipluf_field_id" class="mipluf_field_input <?php echo esc_html($id_error) ?>" value="<?php if(isset($mipluf_custom_field['field_id'][$key])){ echo esc_html($mipluf_custom_field['field_id'][$key]);}?>" /><span class="mipluf_reg_form_error"><?php echo esc_html($id_field_error);?></span></label>
                    </div>
                    <div class="mipluf_optional_form_field">
                        <label><?php esc_html_e('Field Class', 'mipl-wp-user-forms'); ?>:
                        <input type="text" name="mipluf_field[field_class][]" id="mipluf_field_class" class="mipluf_field_input <?php echo esc_html($class_error) ?>" value="<?php if(isset($mipluf_custom_field['field_class'][$key])){ echo esc_attr($mipluf_custom_field['field_class'][$key]);}?>" /><br><?php esc_html_e('[Note:you can added multiple classes with space seprated]', 'mipl-wp-user-forms'); ?><br><span class="mipluf_reg_form_error"><?php echo esc_html($class_field_error);?></span></label>
                    </div>
                    <div class="mipluf_optional_form_field">
                        <label><?php esc_html_e('Field Placeholder', 'mipl-wp-user-forms'); ?>:<input type="text" name="mipluf_field[field_placeholder][]" id="mipluf_field_placeholder" class="mipluf_field_input <?php echo esc_html($placeholder_error) ?>" value="<?php if(isset($mipluf_custom_field['field_placeholder'][$key])) {echo esc_html($mipluf_custom_field['field_placeholder'][$key]);}?>" /><span class="mipluf_reg_form_error"><?php echo esc_html($placeholder_field_error);?></span></label>                 
                    </div>
                    <div class="mipluf_optional_checkbox_field">
                        <?php
                        if(isset($mipluf_custom_field['field_name'][$key])){
                            $field_name = $mipluf_custom_field['field_name'][$key];
                        }
                        if(empty($mipluf_custom_field['field_required'])){
                            $mipluf_custom_field['field_required']= array();
                        }
                       
                        $checked = ""; 
                        if(!empty($field_name) && in_array($field_name, $mipluf_custom_field['field_required'])){
                            $checked = 'checked';
                        }
                        ?>
                        <label><input type="checkbox" <?php echo esc_html($checked)?> class="required_field" name="mipluf_field[field_required][]" id="mipluf_field_required" value="<?php echo esc_html($field_name) ?>" style="float:left; width:auto;"/>
                        <?php esc_html_e('Required', 'mipl-wp-user-forms'); ?></label>
                    </div>
                    <div style="margin-top:15px;">
                        <a class="mipluf_close_edit_field button"><?php esc_html_e('Close', 'mipl-wp-user-forms'); ?></a>
                    </div>
                    <input type="hidden" name='mipluf_register_form_id' value="<?php echo esc_html($post->ID);?>">
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>

<div style="margin:20px 10px;">
    <a class="mipluf_add_field button"><?php esc_html_e('Add New', 'mipl-wp-user-forms'); ?></a>
</div>

<script>
    jQuery(document).ready(function(){
        jQuery('.mipluf_add_field').on('click', function() {
        var $append_data = `<div class="mipluf_edit_row">
                <div class="mipluf_field_data">
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php echo esc_html__('(Type)', 'mipl-wp-user-forms');?></span></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php echo esc_html__('(Label)', 'mipl-wp-user-forms');?></a></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php echo esc_html__('(Name)', 'mipl-wp-user-forms');?></span></div>
                    <div class="mipluf_extra_custom_fields"><span class="mipluf_custom_field_span"><?php echo esc_html__('(shortcode)', 'mipl-wp-user-forms');?></span></div>
                    <div class="mipluf_extra_custom_fields">
                    <a href="#" class="mipluf_delete_field mipl_uf_edit_field"><?php echo esc_html__('Delete', 'mipl-wp-user-forms');?></a> 
                        <a href="#" class="mipluf_edit_field mipl_uf_edit_field"><?php echo esc_html__('Edit', 'mipl-wp-user-forms');?></a>
                    </div>
                </div>
                    
                <div class="mipluf_edit_registration_form_row">
                    <div class="mipluf_required_form_field">
                    <label for="mipluf_field_type"><?php echo esc_html__('Field Type', 'mipl-wp-user-forms');?>:</label>
                    <select id="mipluf_field_type" name="mipluf_field[field_type][]">
                    <option><?php echo esc_html__('Select Type', 'mipl-wp-user-forms');?></option>
                    <?php $options=array('text','tel','number','textarea','checkbox','radio','select');
                        foreach($options as $option){?>
                            <option value="<?php echo esc_html($option);?>"><?php echo esc_html(ucfirst($option))?></option><?php
                        }?>
                    </select>
                    </div>
                    <div class="mipluf_required_form_field">
                        <label><?php echo esc_html__('Field Label', 'mipl-wp-user-forms');?>:</label><input type="text" name="mipluf_field[field_label][]" id="mipluf_field_label" class="mipluf_field_input field_label" value=""/>
                    </div>
                    <div class="mipluf_required_form_field">
                        <label><?php echo esc_html__('Field Name', 'mipl-wp-user-forms');?>:</label><input type="text" name="mipluf_field[field_name][]" id="mipluf_field_name" class="mipluf_field_name field_name" value=""/>
                    </div>
                    <div class="mipluf_field_options" style="display:none">
                        <div class="mipluf_required_form_field">
                            <label><?php echo esc_html__('Field Options', 'mipl-wp-user-forms');?>:</label><br>[<?php echo esc_html__('Note', 'mipl-wp-user-forms');?>: <?php echo esc_html__('One option per line', 'mipl-wp-user-forms');?>.]<br><textarea id="mipluf_field_option" name="mipluf_field[field_option][]" class="mipluf_field_option" value="" /></textarea>
                        </div>
                     </div> 
                    <div class="mipluf_optional_form_field">
                        <label><?php echo esc_html__('Field Id', 'mipl-wp-user-forms');?>:<input type="text" name="mipluf_field[field_id][]" id="mipluf_field_id" class="mipluf_field_input" value=""/></label>
                    </div>
                    <div class="mipluf_optional_form_field">
                        <label><?php echo esc_html__('Field Class', 'mipl-wp-user-forms');?>:<input type="text" name="mipluf_field[field_class][]" id="mipluf_field_class" class="mipluf_field_input" value=""/>
                        <br>[<?php echo esc_html__('Note', 'mipl-wp-user-forms')?>: <?php echo esc_html__('you can added multiple classes with space seprated', 'mipl-wp-user-forms');?>]</label>
                    </div>
                    <div class="mipluf_optional_form_field">
                        <label><?php echo esc_html__('Field Placeholder', 'mipl-wp-user-forms');?>:<input type="text" name="mipluf_field[field_placeholder][]" id="mipluf_field_placeholder" class="mipluf_field_input" value=""/></label>
                    <div>
                    <div class="mipluf_optional_checkbox_field">
                        <label><input type="checkbox" name="mipluf_field[field_required][]" id="mipluf_field_required" class="required_field" value="" style="float:none; width:auto;margin-top: -3px;"/><?php echo esc_html__('Required', 'mipl-wp-user-forms');
                        ?></label>
                    </div>
                    <input type="hidden" name='mipluf_register_form_id' value="<?php echo esc_html($post->ID);?>">
                    <div style="margin-top:15px;">
                        <a class="mipluf_close_field button"><?php echo esc_html__('Close', 'mipl-wp-user-forms');?></a>
                    </div>
                </div>
            </div>`;

            jQuery(document).find('.mipluf_field_table').append($append_data);
            return false;

        });

    });

    jQuery('.mipluf_field_name').on('input', function () {
        var name = jQuery(this).val();
        jQuery(this).parents('.mipluf_edit_registration_form_row').find('.required_field').val(name)
    });

    jQuery('.mipluf_field_table').on('click', '.mipluf_close_field', function() {
        jQuery(this).parents('.mipluf_edit_registration_form_row').slideToggle();
        return false;
    });

    jQuery('.mipluf_field_table').on('click', '.mipluf_close_edit_field', function() {
        jQuery(this).parents('.mipluf_edit_registration_form_row').slideToggle();
        return false;
    });

    jQuery('.mipluf_field_table').on('click', 'a.mipluf_delete_field', function(){
        var cnfrm = confirm("Are you sure to delete this field.");
        if(cnfrm){
          jQuery(this).parents(".mipluf_edit_row").remove();
          
        }
        return false;
       
    });

    jQuery('.mipluf_field_table').on('click', 'a.mipluf_edit_field', function(){
        jQuery(this).parents('.mipluf_edit_row').find('.mipluf_edit_registration_form_row').slideToggle(500);
        return false;
    });

    jQuery(".mipluf_edit_added_field").on('click', function() {
        jQuery(this).parents('.mipluf_edit_row').find('.mipluf_field_data').slideToggle(500);
        return false;
    });

    jQuery('.mipluf_field_table').on('focusout','.field_label',function (){

        var $label = jQuery(this).val();
        $label = $label.split(' ').join('_');
        $label = $label.toLowerCase();
        $label = $label.replace(/[^\w\s]/g, '');
        $label = $label.replace(/^_+|_+$/g, '');

        var $field_name = jQuery(this).parents('.mipluf_edit_row').find(".field_name").val();
        
        if($field_name == ""){
            jQuery(this).parents('.mipluf_edit_row').find(".field_name").val($label);
        }

        jQuery(this).parents('.mipluf_edit_registration_form_row').find('.required_field').val($label);
    });

    jQuery('.mipluf_field_table').on('change','select[name="mipluf_field[field_type][]"]',function(){ 
        var $option = jQuery(this).val();
        if($option == 'select' || $option == 'radio' || $option == 'checkbox'){
            jQuery(this).parents('.mipluf_edit_registration_form_row').find('.mipluf_field_options').show();
        }else{
            jQuery(this).parents('.mipluf_edit_registration_form_row').find('.mipluf_field_options').hide();
        }
    });

</script>

<script>

    jQuery('body').on('change','select[name="mipluf_field[field_type][]"]',function(){ 
        var $label = jQuery(this).val();
        jQuery(this).parents('.mipluf_edit_row').find('.mipluf_extra_custom_fields:nth-child(1) .mipluf_custom_field_span').text($label);
    });

    jQuery('body').on('focusout','input[name="mipluf_field[field_label][]"]',function(){ 
        var $label = jQuery(this).val();
        jQuery(this).parents('.mipluf_edit_row').find('.mipluf_extra_custom_fields:nth-child(2) .mipluf_custom_field_span').text($label);
        var $value = jQuery(this).parents('.mipluf_edit_row').find('input[name="mipluf_field[field_name][]"]').val();
        jQuery(this).parents('.mipluf_edit_row').find('.mipluf_extra_custom_fields:nth-child(3) .mipluf_custom_field_span').text($value);
    });

    jQuery('body').on('focusout','input[name="mipluf_field[field_name][]"]',function(){ 
        var $name = jQuery(this).val();
        var $parent = jQuery(this).parents('.mipluf_edit_row').find('.mipluf_extra_custom_fields:nth-child(3) .mipluf_custom_field_span').text($name);
    });

    jQuery('body').on('focusout','input[name="mipluf_field[field_name][]"]',function(){ 
        var $name = jQuery(this).val();
        var $parent = jQuery(this).parents('.mipluf_edit_row').find('.mipluf_extra_custom_fields:nth-child(4) .mipluf_custom_field_span').text("[field name='"+$name+"']");
    });

</script>
