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


/* Fill User Details */
function mipluf_user_fill_details(){

    jQuery.post('?mipluf_action=mipluf_get_user_details', function($data){
        
        $data = JSON.parse($data);
        $field_type = $data['field_type'];
        $user_data = $data['user_data'];
        $form_id = $data['form_id'];
        
        var $form_obj = jQuery('[name=mipluf_register_form_id][value="'+$form_id+'"]').closest("form");
        for(var key in $user_data){
            
            if(jQuery($form_obj).find("[name='"+key+"']").length > 0 && $field_type[key] != 'checkbox' && $field_type[key] != 'radio') {

                jQuery($form_obj).find("[name='"+key+"']").val($user_data[key]);

            }else if(($field_type[key] == 'checkbox' || $field_type[key] == 'radio')){
                console.log($field_type[key]);
                if(jQuery.isArray($user_data[key])){
                    for(var $key in $user_data[key]){
                        jQuery($form_obj).find("[name='"+key+"[]'][value='"+$user_data[key][$key]+"']").prop('checked', true);
                    }
                }else{
                    jQuery($form_obj).find("[name='"+key+"'][value='"+$user_data[key]+"']").prop('checked', true);
                }

            }
            
        }

    });

    return false;
    
}


jQuery(document).ready(function(){
    var mipluf_login_modal= jQuery(window.location.href.indexOf('?mi-login-modal'));
    if(mipluf_login_modal[0] != '-1'){
        setTimeout(function(){ 
            jQuery("#mipluf_login_modal").toggleClass('mipluf_show_modal');
        },2500);
    }

    var mipluf_registration_modal= jQuery(window.location.href.indexOf('?mi-register-modal'));
    if(mipluf_registration_modal[0] != '-1'){
        setTimeout(function(){ 
            jQuery("#mipluf_registration_modal").toggleClass('mipluf_show_modal');
        },2500);
    }

    var mipluf_registration_login_modal= jQuery(window.location.href.indexOf('?mi-register-login-modal'));
    if(mipluf_registration_login_modal[0] != '-1'){
        setTimeout(function(){
            jQuery("#mipluf_registration_login_modal").toggleClass('mipluf_show_modal');
        },2500);
    }

});


/* Login User */
jQuery(document).ready(function(){
    jQuery('.mipluf_user_login_page_form, .mipluf_user_login_form, .mipluf_user_login_form_modal, .mipluf_user_register_login_form, .mipluf_user_register_login_form_modal').submit(function(){
        var $formObj = jQuery(this);
        var $data = $formObj.serialize();

        jQuery.post('?mipluf_action=mipluf_login_user', $data, function($response){

            $response = JSON.parse( $response );
            if( $response.status == 'success' && $response.redirection_url != undefined ){
                
                window.location.href = $response.redirection_url;
                
            }
            
            if( $response.status == 'error' ){
                $formObj.find('.mipluf_alert').html($response.message).show();
                $formObj.find('.mipluf_alert').removeClass('mipluf_alert-success').addClass('mipluf_alert-error');
                
                if ( ( $response.recaptcha != undefined && $response.recaptcha == 'v2' ) && window.grecaptcha && typeof grecaptcha !== 'undefined' && grecaptcha !== null && ( $response.disable_recaptcha != undefined && $response.disable_recaptcha != 'disable' )) {
                    grecaptcha.reset();
                }
                
                if($response.recaptcha == 'v3' && $response.recaptcha_v3 != undefined){
                    var $recaptcha_v3 = atob($response.recaptcha_v3);
                    grecaptcha.ready(function() {
                        grecaptcha.execute($recaptcha_v3, {action: 'submit'}).then(function(token) {
                            jQuery($formObj).find('.g-recaptcha-response').val(token);
                        });
                    });
                }

            }
           
        })

        return false;
    });
});


