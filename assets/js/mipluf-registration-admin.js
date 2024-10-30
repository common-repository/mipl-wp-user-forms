/* Save Login Form */
jQuery(document).ready(function(){
    if( jQuery("#mipluf_login_form" ).length >= 1 ){
        jQuery('#mipluf_save_login_form.mipluf_save_login_form').click(function(){
            
            var $this = this;
            jQuery($this).val('Saving...');
            jQuery($this).prop('disabled',true);
            jQuery($this).css('cursor','progress');

            var $codemirror_value = document.querySelector('.CodeMirror').CodeMirror.getValue()
            jQuery('#mipluf_user_login_form').val($codemirror_value);

            jQuery.post( "", jQuery("#mipluf_login_form").serialize())
            .done(function( $data ){
              
                $data = JSON.parse($data);
                jQuery('.mipluf_my_notice').remove();
                if( $data.status == 'fail' ){
                    var msg= '';
                   
                    if($data.message){
                        msg= '<div class="notice notice-error is-dismissible mipluf_my_notice"><p>'+$data.message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                        jQuery('.wp-heading-inline').after(msg);
                    }
                }else{
                    if($data.message){
                        msg= '<div class="notice is-dismissible notice-success mipluf_my_notice"><p>'+$data.message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                        jQuery('.wp-heading-inline').after(msg);
                    }
                }
                

                if( $data.status == 'success' ){
                    jQuery($this).val('Saved!');
                }
                
                
                setTimeout(function(){
                    jQuery($this).val('Save Settings');
                }, 4000);

                
                jQuery($this).prop('disabled',false);
                jQuery($this).css('cursor','pointer');
                
            });
            
            return false;
            
        });
    }

    if( jQuery( "#mipluf_form_setting" ).length >= 1 ){
        jQuery('#mipluf_save_setting.mipluf_save_setting').click(function(){
            
            var $this = this;
            
            jQuery($this).val('Saving...');
            jQuery($this).prop('disabled',true);
            jQuery($this).css('cursor','progress');
            
            jQuery.post( "", jQuery( "#mipluf_form_setting" ).serialize())
            .done(function( $data ){

                $data = JSON.parse($data);
                
                var v2_key = jQuery('input[name="mipluf_recaptchaV2_site_key').val();
                var v2_value = v2_key.replace(/(.{4}).*(.{4})/, '$1********************$2');
                jQuery('input[name="mipluf_recaptchaV2_site_key"]').val(v2_value);
                var v2_key = jQuery('input[name="mipluf_recaptchaV2_secrate_key').val();
                var v2_value = v2_key.replace(/(.{4}).*(.{4})/, '$1********************$2');
                jQuery('input[name="mipluf_recaptchaV2_secrate_key"]').val(v2_value);
                if(v2_key){
                    jQuery('.mipluf_v2_reset_button').show();
                    jQuery('#mipluf_recaptchaV2_site_key').attr('readonly', true);
                    jQuery('#mipluf_recaptchaV2_secrate_key').attr('readonly', true);
                }

                var v3_key = jQuery('input[name="mipluf_recaptchaV3_site_key').val();
                var v3_value = v3_key.replace(/(.{4}).*(.{4})/, '$1********************$2');
                jQuery('input[name="mipluf_recaptchaV3_site_key"]').val(v3_value);
                var v3_key = jQuery('input[name="mipluf_recaptchaV3_secrate_key').val();
                var v3_value = v3_key.replace(/(.{4}).*(.{4})/, '$1********************$2');
                jQuery('input[name="mipluf_recaptchaV3_secrate_key"]').val(v3_value);
                if(v3_key){
                    jQuery('.mipluf_v3_reset_button').show();
                    jQuery('#mipluf_recaptchaV3_site_key').attr('readonly', true);
                    jQuery('#mipluf_recaptchaV3_secrate_key').attr('readonly', true);
                }

               
                jQuery('.mipluf_my_notice').remove();
                if( $data.status == 'fail' ){
                    var msg= '';
                    if($data.message){
                        msg= '<div class="notice notice-error is-dismissible mipluf_my_notice"><p>'+$data.message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                        jQuery('.wp-heading-inline').after(msg);
                    }
                }
                else{
                    if($data.message){
                        msg= '<div class="notice is-dismissible notice-success mipluf_my_notice"><p>'+$data.message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                        jQuery('.wp-heading-inline').after(msg);
                    }
                }
                
                if( $data.status == 'success' ){
                    jQuery($this).val('Saved!');
                } 
                
                jQuery($this).prop('disabled',false);
                jQuery($this).css('cursor','pointer');

                setTimeout(function(){
                    window.location.reload();
                }, 4000);
                
            });
            
            return false;
            
        });
    }

       
    jQuery('#mipluf_add_role_setting').on( 'click', '.mipluf_add_Role', function(){
        
        var $this = this;
        jQuery($this).val('Saving...');
        jQuery($this).closest('form').find('.mipluf_error_fields').remove();
        var $form_data = jQuery($this).closest('form').serializeArray();
        jQuery.post( "?mipluf_action=mipluf_add_Role", $form_data, function( $data ) {
            
            $data = JSON.parse($data);
           
            if( $data.status == 'error_message' ){
                alert($data.message);
            }

            if( $data.status == 'success' ){
                jQuery($this).val('Saved!');
                jQuery($this).closest('form').after('<span class="mipluf_success_message">'+$data.message+'</span>')
            
                setTimeout(function(){
                    window.location.reload();
                }, 1000);

            }else{

                for (var key in $data.message){
                    
                    if(jQuery($this).closest('form').find('[name='+key+']')){
                        jQuery($this).closest('form').find("[name='"+key+"']").after('<div class="mipluf_error_fields">'+$data.message[key]+'</div>');
                    }
                }
                jQuery($this).closest('form').find('.mipluf_success_message').text('');
            }
           
        });


        return false;
    });


});


