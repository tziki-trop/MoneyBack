<?php
namespace TaxesCpt;
use WP_Error; 
use WP_Query;

 class Taxes {
    protected $types = [];
    protected $dynamicData = [];
    public function __construct(){
        $this->add_wp_actions();
     }
   
    public function get_Taxes_types( $types ){
        return  array_merge($this->types, $types);
    }
    public function add_wp_actions(){
   // add_filter( 'get_Taxes_types', [$this,'get_Taxes_types']);
    add_action( 'init', [$this,'on_init' ]);
    add_action( 'reg_cpts', [$this,'on_init' ]);

  //  add_action( 'add_meta_boxes_Taxes', [$this,'meta_box' ]);
 //   add_action( 'save_post_Taxes', [$this,'save_meta_box_data' ]);
    add_filter( 'manage_Taxes_posts_columns', [$this,'set_custom_Taxes_column'] );
    add_action( 'manage_Taxes_posts_custom_column' , [$this,'custom_Taxes_column'], 10, 2 ); 
  //  add_filter('acf/fields/google_map/api', [ $this,'my_acf_google_map_api']);
  //  add_action( 'wp_ajax_get_Taxes',  [ $this,'get_Taxes'] );
   // add_action( 'wp_ajax_nopriv_get_Taxes',  [ $this,'get_Taxes' ]);
    add_action( 'elementor_pro/forms/validation', [ $this,'send_email_to_client'],10,2 ); 
  //  add_filter('acf/pre_save_post' , [ $this,'acf_pre_save'], 10, 1 );
 // add_filter( 'wp_insert_post_empty_content', [$this,'disable_save'], 999999, 2 );
  // add_action('acf/save_post', [$this,'acf_save_data'], 20,1);
  // add_action( 'save_post', [$this,'acf_save_data'] );
    }
    public function disable_save( $maybe_empty, $postarr ) {
        if ( ! function_exists( 'post_exists' )) {
        require_once( ABSPATH . 'wp-admin/includes/post.php' );
        }
        if(post_exists($postarr['post_title']) && $postarr['post_type'] == 'Taxes' )
        {
            /*This if statment important to allow update and trash of the post and only prevent new posts with new ids*/
            if(!get_post($postarr['ID']))
            {
                  $maybe_empty = true;
            }
        }
        else
        {
            $maybe_empty = false;
        }
    
        return $maybe_empty;
    }
    public function acf_save_data( $post_id ){
    if(isset($_SESSION['owner'])) {
    update_post_meta($post_id, 'owner', $_SESSION['owner']);
    unset($_SESSION['owner']);
  //  wp_redirect(get_permalink($post_id));  

}
}
    public function acf_pre_save( $post_id ) {
       // var_dump($post_id);
       // wp_die();
        if( $post_id === 0 ) {
           // if(!is_user_logged_in())
           // return null;
            if (!session_id())
            session_start();
            $_SESSION['owner'] = get_current_user_id();
        }
        else{
            if(get_current_user_id() != get_post_meta($post_id, 'owner', true))
            return null;
        }
        do_action( 'reg_cpts' );
        return $post_id;
    }

    public function send_email_to_client($record, $ajax_handler){

        $send = $record->get_form_settings( 'form_id' );
        if($send != "lead_to_client")
                return;
        $raw_fields = $record->get( 'fields' );
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = (string)$field['value'];
        }
        $email = get_post_meta($fields[ 'pid' ] , 'email' , true);

        $data = array_merge($fields, get_post_meta($fields[ 'pid' ]));
        $user_data = get_field( "owner" , $fields[ 'pid' ] );
        $data = array_merge( $data , $user_data);
        do_action('send_castum_email_temp','send_messeg',$email,$data);
    }
  
    public function get_Taxes(){	  
   
     if ( !wp_verify_nonce( $_POST['nons'], 'validate' )){
        echo json_encode (array('status' => "error",'error' =>'no validate' ) );
        wp_die();
     }

     if(!isset($_POST['type']) || !isset($_POST['id'])){
      echo json_encode ( array('status' => "error",'error' =>'no bus' ) );
         wp_die();
     }
    //global $wp_query;
    $args=array(  
        'post_type' => 'Taxes',
        'post_status' => 'publish',
        'post__in' =>  array((int)$_POST['id'])
    );

    $wp_query = new WP_Query( $args);
    //$wp_query = new WP_Query( ); sdsdf
    
        $output = $wp_query->have_posts();
        if( $wp_query->have_posts() ){
        //$output = "test1";
        //test t
        while ($wp_query->have_posts()) : $wp_query->the_post();
               ob_start();
               //echo get_the_ID(); 
               //$type =  get_post_meta( get_the_ID() , 'Taxes_type' , true ); 
               elementor_theme_do_location( 'aTaxes_extended_'.$_POST['type'] );
               $output = ob_get_contents();
               ob_end_clean();
        endwhile;
       
        }   
        //wp_reset_query();
        echo json_encode(array('status' => "seccsee",'data' => $output ));
        wp_die();
      
}
  
    public function set_custom_Taxes_column($columns){
       $columns['type'] = __( 'Type', 'Taxes-management' );
       return $columns;
    }
    public function custom_Taxes_column( $column, $post_id ) {
    switch ( $column ) {
    case 'type' :
            echo get_post_meta( $post_id , 'Taxes_type' , true ); 
            break;

    }

    }
     public function on_init(){
        acf_form_head(); 
   
               if( !session_id() )
                   session_start();
  
 register_post_type( 'Taxes',
    array(
      'labels' => array(
        'name' => __( 'Taxes', 'donat'),
        'singular_name' => __( 'Taxes', 'donat'),
        'add_new' => __('Add Taxes','donat'),      
          'add_new_item' => __('Add Taxes','donat')
      ),
        'show_in_menu' => true,
        'show_ui' => true,
      'public' => true,
      'has_archive' => true,
      'supports' => array('title','editor')
    )
  );

  
$labels = array(
    'name' => __( 'year', 'taxonomy general name' ),
    'singular_name' => __( 'year', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search year' ),
    'popular_items' => __( 'Popular year' ),
    'all_items' => __( 'All year' ),
    'parent_item' => null,
    'parent_item_colon' => null,
    'edit_item' => __( 'Edit year' ), 
    'update_item' => __( 'Update year' ),
    'update_item' => __( 'Update year' ),
    'add_new_item' => __( 'Add New year' ),
    'new_item_name' => __( 'New year Name' ),
    'separate_items_with_commas' => __( 'Separate years with commas' ),
    'add_or_remove_items' => __( 'Add or remove year' ),
    'choose_from_most_used' => __( 'Choose from the most used year' ),
    'menu_name' => __( 'year' ),
  ); 
    register_taxonomy('year','Taxes',array(
	'public' => true,
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'update_count_callback' => '_update_post_term_count',
    'query_var' => true,
    'rewrite' => array( 'slug' => 'year' ),
	 'show_admin_column' => true
  ));
           }
 
        }
new Taxes();