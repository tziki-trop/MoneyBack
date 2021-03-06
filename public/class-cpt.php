<?php
namespace TaxesCpt;
use WP_Error; 
use WP_Query;

 class Taxes {
    protected $types = [];
    protected $dynamicData = [];
    public function __construct(){
        $this->add_wp_actions();
     }
   
    public function get_Taxes_types( $types ){
        return  array_merge($this->types, $types);
    }
    public function add_wp_actions(){
   // add_filter( 'get_Taxes_types', [$this,'get_Taxes_types']);
    add_action( 'init', [$this,'on_init' ]);
    add_action( 'reg_cpts', [$this,'on_init' ]);
    add_filter('insert_cpt', [$this,'insert_cpt'],10,2 );
    add_action( 'elementor_pro/posts/query/user_posts', [$this,'user_posts'] );

  //  add_action( 'add_meta_boxes_Taxes', [$this,'meta_box' ]);
 //   add_action( 'save_post_Taxes', [$this,'save_meta_box_data' ]);
    add_filter( 'manage_Taxes_posts_columns', [$this,'set_custom_Taxes_column'] );
    add_action( 'manage_Taxes_posts_custom_column' , [$this,'custom_Taxes_column'], 10, 2 ); 
  //  add_filter('acf/fields/google_map/api', [ $this,'my_acf_google_map_api']);
  //  add_action( 'wp_ajax_get_Taxes',  [ $this,'get_Taxes'] );
   // add_action( 'wp_ajax_nopriv_get_Taxes',  [ $this,'get_Taxes' ]);
    add_action( 'elementor_pro/forms/validation', [ $this,'send_email_to_client'],10,2 ); 
   // add_action('acf/pre_save_post' , [ $this,'acf_pre_save'], 10, 1 );
 // add_filter( 'wp_insert_post_empty_content', [$this,'disable_save'], 999999, 2 );
   add_action('acf/save_post', [$this,'acf_save_data'], 20,1);
  // add_action( 'save_post', [$this,'acf_save_data'] );
  add_filter('check_if_cpt_exsist', [$this,'check_if_cpt_exsist'], 10, 2);
  add_filter('check_uploud_filds_condition', [$this,'check_uploud_filds_condition'], 10, 2);

    }

public function check_uploud_filds_condition($fild,$pid){
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
          case 'field_5c7416a3896ba'://personal_Details_leave_outhere
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
          case 'field_5c7416a6896bd':
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
          case 'field_5c5fff46502a2':
          $btl = get_post_meta($pid, 'incum_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי מילואים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820aca':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי מילואים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820acc':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי מילואים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820acd':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי אבטלה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820acf':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי אבטלה", $btl);
          return $key;
         }
         else return false;
          break;
          //field_5c77d3e820ae2	field_5c77d3e820ae4
          case 'field_5c77d3e820ae2':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("קצבת שאירים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ae4':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("קצבת שאירים", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820adf':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דני נכות מעבודה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ae1':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דני נכות מעבודה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820adc':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("גמלת נכות", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ade':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("גמלת נכות", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad9':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("קצבת זקנה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820adb':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("קצבת זקנה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad6':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("פגיעה בעבודה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad8':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("פגיעה בעבודה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad3':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("שמירת הריון", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad5':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("שמירת הריון", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad0':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי לידה", $btl);
          return $key;
         }
         else return false;
          break;
          case 'field_5c77d3e820ad2':
          $btl = get_post_meta($pid, 'incum_partner_btl', true);
         if(is_array($btl)){
          $key = in_array("דמי לידה", $btl);
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
          case 'field_5c7414f075e78':
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
          case 'field_5c7414f175e79':
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
          case 'field_5c7414f175e7a':
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
          case 'field_5c7414f975e7c':
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
          case 'field_5c7414f975e7d':
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
          case 'field_5c7414f175e7b':
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
          case 'field_5c7414eb75e77':
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
          case 'field_5c7414fb75e7e':
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
          return true;
          }
          else return false;
          break;
          case 'field_5c741749896c4':
          $btl = get_post_meta($pid, 'personal_Details_Which_deposit', true);
          if(is_array($btl)){
          return true;
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
          case 'field_5c24d9d5a3f54':
          $btl = get_post_meta($pid, 'incum_btl', true);
          if(is_array($btl)){

          $key = in_array("אחר", $btl);
          return $key;
        }
        else return false;
          break;
          case 'field_5c7414fc75e7f':
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
          case 'field_5c7414fc75e80':
          $data = get_post_meta($pid, 'incum_do_you_get2', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d3e820ae8':
          $data = get_post_meta($pid, 'incum_partner_do_you_get2', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d3e820aea':
          $data = get_post_meta($pid, 'incum_partner_do_you_get2', true);
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
          case 'field_5c741749896c5':
          $data = get_post_meta($pid, 'personal_Details_leave_outhere', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b43	field_5c77d47a20b45
          case 'field_5c77d47a20b43':
          $data = get_post_meta($pid, 'personal_Details_partner_leave_outhere', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b45':
          $data = get_post_meta($pid, 'personal_Details_partner_leave_outhere', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24dacea3f58':
          $data = get_post_meta($pid, 'incum_resing', true);
         if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c7414fc75e82':
          $data = get_post_meta($pid, 'incum_resing', true);
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
          case 'field_5c74152375e84':
          $data = get_post_meta($pid, 'incum_bank', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b1b':
          $data = get_post_meta($pid, 'incum_partner_bank', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b1d':
          $data = get_post_meta($pid, 'incum_partner_bank', true);
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
          case 'field_5c7414fc75e83':
          $data = get_post_meta($pid, 'incum_lottory', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b1e':
          $data = get_post_meta($pid, 'incum_partner_lottory', true);
        //  var_dump($data);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b20':
          $data = get_post_meta($pid, 'incum_partner_lottory', true);
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
          case 'field_5c7416a6896bb':
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
          case 'field_5c7416a7896be':
          $data = get_post_meta($pid, 'personal_Details_personal_Details_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b21 field_5c77d47a20b23 field_5c77d47a20b24 field_5c77d47a20b26
          case 'field_5c77d47a20b21':
          $data = get_post_meta($pid, 'personal_Details_partner_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b23':
          $data = get_post_meta($pid, 'personal_Details_partner_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b24':
          $data = get_post_meta($pid, 'personal_Details_partner_iver_partner', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b26':
          $data = get_post_meta($pid, 'personal_Details_partner_iver_partner', true);
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
          case 'field_5c7416a7896bf':
          $data = get_post_meta($pid, 'personal_Details_mezunot', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b2d':
          $data = get_post_meta($pid, 'personal_Details_partner_mezunot', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b2f':
          $data = get_post_meta($pid, 'personal_Details_partner_mezunot', true);
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
          case 'field_5c7416a8896c0':
          $data = get_post_meta($pid, 'personal_Details_iver', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b30	field_5c77d47a20b32
          case 'field_5c77d47a20b32':
          $data = get_post_meta($pid, 'personal_Details_partner_iver', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b30':
          $data = get_post_meta($pid, 'personal_Details_partner_iver', true);
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
          case 'field_5c7416a6896bc':
          $data = get_post_meta($pid, 'personal_Details_Police', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b36	field_5c77d47a20b38
          case 'field_5c77d47a20b36':
          $data = get_post_meta($pid, 'personal_Details_partner_Police', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b38':
          $data = get_post_meta($pid, 'personal_Details_partner_Police', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c24e05a21117':
          $data = get_post_meta($pid, 'personal_Details_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c74169f896b9':
          $data = get_post_meta($pid, 'personal_Details_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b39	field_5c77d47a20b3b
          case 'field_5c77d47a20b39':
          $data = get_post_meta($pid, 'personal_Details_partner_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b3b':
          $data = get_post_meta($pid, 'personal_Details_partner_lerning', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b40	field_5c77d47a20b42
          case 'field_5c77d47a20b40':
          $data = get_post_meta($pid, 'personal_Details_partner_pay_lala', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b42':
          $data = get_post_meta($pid, 'personal_Details_partner_pay_lala', true);
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
          case 'field_5c741748896c3':
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
          case 'field_5c7416a8896c2':
          $data = get_post_meta($pid, 'incum_more_income', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          //field_5c77d47a20b46	field_5c77d47a20b48
          case 'field_5c77d47a20b46':
          $data = get_post_meta($pid, 'incum_partner_more_income', true);
          if($data == "false" || $data == false || $data == "" || $data == null)
          return false;
          return true;
          break;
          case 'field_5c77d47a20b48':
          $data = get_post_meta($pid, 'incum_partner_more_income', true);
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
          case 'field_5c7416a8896c1':
          $data = get_post_meta($pid, 'personal_Details_offsea_copy', true);
         if($data == "false" || $data == false || $data == "" || $data == null)
         return false;
         return true;
         break;
         //field_5c77d47a20b33	field_5c77d47a20b35
         case 'field_5c77d47a20b33':
         $data = get_post_meta($pid, 'personal_Details_partner_offsea_copy', true);
        if($data == "false" || $data == false || $data == "" || $data == null)
        return false;
        return true;
        break;
        case 'field_5c77d47a20b35':
        $data = get_post_meta($pid, 'personal_Details_partner_offsea_copy', true);
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
         case 'field_5c7414fc75e81':
         $data = get_post_meta($pid, 'incum_do_you_get_else_copy_copy', true);
        if($data == "false" || $data == false || $data == "" || $data == null)
        return false;
        return true;
        break;
          
          
          default:
return true;
          break;
      }
  }
    protected function get_pages_tufes($user = false){
        return array(
            40, 190,372,374,376,378
        );

    }
    public function insert_cpt($user_id,$year){
               // error_log( "insert cpt function");

            $exsist = $this->check_if_cpt_exsist($user_id,$year);
           // return $exsist;
         //   error_log($exsist);
            if($exsist != false)
            return $exsist;
            return  $this->insert_new_cpt($user_id,$year);

    }
    protected function insert_new_cpt($user_id,$year){
       // error_log($year);

      //  tax_input meta_input
      $tax_input  = array(
        'taxes_year' => array(
            (int)$year       
        )
    );

        $args = array(
            'post_type' =>  'taxes',
            'post_status' => 'trash',
            'tax_input' =>  $tax_input,
            'meta_input' => array('owner' => $user_id)
        );
        $pid = wp_insert_post($args);
      $SET_TERMS =  wp_set_object_terms( $pid, (int)$year , 'taxes_year');
      if(is_wp_error($SET_TERMS)){
        error_log($SET_TERMS->get_error_message());
        }
       return $pid;   
    }
    public function user_posts($query){
      $query->set( 'author', get_current_user_id());
        $query->set( 'post_status', 'any');
       $query->set( 'post_type', 'taxes');


    }
    public function check_if_cpt_exsist($user_id,$year){
        $exsist = false;
        $args = array(
            'post_type' =>  'taxes',
            'post_status' => 'any',
            'author' =>  get_current_user_id(),      
            'tax_query' => array(
                    array(
                    'taxonomy' => 'taxes_year',
                    'field'    => 'term_id',
                    'terms'    => array( (int)$year ),
                    'operator' => 'IN',
                        ),            
                    )
                    );
        $wp_query = new WP_Query( $args);
         // error_log( print_r($wp_query, TRUE) );

            $output = $wp_query->have_posts();
            if( $wp_query->have_posts() ){
                while ($wp_query->have_posts()) : $wp_query->the_post();
                return get_the_ID();
                endwhile;
            }
            wp_reset_query();
            return false;
    }
    public function disable_save( $maybe_empty, $postarr ) {
        if ( ! function_exists( 'post_exists' )) {
        require_once( ABSPATH . 'wp-admin/includes/post.php' );
        }
        if(post_exists($postarr['post_title']) && $postarr['post_type'] == 'Taxes' )
        {
            /*This if statment important to allow update and trash of the post and only prevent new posts with new ids*/
            if(!get_post($postarr['ID']))
            {
                  $maybe_empty = true;
            }
        }
        else
        {
            $maybe_empty = false;
        }
    
        return $maybe_empty;
    }
    protected function add_tfasim($added_rows,$pid){
      //  var_dump($added_rows);
        //       wp_die();
            $rows = [];
            $required_tofes_aas = get_field("required", $pid);
            $exixt = false;
            if(!is_array($required_tofes_aas['tofes_aas'])){
                    foreach ($added_rows as $key => $value) {
                        $rows [] = array(
                            'name'	=> $value,
                            'tofes' => '',
                        );
                        # code...
                    }
            }
            else{
                $current_rows = [];
                foreach($required_tofes_aas['tofes_aas'] as $index=>$current_row){
                    $current_rows [] = $current_row['name'];
                    var_dump($current_rows);
                }
                    foreach($added_rows as $index=>$add_row){
                    if(!in_array($add_row, $current_rows)){
                        $rows [] = array(
                            'name'	=> $add_row[''],
                            'tofes' => '',
                        );
                    }
                   
            

                 }
                 var_dump("fore");

                 var_dump($rows);
                $rows = $rows + $required_tofes_aas['tofes_aas'];
                var_dump($rows);

                var_dump($required_tofes_aas['tofes_aas']);
                $required_tofes_aas['tofes_aas'] = $rows;	
                }
                
                update_field("required", $required_tofes_aas, $pid);
     }

     protected function add_tfasim_mosad($pid){
      //  var_dump ( $pid);
       //  return;
        if( have_rows('personal_Details_lerning_detail',$pid) ):
            //    var_dump ( "test");
    
            $rows = [];
            $required_tofes_aas = get_field("more_files", $pid);
          //  var_dump($required_tofes_aas);
            while ( have_rows('personal_Details_lerning_detail',$pid) ) : the_row();
           // echo get_sub_field("name");
                $exixt = false;
               // $new_rows = [];
               //detiels_lerning
               if(is_array($required_tofes_aas['eanlimodim_fiels'])){
                foreach($required_tofes_aas['eanlimodim_fiels'] as $index=>$current_row){
                    if($current_row['detiels_lerning'] === get_sub_field("mosad_name"))
                    $exixt = $index;
                    //sdfsf dfsdfsf 
                }
            }
                if($exixt !== false){
                    $rows [] = $required_tofes_aas['eanlimodim_fiels'][$exixt];
                }
                else  $rows [] = array(
                'detiels_lerning'	=> get_sub_field("mosad_name"),
            );
    
          endwhile;
        //  var_dump($required_tofes_aas);
          $required_tofes_aas['eanlimodim_fiels'] = $rows;		
          update_field("more_files", $required_tofes_aas, $pid);
          endif;
          //var_dump( get_field_object('required') );
    
      
     }
    public function acf_save_data( $post_id ){
        $current_url = wp_get_referer();

      //  $page = get_page_by_path($_SERVER['HTTP_REFERER']);
    //    $page =    str_replace("?tex_id=".$post_id,"",$_SERVER['HTTP_REFERER']);
    //    $page =    str_replace(home_url(),"",$page);
        $page = trim(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH), '/');

        $page = get_page_by_path($page);
         $pages = get_post_meta($post_id, 'pages', true);
   
         if(is_array($pages))
        $pages [] = $page->ID;
         else $pages = array($page->ID);
         update_post_meta($post_id, 'pages', $pages);

         
  //var_dump($page);
  //  wp_die();
    if(isset($_POST['acf']['field_5c19084918bc3']) && $_POST['acf']['field_5c19084918bc3'] != '' ){
      //  var_dump($_POST['acf']);
     //   wp_die();
        $args = array(
            'post_status' => 'wait',
            'ID' => $post_id
        );
        wp_update_post($args);
   }
        // set 
//var_dump($_POST);
//wp_die();
     $rowscount = 0;
    foreach($_POST['acf'] as $group => $val){
        $uoniq = true;
        $rows = get_field('post_groups',$post_id);
        if($rows)
        {
           foreach($rows as $row)
               {
                      if($row['group_key'] === $group)
                     $uoniq = false;
              }
        }
     if($uoniq && !empty($val)){
        $group_detl =  get_field_object($group) ;
        //    ["label"]=> string(23) "פרטים אישיים" ["name"]=> string(8) "personal" 
        $row = array(
            'group_key'	=> $group,
            'group_label' => $group_detl['label'],
            'group_name' => $group_detl['name'],

        );
        $rowscount = add_row( 'post_groups',$row, $post_id );
             }
         }
    if((isset($_POST['acf']['field_5c24e27628aec']) && $_POST['acf']['field_5c24e27628aec'] == "true" )
          || (isset($_POST['acf']['field_5c24e4da654ed']) && $_POST['acf']['field_5c24e4da654ed'] == "true"  )){
            $args = array(
                'post_status' => 'mail',
                'ID' => $post_id
            );
            wp_update_post($args);
    }//field_5c190a65158e2
    else if(isset($_POST['acf']['field_5c19084918bc3'])){
        $args = array(
            'post_status' => 'submitted',
            'ID' => $post_id
        );
        wp_update_post($args);
    }
    else if(isset($_POST['acf']['field_5c190a65158e2'])){
        $args = array(
            'post_status' => 'wait',
            'ID' => $post_id
        );
        wp_update_post($args);
    }  
    else if($rowscount > 1){
             $args = array(
                 'post_status' => 'new',
                 'ID' => $post_id
             );
             wp_update_post($args);
         }



    }
    public function acf_pre_save( $post_id ) {
        if(isset($_POST['acf']['field_5c16c2873fb89'])){
            $bis = [];
            foreach($_POST['acf']['field_5c16c2873fb89'] as $key => $val){
                    $bis [] = $val;
            }
            $this->add_tfasim($added_rows,$post_id);
        }


       // var_dump($post_id);
       // var_dump([$_POST]);
       // wp_die();
       // wp_die();
        if( $post_id === 0 ) {
            if (!session_id())
            session_start();
            $_SESSION['owner'] = get_current_user_id();
        }
        else{
            if(get_current_user_id() != get_post_meta($post_id, 'owner', true))
            return null;
        }
        do_action( 'reg_cpts' );
        return $post_id;
    }

    public function send_email_to_client($record, $ajax_handler){

        $send = $record->get_form_settings( 'form_id' );
        if($send != "lead_to_client")
                return;
        $raw_fields = $record->get( 'fields' );
        foreach ( $raw_fields as $id => $field ) {
            $fields[ $id ] = (string)$field['value'];
        }
        $email = get_post_meta($fields[ 'pid' ] , 'email' , true);

        $data = array_merge($fields, get_post_meta($fields[ 'pid' ]));
        $user_data = get_field( "owner" , $fields[ 'pid' ] );
        $data = array_merge( $data , $user_data);
        do_action('send_castum_email_temp','send_messeg',$email,$data);
    }
  
    public function get_Taxes(){	  
   
     if ( !wp_verify_nonce( $_POST['nons'], 'validate' )){
        echo json_encode (array('status' => "error",'error' =>'no validate' ) );
        wp_die();
     }

     if(!isset($_POST['type']) || !isset($_POST['id'])){
      echo json_encode ( array('status' => "error",'error' =>'no bus' ) );
         wp_die();
     }
    //global $wp_query;
    $args=array(  
        'post_type' => 'taxes',
        'post_status' => 'publish',
        'post__in' =>  array((int)$_POST['id'])
    );

    $wp_query = new WP_Query( $args);
    //$wp_query = new WP_Query( ); sdsdf
    
        $output = $wp_query->have_posts();
        if( $wp_query->have_posts() ){
        //$output = "test1";
        //test t
        while ($wp_query->have_posts()) : $wp_query->the_post();
               ob_start();
               //echo get_the_ID(); 
               //$type =  get_post_meta( get_the_ID() , 'Taxes_type' , true ); 
               elementor_theme_do_location( 'aTaxes_extended_'.$_POST['type'] );
               $output = ob_get_contents();
               ob_end_clean();
        endwhile;
       
        }   
        //wp_reset_query();
        echo json_encode(array('status' => "seccsee",'data' => $output ));
        wp_die();
      
}
  
    public function set_custom_Taxes_column($columns){
       $columns['type'] = __( 'Type', 'Taxes-management' );
       return $columns;
    }
    public function custom_Taxes_column( $column, $post_id ) {
    switch ( $column ) {
    case 'type' :
            echo get_post_meta( $post_id , 'Taxes_type' , true ); 
            break;

    }

    }
    protected function register_status(){
   if( have_rows('cpt_status','option') ):

    // loop through the rows of data
   while ( have_rows('cpt_status','option') ) : the_row();

       // display a sub field value
       // $status =  get_sub_field('status');
        if( have_rows('status') ): 
        while( have_rows('status')) : the_row();
        $status =[];
        $status['name'] =  get_sub_field('name');
        $status['val'] =  get_sub_field('val');  
   register_post_status($status['val'] , array(
    'label'                     => $status['name'],
    'public'                    => true,
    'exclude_from_search'       => false,
    'show_in_admin_all_list'    => true,
    'show_in_admin_status_list' => true,
    'label_count'               => _n_noop( $status['name'].' <span class="count">(%s)</span>', $status['name'].' <span class="count">(%s)</span>' ),
    ) );
   
        endwhile; 	
    endif;

   endwhile;

else :

   // no rows found

endif;


    }
     public function on_init(){
                 acf_form_head(); 
                 if( !session_id() )
                 session_start();
  
        register_post_type( 'taxes',
          array(
        'labels' => array(
         'name' => __( 'Taxes', 'donat'),
         'singular_name' => __( 'Taxes', 'donat'),
         'add_new' => __('Add Taxes','donat'),      
              'add_new_item' => __('Add Taxes','donat')
              ),
                 'show_in_menu' => true,
                 'show_ui' => true,
                'public' => true,
               'has_archive' => true,
               'supports' => array('title','editor')
               )
             );
  $labels_textemony = array(
    'name'              => _x( 'Year', 'taxonomy general name', 'textdomain' ),
    'singular_name'     => _x( 'Year', 'taxonomy singular name', 'textdomain' ),
    'search_items'      => __( 'Search Year', 'textdomain' ),
    'all_items'         => __( 'All Year', 'textdomain' ),
    'parent_item'       => __( 'Parent Year', 'textdomain' ),
    'parent_item_colon' => __( 'Parent GeYearnre:', 'textdomain' ),
    'edit_item'         => __( 'Edit Year', 'textdomain' ),
    'update_item'       => __( 'Update Year', 'textdomain' ),
    'add_new_item'      => __( 'Add New Year', 'textdomain' ),
    'new_item_name'     => __( 'New Year Name', 'textdomain' ),
    'menu_name'         => __( 'Year', 'textdomain' ),
);

$args_tex = array(
    'hierarchical'      => true,
    'labels'            => $labels_textemony,
    'show_ui'           => true,
    'show_admin_column' => true,
    'query_var'         => true,
    'rewrite'           => array( 'slug' => 'taxes_year' ),
);
    
register_taxonomy( 'taxes_year', array( 'taxes' ), $args_tex );
  //var_dump($test);
  //wp_die();
  $this->register_status();

           }
 
        }
new Taxes();