/* Toggal Modal */
jQuery(document).ready(function(){

    jQuery('.mipluf_toggal_modal').click(function() {
        var $modal_id = jQuery(this).attr('data-modal');
        
        if( typeof $modal_id != 'undefined' && $modal_id != '' ){
            jQuery($modal_id).toggleClass('mipluf_show_modal');
        }else{
            var $modal_id = jQuery(this).attr('href');
            jQuery($modal_id).toggleClass('mipluf_show_modal');
        }
        jQuery('body').addClass('mipluf_popup_open');
        return false;
    });

    jQuery(".mipluf_popup_modal .mipluf_close_modal").click(function(){
        close_popup_modal(this);
        return false;
    });
    
    jQuery(".mipluf_popup_modal").click(function(e){
        if( !jQuery(e.target).hasClass('mipluf_popup_modal') ){ return; }
        close_popup_modal(this);
        return false;
    });
    
    function close_popup_modal($this_obj){
        if( jQuery($this_obj).hasClass('mipluf_popup_modal') ){
            jQuery($this_obj).removeClass('mipluf_show_modal');
        }else{
            jQuery($this_obj).parents('.mipluf_popup_modal').removeClass('mipluf_show_modal');
        }
        jQuery('body').removeClass('mipluf_popup_open');
    }    
    
});


jQuery(document).ready(function() {
    if( jQuery( "#mipluf_user_register_form, #mipluf_user_login_form" ).length >= 1 ){
        wp.codeEditor.initialize(jQuery('#mipluf_user_register_form, #mipluf_user_login_form'), cm_settings);
    }
});


jQuery(document).ready(function() {

    /* Login form */
    jQuery(".mipluf_add_default_login_fields").click(this,function(){
 
        var shortcode=jQuery(this).attr('data-shortcode');
        
        var pos = document.querySelector('.CodeMirror').CodeMirror.getCursor();

        document.querySelector('.CodeMirror').CodeMirror.setSelection(pos, pos);

        $codemirror_data = document.querySelector('.CodeMirror').CodeMirror.replaceSelection(shortcode);
        
        if($codemirror_data != undefined){
            var editor = document.querySelector('.CodeMirror').CodeMirror.setValue($codemirror_data);
        }
     
        return false;

    });
 

    /* Registration form */
    jQuery(".mipluf_add_default_fields").click(this,function(){
            
        var shortcode=jQuery(this).attr('data-shortcode');
        
        var pos = document.querySelector('.CodeMirror').CodeMirror.getCursor();
        
        document.querySelector('.CodeMirror').CodeMirror.setSelection(pos, pos);
        
        $codemirror_data = document.querySelector('.CodeMirror').CodeMirror.replaceSelection(shortcode);

        if($codemirror_data != undefined){
            var editor = document.querySelector('.CodeMirror').CodeMirror.setValue($codemirror_data);
        }
     
        return false;

    });
    
});


