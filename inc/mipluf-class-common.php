<?php
/*
Class: MIPLUF Common
*/

class MIPLUF_Common {

    // Register Post Type
    function mipluf_register_custom_post_type(){
        
        $mipluf_post_types = array(
            
            'registration_forms' => array(
                "name"            => __("Users Forms", "mipl-wp-user-forms"),
                'all_items'       => __("Registration Forms", "mipl-wp-user-forms"),
                "singular_name"   => __("Registration Form", "mipl-wp-user-forms"),
                "slug"            => "registration_forms",
                "capability_type" => "post",
                "capabilities"    => array(),
                "image"           => "dashicons-admin-users"
            )
            
        );
        
        foreach( $mipluf_post_types as $post_type_key => $post_type ){
         
            $labels = array(
                'name'               => sprintf( esc_html__( '%s', 'mipl-wp-user-forms' ), esc_html( $post_type['name'] ) ),
                'singular_name'      => sprintf( esc_html__( '%s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'all_items'          => __("Registration Forms", 'mipl-wp-user-forms'),
                'add_new'            => __( 'Add New', 'mipl-wp-user-forms' ),
                'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'edit_item'          => sprintf( esc_html__( 'Edit %s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'new_item'           => sprintf( esc_html__( 'New %s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'view_item'          => sprintf( esc_html__( 'View %s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'search_items'       => sprintf( esc_html__( 'Search %s', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'not_found'          => sprintf( esc_html__( 'No %s found', 'mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'not_found_in_trash' => sprintf( esc_html__( 'No %s found in Trash','mipl-wp-user-forms' ), esc_html( $post_type['singular_name'] ) ),
                'parent_item_colon'  => ''
            );

            $args = array(
                'labels'             => $labels,
                'public'             => true,
                'show_ui'            => true,
                'publicly_queryable' => false,
                'capability_type'    => $post_type['capability_type'],
                'capabilities'       => $post_type['capabilities'],
                'hierarchical'       => true,
                'menu_position'      => 70,
                'menu_icon'          => $post_type['image'],
                'supports'           => array( 'title'),
                'rewrite'            => array( 'slug' => $post_type['slug'] )
            );
            
            register_post_type( $post_type_key, $args );
            
        }
        
    }
    

    function mipluf_post_type_rewrite_flush(){
        $this->mipluf_register_custom_post_type();
        flush_rewrite_rules();
    }

    // start session
    function mipluf_session(){
        
        if(!session_id()){ session_start(); }
        
    }

    // Urls
    function mipluf_wp_urls(){
        ?>
        <script>
            var MIPLUF_HOME_URL = '<?php echo esc_url(MIPLUF_HOME_URL);?>';
            var MIPLUF_SITE_URL = '<?php echo esc_url(MIPLUF_SITE_URL);?>';
            var MIPLUF_TEMPLATE_URL = '<?php echo esc_url(MIPLUF_TEMPLATE_URL);?>';
        </script>
        <?php
    }

     
    // Enqueue Client Scripts
    function mipluf_user_scripts(){

        wp_enqueue_style( 'mipluf-registration-css',  MIPLUF_PLUGIN_URL . "assets/css/mipluf-registration.css");
        wp_enqueue_script('jquery');
        wp_enqueue_script( 'mipluf-registration-js',  MIPLUF_PLUGIN_URL . "assets/js/mipluf-registration.js");
        
        $google_client_id = get_option("_mipluf_google_client_id");
        if(!empty($google_client_id)){
            wp_enqueue_script('mipluf_social_media_google_login','https://accounts.google.com/gsi/client');
        }

        $mipluf_recaptcha = get_option('_mipluf_recaptcha');
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
        
        $mipluf_recaptchav2_site_key = get_option("_mipluf_recaptchaV2_site_key");
        // && $enable_recaptcha == 'enable'
        if(!empty($mipluf_recaptchav2_site_key)){
            if($mipluf_recaptcha == 'v2'){
                wp_enqueue_script('mipluf_recaptchav2','https://www.google.com/recaptcha/api.js');
            }
        }
        
        $mipluf_recaptchav3_site_key = get_option("_mipluf_recaptchaV3_site_key");
        // && $enable_recaptcha == 'enable'
        if(!empty($mipluf_recaptchav3_site_key)){
            if($mipluf_recaptcha == 'v3'){
                wp_enqueue_script('mipluf_recaptchav3','https://www.google.com/recaptcha/api.js?render='.$mipluf_recaptchav3_site_key);
            }
        }
    
    }


    // Enqueue Admin Scripts
    function mipluf_admin_enqueue_scripts() {
        global $post;
        
        wp_enqueue_script('jquery');


        if(isset($post->ID) && !empty($post->ID)){
            $post_type = get_post_type($post->ID);
            if( $post_type == 'registration_forms'){

                $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
                wp_localize_script('jquery', 'cm_settings', $cm_settings);

            }
        }elseif( isset($_GET['post_type'] ) && $_GET['post_type'] == 'registration_forms') {

            $cm_settings['codeEditor'] = wp_enqueue_code_editor(array('type' => 'text/html'));
            wp_localize_script('jquery', 'cm_settings', $cm_settings);

        }


        
        wp_enqueue_style( 'mipluf-registration-admin-css',  MIPLUF_PLUGIN_URL . "assets/css/mipluf-registration-admin.css");
        wp_enqueue_script('mipluf-admin-script', MIPLUF_PLUGIN_URL. 'assets/js/mipluf-registration-admin.js',array('jquery'), '1.0');
        
    }
    

    // Add Meta Box
    function mipluf_add_custom_meta_box(){

        add_meta_box("mipluf_registration_forms-metabox", __("Extra Custom Fields", 'mipl-wp-user-forms'), array( $this, 'mipluf_manage_form_fields' ), "registration_forms","normal","default");
        add_meta_box("mipluif_registration_forms_roles-metabox", __("Settings", 'mipl-wp-user-forms'), array( $this, 'mipluf_users_role' ), "registration_forms","normal","default");
        add_meta_box("mipluif_registration_forms_shortcode-metabox", __("Shortcode", 'mipl-wp-user-forms'), array( $this, 'mipluf_form_shortcode' ), "registration_forms","side","high");

    }


    // Custom Fields of Registration Form
    function mipluf_manage_form_fields($post){
        
        include_once MIPLUF_PLUGIN_PATH.'views/mipluf-manage-custom-fields.php';

    }


    // Add user Role of Form
    function mipluf_users_role($post){ ?>
        <div class="mipluf_form_role">
            <strong><?php esc_html_e('Role', 'mipl-wp-user-forms') ?>:</strong><br>
            <select name="mipluf_form_user_role" id="mipluf_form_user_role_<?php echo esc_html($post->ID);?>" style="width:50%;">
                <option><?php esc_html_e('Select Role', 'mipl-wp-user-forms');?></option>
                <?php wp_dropdown_roles(); ?>
            </select>&emsp;<b><?php esc_html_e('or', 'mipl-wp-user-forms');?></b>&ensp;

            <a href="#mipluf_modal" class="mipluf_toggal_role_modal" data-modal="#mipluf_modal"><?php esc_html_e('Create New Role', 'mipl-wp-user-forms'); ?></a>
        </div>
        
        <script>
            jQuery(document).ready(function(){
                jQuery("select#mipluf_form_user_role_<?php echo esc_attr($post->ID);?> option[value='<?php echo esc_html(get_post_meta( $post->ID, '_mipluf_form_user_role', true)); ?>']").attr('selected','selected');
                jQuery("select[name='mipluf_form_user_role'] option[value='administrator']").remove();
            });

            jQuery(document).ready(function(){
                jQuery('.mipluf_toggal_role_modal').on('click',function() {
        
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
        </script>
        <?php
      
        $enable_recaptcha = get_option('_mipluf_enable_recaptcha');
        if($enable_recaptcha == 'enable'){
            ?>
            <div class="mipluf_disable_recaptcha">
                <?php
                $disable_recaptcha = get_post_meta($post->ID,'_mipluf_disable_recaptcha',true);
                $checked = "";
                if($disable_recaptcha == 'disable'){
                    $checked = "checked";
                }
                ?>
                <strong><?php esc_html_e('Disable Recaptcha', 'mipl-wp-user-forms') ?>:</strong><br>
                <label><input type="checkbox" <?php echo esc_html($checked) ?> name="mipluf_disable_recaptcha" value="disable" ><?php esc_html_e('Disable Recaptcha', 'mipl-wp-user-forms') ?></label>
            </div>
            <?php
        }
        ?>
        <div class="mipluf_reg_redirection_page">
            <strong><?php esc_html_e('Redirection URL', 'mipl-wp-user-forms') ?>:</strong><br>
            <input type="text" name="mipluf_reg_page" class="mipluf_reg_page" style="width:97%;" value="<?php echo esc_html(get_post_meta($post->ID,'_mipluf_reg_page',true)); ?>"/>
        </div>
        <?php
    }


    // Registration form shortcode
    function mipluf_form_shortcode(){
        global $post;
        ?>
        <div class="mipluf_reg_shortcode">
            <?php esc_html('[mipluf_registration_form id="'.$post->ID.'"]'); ?>
            <!--  <?php// esc_html_e('[mipluf_registration_form id'); ?> = "<?php// echo esc_html($post->ID)?>"] -->
        </div>
       <?php
    }
    

    // Admin Footer
    function mipluf_create_role_for_regform(){

        include_once MIPLUF_PLUGIN_PATH.'views/mipluf-create-new-role.php';
        include_once MIPLUF_PLUGIN_PATH.'views/mipluf-update-user-pass.php';
        
    }


    // Add Post Type Columns
    function mipluf_add_forms_posts_columns( $columns ) {

        $columns = array(
            'cb'          => 'cb',
            'title'       => 'Title',
            'mipluf_role_name'   => 'Role Name',
            'mipluf_shortcode'   => 'Shortcode',
            'date'        => $columns['date']
        );
        
        return $columns;

    }

    
    // Show Column Data of Registration forms
    function mipluf_registration_form_shortcode($column,$post_id){
        
        if('mipluf_shortcode' === $column){?>
            <input type="text" onfocus="this.select();" readonly="readonly" value='[mipluf_registration_form id="<?php echo esc_html($post_id)?>"]' class="mipluf_reg_form_shortcode_column large-text code"><br>
            <input type="text" onfocus="this.select();" readonly="readonly" value='[mipluf_login_registration_form id="<?php echo esc_html($post_id)?>"]' class="mipluf_reg_form_shortcode_column large-text code">
            <?php
        }

        if('mipluf_role_name' === $column){
            echo esc_html(ucfirst(get_post_meta($post_id,'_mipluf_form_user_role',true)));
        }

    }


    // Add Registration Form Fields
    function mipluf_registration_form_field(){

        global $post;

        if($post->post_type == "registration_forms"){?>

            <div class="mipluf_registration_form" style="width:100%;margin-top:20px;"> 
            <strong><?php esc_html_e('Fields', 'mipl-wp-user-forms') ?>:</strong>
            <div style="color: #646970;"><?php esc_html_e('Click the fields and add these fields in Editor.', 'mipl-wp-user-forms'); ?></div>
                <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field name='user_email']" data-id="" style="margin-bottom:5px!important;"><?php esc_html_e('User Email', 'mipl-wp-user-forms') ?></a>
                <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field name='first_name']" data-id=""><?php esc_html_e('First Name', 'mipl-wp-user-forms') ?></a>
                <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field name='last_name']" data-id="" style="margin-bottom:5px!important;"><?php esc_html_e('Last Name', 'mipl-wp-user-forms') ?></a>
                <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field name='user_pass']" data-id="" style="margin-bottom:5px!important;"><?php esc_html_e('Password', 'mipl-wp-user-forms') ?></a>

                <?php
                $mipluf_custom_field = get_post_meta($post->ID,'_mipluf_reg_custom_field',true);
                
                if(isset($mipluf_custom_field['field_name'])){
                    $mipluf_custom_field['field_name'] = array_unique($mipluf_custom_field['field_name']);
                    foreach($mipluf_custom_field['field_name'] as $key=>$value ){
                        if(!empty($mipluf_custom_field['field_label'][$key])){?>
                            <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field name='<?php echo esc_html($mipluf_custom_field['field_name'][$key])?>']" data-id="" style="margin-bottom:5px!important;"><?php echo esc_html($mipluf_custom_field['field_label'][$key])?></a><?php
                        }
                    }
                }
                ?>

                <a href="#" class="mipluf_add_default_fields mipluf_button button " data-id='' data-shortcode="[mipluf_recaptcha]" style="margin-bottom:5px!important;"><?php esc_html_e('reCAPTCHA', 'mipl-wp-user-forms') ?></a>

                <a href="#" class="mipluf_add_default_fields mipluf_button button"  data-shortcode="[field type='submit' title='Register']" data-id="" style="margin-bottom:5px!important;"><?php esc_html_e('Submit', 'mipl-wp-user-forms') ?></a>

                <textarea id="mipluf_user_register_form" name="mipluf_user_register_form" style="display: none;"><?php echo esc_textarea( $post->post_content ); ?></textarea>
                 
            </div>
        <?php
        }
    }

    
    // Save Registration Form
    function mipluf_save_registration_form($post_id, $post, $update){
     
        global $wpdb;

        if(!$update){
            return false;
        }

        $mipluf_post_status = array('publish', 'draft', 'pending');
        if( !in_array($post->post_status, $mipluf_post_status)){
            return false;
        }
        
        if ( $post->post_type != "registration_forms" ){
            return false;
        }
        
        if(isset($_POST['mipluf_field'])){
           
            if(isset($_POST['mipluf_field']['field_required'])){
                foreach($_POST['mipluf_field']['field_required'] as $req_field){
                    if(isset( $req_field)){
                        if(!strstr(stripslashes($_POST['mipluf_user_register_form']),  $req_field)){
                            $_SESSION['mipluf_admin_notices']['error'] = esc_html__('Add all required fields in form!', 'mipl-wp-user-forms').'<br>';
                        }
                    }
                }
            }
        }


        if(isset($_POST['mipluf_field'])){
           
            $settings_fields = $this->mipluf_get_regform_fields($_POST['mipluf_field']);
            
            if(isset($settings_fields)){
                update_post_meta($post_id,'_mipluf_reg_custom_field',$settings_fields);
            }

            if(is_array($settings_fields) && in_array('errors',array_keys($settings_fields))){

                if(empty($_SESSION['mipluf_admin_notices']['error'])){
                    $_SESSION['mipluf_admin_notices']['error'] = esc_html__('Solve custom fields error!', 'mipl-wp-user-forms');
                }else{
                    $_SESSION['mipluf_admin_notices']['error'] .= '<br>'.esc_html__('Solve custom fields error!', 'mipl-wp-user-forms');
                }

            }
          
            
        }

        if(!isset($_POST['mipluf_field']) && $post->post_status == 'publish'){
            update_post_meta($post_id,'_mipluf_reg_custom_field','');
        }

        $mipluf_reg_url_field = $this->mipluf_reg_redirection_url_validation();
        $mipluf_validation_obj = new MIPLUF_Input_Validation($mipluf_reg_url_field);
      
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $valid_reg_url_data = $mipluf_validation_obj->get_valid_data();
        
        if(empty($_SESSION['mipluf_admin_notices']['error'])){
            $_SESSION['mipluf_admin_notices']['error'] = implode('<br>', $errors);
        }else{
            $_SESSION['mipluf_admin_notices']['error'] .= '<br>' . implode('<br>', $errors);
        }

        foreach($valid_reg_url_data as $valid_reg_key => $valid_reg_url){

            if(isset($valid_reg_url_data[$valid_reg_key])){
                update_post_meta($post_id, '_'.$valid_reg_key, $valid_reg_url_data[$valid_reg_key]);
            }

        }

        if(isset($_POST['mipluf_disable_recaptcha']) && is_string($_POST['mipluf_disable_recaptcha'])){
            $mipluf_disable_recaptcha = sanitize_text_field($_POST['mipluf_disable_recaptcha']);
            update_post_meta($post_id, '_mipluf_disable_recaptcha',$mipluf_disable_recaptcha);
        }else{
            update_post_meta($post_id, '_mipluf_disable_recaptcha','enable');
        }
        
    }


    // save post content
    function mipluf_filter_post_name($post){
       
        if(isset($post['post_type']) && $post['post_type'] == 'registration_forms'){

            if(isset($_POST['mipluf_user_register_form'])){

                $shortcode = wp_kses_post($_POST['mipluf_user_register_form']);
                preg_match_all('/\[(.*?)\]/', $shortcode, $match );
                $repeated_string = array_unique( array_diff_assoc( $match[0], array_unique( $match[0] ) ) );
                $repeated_fields = implode(' ,', $repeated_string);
                $field_name = str_ireplace('[field name', ' [name ', $repeated_fields);

                $fields = array();
                if(!empty($field_name)){
                    $fields = explode(',', $field_name);
                }
                
                $duplicate_error =  "";
                foreach($fields as $key => $val){
                    $field_error = "";
                    if(empty($duplicate_error)){

                        $field_error = sprintf( esc_html__( 'Duplicate Field %s are not Allowed', 'mipl-wp-user-forms' ), stripslashes($val) );
                        $duplicate_error = $field_error.'<br>';

                    }else{

                        $field_error = sprintf( esc_html__( 'Duplicate Field %s are not Allowed', 'mipl-wp-user-forms' ), stripslashes($val) );
                        $duplicate_error .= $field_error;

                    }
                }

                if(!strstr(stripcslashes($shortcode), "[field type='submit'")){
                    $field_error = esc_html__( "Submit button is required!", 'mipl-wp-user-forms' );
                    $duplicate_error .= empty($duplicate_error) ? $field_error : '<br>'.$field_error;
                }
                

                $_SESSION['mipluf_admin_notices']['error'] = $duplicate_error;
                if(empty($_SESSION['mipluf_admin_notices']['error'])){
                    $post['post_content'] = wp_kses_post($_POST['mipluf_user_register_form']);
                }
                
            }

        }

        return $post;

    }


    // Add Role of Registration Form
    function mipluf_add_role(){
        
        if ( isset( $_POST['mipluf_add_role_setting_nonce'] ) && !wp_verify_nonce( $_POST['mipluf_add_role_setting_nonce'], 'mipluf_add_role_setting' ) ) {
            echo wp_json_encode( array( 'status' => "error_message", 'message' => __('Invalid request!', 'mipl-wp-user-forms') ) );
            die();
        }
            
        $roles = $this->form_role_validation();
        $mipluf_validation_obj = new MIPLUF_Input_Validation($roles, $_POST);
        
        $valid_resp = $mipluf_validation_obj->validate();
        $valid_data = $mipluf_validation_obj->get_valid_data();
        $errors = $mipluf_validation_obj->get_errors();
        
        $roles = get_option('wp_user_roles');
        $user_role = array();
        foreach($roles as $role => $values){
            $user_role[] = $values['name'];
        }

        if(isset($valid_data['mipluf_role_name']) && in_array($valid_data['mipluf_role_name'],$user_role)){
            $resp = array('status' => 'error_message','message' => __('Duplicate Role', 'mipl-wp-user-forms'));
            echo wp_json_encode($resp);
            die();
        }
        
        if(empty($errors)){
            add_role(
                $valid_data['mipluf_role_name'],
                $valid_data['mipluf_role_slug'],
                get_role( $valid_data['mipluf_copy_role_capabilities'] )->capabilities
            );
            $resp = array('status' => 'success', 'message' => __('Successfully Saved!', 'mipl-wp-user-forms'));
            echo wp_json_encode($resp);
            die();
        }else{
            $resp = array('status'=>'error','message'=> $errors);
            echo wp_json_encode($resp);
            die();
        }

    }


    // Form role validation
    function form_role_validation(){

        $mipluf_create_role = array(
            'mipluf_role_name' => array(
                'label'      => 'mipluf_role_name',
                'type'       => 'text',
                'validation' => array(
                    'required' => __('Role Name is required', 'mipl-wp-user-forms'),
                    'regex' => '/^[a-zA-Z-_]*$/',
                    'regex_msg' => __('Role Name should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_role_slug' => array(
                'label'      => 'mipluf_role_slug',
                'type'       => 'text',
                'validation' => array(
                    'required' => __('Role Slug is required', 'mipl-wp-user-forms'),
                    'regex' => '/^[a-zA-Z_]*$/',
                    'regex_msg' => __('Role Slug should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_copy_role_capabilities' => array(
                'label'      => 'mipluf_copy_role_capabilities',
                'type'       => 'select',
                'validation' => array(
                    'required' => __('Role Capabilities is required', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
        );

        return $mipluf_create_role;

    }

    
    // Save All Settings
    function mipluf_save_all_setting(){
        
        $mipluf_field = $this->mipluf_users_forms_setting_fields();
        
        $setting_keys = array('mipluf_google_client_id', 'mipluf_google_client_secrate', 'mipluf_recaptchaV2_site_key', 'mipluf_recaptchaV2_secrate_key', 'mipluf_recaptchaV3_site_key','mipluf_recaptchaV3_secrate_key');
        
        $mipluf_field = apply_filters('mipluf_get_setting_keys', $mipluf_field);
        $setting_keys = apply_filters('mipluf_get_setting_fields', $setting_keys);

        foreach($setting_keys as $setting_key){
            $setting_value = get_option('_'.$setting_key);
            if(!empty($setting_value)){
                unset($mipluf_field[$setting_key]);
            }
        }
        
        $mipluf_validation_obj = new MIPLUF_Input_Validation($mipluf_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $custom_fields_error = implode('<br>', $errors);

        $social_media_data = $mipluf_validation_obj->get_valid_data();
        
        foreach ($social_media_data as $setting_key => $setting_value) {
            update_option( '_'.$setting_key, $setting_value );
        }

        if(isset($_POST['mipluf_social_media_logins'])){
            update_option('_mipluf_social_media_logins', $this->mipluf_sm_sanitize_key_array($_POST['mipluf_social_media_logins']));
        }else{
            update_option('_mipluf_social_media_logins', 'false');
        }

        if(isset($_POST['mipluf_enable_recaptcha']) && is_string(($_POST['mipluf_enable_recaptcha']))){
            $mipluf_enable_recaptcha = sanitize_text_field($_POST['mipluf_enable_recaptcha']);
            update_option('_mipluf_enable_recaptcha',$mipluf_enable_recaptcha );
        }else{
            update_option('_mipluf_enable_recaptcha','disable');
        }

        if(isset($_POST['mipluf_data_auto_prompt']) && is_string($_POST['mipluf_data_auto_prompt'])){
            $mipluf_data_auto_prompt = sanitize_text_field($_POST['mipluf_data_auto_prompt']);
            update_option('_mipluf_data_auto_prompt',$mipluf_data_auto_prompt);
        }else{
            update_option('_mipluf_data_auto_prompt','false');
        }

        if(isset($_POST['mipluf_login_user_role'])){
            $mipluf_login_user_role = sanitize_text_field($_POST['mipluf_login_user_role']);
            update_option( '_mipluf_login_user_role', $mipluf_login_user_role );
        }
     
        if(isset($_POST['mipluf_page_restriction'])){

            $restriction_data = isset($_POST['mipluf_page_restriction']) ? $_POST['mipluf_page_restriction'] : array();
            $mipluf_valid_data = mipluf_page_restriction_validation($restriction_data);
            update_option('_mipluf_page_restriction', $mipluf_valid_data);            

        }else{
            update_option('_mipluf_page_restriction', '');
        }
       
        $custom_fields_error = "";
        if(!empty($errors) ){
            $custom_fields_error .= implode('<br>', $errors);
        }
        
        if( !empty($custom_fields_error) ){
            $resp = array('status' => 'fail', 'message' => $custom_fields_error);
            echo wp_json_encode($resp);
            die();
        }else{
            $resp = array('status' => 'success', 'message' => __('Successfully Saved!', 'mipl-wp-user-forms'));
            echo wp_json_encode($resp);
            die();
        }

    }


    // Sanitize key array
    function mipluf_sm_sanitize_key_array( $data ){
    
        if( !is_array($data) ){ return false; }

        $new_data = array();
        foreach($data as $key=>$value){
            $new_data[$key] = sanitize_text_field($value);
        }

        return $new_data;
        
    }


    // Default Mail Settings
    function mipluf_default_mail_settings(){

        $option_value['_mipluf_supplier_activation_email'] = "Hello [first_name] [last_name],<br><br>
    
        Your are registered successfully,<br>
        Please click activation link, to activate your account,<br>
            
        [activation_link]<br><br>
            
        Thanks,<br>
        Admin";

        $option_value['_mipluf_supplier_notify_email']="Hello Admin,<br><br>
                
        [first_name] [last_name] is registered successfully,<br>
        Please Accept or Reject,<br><br>
            
        Accept: [approve_link]<br>
        Reject: [reject_link]<br><br>
            
        Thanks,<br>
        Admin";

        $option_value['_mipluf_login_user_role'] = "subscriber";
        $option_value['_mipluf_admin_mail_to'] = get_option('admin_email');
        $option_value['_mipluf_user_login_form'] = "<label>username/Email*:
[field name=\"text\" name=\"user_login\" id=\"user_login\" class=\"\" placeholder=\"Username/Email\" required=\"true\"]</label>
<label>Password*:
[field name=\"password\" name=\"user_password\" id=\"user_password\" class=\"\" placeholder=\"Password\" required=\"true\"]</label>
[mipluf_recaptcha]
<label>[field name=\"rememberme\" name=\"rememberme\" id=\"rememberme\" class=\"\"]</label>
[field type=\"submit\" title=\"Login\"]";
    
        $option_value['_mipluf_enable_recaptcha']="enable";
        
        $options = array(
            "_mipluf_supplier_activation_email",
            "_mipluf_supplier_notify_email",
            "_mipluf_login_user_role",
            "_mipluf_admin_mail_to",
            "_mipluf_user_login_form",
            "_mipluf_enable_recaptcha"
        );
       
        if (is_array($options)) {
            foreach ($options as $option_name) {
                update_option( $option_name, $option_value[$option_name] );
            }
        }

    }


    // Save login form
    function mipluf_save_login_form(){

        // Duplicate fields
        $error_massages = array();
        if(isset($_POST['mipluf_user_login_form'])){

            $shortcode = wp_kses_post($_POST['mipluf_user_login_form']);
            preg_match_all('/\[(.*?)\]/', $shortcode, $match );
            $repeated_string = array_unique( array_diff_assoc( $match[0], array_unique( $match[0] ) ) );
            $repeated_fields = implode(' , ', $repeated_string);
            $field_name = str_ireplace('[field name', ' [name ', $repeated_fields);

            $fields = array();
            if(!empty($field_name)){
                $fields = explode(',', $field_name);
            }
            
            $duplicate_error =  "";
            if(!empty($fields)){

                foreach($fields as $key => $val){
                    if(empty($duplicate_error)){
                        
                        $field_error = sprintf( esc_html__( 'Duplicate Field %s are not Allowed', 'mipl-wp-user-forms' ), stripslashes($val) );
                        $duplicate_error = $field_error;

                    }else{
                        
                        $field_error = sprintf( esc_html__( 'Duplicate Field %s are not Allowed', 'mipl-wp-user-forms' ), stripslashes($val) );
                        $duplicate_error = '<br>'.$field_error;
                    }
                }

                $error_massages[] = $duplicate_error;
            }

        }

        if(empty($error_massages)){
            if(isset($_POST['mipluf_user_login_form']) && is_string($_POST['mipluf_user_login_form'])){
                $mipluf_user_login_form = wp_kses_post($_POST['mipluf_user_login_form']);
                update_option( '_mipluf_user_login_form', $mipluf_user_login_form);
            }
        }

        if(isset($_POST['mipluf_disable_login_recaptcha']) && is_string($_POST['mipluf_disable_login_recaptcha'])){
            $mipluf_disable_login_recaptcha = sanitize_text_field($_POST['mipluf_disable_login_recaptcha']);
            update_option('_mipluf_disable_login_recaptcha', $mipluf_disable_login_recaptcha);
        }else{
            update_option('_mipluf_disable_login_recaptcha','enable');
        }

        $mipluf_url_field = $this->mipluf_login_redirection_url_validation();
        $mipluf_validation_obj = new MIPLUF_Input_Validation($mipluf_url_field);
        $valid_resp = $mipluf_validation_obj->validate();
        $errors = $mipluf_validation_obj->get_errors();
        $valid_url_data = $mipluf_validation_obj->get_valid_data();
        
        $error_massages[] = empty($error_massages) ? implode('<br>', $errors) : '<br>' . implode('<br>', $errors) ;
        
        if(!strstr(stripcslashes($shortcode), '[field type="submit"')){            
            $field_error = esc_html__( "Submit button is required!", 'mipl-wp-user-forms' );
            $error_massages[] = empty($error_massages) ? $field_error : '<br>'.$field_error;
        }
        
        foreach($valid_url_data as $valid_url){
            update_option( '_mipluf_login_redirect_page', $valid_url);
        }

        if(!empty($error_massages)){
            $resp = array('status'=>'fail','message'=>$error_massages);
            echo wp_json_encode($resp);
            die();
        }else{
            $resp = array('status' => 'success', 'message' => __('Successfully Saved!', 'mipl-wp-user-forms'));
            echo wp_json_encode($resp);
            die();
        }
        
    }

   
    // Add Page Restriction
    function mipluf_page_restriction(){

        global $post;
        $current_user = wp_get_current_user();
        $pages_restriction = get_option('_mipluf_page_restriction');

        if(isset($pages_restriction['mipluf_default_pages'])){
            
            foreach($pages_restriction['mipluf_default_pages'] as $key => $page_id){
                if(!empty($post)){

                    if( $page_id == $post->ID ){
                        if( 0 == $current_user->ID ){
                            // header('Location:'.home_url('/login'));
                            // die();
                        }
                        
                        $page_role = $pages_restriction['mipluf_default_user_role'][$key];
                       
                        if( isset($current_user->roles[0]) && $current_user->roles[0] != 'administrator' ){
                            if( !in_array($page_role, $current_user->roles) ){
                                wp_die("Sorry, you are not allowed to access this page.");
                                die();
                            }
                        }
    
                    }
                }
            }
        }
    
    }


    // Validation of registration forms Fields  
    function mipluf_get_custom_fields($meta_fields, $mipfuf_user_role){
        
        $mipluf_form_field_val = array();
        $default_field = array();
        $required = 'required';
        $current_user = wp_get_current_user();        
        if( $current_user->ID != 0  && $mipfuf_user_role == $current_user->roles[0]){
        // if(is_user_logged_in()){
            $required = '';
        }
        
        $default_field['first_name'] = array(
            'label'      => 'first_name',
            'type'       => 'text',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('First Name is required', 'mipl-wp-user-forms'),
                'regex' => '/^([a-z]|[A-Z]| |_|-)+$/',
                'regex_msg' => __('First Name should be valid!', 'mipl-wp-user-forms')
            ),
        );
        $default_field['last_name'] = array(
            'label'      => 'last_name',
            'type'       => 'text',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('Last Name is required', 'mipl-wp-user-forms'),
                'regex' => '/^([a-z]|[A-Z]| |_|-)+$/',
                'regex_msg' => __('Last Name should be valid!', 'mipl-wp-user-forms')
            ),
        );
        $default_field['user_pass'] = array(
            'label'      => 'user_pass',
            'type'       => 'password',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                $required => __('Password is required', 'mipl-wp-user-forms'),
                'regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
                'regex_msg' => __('Password should be valid!', 'mipl-wp-user-forms')
            ),
        );
        $default_field['user_email'] = array(
            'label'      => 'user_email',
            'type'       => 'text',
            'sanitize'   => array('sanitize_text_field'),
            'validation' => array(
                'required' => __('Email is required', 'mipl-wp-user-forms'),
                'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
                'regex_msg' => __('Please enter valid Email!', 'mipl-wp-user-forms')
            ),
        );
        $default_field['mipluf_form_user_role'] = array(
            'label'      => 'mipluf_form_user_role',
            'type'       => 'select',
            'sanitize'   => array('sanitize_text_field'),
        );
        
        if(!empty($meta_fields)){
            
            foreach($meta_fields as $field_key => $meta_field){

                foreach($meta_field as $key => $meta_val){

                    $required_field = "";
                    if(isset($meta_fields['field_required'])){
                        if(isset($meta_fields['field_name'][$key])){
                            $field_required = $meta_fields['field_name'][$key];
                        }
                        
                        if(isset($meta_fields['field_required']) && in_array($field_required, $meta_fields['field_required'])){
                            $required_field = 'required';
                        }
                    }
                    
                    if(isset($meta_fields['field_name'][$key])){
                        
                        if($meta_fields['field_type'][$key] == 'text'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('This field is required', 'mipl-wp-user-forms'),
                                    'regex' => '/^([a-z]|[A-Z]| |_|-)+$/',
                                    'regex_msg' => sprintf(
                                        __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                        ucfirst($meta_fields['field_label'][$key])
                                    ),
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'email'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('This field is required', 'mipl-wp-user-forms'),
                                    'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
                                    'regex_msg' => __('Please enter valid email!', 'mipl-wp-user-forms')
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'textarea'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('This field is required', 'mipl-wp-user-forms'),
                                    'limit' => '200',
                                    'limit_msg' => sprintf(
                                        __( '%s Write 200 Character only!', 'mipl-wp-user-forms' ),
                                        ucfirst($meta_fields['field_label'][$key])
                                    ),
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'tel'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('This field is required', 'mipl-wp-user-forms'),
                                    'regex' => '/^([+]\d{2}[ ])?\d{10,20}$/',
                                    'regex_msg' => sprintf(
                                        __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                        ucfirst($meta_fields['field_label'][$key])
                                    ),
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'number'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('This field is required', 'mipl-wp-user-forms'),
                                    'regex' => '/^[0-9]+$/',
                                    'regex_msg' => sprintf(
                                        __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                        ucfirst($meta_fields['field_label'][$key])
                                    ),
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'radio'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('Please select this field!', 'mipl-wp-user-forms')
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'checkbox'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('Please check this field!', 'mipl-wp-user-forms')
                                ),
                            );
                        }

                        if($meta_fields['field_type'][$key] == 'select'){
                            $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                                'label'      => $meta_fields['field_name'][$key],
                                'type'       => $meta_fields['field_type'][$key],
                                'sanitize'   => array('sanitize_text_field'),
                                'validation' => array(
                                    $required_field => __('Please select this field!', 'mipl-wp-user-forms')
                                ),
                            );
                        }
                       
                    }
                }
            }
        }

        if(is_user_logged_in()){
            
            $default_field['new_password'] = array(
                'label'      => 'new_password',
                'type'       => 'password',
                'sanitize'   => array('sanitize_text_field'),
                'validation' => array(
                    'regex' => '/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,16}$/',
                    'regex_msg' => __('New Password should be valid!', 'mipl-wp-user-forms')
                ),
            );

        }
       
        $mipluf_form_fields = array_merge($default_field, $mipluf_form_field_val);

        return $mipluf_form_fields;

    }

    
    // Login redirection page url validation
    function mipluf_login_redirection_url_validation(){

        $mipluf_login_redirection_field = array(
            'mipluf_login_redirect_page' => array(
                'label'      => 'Login Redirect Page',
                'type'       => 'text',
                'validation' => array(
                    'required' => __('Login page redirection url should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',
                    'regex_msg' => __('Login page redirection url should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
        );

        return $mipluf_login_redirection_field;
    }


    // Registration redirection page url validation
    function mipluf_reg_redirection_url_validation(){

        $user_role = array();
        $roles = get_option('wp_user_roles');
        
        if(is_array($roles) || is_object($roles)){
            foreach($roles as $role => $values){
                $user_role[] = $role;
            }
        }

        $mipluf_reg_redirection_field = array(
            'mipluf_reg_page' => array(
                'label'      => 'Registration Page',
                'type'       => 'text',
                'validation' => array(
                    'required' => __('Registration page redirection url should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i',
                    'regex_msg' => __('Registration page redirection url should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_form_user_role' => array(
                'label'      => 'Form Role',
                'type'       => 'select',
                'values'     => $user_role,
                'validation' => array(
                    'required' => __('Form Role should not blank!', 'mipl-wp-user-forms'),
                    'in_values' => __('Form Role should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
        );

        return $mipluf_reg_redirection_field;

    }



    // Validation of Update Fields of User Edit Profile
    function mipluf_get_user_custom_fields($meta_fields){
        
        $mipluf_form_field_val = array();
        
        if(!empty($meta_fields)){
            $explode_array = array();
            foreach($meta_fields as $field_key => $meta_field){

                foreach($meta_field as $key => $meta_val){

                    $required_field = "";
                    if(isset($meta_fields['field_required'])){
                        if(isset($meta_fields['field_name'][$key])){
                            $field_required = $meta_fields['field_name'][$key];
                        }
                        
                        if(isset($meta_fields['field_required']) && in_array($field_required, $meta_fields['field_required'])){
                            $required_field = 'required';
                        }
                    }

                    if($meta_fields['field_type'][$key] == 'text'){
                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key], 
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => sprintf(
                                    __( '%s is required', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                                'regex' => '/^([a-z]|[A-Z]| |_|-)+$/',
                                'regex_msg' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'email'){
                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key], 
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => __('Email is required', 'mipl-wp-user-forms'),
                                'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
                                'regex_msg' => __('Please enter valid email!', 'mipl-wp-user-forms')
                            ),
                        ); 
                    }

                    if($meta_fields['field_type'][$key] == 'textarea'){
                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'sanitize'   => array('sanitize_textarea_field'),
                            'validation' => array(
                                $required_field => sprintf(
                                    __( '%s is required', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                                'limit' => '200',
                                'limit_msg' => sprintf(
                                    __( '%s Write 200 Character only!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'tel'){
                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => sprintf(
                                    __( '%s is required', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                                'regex' => '/^([+]\d{2}[ ])?\d{10,20}$/',
                                'regex_msg' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'number'){
                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => sprintf(
                                    __( '%s is required', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                                'regex' => '/^[0-9]+$/',
                                'regex_msg' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'radio'){

                        $field_values = $meta_fields['field_option'][$key];
                        $field_values = preg_split("/[\n]+/", trim($field_values));
                        $radio_values = [];
                        foreach($field_values as $option_key => $option_value){
                            if(strpos($option_value,":")){
                                $tmp = explode(':',trim($option_value));
                                if(!empty($tmp[0] && !empty($tmp[1]))){
                                    $radio_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[1]));
                                }
                            }else{
                                $tmp = explode('/n',trim($option_value));
                                if(!empty($tmp[0])){
                                    $radio_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[0]));
                                }
                            }
                        }

                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'values'     => $radio_values,
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => __('Please select radio button!', 'mipl-wp-user-forms'),
                                'regex_msg' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'checkbox'){

                        $field_values = $meta_fields['field_option'][$key];
                        $field_values = preg_split("/[\n]+/", trim($field_values));
                        $checkbox_values = [];
                        foreach($field_values as $option_key => $option_value){
                            if(strpos($option_value,":")){
                                $tmp = explode(':',trim($option_value));
                                if(!empty($tmp[0] && !empty($tmp[1]))){
                                    $checkbox_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[1]));
                                }
                            }else{
                                $tmp = explode('/n',trim($option_value));
                                if(!empty($tmp[0])){
                                    $checkbox_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[0]));
                                }
                            }
                        }

                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'values'     => $checkbox_values,
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => __('Please check at least one!', 'mipl-wp-user-forms'),
                                'regex_msg' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }

                    if($meta_fields['field_type'][$key] == 'select'){

                        $field_values = $meta_fields['field_option'][$key];
                        $field_values = preg_split("/[\n]+/", trim($field_values));
                        $select_values = [];
                        foreach($field_values as $option_key => $option_value){
                            if(strpos($option_value,":")){
                                $tmp = explode(':',trim($option_value));
                                if(!empty($tmp[0] && !empty($tmp[1]))){
                                    $select_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[1]));
                                }
                            }else{
                                $tmp = explode('/n',trim($option_value));
                                if(!empty($tmp[0])){
                                    $select_values[strtolower(trim($tmp[0]))] = strtolower(trim($tmp[0]));
                                }
                            }
                        }

                        $mipluf_form_field_val[$meta_fields['field_name'][$key]] = array(
                            'label'      => $meta_fields['field_name'][$key],
                            'type'       => $meta_fields['field_type'][$key],
                            'values'     => $select_values,
                            'sanitize'   => array('sanitize_text_field'),
                            'validation' => array(
                                $required_field => __('Please select the field!', 'mipl-wp-user-forms'),
                                'in_values' => sprintf(
                                    __( '%s should be valid!', 'mipl-wp-user-forms' ),
                                    ucfirst($meta_fields['field_label'][$key])
                                ),
                            ),
                        );
                    }
                }
            }
        }

        return $mipluf_form_field_val;

    }

   
    
    // validation of create registration form fields 
    function mipluf_get_regform_fields($custom_fields){

        if(empty($custom_fields)){
            return $custom_fields;
        }

        $show_post_types = array();
        $validation_array = array();
        $validation_data = array();
        $form_array = array();
    
        $sub_field_keys = array('field_type','field_label','field_name','field_id','field_class','field_placeholder','field_option','field_required');
        foreach($custom_fields['field_name'] as $field_index => $field_name){
            
            $temp_field_label = $field_index.'_field_label';
            $validation_array[$temp_field_label] = array(
                'label' => "Field Label",
                'type' => 'text',
                'validation' => array(
                    'required' => __('Field Label should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^([a-z]|[A-Z]|[0-9]| |_|-)+$/',
                    'regex_msg' => __('Field Label should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );
    
            $temp_field_name = $field_index.'_field_name';
            $validation_array[$temp_field_name] = array(
                'label' => "Field Name",
                'type' => 'text',
                'validation' => array(
                    'required' => __('Field Name should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^([a-z]|[A-Z]|[0-9]|_|-)+$/',
                    'regex_msg' => __('Field Name should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );
    
            $temp_field_type = $field_index.'_field_type';
            $validation_array[$temp_field_type] = array(
                'label' =>"Field Type",
                'type' => 'select',
                'values' => array('text','textarea','tel','number','checkbox','radio','select'),
                'validation' => array(
                    'required' => __('Field Type should not blank!', 'mipl-wp-user-forms'),
                    'in_values' => __('Field Type should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );
    
            $temp_field_placeholder = $field_index.'_field_placeholder';
            $validation_array[$temp_field_placeholder] = array(
                'label' => "Field Placeholder",
                'type' => 'text',
                'validation' => array(
                    'limit' => '200',
                    'limit_msg' => __('Field Placeholder Value Write 200 Character only!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );
    
            $temp_field_option = $field_index.'_field_option';
            $validation_array[$temp_field_option] = array(
                'label' => "Field Options",
                'type' => 'textarea',
                'depend' => array(
                    'field' => $temp_field_type,
                    'value' => array('select','radio','checkbox')
                ),
                'validation' => array(
                    'required' => __("Field Options should not blank!", 'mipl-wp-user-forms'),
                    'limit' => '500',
                    'limit_msg' => __('Field Options Write 500 Character only!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_textarea_field')
            );

            $temp_field_id = $field_index.'_field_id';
            $validation_array[$temp_field_id] = array(
                'label' => "Field ID",
                'type' => 'text',
                'validation' => array(
                    'regex' => '/^([a-z]|[A-Z]|[0-9]|_|-)+$/',
                    'regex_msg' => __('Field ID should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );

            $temp_field_class = $field_index.'_field_class';
            $validation_array[$temp_field_class] = array(
                'label' => "Field class",
                'type' => 'text',
                'validation' => array(
                    'regex' => '/^([a-z]|[A-Z]|[0-9]|_|-)+$/',
                    'regex_msg' => __('Field Class should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize' => array('sanitize_text_field')
            );

            $temp_field_required = $field_index.'_field_required';
            $validation_array[$temp_field_required] = array(
                'label' => "Field Required",
                'type' => 'checkbox',
                'sanitize' => array('sanitize_text_field')
            );
    
            $validation_data[$temp_field_label] = $custom_fields['field_label'][$field_index];
            $validation_data[$temp_field_name] = $custom_fields['field_name'][$field_index];
            $validation_data[$temp_field_type] = $custom_fields['field_type'][$field_index];
            $validation_data[$temp_field_id] = $custom_fields['field_id'][$field_index];
            $validation_data[$temp_field_class] = $custom_fields['field_class'][$field_index];
            $validation_data[$temp_field_placeholder] = $custom_fields['field_placeholder'][$field_index];
            $validation_data[$temp_field_option] = $custom_fields['field_option'][$field_index];
            $validation_data[$temp_field_required] = isset($custom_fields['field_required'][$field_index])?$custom_fields['field_required'][$field_index]:'';

            foreach($sub_field_keys as $sub_field_key){

                if( $sub_field_key == 'field_option'){
                    $form_array[$sub_field_key][$field_index] =  sanitize_textarea_field($custom_fields[$sub_field_key][$field_index]);
                }else{
                    $fields = isset($custom_fields[$sub_field_key][$field_index])?$custom_fields[$sub_field_key][$field_index]:'';
                    $form_array[$sub_field_key][$field_index] =  sanitize_text_field($fields);
                }

            }
    
        }
    
        $counts = array_count_values(array_map('strtolower', $form_array['field_name']));
        
        $filtered = array_filter($form_array['field_name'], function ($value) use ($counts) {
            return $counts[strtolower($value)] > 1;
        });
        
        $array_first_key = array_key_first($filtered);
    
        if($array_first_key !== null){
            unset($filtered[$array_first_key]);
            foreach($filtered as  $filter_key => $filter_val){
                $duplicate_names[$filter_key.'_field_name'] = __("Field name was duplicate", 'mipl-wp-user-forms');
            }
        }
    
        $val_obj = new MIPLUF_Input_Validation($validation_array, $validation_data);
        
        $rs = $val_obj->validate();
        $errors = $val_obj->get_errors();
        $post_data = $val_obj->get_valid_data();
        
        foreach($form_array as $form_field => $value_arr){

            foreach($value_arr as $v_index => $value){
    
                if( isset($errors[$v_index.'_field_label']) ){
                    $form_array['errors']['field_label'][$v_index] = $errors[$v_index.'_field_label'];
                }
                if( isset($errors[$v_index.'_field_name']) ){
                    $form_array['errors']['field_name'][$v_index] = $errors[$v_index.'_field_name'];
                }elseif( !isset($errors[$v_index.'_field_name'])  && isset($duplicate_names[$v_index.'_field_name'])){
                    $form_array['errors']['field_name'][$v_index] = $duplicate_names[$v_index.'_field_name'];
                }
                if( isset($errors[$v_index.'_field_type']) ){
                    $form_array['errors']['field_type'][$v_index] = $errors[$v_index.'_field_type'];
                }
                if( isset($errors[$v_index.'_field_id']) ){
                    $form_array['errors']['field_id'][$v_index] = $errors[$v_index.'_field_id'];
                }
                if( isset($errors[$v_index.'_field_option']) ){
                    $form_array['errors']['field_option'][$v_index] = $errors[$v_index.'_field_option'];
                }
                if( isset($errors[$v_index.'_field_class']) ){
                    $form_array['errors']['field_class'][$v_index] = $errors[$v_index.'_field_class'];
                }
                if( isset($errors[$v_index.'_field_placeholder']) ){
                    $form_array['errors']['field_placeholder'][$v_index] = $errors[$v_index.'_field_placeholder'];
                }
                if( isset($errors[$v_index.'_field_required']) ){
                    $form_array['errors']['field_required'][$v_index] = $errors[$v_index.'_field_required'];
                }
            }
        }
        
        return $form_array;
        
    }
        


    //validation of users forms setting fields
    function mipluf_users_forms_setting_fields(){

        $mipluf_users_setting_fields = array(
            
            'mipluf_supplier_activation_email' => array(
                'label'      => 'Supplier Activation Email',
                'type'       => 'textarea',
                'validation' => array(
                    'required' => __('Supplier activation Email should not blank!', 'mipl-wp-user-forms'),
                    'limit' => '500',
                    'limit_msg' => __('Field label write 500 characters only!', 'mipl-wp-user-forms')
                ),
            ),
            'mipluf_admin_mail_to' => array(
                'label'      => 'Admin Mail To',
                'type'       => 'text',
                'validation' => array(
                    'required' => __('Admin mail should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/',
                    'regex_msg' => __('Admin mail should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_supplier_notify_email' => array(
                'label'      => 'Supplier Notify Email',
                'type'       => 'textarea',
                'validation' => array(
                    'required' => __('Supplier notify email should not blank!', 'mipl-wp-user-forms'),
                    'limit' => '500',
                    'limit_msg' => __('Field label write 500 characters only!', 'mipl-wp-user-forms')
                ),
            ),
            'mipluf_recaptcha' => array(
                'label'      => 'Recaptcha',
                'type'       => 'select',
                'values'     => array('select','v2','v3'),
                'depend'     => array(
                    'field' => 'mipluf_enable_recaptcha',
                    'value' => 'enable'
                ),
                'validation' => array(
                    'required' => __('Recaptcha Type is required', 'mipl-wp-user-forms'),
                    'in_values' => __('Recaptcha Type should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_recaptchaV2_site_key' => array(
                'label'      => 'RecaptchaV2 site key',
                'type'       => 'text',
                'depend'     => array(
                    'field' => 'mipluf_recaptcha',
                    'value' => 'v2'
                ),
                'validation' => array(
                    'required' => __('RecaptchaV2 site key should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^[A-Za-z0-9_-]{40}+$/',
                    'regex_msg' => __('RecaptchaV2 site key should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_recaptchaV2_secrate_key' => array(
                'label'      => 'RecaptchaV2 secrete key',
                'type'       => 'text',
                'depend'     => array(
                    'field' => 'mipluf_recaptcha',
                    'value' => 'v2'
                ),
                'validation' => array(
                    'required' => __('RecaptchaV2 secrete key should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^[A-Za-z0-9_-]{40}+$/',
                    'regex_msg' => __('RecaptchaV2 secrete key should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_recaptchaV3_site_key' => array(
                'label'      => 'RecaptchaV3 site key',
                'type'       => 'text',
                'depend'     => array(
                    'field' => 'mipluf_recaptcha',
                    'value' => 'v3'
                ),
                'validation' => array(
                    'required' => __('RecaptchaV3 site key should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^[A-Za-z0-9_-]{40}+$/',
                    'regex_msg' => __('RecaptchaV3 secrete key should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field')
            ),
            'mipluf_recaptchaV3_secrate_key' => array(
                'label'      => 'RecaptchaV3 secrete key',
                'type'       => 'text',
                'depend'     => array(
                    'field' => 'mipluf_recaptcha',
                    'value' => 'v3'
                ),
                'validation' => array(
                    'required' => __('RecaptchaV3 secrete key should not blank!', 'mipl-wp-user-forms'),
                    'regex' => '/^[A-Za-z0-9_-]{40}+$/',
                    'regex_msg' => __('RecaptchaV3 secrete key should be valid!', 'mipl-wp-user-forms')
                ),
                'sanitize'   => array('sanitize_text_field', 'mipl-wp-user-forms')
            ),

        );
       
        return $mipluf_users_setting_fields;

    }



    function mipluf_wp_login_form_field(){

        $default_social_media_button = get_option('_mipluf_social_media_button_type');
        $buttons_to_show = get_option('_mipluf_enable_google_login_in_wp_login');
        
        if($default_social_media_button == 'default'){
            ?>
            <div class="mipluf_wp_admin_default_buttons">
                <?php
                if(!empty($buttons_to_show) && $buttons_to_show == "enable"){
                    ?>
                    <div class="mipluf_default_google_login_button">
                        <a href="<?php echo esc_url(home_url('/?mipluf_action=google-auth&form_id=wp_admin'));?>"><img src="<?php echo esc_url(MIPLUF_PLUGIN_URL.'assets/images/default_google_button.png');?>"></a>
                    </div>
                    <?php
                }
                ?>
            </div>
            <?php
        }else if($default_social_media_button == 'custom'){
            ?>
            <div class="mipluf_wp_admin_default_buttons">
                <?php
                if(!empty($buttons_to_show) && $buttons_to_show == "enable"){
                    ?>
                    <div class="mipluf_custom_google_login_button">
                        <a href="<?php echo esc_url(home_url('/?mipluf_action=google-auth&form_id=wp_admin'));?>" class="mipluf_custom_button_anchor"><svg class="mipluf_svg_icons"><use xlink:href="<?php echo esc_url(MIPLUF_PLUGIN_URL.'assets/sprites/brands.svg#google');?>"></use></svg><?php esc_html_e('Login with google', 'mipl-wp-user-forms'); ?></a>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <?php
        }else if($default_social_media_button == 'icon'){
            ?>
            <div class="mipluf_wp_admin_default_buttons">
                <?php
                if(!empty($buttons_to_show) && $buttons_to_show == "enable"){
                    ?>
                    <div class="mipluf_google mipluf_wp_admin_form_icons">
                        <a href="<?php echo esc_url(home_url('/?mipluf_action=google-auth&form_id=wp_admin'));?>"><img src="<?php echo esc_url(MIPLUF_PLUGIN_URL.'assets/images/google_icon.png');?>"></a>
                    </div>
                    <?php
                }
                ?>
            </div>
            
            <?php
        }

    }


}
