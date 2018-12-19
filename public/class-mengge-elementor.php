<?php
namespace MeneggeElementor;

 class MeneggeElementor {
    public function __construct(){
        $this->add_wp_actions();
     }
   
    public function add_wp_actions(){
   // add_action( 'elementor/theme/register_locations', [$this,'register_elementor_locations'] );
    add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );
    add_action( 'elementor/dynamic_tags/register_tags',[$this,'reg_my_tag']);
    
}
    public function register_elementor_locations($elementor_theme_manager){
        $types = apply_filters( 'get_business_types',array());
        foreach ($types as $type){
         $elementor_theme_manager->register_location(
         'abusiness_'.$type,
         [
             'label' =>  "business ".$type,
             'multiple' => true,
             'edit_in_content' => true,
         ]
          );
          $elementor_theme_manager->register_location(
             'abusiness_extended_'.$type,
             [
                 'label' =>  "business extended".$type,
                 'multiple' => true,
                 'edit_in_content' => true,
             ]
           ); 
    }
    }
        //TZT_Tag_log_our
    public function reg_my_tag($dynamic_tags){
			\Elementor\Plugin::$instance->dynamic_tags->register_group( 'meta-variables', [
		   'title' => 'Meta Variables' 
           ] );
               
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elementor-tag-login-logout-text.php';
           $dynamic_tags->register_tag( new \mb\Tags\TZT_login_logout_text() ); 
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elementor-tag-login-logout-url.php';
           $dynamic_tags->register_tag( new \mb\Tags\TZT_login_logout_url() );              
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elementor-tag-get-all-terms.php';
           $dynamic_tags->register_tag( new \mb\Tags\TZT_Tag_all_terms() );  
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elementor-tag-acf-group.php';
           $dynamic_tags->register_tag( new \mb\Tags\TZT_acf_group_text() );  
           require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elmentor-tag-cpt-status.php';
           $dynamic_tags->register_tag( new \mb\Tags\TZT_acf_post_status_text() );  

    }
    public function on_widgets_registered() {
   // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-index-pashkevil-arc-widget.php';
   // \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Pashkevil\Widgets\TZT_Archive_Widget() );
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-index-pashkevil-front-and-acf.php';
    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Pashkevil\Widgets\TZT_Acf_Front_And_Widget() );
   // require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-get-terms-widget.php';
  //  \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Pashkevil\Widgets\TZT_Get_Terms_Widget() );

    }
}
new MeneggeElementor();