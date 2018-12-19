<?php
class mb_option_page {
public function __construct(){
       // $this->set_mail_types();
        $this->add_wp_actions();
     }
     public function add_wp_actions(){
       
        add_action( 'wp_loaded', [$this,'add_option_page' ]);

     }
     public function add_option_page(){
	
            acf_add_options_page();
            
        
     }
   
}
    new mb_option_page();
