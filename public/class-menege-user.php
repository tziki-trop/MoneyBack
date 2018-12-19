<?php
namespace menegembusers;
use WP_Query; 
use WP_Error; 
class menegge_users {
    protected $types = [];
    protected $dynamicData = [];
public function __construct(){
       // $this->set_mail_types();
        $this->add_wp_actions();
     }
     public function add_wp_actions(){
        add_action('after_setup_theme', [$this,'remove_admin_bar']);
        add_action( 'init', [$this,'register_role' ]);
        add_action( 'elementor_pro/forms/validation', [$this,'add_user'],10,2 ); 
        add_action( 'elementor_pro/posts/query/owner_business',[$this,'user_query']);

     }
     public function user_query($query){
             $meta =  array(
         'relation' => 'AND',
          array(
            'key' => 'owner',
            'value' => get_current_user_id(),
            'compare' => 'LIKE',
       ));
        
            //$query->set( 'post_author', get_current_user_id() );
              
              $query->set( 'meta_query', $meta );
    }
     public function register_role(){

        $result = add_role(
            'client',
            __( 'client' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => false,
                'delete_posts' => false, 
                'edit_private_pages' => true,
                'edit_posts' => true,
                'read_private_posts' => true,// Use false to explicitly deny
            )
        );
        $result = add_role(
            'cpa',
            __( 'cpa' ),
            array(
                'read'         => true,  // true allows this capability
                'edit_posts'   => false,
                'delete_posts' => false, 
                'edit_private_pages' => true,
                'edit_posts' => true,
                'read_private_posts' => true,// Use false to explicitly deny
            )
        );
     }
     public function remove_admin_bar() {
        if (!current_user_can('administrator') && !is_admin()) {
          show_admin_bar(false);
     }
     }
     public function add_user ( $record, $ajax_handler ) {
       // $ajax_handler->add_error_message("error");
      // error_log( "login form" );

        $send = $record->get_form_settings( 'form_id' );
        if($send === "add_user"){
        //return;   
        $raw_fields = $record->get( 'fields' );
        $fields = [];   
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = (string)$field['value'];
        }  
      //  $ajax_handler->add_error_message( var_export($fields));
       // error_log( print_r($fields, TRUE) );
        if(email_exists($fields[ 'email' ])){
        $ajax_handler->add_error_message("user exsist");
        return;
        }
        if($fields[ 'pass' ] != $fields[ 'pass1' ]){
            $ajax_handler->add_error_message("pas dont good");
            return;
        }
        $userarray = array(
            'user_pass' => $fields[ 'pass' ],
            'user_email' => $fields[ 'email' ],
            'user_login' => $fields[ 'email' ],
            'role' =>  'owner' 
        );
        $user = wp_insert_user($userarray);
        if(is_wp_error($user)){
        $ajax_handler->add_error_message($user->get_error_message());
        return;
        }
               error_log( print_r($fields, TRUE) );

        $insert_cpt = apply_filters('insert_cpt',$user,$fields['8d26fa3']);
       // $ajax_handler->add_error_message((string)$insert_cpt);

       // do_action('send_castum_email_temp','user_reg',$fields[ 'email' ],$fields);
        wp_clear_auth_cookie();
        wp_set_current_user ( $user );
        wp_set_auth_cookie  ( $user );
        $vars = array('tex_id' => $insert_cpt); 
        $url = add_query_arg($vars,get_permalink(40));
        $ajax_handler->add_response_data( 'redirect_url', $url );
      //  $url = add_query_arg($vars,get_permalink(40));
       // $ajax_handler->add_response_data( 'redirect_url', $url);
    }
    if($send === "update_user"){
        $raw_fields = $record->get( 'fields' );
        $fields = [];   
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = (string)$field['value'];
        } 
        $userarray = array(
            'ID' => get_current_user_id(),
            'user_pass' => $fields[ 'pas' ],
        );
       // $ajax_handler->add_error_message($fields[ 'pas' ]);

         $user =  wp_update_user($userarray);
        if(is_wp_error($user)){
        $ajax_handler->add_error_message($user->get_error_message());
        return;
        }
    
    }
    if($send === "login_user"){
        $raw_fields = $record->get( 'fields' );
        $fields = [];   
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = (string)$field['value'];
        } 
        $user = wp_authenticate( $fields[ 'email' ], $fields[ 'pas' ]);
        if(is_wp_error($user)){
        $ajax_handler->add_error_message($user->get_error_message());
        return;
        } 
        wp_clear_auth_cookie();
        wp_set_current_user ( $user->ID );
        wp_set_auth_cookie  ( $user->ID ); 
        $ajax_handler->add_response_data( 'redirect_url', get_permalink(109));
    }

        
    
    }   
}
    new menegge_users();
