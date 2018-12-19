<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_acf_post_status_text extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'get_post_status';
	}

	public function get_title() {
		return __( 'post status', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
protected function _register_controls() {
  
         $this->add_control(
			'ststus_type',
			[
				'label' => __( 'type', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
                    "name" =>__("status name","textfomin"),
                    "label" =>__("status label","textdomain")
                ],
            
			]
        );
    	
	}

	public function render() {
        $ststus_type =  $this->get_settings( 'ststus_type' );
     // $status = get_post_status_object( get_post_status( get_the_ID()) );
      switch($ststus_type){
          case "label":
		  $status = get_post_status_object( get_post_status( get_the_ID()) );
		//  var_dump( $status );   ddd
          echo $status->label;
          break;
          case "name":
          echo get_post_status( get_the_ID());
      }
 	}
}

