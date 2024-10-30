<?php

// Random string
if(!function_exists('mipluf_rand')){
    function mipluf_rand($length = 10) {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),0,$length);
    }
}


// Validation of pages restriction
if(!function_exists('mipluf_page_restriction_validation')){
    function mipluf_page_restriction_validation($restriction_fields){

        $user_role = array();
        $roles = get_option('wp_user_roles');
        
        if(is_array($roles) || is_object($roles)){
            foreach($roles as $role => $values){
                $user_role[] = $role;
            }
        }
        
        $args = array(
            'post_type' => 'page',
            'posts_per_page'=> -1
        );
        $info = get_posts($args);
        foreach($info as $page){
            $pages[] = $page->ID;
        }

        $validation_array = array();
        $validation_data = array();
        $store_array = array();
        
        $sub_field_keys = array('mipluf_default_user_role','mipluf_default_pages');
        
        if(empty($restriction_fields)){
            return $store_array;
        }

        foreach($restriction_fields['mipluf_default_user_role'] as $field_index => $field_name){
            
            $temp_field_label = 'mipluf_default_user_role_'.$field_index;
            $validation_array[$temp_field_label]  =  array(
                'label'      =>'Default User Role',
                'type'       => 'select',
                'values'     => $user_role,
                'validation' => array(
                    'required' => __('Default User Role should not blank!', 'mipl-wp-user-forms'),
                    'in_values' => __('Default User Role should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            );

            $temp_field_name = 'mipluf_default_pages_'.$field_index;
            $validation_array[$temp_field_name]  =  array(
                'label'      =>'Default Pages',
                'type'       => 'select',
                'values'     => $pages,
                'validation' => array(
                    'required' => __('Default Pages should not blank!', 'mipl-wp-user-forms'),
                    'in_values' => __('Default Pages should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            );

            $validation_data[$temp_field_label] = stripslashes(isset($restriction_fields['mipluf_default_user_role'][$field_index]) ? $restriction_fields['mipluf_default_user_role'][$field_index] : '');
            $validation_data[$temp_field_name] = stripslashes(isset($restriction_fields['mipluf_default_pages'][$field_index]) ? $restriction_fields['mipluf_default_pages'][$field_index] : '');
            
            foreach($sub_field_keys as $sub_field_key){
                
                $store_array[$sub_field_key][$field_index] =  stripslashes(sanitize_text_field(isset($restriction_fields[$sub_field_key][$field_index]) ? $restriction_fields[$sub_field_key][$field_index] : ''));
                
            }
            
        }

        $counts = array_count_values(array_map('strtolower', $store_array['mipluf_default_pages']));
        $filtered = array_filter($store_array['mipluf_default_pages'], function ($value) use ($counts) {
            return $counts[strtolower($value)] > 1;
        });
        
        $array_first_key = array_key_first($filtered);
        $duplicate_names = [];
        if($array_first_key !== null){
            unset($filtered[$array_first_key]);
            foreach($filtered as  $filter_key => $filter_val){
                $duplicate_names['mipluf_default_pages_'.$filter_key] = __("User page was duplicate");
            }
        }

        $mipluf_validation_obj = new MIPLUF_Input_Validation($validation_array, $validation_data);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $mipluf_valid_data = $mipluf_validation_obj->get_valid_data();

        foreach($store_array as $store_key => $store_fields){
            foreach($store_fields as $v_index => $value_arr){
                if( isset($errors['mipluf_default_user_role_'.$v_index]) ){
                    $store_array['errors']['mipluf_default_user_role'][$v_index] = $errors['mipluf_default_user_role_'.$v_index];
                }
                if( isset($errors['mipluf_default_pages_'.$v_index]) ){
                    $store_array['errors']['mipluf_default_pages'][$v_index] = $errors['mipluf_default_pages_'.$v_index];
                }elseif( !isset($errors['mipluf_default_pages_'.$v_index])  && isset($duplicate_names['mipluf_default_pages_'.$v_index])){
                    $store_array['errors']['mipluf_default_pages'][$v_index] = $duplicate_names['mipluf_default_pages_'.$v_index];
                }
            }
        }
        
        return $store_array;

    }
}


// Sanitize array
if(!function_exists('mipluf_sanitize_numric_array')){
    function mipluf_sanitize_numric_array( $data ){
        
        if( !is_array($data) ){ return false; }
        
        $new_data = array();
        foreach($data as $no){
            if(is_numeric($no)){
                $new_data[] = $no;
            }
        }
        
        return $new_data;
        
    }
}


// Registration form data
if(!function_exists('mipluf_reg_forms_data')){
    function mipluf_reg_forms_data(){

        $posts = get_posts(array(
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'registration_forms'
        ));
    
        $data = array();
        foreach($posts as $post){
    
            $data[$post->ID] = $post->post_title;
    
        }

        return $data;

    }
}


// sanitize array fields
if(!function_exists('mipl_uf_sanitize_export_post_fields')){
    function mipl_uf_sanitize_export_post_fields($fields) {
        
        if(!is_array($fields)){
            return $fields;
        }

        foreach ( $fields as $key => $value ) {
            if( is_array($value) ){
                foreach ( $value as $key1 => $value1 ) {
                    if( is_array($value1) ){
                        foreach($value1 as $key2 => $value2){
                            $fields[$key][$key2] = sanitize_text_field( $value2 );
                        }
                    }else{
                        $fields[$key][$key1] = sanitize_text_field( $value1 );
                    }
                }
            }else{
                $fields[$key] = sanitize_text_field( $value );
            }
        }
        
        return $fields;

    }
}
