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
    add_filter('insert_cpt', [$this,'insert_cpt'],10,2 );

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
   add_action('acf/save_post', [$this,'acf_save_data'], 20,1);
  // add_action( 'save_post', [$this,'acf_save_data'] );
    }
    public function insert_cpt($user_id,$year){
               // error_log( "insert cpt function");

            $exsist = $this->check_if_cpt_exsist($user_id,$year);
         //   error_log($exsist);
            if($exsist != false)
            return $exsist;
            return  $this->insert_new_cpt($user_id,$year);

    }
    protected function insert_new_cpt($user_id,$year){
       // error_log($year);

      //  tax_input meta_input
      $tax_input  = array(
        'taxes_year' => array(
            (int)$year       
        )
    );

        $args = array(
            'post_type' =>  'taxes',
            'post_status' => 'new',
            'tax_input' =>  $tax_input,
            'meta_input' => array('owner' => $user_id)
        );
        $pid = wp_insert_post($args);
      $SET_TERMS =  wp_set_object_terms( $pid, (int)$year , 'taxes_year');
      if(is_wp_error($SET_TERMS)){
        error_log($SET_TERMS->get_error_message());
        }
       return $pid;   
    }
    protected function check_if_cpt_exsist($user_id,$year){
        $exsist = false;
        $args = array(
            'post_type' =>  'taxes',
            'post_status' => 'publish',
            'meta_query' =>  array(
                    array(
                    'key'     => 'owner',
                    'value'   => $user_id,
                    'compare' => '='
                    )
                    ),
            'tax_query' => array(
                    array(
                    'taxonomy' => 'taxes_year',
                    'field'    => 'term_id',
                    'terms'    => array( (int)$year ),
                    'operator' => '=',
                        ),            
                    )
                    );
        $wp_query = new WP_Query( $args);
         // error_log( print_r($wp_query, TRUE) );

            $output = $wp_query->have_posts();
            if( $wp_query->have_posts() ){
                while ($wp_query->have_posts()) : $wp_query->the_post();
                return get_the_ID();
                 endwhile;
            }
            wp_reset_query();
            return false;
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
       // var_dump($post_id);
       // var_dump([$_POST]);
      //  var_dump($_POST['acf']);  
      //  var_dump($_POST['acf']);  
    /*  foreach(acf_get_field_groups() as $group){
        echo "acf_get_field_groups: <br>";

       //   var_dump($group);
        $opsion[$group['ID']] = $group['title']. "-" .$group['ID'];
        }*/
//echo "row count: <br>";
$rowscount = 0;
foreach($_POST['acf'] as $group => $val){
    //var_dump( get_field_object($group) );
   // wp_die();

    $uoniq = true;
        $rows = get_field('post_groups',$post_id);
        if($rows)
        {
           foreach($rows as $row)
               {
                      if($row['group_key'] === $group)
                     $uoniq = false;
              }
        }
    if($uoniq && !empty($val)){
        $group_detl =  get_field_object($group) ;
    //    ["label"]=> string(23) "פרטים אישיים" ["name"]=> string(8) "personal" 
        $row = array(
            'group_key'	=> $group,
            'group_label' => $group_detl['label'],
            'group_name' => $group_detl['name'],

        );
        $rowscount = add_row( 'post_groups',$row, $post_id );
    }
}


}
    public function acf_pre_save( $post_id ) {
       // var_dump($post_id);
       // var_dump([$_POST]);
       // wp_die();
       // wp_die();
        if( $post_id === 0 ) {
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
        'post_type' => 'taxes',
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
    protected function register_status(){
   if( have_rows('cpt_status','option') ):

    // loop through the rows of data
   while ( have_rows('cpt_status','option') ) : the_row();

       // display a sub field value
       // $status =  get_sub_field('status');
        if( have_rows('status') ): 
        while( have_rows('status')) : the_row();
        $status =[];
        $status['name'] =  get_sub_field('name');
        $status['val'] =  get_sub_field('val');  
   register_post_status($status['val'] , array(
    'label'                     => $status['name'],
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true,
    'label_count'               => _n_noop( $status['name'].' <span class="count">(%s)</span>', $status['name'].' <span class="count">(%s)</span>' ),
    ) );
   
        endwhile; 	
    endif;

   endwhile;

else :

   // no rows found

endif;


    }
     public function on_init(){
                 acf_form_head(); 
                 if( !session_id() )
                 session_start();
  
        register_post_type( 'taxes',
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
  $labels_textemony = array(
    'name'              => _x( 'Year', 'taxonomy general name', 'textdomain' ),
    'singular_name'     => _x( 'Year', 'taxonomy singular name', 'textdomain' ),
    'search_items'      => __( 'Search Year', 'textdomain' ),
    'all_items'         => __( 'All Year', 'textdomain' ),
    'parent_item'       => __( 'Parent Year', 'textdomain' ),
    'parent_item_colon' => __( 'Parent GeYearnre:', 'textdomain' ),
    'edit_item'         => __( 'Edit Year', 'textdomain' ),
    'update_item'       => __( 'Update Year', 'textdomain' ),
    'add_new_item'      => __( 'Add New Year', 'textdomain' ),
    'new_item_name'     => __( 'New Year Name', 'textdomain' ),
    'menu_name'         => __( 'Year', 'textdomain' ),
);

$args_tex = array(
    'hierarchical'      => true,
    'labels'            => $labels_textemony,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'taxes_year' ),
);
    
register_taxonomy( 'taxes_year', array( 'taxes' ), $args_tex );
  //var_dump($test);
  //wp_die();
  $this->register_status();

           }
 
        }
new Taxes();