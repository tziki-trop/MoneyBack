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
    add_action( 'elementor/elements/categories_registered', [$this,'add_categories'] ); 
	add_action("elementor/frontend/section/before_render", [ $this,'before_section_render']);
        add_action("elementor/frontend/section/after_render", [ $this,'after_section_render']);
        add_action('elementor/element/before_section_start', [ $this,'add_section_contrulers'],10, 2);

}
public function add_section_contrulers($element,$section_id){
				
	if("section_custom_css" !== $section_id || "section" !== $element->get_type())
		return;
        $element->start_controls_section(
					
			'section_for_settings',
			[
				'label' => __( ' js conditition', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $element->add_control(
			'js_conditition',
			[
				'label' => __( 'js conditition?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => '',
             
			]
		);	
		
		$element->add_control(
			'js_conditition_fild', [
				'label' => __( 'conditition_fild = one of the option:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'condition' => [
					'js_conditition' => 'true',
				],
			]
		);
		
		$element->add_control(
			'js_conditition_val', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => false,
				'condition' => [
					'js_conditition' => 'true',
				],
			]
		);
        $element->end_controls_section();
        $element->start_controls_section(
					    
			'section_for_settings_r',
			[
				'label' => __( 'reperter ', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $element->add_control(
			'reperter',
			[
				'label' => __( 'reperter ?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => '',
             
			]
        );
        $element->end_controls_section();

        
     
	
	}
	//section_custom_css
public function before_section_render($element){
      //  var_dump($element);
		$settings = $element->get_settings_for_display();
		if(isset($settings['js_conditition']) && "true" === $settings['js_conditition']){
        
            $element->add_render_attribute( '_wrapper', [
                'class' => 'js_conditition',
                'style' => 'display: none;',
                'data-is-hidden' => 'true',
                'data-fild' => $settings['js_conditition_fild'],
                'data-fild-val' => $settings['js_conditition_val'],

            ] );
            /*
                data-is-hidden='true'
             style="display: none;"
             data-fild="<?php echo $field['js_conditition_fild'];?>"
             data-fild-val="<?php echo $field['js_conditition_val'];?>" >
            */
        }
        if(isset($settings['reperter']) && "true" === $settings['reperter']){
            $element->add_render_attribute( '_wrapper', [
                'class' => 'reperter_section',
                'data-totel' => '0',

            ] );
        }
        
           
}
public function after_section_render($element){
     
        $settings = $element->get_settings_for_display();
        if(!isset($settings['reperter']))
        return;
        if("true" != $settings['reperter'])
        return;

			//return;
		?>
     <div class="re_buttons">

        <div class="add_row_to_re">add</div>
        <div class="remove_row_to_re">remove</div>
    </div>
    <?php
    
	}
    
public function add_categories( $elements_manager ) {

	$elements_manager->add_category(
		'site_widgets',
		[
			'title' => __( 'site widgets', 'carousel_elementor' ),
			'icon' => 'fa fa-plus-square',
		]
	);


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
            //TZT_Tag_link_with_get
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-elementor-tag-url-with-get.php';
            $dynamic_tags->register_tag( new \mb\Tags\TZT_Tag_link_with_get() ); 

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
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/input.php';

  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/form.php';
  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/widgets/form-helper.php';
  
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \New_Form\Widget\TZT_forms_input() );

    \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \New_Form\Widget\TZT_forms() );
 
    }
}
new MeneggeElementor();