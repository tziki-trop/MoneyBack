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
		'logguserout',
		[
			'label' => __( 'logg user out', 'elementor-pro' ),
			'type' => \Elementor\Controls_Manager::SWITCHER,
			'label_on' => __( 'Show', 'your-plugin' ),
			'label_off' => __( 'Hide', 'your-plugin' ),
			'return_value' => 'yes',
			'default' => 'yes',			
		]
		
	);
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
				//ff
            ]
            
        );
        
		
		
	}
///asdfasdf
	public function render() {
		
		if($this->get_settings( 'logguserout' ) === "yes"){
		$login_page = get_permalink(256);
        if(is_user_logged_in())
        echo wp_logout_url( $login_page );	
		else  echo $login_page;
		}
		else{
			if(is_user_logged_in())
			echo $this->get_settings( 'login' )['url'];	
			else  echo $this->get_settings( 'logout' )['url'];	
		}

	}
}