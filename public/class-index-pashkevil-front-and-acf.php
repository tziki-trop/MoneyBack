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
            $fildsforgroup[ $fild['key'] ] = $fild['name'];
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
 protected function add_tfasim($pid){
    if( have_rows('placeOfWork_bizz_names',$pid) ):
        $rows = [];
        $required_tofes_aas = get_field("required", $pid);
      //  var_dump($required_tofes_aas);
        while ( have_rows('placeOfWork_bizz_names',$pid) ) : the_row();
       // echo get_sub_field("name");
            $exixt = false;
           // $new_rows = [];
           if(is_array($required_tofes_aas['tofes_aas'])){
            foreach($required_tofes_aas['tofes_aas'] as $index=>$current_row){
                if($current_row['name'] === get_sub_field("name"))
                $exixt = $index;
                //sdfsf dfsdfsf 
            }
        }
            if($exixt !== false){
                     //  var_dump($required_tofes_aas);

                $rows [] = $required_tofes_aas['tofes_aas'][$exixt];
            }
            else  $rows [] = array(
            'name'	=> get_sub_field("name"),
            'tofes' => '',
        );

      endwhile;
    //  var_dump($required_tofes_aas);
      $required_tofes_aas['tofes_aas'] = $rows;	
    //  var_dump($required_tofes_aas);
	
      update_field("required", $required_tofes_aas, $pid);

  // var_dump( get_field("required",$pid) );
      endif;
      //var_dump( get_field_object('required') );

  
 }
 protected function add_tfasim_mosad($pid){
  if( have_rows('personal_Details_lerning_detail',$pid) ):
      $rows = [];
        $required_tofes_aas = get_field("mosad_limudim", $pid);
        while ( have_rows('personal_Details_lerning_detail',$pid) ) : the_row();
            $exixt = false;
           if(is_array($required_tofes_aas['eanlimodim_fiels'])){
            foreach($required_tofes_aas['eanlimodim_fiels'] as $index=>$current_row){
                if($current_row['detiels_lerning'] === get_sub_field("mosad_name"))
                $exixt = $index;
            }
        }
            if($exixt !== false){
                $rows [] = $required_tofes_aas['eanlimodim_fiels'][$exixt];
            }
            else  $rows [] = array(
            'detiels_lerning'	=> get_sub_field("mosad_name"),
        );

      endwhile;
      $required_tofes_aas['eanlimodim_fiels'] = $rows;		
      update_field("mosad_limudim", $required_tofes_aas, $pid);
    //  echo "true";
      return true;
     else:
       // echo "false";

        return false;
      endif;
    
    
 }
  protected function check_logic($group,$pid){
     // if($group != 'field_5c4e2d0324a10')
    //  return true;
   //   else return true;
     //  echo get_field('field_5c4e27e9b4ea9',(int)$pid);
     //do_you_get_else_copy_copy
      // echo get_post_meta((int)$pid, 'incum_do_you_get_else_copy_copy', true);
        if( have_rows('condition','option') ):
        while ( have_rows('condition','option') ) : the_row();
        if($group === get_sub_field("fild")){
           $con_fild = get_field(get_sub_field("con_fild"), (int)$pid) ?: false;
           $con_fild =  get_post_meta((int)$pid, get_sub_field("con_fild"), true);
           if(empty($con_fild))
           $con_fild = false;
           //var_dump( $con_fild);
            while ( have_rows('vals') ) : the_row();
            $layout = get_row_layout();
            if( $layout == 'values' ){
                if(is_array($con_fild)){    
                    if(in_array(get_sub_field('val'), $con_fild))
                    return true;
                }
                else if(get_sub_field('val') === $con_fild){
                    return true;
                }      
            }
            else if( $layout == 'true_false' ){
              //  echo "trur false";
              //  var_dump( get_sub_field('true_false') );
               if(get_sub_field('true_false'))
                $sub_val = "true";
                else $sub_val = "false";
         
                if($sub_val === (string)$con_fild){
                   return true;
                }
                else return false;
            }
               
    
            endwhile;

    
            return false;
        }
        endwhile;
        endif;
        return true;

  }
  protected function check_uploud_filds($fild,$pid){
     $pid =(int)$pid;
   //  var_dump($pid);
      switch($fild){
          //field_5c24de1e21106
          case 'field_5c24de1e21106':
         $btl2 = get_post_meta($pid, 'personal_Details_chileds_childs_how_hold_the_boy', true);
            if(!is_array($btl2))
            return false;
            if(in_array("ילד זה נמצא במוסד טיפול מיוחד", $btl2) || in_array("ילד בעל לקות למידה", $btl2))
            return true;
            break;
          case 'field_5c24ddd721104'://personal_Details_leave_outhere
          $btl = get_post_meta($pid, 'personal_Details_personal_Details_iver_partner', true);
          if($btl == "false" || $btl == false || $btl == "" || $btl == null){
            $btl2 = get_post_meta($pid, 'personal_Details_chileds_childs_how_hold_the_boy', true);
            if(!is_array($btl2))
            return false;
            if(in_array("ילד זה נטול יכולת", $btl2) || in_array("ילד זה נמצא במוסד טיפול מיוחד", $btl2) || in_array("ילד בעל לקות למידה", $btl2))
            return true;
          }
          else return true;
         if(is_array($btl)){
          $key = in_array("דמי מילואים", $btl);
          return $key;
         }
         else return false;
          break;
          //field_5c24de5321108
          case 'field_5c24de5321108':
          $btl = get_post_meta($pid, 'personal_Details_chileds_childs_how_hold_the_boy', true);
         if(is_array($btl)){
          $key = in_array("ילד בעל לקות למידה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c1b971f86b32':
          $btl = get_post_meta($pid, 'incum_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי מילואים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c24d8aca3f4a':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){
          $key = in_array("דמי אבטלה", $btl);
          return $key;
          }
        else return false;
          break;
          case 'field_5c24d91ea3f4c':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){
          $key = in_array("דמי לידה", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d962a3f4e':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("שמירת הריון", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d971a3f4f':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("פגיעה בעבודה", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d98ba3f50':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("קצבת זקנה", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d99ea3f51':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("גמלת נכות", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d9aea3f52':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("דני נכות מעבודה", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24d9bfa3f53':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("קצבת שאירים", $btl);
          return $key;
        }
        else return false;
          break;
          //field_5c24e08821119
          case 'field_5c24e08821119':
          $btl = get_post_meta($pid, 'personal_Details_Which_deposit', true);
          if(is_array($btl)){
         // $key = in_array("קצבת שאירים", $btl);
          return true;
          }
          else return false;
          case 'field_5c24d9bfa3f53':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("קצבת שאירים", $btl);
          return $key;
        }
        else return false;
          break;     
          case 'field_5c24d9d5a3f54':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("אחר", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c24da20a3f55':
          $data = get_post_meta($pid, 'incum_do_you_get2', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24e11b2111d':
          $data = get_post_meta($pid, 'personal_Details_leave_outhere', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24dacea3f58':
         // var_dump("test");
          //field_5c16c3320ba99 resing
          $data = get_post_meta($pid, 'incum_resing', true);
                  //  var_dump($data);

          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c16c3320ba99
          case 'field_5c24dd5e21100':
          $data = get_post_meta($pid, 'incum_bank', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24dd9921102':
          $data = get_post_meta($pid, 'incum_lottory', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24de1e21106':
          $data = get_post_meta($pid, 'personal_Details_personal_Details_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24de872110a':
          $data = get_post_meta($pid, 'personal_Details_personal_Details_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24def32110c':
          $data = get_post_meta($pid, 'personal_Details_mezunot', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24df282110e':
          $data = get_post_meta($pid, 'personal_Details_iver', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24df8621113':
          $data = get_post_meta($pid, 'personal_Details_Police', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
     /*     case 'field_5c24dfb821115':
          $data = get_post_meta($pid, 'personal_Details_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break; */
          case 'field_5c24e05a21117':
          $data = get_post_meta($pid, 'personal_Details_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24e0e32111b':
          $data = get_post_meta($pid, 'personal_Details_pay_lala', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24e1482111f':
          $data = get_post_meta($pid, 'incum_more_income', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c476c9f65b8d':
         // var_dump("t");
          $data = get_post_meta($pid, 'how_is_reporting', true);
         // var_dump ($data);
          if($data == "הכנסותי והכנסות בן/בת זוגי" )
          return true;
          return false;
          break;
          case 'field_5c4e27e9b4ea9':
           $data = get_post_meta($pid, 'personal_Details_offsea_copy', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c4e2d0324a10':

          $data = get_post_meta($pid, 'incum_do_you_get_else_copy_copy', true);
        // var_dump($data);
         if($data == "false" || $data == false || $data == "" || $data == null)
         return false;
         return true;
         break;
          
          
          default:
return true;
          break;
      }
  }
  protected function render() {
   
     
  // var_dump( wp_count_posts( 'taxes' ) );


    $settings = $this->get_settings_for_display();
   
    $famely_status = get_field('personal_famely_status', (int)$settings['pid']);
    if($famely_status != ''){
        if($famely_status != 'Married' &&  $famely_status != 'Separated'){
            if (($key = array_search(78, $settings['fild_group_id_array'])) !== false) {
                unset($settings['fild_group_id_array'][$key]);
            }
        }
    }

    $filds = [];
    foreach($settings['fild_group_id_array'] as $group_filds){
     if(!empty($group_filds) && !empty($settings['fild'.$group_filds])){
      $this_group_filds = $settings['fild'.$group_filds] ;
      foreach($this_group_filds as $group_to_check){
          // בדיקת טופס 106
          if($group_to_check === "field_5c1b953adb990"){
          $this->add_tfasim($settings['pid']);
          $filds [] = $group_to_check;
          continue;
          }
         // בדיקת טופס מוסד לימודים
          if($group_to_check === "field_5c52e2b12d4e0"){
           if($this->add_tfasim_mosad($settings['pid']))
            $filds [] = $group_to_check;
            continue; 
          }
        //  var_dump($group_to_check);
          if($group_to_check === "field_5c190a65158e2"){
            if( !is_user_logged_in() ) 
            return '';
             $user = wp_get_current_user();
             $role = ( array ) $user->roles;
             if(in_array("cpa",$role)){
                return '';
             }
         
           } 
          //field_5c190a65158e2
           // בדיקת טפסי העלאת קבצים לפי תנאים ספציפיים
          if( /* $group_to_check === 'field_5c1b953adb990' || */ $group_to_check === 'field_5c1b970186b31'  || $group_to_check === 'field_5c24daa6a3f57'){
              $fild_obj = get_field_object($group_to_check);
              foreach($fild_obj['sub_fields'] as $one_fild){
                if( apply_filters('check_uploud_filds_condition',$one_fild['key'],$settings['pid']))
                $filds [] =  $one_fild['key'];
 
              }
              continue;
          }
          // בדיקת לוגיקה לפי גרופים
         if($this->check_logic($group_to_check,$settings['pid']))
         $filds [] = $group_to_check;
      }
     }
    }
    $fild_grups = array_map('intval',$settings['fild_group_id_array']);


      $settungs_acf = array();
      $settungs_acf['html_submit_button'] = "<input type=\"submit\" class=\"acf-button acf_submit button button-primary button-large ".$settings['show_submit']."\" value=\"%s\" />";
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