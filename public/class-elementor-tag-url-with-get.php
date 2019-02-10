<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Tag_link_with_get extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'link-with-get';
	}

	public function get_title() {
		return __( 'link-with-get', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}
protected function _register_controls() {

		
     
		$this->add_control(
			'get_name',
			[
				'label' => __( 'URL to redirect', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::URL,
				
			]
        );
        $this->add_control(
			'get_parameter',
			[
				'label' => __( 'URL to redirect', 'elementor-pro' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				
			]
		);
		$this->add_control(
			'current_id',
			[
				'label' => __( 'current id', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'true', 'your-plugin' ),
				'label_off' => __( 'false', 'your-plugin' ),
				'return_value' => 'true',
				'default' => 'false',
			]
		);
		
		
	}

	public function render() {
        $param_name = $this->get_settings( 'get_name' );
        $val =    $param_name['url'] ;	
        $get_parameter = $this->get_settings( 'get_parameter' ); 
        if(isset($_GET[ $get_parameter ]))
		$val  = add_query_arg($get_parameter , $_GET[ $get_parameter ] , $val );
		if( $this->get_settings( 'current_id' ) == "true")
		$val  = add_query_arg($get_parameter , get_queried_object_id() , $val );

       
		echo $val;
	}
}