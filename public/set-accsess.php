<?php
namespace Donations\Accsess;
use Donations;

		  
class TZT_set_access {
    public function __construct() {
$this->add_actions();
    }
	public function add_actions(){
	
		add_action( 'wp', [$this,'set_access'] );

    }
	protected function get_user_role(){
		if( is_user_logged_in() ) {
        $user = wp_get_current_user();
         $role = ( array ) $user->roles;
         return $role;
          } else {
          return false;
        }
	}
	

	public function set_access(){
        
        $role = $this->get_user_role();
        // עמוד סינגל
        if(is_single()){
        //   var_dump($role);

            if($role == false)
            wp_redirect(get_permalink(224));
           else if(!in_array("cpa",$role) && !in_array("administrator",$role)){
              wp_redirect(get_permalink(224));
           }
        } 
        // עמוד מנהל רגיל
        if(is_page(447) && !is_user_logged_in() && !in_array("cpa",$role))
        wp_redirect(get_permalink(224));
        // עמוד מנהל ראשי 554
        if((is_page(554) || is_page(2556)) && in_array("cpa", $role ))
        wp_redirect(get_permalink(447));
        // 369 - הגשת טופס
        if(is_page(369) && !is_user_logged_in() )
        wp_redirect(get_permalink(224));
        global $post;
        if ($post->post_parent == 369 && !is_user_logged_in()) {
            wp_redirect(get_permalink(224));
        }
   	}

}
		

new TZT_set_access();