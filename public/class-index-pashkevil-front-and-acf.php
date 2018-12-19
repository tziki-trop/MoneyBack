<?php
namespace Pashkevil\Widgets;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use ElementorPro\Base\Base_Widget;
use Donations\Widgets\Helpers;
use WP_Query;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class TZT_Acf_Front_And_Widget extends Widget_Base {

public function get_name() {
		return 'acf_front_end_widget'; 
	}

	public function get_title() {
		return __( 'acf_front_end_widget', 'client_to_google_sheet' );
	}

	public function get_icon() {
		return 'fa fa-file-text';
	}

	public function get_categories() {
		return [ 'pro-elements' ];
    }
    public function is_reload_preview_required() {
		return true;
	}
	 protected function _register_controls() {
		 	$this->start_controls_section(
					
			'content',
			[
				'label' => __( 'Content', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
        );
     
        $opsion = [];
        $filds = [];

        foreach(acf_get_field_groups() as $group){
        $opsion[$group['ID']] = $group['title']. "-" .$group['ID'];
        $fildsforgroup = [];
        foreach(acf_get_fields($group['ID']) as $fild){
          //  echo $fild['name'];
            if($fild['name'] != '')
            $fildsforgroup[ $fild['name'] ] = $fild['name'];
            }
            $filds[$group['ID']] = $fildsforgroup;
        }
    /*     foreach(acf_get_field_groups() as $group){
            $opsion[$group['ID']] = $group['title']. "-" .$group['ID'];
          
    }*/
    $this->add_control(
        'ajex',
        [
            'label' => __( 'use ajax?', 'plugin-domain' ),
            'type' => \Elementor\Controls_Manager::SWITCHER,
            'label_on' => __( 'yes', 'your-plugin' ),
            'label_off' => __( 'no', 'your-plugin' ),
            'return_value' => 'true',
            'default' => 'no',
        ]
    );
        $this->add_control(
			'fild_group_id_array',
			[
				'label' => __( 'Show Elements', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' => $opsion,
            
			]
        );
        foreach($filds as $group => $filds){
            $this->add_control(
                'fild'.$group,
                [
                    'label' => 'feild in group: '.$group,
                    'type' => \Elementor\Controls_Manager::SELECT2,
                    'multiple' => true,
                    'options' => $filds,
                    'condition' => [
                        'fild_group_id_array' => (string)$group,
                    ],
                
                ]
            );
        }
        $this->add_control(
			'pid', [
				'label' => __( 'post id', 'client_to_google_sheet' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'show_label' => true,
				'dynamic'=>array('active'=>'true'),
              
			]
        );
        $this->add_control(
			'submit_text', [
				'label' => __( 'submit_text', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
        );
        $this->add_control(
			'update_messeg', [
				'label' => __( 'update_messeg', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
        );
        $this->add_control(
			'url', [
				'label' => __( 'url', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
			]
		);
        $this->end_controls_section(); 
        $this->start_controls_section(
					
			'label',
			[
				'label' => __( 'label', 'client_to_google_sheet' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
        );

         $this->add_control(
        'label_placement',
        [
            'label' => __( 'Show Elements', 'plugin-domain' ),
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'top',
				'options' => [
					'top'  => __( 'top', 'plugin-domain' ),
					'left' => __( 'left', 'plugin-domain' ),
				],
        
        ]
    );
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'label_typography',
				'label' => __( 'Typography', 'client_to_google_sheet' ),
				'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => "{{WRAPPER}} .acf-label",
			]
        );
        $this->add_control(
			'label_color',
			[
				'label' => __( 'Color', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000',
				'selectors' => ['{{WRAPPER}} .acf-label' => 'color: {{VALUE}}',],
			]
                );
                $this->end_controls_section(); 
        $this->start_controls_section(
                            
                    'input',
                    [
                        'label' => __( 'input', 'client_to_google_sheet' ),
                        'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                    ]
                );
        $this->add_group_control(
                    Group_Control_Typography::get_type(),
                    [
                        'name' => 'input_typography',
                        'label' => __( 'Typography', 'client_to_google_sheet' ),
                        'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                            'selector' => "{{WRAPPER}} .acf-input",
                    ]
                );
         $this->add_control(
                    'input_color',
                    [
                        'label' => __( 'Color', 'client_to_google_sheet' ),
                        'type' => \Elementor\Controls_Manager::COLOR,
                        'default' => '#000',
                        'selectors' => ['{{WRAPPER}} .acf-input' => 'color: {{VALUE}}',],
                    ]
                        );
        $this->end_controls_section(); 

        $this->start_controls_section(
					
                            'submit',
                            [
                                'label' => __( 'submit', 'client_to_google_sheet' ),
                                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
                            ]
                        );
                        $this->add_control(
                            'show_submit',
                            [
                                'label' => __( 'hide submit', 'plugin-domain' ),
                                'type' => \Elementor\Controls_Manager::SWITCHER,
                                'label_on' => __( 'hide', 'your-plugin' ),
                                'label_off' => __( 'show', 'your-plugin' ),
                                'return_value' => 'hidden',
                                'default' => 'show',
                            ]
                        );
                     $this->add_group_control(

                        Group_Control_Typography::get_type(),
                        [
                            'name' => 'submit_typography',
                            'label' => __( 'Typography', 'client_to_google_sheet' ),
                            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
                                'selector' => "{{WRAPPER}} .acf_submit",
                        ]
                    );
                    $this->add_control(
                        'submit_color',
                        [
                            'label' => __( 'Color', 'client_to_google_sheet' ),
                            'type' => \Elementor\Controls_Manager::COLOR,
                            'default' => '#000',
                            'selectors' => ['{{WRAPPER}} .acf_submit' => 'color: {{VALUE}}',],
                        ]
                            );
                            $this->add_control(
                                'submit_bg_color',
                                [
                                    'label' => __( 'background color', 'client_to_google_sheet' ),
                                    'type' => \Elementor\Controls_Manager::COLOR,
                                    'default' => '#000',
                                    'selectors' => ['{{WRAPPER}} .acf_submit' => 'background-color: {{VALUE}}!important',],
                                ]
                                    );
                                    $this->add_group_control(
                                        Group_Control_Border::get_type(),
                                        [
                                            'name' => 'submit_border',
                                            'label' => __( 'Border', 'plugin-domain' ),
                                            'selector' => '{{WRAPPER}} .acf_submit',
                                        ]
                                    );
                                    $this->add_control(
                                        'border_radus_submit',
                                        [
                                            'label' => __( 'border radius', 'plugin-domain' ),
                                            'type' => Controls_Manager::DIMENSIONS,
                                            'size_units' => [ 'px'],
                                            'selectors' => [
                                                '{{WRAPPER}} .acf_submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                                            ],
                                        ]
                                    );
                     
		 }
  protected function render() {
   /* $groups =[];
    $filds = [];
    foreach(acf_get_field_groups() as $group){
        $option_fild_groups[$group['ID']] = $group['title']. "-" .$group['ID'];

        foreach( acf_get_fields($group['ID']) as $main_fild){
       // var_dump($main_fild);
            if($main_fild['type'] === 'group'){
            $groups[$main_fild['ID']] = $main_fild['name'];
                foreach($main_fild['sub_fields'] as $child_fild){    
                    $filds[$main_fild["name"]."_".$child_fild["name"]] = $child_fild['name'];

                 }
            }
       }
     }
     var_dump($groups);
     var_dump($filds);
     echo "<br> <br> <br>";*/
   
   // 'fields' =>
    $settings = $this->get_settings_for_display();
    if($settings['pid'] != ""){
    $user = (int)get_field( 'owner', $settings['pid'] );
    if($user != get_current_user_id()  && !current_user_can('administrator') && !is_admin())
    return;
   }

    $famely_status = get_field('personal_famely_status', (int)$settings['pid']);
        if($famely_status != 'Married' &&  $famely_status != 'Separated'){
            if (($key = array_search(78, $settings['fild_group_id_array'])) !== false) {
                unset($settings['fild_group_id_array'][$key]);
            }
        }
    $filds = [];
    foreach($settings['fild_group_id_array'] as $group_filds){
     if(!empty($group_filds) && !empty($settings['fild'.$group_filds])){
      $this_group_filds = $settings['fild'.$group_filds] ;
      $filds = $filds + $this_group_filds;
     }
    }
   // $filds_in_grups = array_map('intval',$filds);
    $fild_grups = array_map('intval',$settings['fild_group_id_array']);

    acf_enqueue_uploader(); 
    ?>
         <script type="text/javascript">
           (function($) {
        	acf.do_action('append', $('#popup-id'));	
            });
            </script>
    <?php
      $settungs_acf = array();
      $settungs_acf['html_submit_button'] = "<input type=\"submit\" class=\"acf-button acf_submit button button-primary button-large ".$settings['show_submit']."\" value=\"%s\" />";
      if($settings['ajex'] === 'true')
      $settungs_acf['form_attributes'] =  array(
		'class' => 'ajex',
      );
      //				  var i = "<i class=\"ctggbi fa fa-spinner fa-spin\"></i>"
     // 'html_submit_spinner'	=> '<span class="acf-spinner"></span>',
    //  $settungs_acf['html_submit_spinner'] = "<i class=\"acf-spinner ctggbi fa fa-spinner fa-spin\"></i>";
      $settungs_acf['label_placement'] =   $settings['label_placement'];
      $settungs_acf['field_groups'] =   $fild_grups;
      $settungs_acf['submit_value'] =  $settings['submit_text'];
      $settungs_acf['updated_message'] =  $settings['update_messeg'];
      $settungs_acf['post_content'] = false;
      $settungs_acf['post_title'] = false;
      if($settings['pid'] != "")
      $settungs_acf['post_id'] = $settings['pid'];
      $settungs_acf['uploader'] = 'basic';
      if($settings['url'] != '')
      $settungs_acf['return'] = $settings['url'];
      if(!empty($filds))
     $settungs_acf['fields'] = $filds;
    // var_dump($filds);
  ;
      acf_form($settungs_acf);
      
    
}

    protected function _content_template(){ 
    }
}