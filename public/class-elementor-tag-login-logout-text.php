<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_login_logout_text extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'login-logout-text';
	}

	public function get_title() {
		return __( 'login-logout-text', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
protected function _register_controls() {

		
     
		$this->add_control(
			'login',
			[
				'label' => __( 'login text', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				
            ]
            
        );

        $this->add_control(
			'logout',
			[
				'label' => __( 'logout text', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::TEXT,
				
            ]
            
        );
        
		
		
	}

	public function render() {
        if(is_user_logged_in()){
			$login_text = $this->get_settings( 'login' );
		if('display_name' == $login_text ){
			$user = get_user_by('ID', get_current_user_id());
			echo "שלום ".$user->display_name;
		}
		else echo $login_text;
		}
        else   echo $this->get_settings( 'logout' );

	}
}