function px_dissmiss_notice(dobj){
    jQuery(dobj).parent().slideUp("fast", function() {jQuery(this).remove();});
    return false;
}


jQuery(document).ready(function(){
    jQuery('.mipluf_setting_general_tabs').on('click', '.reset_app_setting',function(){
        var info = new Array();
        jQuery(this).closest('.mipluf_reset_fields').find('.reset_keys input').each(function(){
            info.push({name: jQuery(this).attr('data-name'), value: ""});    
        });

        jQuery(this).parents('.mipluf_reset_fields').find('.reset_keys input').each(function(){
            jQuery(this).attr('name',jQuery(this).attr('data-name')).val("");
            jQuery(this).removeAttr('readonly');
        });
            
        jQuery(this).closest('.reset_keys').children('.reset_app_setting').remove();

        jQuery.post('?mipluf_action=mipluf_delete_api', info, function($data){
            
            $data = JSON.parse($data);
            var msg = '';
            jQuery('.mipluf_my_notice').remove();
            if($data.success_message){
                
                msg = '<div class="notice is-dismissible notice-success mipluf_my_notice"><p>'+$data.success_message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                jQuery('.wp-heading-inline').after(msg);
            }
        });

        return false;
    });
    
    
});


jQuery(document).ready(function(){

    jQuery('.mipluf_setting_general_tabs').on('click', '.reset_recaptchav2_keys',function(){
       

        jQuery.post('?mipluf_action=mipluf_reset_recaptchav2_keys', function($data){
            
        $data = JSON.parse($data);
        var msg= '';
        jQuery('.mipluf_my_notice').remove();

        jQuery('#mipluf_recaptchaV2_site_key').val('');   
        jQuery('#mipluf_recaptchaV2_secrate_key').val('');   
        jQuery("#mipluf_recaptchaV2_site_key").removeAttr("readonly");
        jQuery("#mipluf_recaptchaV2_secrate_key").removeAttr("readonly");
        jQuery('.mipluf_v2_reset_button').remove();

        if($data.success_message){
            
            msg= '<div class="notice is-dismissible notice-success mipluf_my_notice"><p>'+$data.success_message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
            jQuery('.wp-heading-inline').after(msg);
            
        }
        
        });
        return false;
    });

    jQuery('.mipluf_setting_general_tabs').on('click', '.mipl_uf_reset_recaptchav3_keys',function(){

        jQuery.post('?mipluf_action=mipluf_reset_recaptchav3_keys', function($data){
           
            $data = JSON.parse($data);
            var msg= '';
            jQuery('.mipluf_my_notice').remove();

            jQuery('#mipluf_recaptchaV3_site_key').val('');   
            jQuery('#mipluf_recaptchaV3_secrate_key').val('');   
            jQuery("#mipluf_recaptchaV3_site_key").removeAttr("readonly");
            jQuery("#mipluf_recaptchaV3_secrate_key").removeAttr("readonly");
            jQuery('.mipluf_v3_reset_button').remove();

            if($data.success_message){
                
                msg= '<div class="notice is-dismissible notice-success mipluf_my_notice"><p>'+$data.success_message+'.</p><button type="button" class="notice-dismiss" onclick="javascript: return px_dissmiss_notice(this);"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
                jQuery('.wp-heading-inline').after(msg);

            }
            
        });

        return false;
    });
    
});

jQuery(document).ready(function(){
    jQuery('.mipluf_update_user_modal').on('click',function() {

        var $modal_id = jQuery(this).attr('data-modal');
        if( typeof $modal_id != 'undefined' && $modal_id != '' ){
            jQuery($modal_id).toggleClass('mipluf_show_modal');
        }else{
            var $modal_id = jQuery(this).attr('href');
            jQuery($modal_id).toggleClass('mipluf_show_modal');
        }
        jQuery('body').addClass('mipluf_popup_open');
        return false;

    });
});