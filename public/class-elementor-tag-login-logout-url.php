<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_login_logout_url extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'login-logout-url';
	}

	public function get_title() {
		return __( 'login-logout-url', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::URL_CATEGORY ];
	}
protected function _register_controls() {

		
     
		$this->add_control(
			'login',
			[
				'label' => __( 'login URL', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::URL,
				
            ]
            
        );

        $this->add_control(
			'logout',
			[
				'label' => __( 'logout URL', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::URL,
				
            ]
            
        );
        
		
		
	}
///asdfasdf
	public function render() {
		$login_page = get_permalink(256);
        if(is_user_logged_in())
        echo wp_logout_url( $login_page );	
        else  echo $login_page;

	}
}