/* Register User */
jQuery(document).ready(function(){
    jQuery('.mipluf_user_register_pageform, .mipluf_user_register_page_form, .mipluf_user_register_login_page_modal, .mipluf_user_register_login_page_form').submit(function(){

        var $formObj = jQuery(this);
        var $data = $formObj.serialize();
       
        jQuery.post('?mipluf_action=mipluf_register_user', $data,function($response){
            
            $response = JSON.parse( $response );            
            $formObj.find('.mipluf_error').remove();
            if($response.errors != 'undefined' && $response.errors != null){
                
                var $error_fields = Object.keys($response.errors);
                $formObj.find('.mipluf_error').remove();
                $formObj.find('.mipluf_error_alert').html('').hide();
               
                for (var key in $error_fields){
                    var field = $error_fields[key];
                    
                    if($formObj.find('[name='+field+']').closest('label').length != 0){
                        $formObj.find('[name='+field+']').closest('label').after('<div class="mipluf_error">'+$response.errors[field]+'</div>');
                    }else if($formObj.find("[name='"+field+"']").length == 1){
                        $formObj.find("[name='"+field+"']").after('<div class="mipluf_error">'+$response.errors[field]+'</div>');
                    }else{
                        if($formObj.find("[name='"+field+"[]']").length > 0){
                            $formObj.find("[name='"+field+"[]']").last().closest('span').after('<div class="mipluf_error">'+$response.errors[field]+'</div>');
                        }else{
                            $formObj.find("[name='"+field+"'").last().closest('span').after('<div class="mipluf_error">'+$response.errors[field]+'</div>');
                        }
                    }
                
                }

                if($response.recaptcha == 'v3' && $response.recaptcha_v3 != undefined){
                    var $recaptcha_v3 = atob($response.recaptcha_v3);
                    grecaptcha.ready(function() {
                        grecaptcha.execute($recaptcha_v3, {action: 'submit'}).then(function(token) {
                            jQuery($formObj).find('.g-recaptcha-response').val(token);
                        });
                    });
                }

            }

            if( $response.status == 'success' ){
                $formObj.find('.mipluf_error_alert').html('').hide();
                $formObj.find('.mipluf_error_alert').html($response.message).show();
                if($response.redirection_url != undefined){
                    window.location.href = $response.redirection_url;
                }

            }else{

                $formObj.find('.mipluf_error_alert').removeClass('mipluf_error_alert-success').addClass('mipluf_error_alert-error');
                
                $formObj.find('.mipluf_error_alert').html($response.message).show();

                if ( ( $response.recaptcha != undefined && $response.recaptcha == 'v2' ) &&  window.grecaptcha && typeof grecaptcha !== 'undefined' && grecaptcha !== null  && ( $response.disable_recaptcha != undefined && $response.disable_recaptcha != 'disable' )) {
                    grecaptcha.reset();
                }

                if($response.recaptcha == 'v3' && $response.recaptcha_v3 != undefined){
                    var $recaptcha_v3 = atob($response.recaptcha_v3);
                    grecaptcha.ready(function() {
                        grecaptcha.execute($recaptcha_v3, {action: 'submit'}).then(function(token) {
                            jQuery($formObj).find('.g-recaptcha-response').val(token);
                        });
                    });
                }
            }

        })
        
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

    jQuery('#mipluf_update_user_setting').on('submit',function() {

        var $form_data = jQuery( this ).serializeArray();
        var $this = this;
        jQuery($this).closest('form').find('.mipluf_error_fields').remove();
        jQuery.post('?mipluf_action=mipluf_update_user_setting', $form_data, function($response){
            
            $response = JSON.parse($response);
            
            if( $response.status == 'error'){
                
                if($response.message != undefined){
                    jQuery('.mipluf_update_user_error_massage').text($response.message);
                }else if($response.errors){
                    
                    for (var key in $response.errors){
                        if(jQuery($this).closest('form').find('[name='+key+']')){
                            jQuery($this).closest('form').find("[name='"+key+"']").after('<div class="mipluf_error_fields">'+$response.errors[key]+'</div>');
                        }
                    }

                }
                
            }else if($response.status == 'success'){
                jQuery('.mipluf_update_user_error_massage').text($response.message);
                var $modal_id = jQuery('.mipluf_update_user_modal').attr('data-modal');
                jQuery($modal_id).toggleClass('mipluf_show_modal');
            }

        });
        return false;

    });

});