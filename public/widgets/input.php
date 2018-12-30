<?php
namespace New_Form\Widget;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Frontend;
use Elementor\Group_Control_Background;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use New_Form\New_Form_helper;
if ( ! defined( 'ABSPATH' ) ) exit;
//protected $helper;
class TZT_forms_input extends Widget_Base {
/*public function __construct() {
    
	$this->helper = new \Donations\Widgets\Helpers\TZT_Form_Functions(); 
}*/
public function get_name() {
		return 'new_input'; 
	}

	public function get_title() {
		return __( 'Input', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-file-text';
	}

	public function get_categories() {
		return [ 'site_widgets' ];
	}
    protected function _register_controls() {
      $this->start_controls_section(
					
			'feilds',
			[
				'label' => __( 'Content', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
	/*	  if($field['conditition'] != "true")
        return true;
        $post_id  = $_GET['tex_id'];
        $val = get_post_meta($post_id, $field['conditition_fild'], true);
        foreach($field['conditition_val'] as $val_to_check){
        if($val === $val_to_check['val'])
        return true;
        }
		return false;*/
		$this->add_control(
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
		$this->add_control(
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
		
		$this->add_control(
			'js_conditition_fild', [
				'label' => __( 'conditition_fild = one of the option:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'condition' => [
					'js_conditition' => 'true',
				],
			]
		);
		
		$this->add_control(
			'js_conditition_val', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => false,
				'condition' => [
					'js_conditition' => 'true',
				],
			]
		);
		$this->add_control(
			'conditition',
			[
				'label' => __( 'server conditition?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'true',
				'default' => '',
             
			]
		);	
		
		$this->add_control(
			'conditition_fild', [
				'label' => __( 'conditition_fild = one of the option:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'condition' => [
					'conditition' => 'true',
				],
			]
		);
		$conditition = new \Elementor\Repeater();
		
		$conditition->add_control(
			'one_conditition_val', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => false,
			]
		);  
		$this->add_control(
			'conditition_val',  
			[
				'label' => __( 'server conditition', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $conditition->get_controls(),
				'title_field' => '{{{ one_conditition_val }}}',
                 'condition' => [
					'conditition' => 'true',
				],
			]
		);
		$this->add_control(
			'filed_name', [
				'label' => __( 'Name', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
   
	
        $this->add_control(
			'filed_type',
			[
				'label' => __( 'Field Type', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'dynamic' =>  true,
				'default' => 'text',
				'options' => [
					'hidden' => __( 'hidden', 'client_to_google_sheet' ),
					'text'  => __( 'text', 'client_to_google_sheet' ),
					'textarea' => __( 'textarea', 'client_to_google_sheet' ),
					'number' => __( 'number', 'client_to_google_sheet' ),
					'email' => __( 'email', 'client_to_google_sheet' ),
                    'tel' => __( 'phone', 'client_to_google_sheet' ),
                    'select' => __( 'select', 'client_to_google_sheet' ),
                    'submit' => __( 'Submit', 'client_to_google_sheet' ),
					'file' =>__( 'File', 'client_to_google_sheet' ),
					'date' =>__( 'date', 'client_to_google_sheet' ),
					'true_false' => __('true / false','ff'),
					'checkbox' => __('checkbox','ff'),
				],
			]
		);
        	$this->add_control(
			'filed_icon',
			[
				'label' => __( 'Icon', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::ICON,
			     'condition' => [
					'filed_type!' => 'hidden',
				],
			]
		);
             $this->add_control(
			'filed_label', [
				'label' => __( 'Label', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
                  'condition' => [
					'filed_type!' => 'hidden',
				],
			]
		);
          $this->add_control(
			'filed_rows', [
				'label' => __( 'Rows', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'show_label' => true,
                 'condition' => [
					'filed_type' => 'textarea',
				],
			]
		);
        
        $repeater = new \Elementor\Repeater();
		
		$repeater->add_control(
			'option_name', [
				'label' => __( 'Name', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
        $repeater->add_control(
			'option_val', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
    
        $this->add_control(
			'select_option',
			[
				'label' => __( 'Options', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ option_name }}}',
                 'condition' => [
					'filed_type' => 'select',
				],
			]
		);

		$checkbox = new \Elementor\Repeater();
		
		$checkbox->add_control(
			'option_name', [
				'label' => __( 'Name', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
        $checkbox->add_control(
			'option_val', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
		$checkbox->add_control(
			'option_icon', [
				'label' => __( 'Val', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::ICON,
				'show_label' => true,
			]
		);
    
        $this->add_control(
			'checkbox_option',
			[
				'label' => __( 'Options', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $checkbox->get_controls(),
				'title_field' => '{{{ option_name }}}',
                 'condition' => [
					'filed_type' => 'checkbox',
				],
			]
		);
		$this->add_control(
			'multy_coisec',
			[
				'label' => __( 'multy coisec?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'checkbox',
				'default' => '',
                'condition' => [
					'filed_type' => 'checkbox',
				],
			]
		);
		$this->add_control(
			'icon_label',
			[
				'label' => __( 'show icon only on check?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'icon_label',
				'default' => '',
                'condition' => [
					'filed_type' => 'checkbox',
				],
			]
		);
		$this->add_control(
			'filed_value', [
				'label' => __( 'Value', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
				'dynamic'=>array('active'=>'true'),
                
			]
		);

		$this->add_control(
			'filed_id', [
				'label' => __( 'ID', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'show_label' => true,
			]
		);
	     $this->add_control(
			'required',
			[
				'label' => __( 'Required?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'required',
				'default' => '',
                'condition' => [
					'filed_type!' => 'hidden',
				],
			]
		);
        $this->add_control(
			'readonly',
			[
				'label' => __( 'Readonly?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'readonly',
				'default' => '',
                'condition' => [
					'filed_type!' => 'hidden',
				],
			]
		);
        
         $this->add_control(
			'min_date',
			[
				'label' => __( 'min?', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'yes', 'client_to_google_sheet' ),
				'label_off' => __( 'no', 'client_to_google_sheet' ),
				'return_value' => 'min',
                'condition' => [
					'filed_type' => 'date',
				],
				
			]
		);
		
 $this->end_controls_section();
 $this->start_controls_section(
			'feild_style',
			[
				'label' => __( 'Feild', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
        $selectors = "{{WRAPPER}} input , {{WRAPPER}} textarea , {{WRAPPER}} select , {{WRAPPER}} .worrper_new_input.checkbox input + span";
             $texts_styile = array(
            'regiler'=> [
                 'title' => __( 'Regiler', 'carousel_elementor' ),
                  'icon' => 'fa fa-pause',
                  'selectors' => "{{WRAPPER}} .checkbox input + span ,{{WRAPPER}} input , {{WRAPPER}} textarea , {{WRAPPER}} select",
                ],
            'activ' => [
						'title' => __( 'Activ', 'carousel_elementor' ),
						'icon' => 'fa fa-play',
                    'selectors' => "{{WRAPPER}} .focos input , {{WRAPPER}} .focos textarea , {{WRAPPER}} .focos select",],
            'hover' => [
						'title' => __( 'Hover', 'carousel_elementor' ),
						'icon' => 'fa fa-mouse-pointer',
                        'selectors' => "{{WRAPPER}} .hover input , {{WRAPPER}} .hover textarea , {{WRAPPER}} .hover select",],       
            'full' => [
						'title' => __( 'Full', 'carousel_elementor' ),
						'icon' => 'fa fa-mouse-pointer',
                        'selectors' => "{{WRAPPER}} .checkbox input:checked + span ,{{WRAPPER}} .full input , {{WRAPPER}} .full textarea , {{WRAPPER}} .full select",],
         
		);
	
      $this->start_controls_tabs( 'tabs_filed_style' );
        foreach ($texts_styile as $name => $option) {
            $this->start_controls_tab(
			'tab_tild_'.$name,
			[
				'label' => $option['title'],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'feilds_typography_'.$name,
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => $option['selectors'],
			]
		);
        $this->add_control(
			'input_text_color_'.$name,
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#331060',
				'selectors' => [$option['selectors'] => 'color: {{VALUE}}',],
			]
            	);
		  $this->add_control(
			'feilds_bg_color_'.$name,
			[
				'label' => __( 'Background Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#e4e1e1',
				'selectors' => [$option['selectors'] => 'background-color: {{VALUE}}',],
			]
            	);
           $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'input_border_'.$name,
				'label' => __( 'Border', 'plugin-domain' ),
				'selector' => $option['selectors'],
              
			]
		);
        $this->end_controls_tab();
   
        }
        $this->end_controls_tabs();   
        $this->add_control(
			'end_tebs',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);
        $this->add_control(
			'feilds_border_radius',
			[
				'label' => __( 'Border radius', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					$selectors => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 25,
               'right' => 25,
                'bottom' => 25,
             'left' => 25,
            'isLinked' => true,
               ],
			]
		);
				$this->add_control(
			'feilds_padding',
			[
				'label' => __( 'Padding', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					$selectors => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 0,
               'right' => 10,
                'bottom' => 0,
             'left' => 10,
            'isLinked' => false,
               ],
			]
		);
$this->end_controls_section();
         $this->start_controls_section(
			'label_style',
			[
				'label' => __( 'Label', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		   );
             	$this->add_control(
			'label_type',
			[
				'label' => __( 'Titles align', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'up'  => __( 'up', 'plugin-domain' ),
					'side' => __( 'side', 'plugin-domain' ),
                ],
				'default' => 'up',
                ]
             
		);
        	$this->add_control(
			'label_text_align',
			[
				'label' => __( 'Alignment', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'plugin-domain' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'plugin-domain' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'plugin-domain' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
                'selectors' => ['{{WRAPPER}} .label ' => 'text-align: {{VALUE}}',],
                  'condition' => [
					'label_type' => 'up',
				],
			]
             
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => ' {{WRAPPER}} label ',
			]
		);
        $this->add_control(
			'label_text_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#331060',
				'selectors' => ['{{WRAPPER}} label ' => 'color: {{VALUE}}',],
			]
            	);
		
     
				$this->add_control(
			'label_padding',
			[
				'label' => __( 'Padding', 'client_to_google_sheet' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
               'default' => [
                 'top' => 0,
               'right' => 10,
                'bottom' => 0,
             'left' => 10,
            'isLinked' => false,
               ],
			]
		);
$this->end_controls_section();

		         }
    /* */
    public function render_plain_content() {
       // $settings = $this->get_settings_for_display();
  
  //  update_post_meta( (int)$settings['post_id'], 'form_js', "test2" );
    
     }
   
    protected function render() {
        $widg_helper =  new \New_Form\New_Form_helper("input"); 
	   $settings = $this->get_settings_for_display();
       $widg_helper->to_render($settings,$this->get_id());
    
     }
	 public function is_reload_preview_required() {
		return true;
	}
    protected function _content_template(){ 
 
    }
}