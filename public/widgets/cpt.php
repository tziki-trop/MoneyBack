<?php
namespace New_Form\cpt;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class New_Form_reg_cpts {
   protected $cpts = [];
    public function __construct($post_id = null) {
      $this->set_cpts_array();
      $this->add_action();
    }
    public function register_cpts(){
          if( !session_id() )
          session_start();
        foreach($this->cpts as $name => $options){
            register_post_type($name,$options);
            add_post_type_support( $name, 'elementor' );
        }    
    }   
    private function set_cpts_array() {
    
      $this->cpts['new_form_elementor'] = array(
			'labels' => array(
				'name' 			=> __( 'form elementor', 'carousel_elementor' ),
				'singular_name' => __( 'form elementor', 'carousel_elementor' ),
				'all_items' 	=> __( 'All form elementor', 'carousel_elementor' ),
				'add_new_item' 	=> __( 'Add New form elementor', 'carousel_elementor' ),
				'new_item' 		=> __( 'Add New form elementor', 'carousel_elementor' ),
				'add_new' 		=> __( 'Add New form elementor', 'carousel_elementor' ),
				'edit_item' 	=> __( 'Edit form elementor', 'carousel_elementor' ),
			),
			'has_archive' 			=> false,
			'rewrite' 				=> array( 'slug' => 'new_form_elementor', 'with_front' => false ),
			'query_var' 			=> false,
			'menu_icon'   			=> 'dashicons-editor-video',
			'public' 				=> true,
			'exclude_from_search' 	=> true,
			'capability_type' 		=> 'post'
		);
    }
    public function add_action(){
        add_action( 'init', [$this,'register_cpts']);
      }
    }
new New_Form_reg_cpts();  