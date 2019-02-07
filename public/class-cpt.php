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
    add_action( 'elementor_pro/posts/query/user_posts', [$this,'user_posts'] );

  //  add_action( 'add_meta_boxes_Taxes', [$this,'meta_box' ]);
 //   add_action( 'save_post_Taxes', [$this,'save_meta_box_data' ]);
    add_filter( 'manage_Taxes_posts_columns', [$this,'set_custom_Taxes_column'] );
    add_action( 'manage_Taxes_posts_custom_column' , [$this,'custom_Taxes_column'], 10, 2 ); 
  //  add_filter('acf/fields/google_map/api', [ $this,'my_acf_google_map_api']);
  //  add_action( 'wp_ajax_get_Taxes',  [ $this,'get_Taxes'] );
   // add_action( 'wp_ajax_nopriv_get_Taxes',  [ $this,'get_Taxes' ]);
    add_action( 'elementor_pro/forms/validation', [ $this,'send_email_to_client'],10,2 ); 
   // add_action('acf/pre_save_post' , [ $this,'acf_pre_save'], 10, 1 );
 // add_filter( 'wp_insert_post_empty_content', [$this,'disable_save'], 999999, 2 );
   add_action('acf/save_post', [$this,'acf_save_data'], 20,1);
  // add_action( 'save_post', [$this,'acf_save_data'] );
  add_filter('check_if_cpt_exsist', [$this,'check_if_cpt_exsist'], 10, 2);
    }
    protected function get_pages_tufes($user = false){
        return array(
            40, 190,372,374,376,378
        );

    }
    public function insert_cpt($user_id,$year){
               // error_log( "insert cpt function");

            $exsist = $this->check_if_cpt_exsist($user_id,$year);
           // return $exsist;
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
            'post_status' => 'trash',
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
    public function user_posts($query){
      $query->set( 'author', get_current_user_id());
        $query->set( 'post_status', 'any');
       $query->set( 'post_type', 'taxes');


    }
    public function check_if_cpt_exsist($user_id,$year){
        $exsist = false;
        $args = array(
            'post_type' =>  'taxes',
            'post_status' => 'any',
            'author' =>  get_current_user_id(),      
            'tax_query' => array(
                    array(
                    'taxonomy' => 'taxes_year',
                    'field'    => 'term_id',
                    'terms'    => array( (int)$year ),
                    'operator' => 'IN',
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
    protected function add_tfasim($added_rows,$pid){
      //  var_dump($added_rows);
        //       wp_die();
            $rows = [];
            $required_tofes_aas = get_field("required", $pid);
            $exixt = false;
            if(!is_array($required_tofes_aas['tofes_aas'])){
                    foreach ($added_rows as $key => $value) {
                        $rows [] = array(
                            'name'	=> $value,
                            'tofes' => '',
                        );
                        # code...
                    }
            }
            else{
                $current_rows = [];
                foreach($required_tofes_aas['tofes_aas'] as $index=>$current_row){
                    $current_rows [] = $current_row['name'];
                    var_dump($current_rows);
                }
                    foreach($added_rows as $index=>$add_row){
                    if(!in_array($add_row, $current_rows)){
                        $rows [] = array(
                            'name'	=> $add_row[''],
                            'tofes' => '',
                        );
                    }
                   
            

                 }
                 var_dump("fore");

                 var_dump($rows);
                $rows = $rows + $required_tofes_aas['tofes_aas'];
                var_dump($rows);

                var_dump($required_tofes_aas['tofes_aas']);
                $required_tofes_aas['tofes_aas'] = $rows;	
                }
                
                update_field("required", $required_tofes_aas, $pid);
     }

     protected function add_tfasim_mosad($pid){
      //  var_dump ( $pid);
       //  return;
        if( have_rows('personal_Details_lerning_detail',$pid) ):
            //    var_dump ( "test");
    
            $rows = [];
            $required_tofes_aas = get_field("more_files", $pid);
          //  var_dump($required_tofes_aas);
            while ( have_rows('personal_Details_lerning_detail',$pid) ) : the_row();
           // echo get_sub_field("name");
                $exixt = false;
               // $new_rows = [];
               //detiels_lerning
               if(is_array($required_tofes_aas['eanlimodim_fiels'])){
                foreach($required_tofes_aas['eanlimodim_fiels'] as $index=>$current_row){
                    if($current_row['detiels_lerning'] === get_sub_field("mosad_name"))
                    $exixt = $index;
                    //sdfsf dfsdfsf 
                }
            }
                if($exixt !== false){
                    $rows [] = $required_tofes_aas['eanlimodim_fiels'][$exixt];
                }
                else  $rows [] = array(
                'detiels_lerning'	=> get_sub_field("mosad_name"),
            );
    
          endwhile;
        //  var_dump($required_tofes_aas);
          $required_tofes_aas['eanlimodim_fiels'] = $rows;		
          update_field("more_files", $required_tofes_aas, $pid);
          endif;
          //var_dump( get_field_object('required') );
    
      
     }
    public function acf_save_data( $post_id ){
        $current_url = wp_get_referer();

      //  $page = get_page_by_path($_SERVER['HTTP_REFERER']);
    //    $page =    str_replace("?tex_id=".$post_id,"",$_SERVER['HTTP_REFERER']);
    //    $page =    str_replace(home_url(),"",$page);
        $page = trim(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH), '/');

        $page = get_page_by_path($page);
         $pages = get_post_meta($post_id, 'pages', true);
   
         if(is_array($pages))
        $pages [] = $page->ID;
         else $pages = array($page->ID);
         update_post_meta($post_id, 'pages', $pages);

         
  //var_dump($page);
  //  wp_die();
    if(isset($_POST['acf']['field_5c19084918bc3']) && $_POST['acf']['field_5c19084918bc3'] != '' ){
      //  var_dump($_POST['acf']);
     //   wp_die();
        $args = array(
            'post_status' => 'wait',
            'ID' => $post_id
        );
        wp_update_post($args);
   }
        // set 
//var_dump($_POST);
//wp_die();
     $rowscount = 0;
    foreach($_POST['acf'] as $group => $val){
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
    if((isset($_POST['acf']['field_5c24e27628aec']) && $_POST['acf']['field_5c24e27628aec'] == "true" )
          || (isset($_POST['acf']['field_5c24e4da654ed']) && $_POST['acf']['field_5c24e4da654ed'] == "true"  )){
            $args = array(
                'post_status' => 'mail',
                'ID' => $post_id
            );
            wp_update_post($args);
    }//field_5c190a65158e2
    else if(isset($_POST['acf']['field_5c19084918bc3'])){
        $args = array(
            'post_status' => 'submitted',
            'ID' => $post_id
        );
        wp_update_post($args);
    }
    else if(isset($_POST['acf']['field_5c190a65158e2'])){
        $args = array(
            'post_status' => 'wait',
            'ID' => $post_id
        );
        wp_update_post($args);
    }  
    else if($rowscount > 1){
             $args = array(
                 'post_status' => 'new',
                 'ID' => $post_id
             );
             wp_update_post($args);
         }



    }
    public function acf_pre_save( $post_id ) {
        if(isset($_POST['acf']['field_5c16c2873fb89'])){
            $bis = [];
            foreach($_POST['acf']['field_5c16c2873fb89'] as $key => $val){
                    $bis [] = $val;
            }
            $this->add_tfasim($added_rows,$post_id);
        }


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