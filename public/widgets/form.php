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
use WP_Query;
use New_Form\New_Form_helper;
//use Carousel\CFELE_form_action;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class TZT_forms extends Widget_Base {
  
    public function get_name() {
		return 'new_form'; 
	}

	public function get_title() {
		return __( 'New Form', 'carousel_elementor' );
	}

	public function get_icon() {
		return 'eicon-tabs';
	}
  
	public function get_categories() {
		return [ 'site_widgets' ];
	}
	public function is_reload_preview_required() {
		return true;
	}
    protected function get_slides_ids(){
        //slides-elementor new_form_elementor
        $args=array(
         'post_type' => 'new_form_elementor',
         'post_status' => 'publish'     
          );
     
         $slides = [];
         $query = new WP_Query($args);
         if( $query->have_posts() ){
                 while ($query->have_posts()) : $query->the_post(); 
                    $slides[get_the_ID()] = get_the_title();
                 endwhile; 
                 wp_reset_query();
                 return $slides;
         }
         wp_reset_query();
         $slides["empty"] = "empty";
         return $slides;
                     
    }
    protected function get_cpts(){
        $cpts = get_post_types(array('public' => true));
        unset($cpts["page"]);
        return array_combine ( $cpts , $cpts  );
    }
    protected function _register_controls() {
        $this->start_controls_section(
					
			'content',
			[
				'label' => __( 'Content', 'carousel_elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
			'forms',
			[
				'label' => __( 'Form Src', 'carousel_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $this->get_slides_ids(),
            ]
		);  
    
            $this->add_control(
			'add_tab_note',
			[
				'label' => __( 'Missing a form?', 'carousel_elementor' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => "<a href=\"edit.php?post_type=new_form_elementor\" target=\"_blank\"><i class=\"fa fa-external-link\"></i>".__( 'Add new Form', 'carousel_elementor' )."</a>",
                   
			]
		);    
          $this->end_controls_section();
         $this->start_controls_section(
					
			'after_submit',
			[
				'label' => __( 'After Submit', 'carousel_elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
            $this->add_control(
			'action_after',
			[
				'label' => __( 'actions', 'carousel_elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
                'label_block' => true,
				'options' => [
                    'zaiper' => __( 'zaiper', 'carousel_elementor' ),
                    'tnx' => __( 'retirect', 'carousel_elementor' ),
                    'email' => __( 'email', 'carousel_elementor' ),
                    'google' => __( 'Google Sheet', 'carousel_elementor' ),
                    'save' => __( 'save', 'carousel_elementor' ),
              ]
			]
		);
          $this->add_control(
			'zaiper_url',
			[
				'label' => __( 'Zaiper Url', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                   'condition' => [
					'action_after' => 'zaiper',
				],
			]
		);
           $this->add_control(
			'mail_div',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
                  'condition' => [
					'action_after' => 'zaiper',
				],
			]
		);
                global $current_user;
$current_user = wp_get_current_user();

$email = (string) $current_user->user_email;
        $this->add_control(
			'to_mail',
			[
                'type' => \Elementor\Controls_Manager::TEXT,
				'label' => __( 'Send To:', 'client_to_google_sheet' ),	
                 'default' => $email,
                  'condition' => [
					'action_after' => 'email',
				],
			]
		); 
        $this->add_control(
			'subject',
			[
				'label' => __( 'Subject', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
	            'default' => __( 'new lead', 'client_to_google_sheet' ),
                  'condition' => [
					'action_after' => 'email',
				],
				
			]
		); 
   
        $this->add_control(
			'from_mail',
			[
				'label' => __( 'Send From:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => "admin@mysitedomain.com",	
                  'condition' => [
					'action_after' => 'email',
				],
			]
		);
        $this->add_control(
			'mail_body',
			[
				'label' => __( 'Mail Body:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '[all]',
                'label_block' => true,
                'description' => __( 'Put [all] to add all fields. To add one, put it id, like [MyFieldId]
                ', 'client_to_google_sheet' ),
                  'condition' => [
					'action_after' => 'email',
				],
			]
		);
        $this->add_control(
			'zaiper_url_div',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
                  'condition' => [
					'action_after' => 'email',
				],
			]
		);
        
          $this->add_control(
			'tnx_url',
			[
				'label' => __( 'Tnx Url', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                   'condition' => [
					'action_after' => 'tnx',
				],
			]
		);
        $this->add_control(
			'tnx_url_div',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'style' => 'thick',
                  'condition' => [
					'action_after' => 'tnx',
				],
			]
		);
        
        $this->add_control(
			'sheet_example',
			[
				'label' => __( 'Get Sheet ID:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'ID is the value between the "/d/" and the "/edit" in the URL of your spreadsheet. For example:<br><span> /spreadsheets/d/<b>****</b>/edit#gid=0</span> 
                ', 'client_to_google_sheet' ),
				'content_classes' => 'google_sheet_example',
                   'condition' => [
					'action_after' => 'google',
				],
			]
		);
      	$this->add_control(
			'sheet_id',
			[
				'label' =>  __( 'Sheet id:', 'client_to_google_sheet' ),
                'show_label' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
				  'condition' => [
					'action_after' => 'google',
				],
			]
		);
    
 	  $this->add_control(
			'conect_to_google',
			[
				'label' =>  __( 'Conect Google Sheet Account', 'client_to_google_sheet' ),
                'show_label' => false,
				'type' => \Elementor\Controls_Manager::BUTTON,
				'button_type' => 'success',
				'text' => __( 'conect google drive', 'client_to_google_sheet' ),
				'event' => 'conectgoogleacount',
                  'condition' => [
					'action_after' => 'google',
				],
				
			]
		);
     $this->add_control(
			'conect_to_google__example',
			[
				'label' => __( 'Notice:', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => __( 'Once you have approved access to Google, we\'ll redirect you back to the current page', 'client_to_google_sheet' ),
				'content_classes' => 'google_sheet_example',
                  'condition' => [
					'action_after' => 'google',
				],
			]
		);
  
    $this->add_control(
			'Upgrade_pro',
			[
				'label' => __( 'Upgrade to PRO', 'client_to_google_sheet' ),
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => "<a href=\"http://sheet.webduck.co.il\" target=\"_blank\">
                <button class=\"button button-primary\" style=\"
                  cursor:  pointer;
                  -webkit-appearance: button-bevel;
                 background-color: #84d576;
                  padding:  5px 5px;
                \">". __( 'Need more than 200 per month? Upgrade to PRO', 'client_to_google_sheet' )."</button></a>",
				'content_classes' => 'pro_class',
                'condition' => [
				'action_after' => 'google',
				],
			]
		);
    $this->add_control(
			'form_reg_id',
			[
				'label' =>  __( 'Site ID (Automatically updated, do not change)', 'client_to_google_sheet' ),
                'show_label' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
                  'condition' => [
					'action_after' => 'google',
				],
				
			]
		);
    $this->add_control(
			'form_reg_pas',
			[
				'label' =>  __( 'password (same as above)', 'client_to_google_sheet' ),
                'show_label' => true,
				'type' => \Elementor\Controls_Manager::TEXT,
                  'condition' => [
					'action_after' => 'google',
				],
				
			]
		);

        
        }
    public function render_plain_content() {
       $settings = $this->get_settings_for_display();
    /*   $form =  new \Carousel\CFELE_form_action((int)$settings['forms']); 
        $actions = [];
       foreach($settings['action_after'] as $actiontype){
           	   switch ($actiontype) {		   
                       case "email":
                    array_push($actions,array(
                        "to_mail" => $settings['to_mail'],
                        "subject" => $settings['subject'],
                        "from_mail" => $settings['from_mail'],
                        "mail_body" => $settings['mail_body']   
                    ));
                        break;
                       case "zaiper":
                       array_push($actions,array("name" => "zaiper","url" => $settings['zaiper_url']));
                       break;
                       case "tnx":
                       array_push($actions,array("name" => "tnx","url" => $settings['tnx_url']));
                       break;
                       case "save":
                       array_push($actions,array("name" => "save"));
                       break;
                       case "google":
                       array_push($actions,array("name" => "google",
                                                 "sheet" => $settings['sheet_id'],
                                                "id" => $settings['form_reg_id'],
                                                 "pas" => $settings['form_reg_pas'],
                                                ));
                       break;
                       
             }
       } 
       $form->add_after_submit($actions,$this->get_id(),true);
   */
     }
    protected function render() {
       $widg_helper =  new \New_Form\New_Form_helper("forms"); 
	   $settings = $this->get_settings_for_display();
       $widg_helper->to_render($settings,$this->get_id());

    }
    protected function _content_template(){ 

    }
}