<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_acf_group_text extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'acf_group_text';
	}

	public function get_title() {
		return __( 'acf_group_text', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
protected function _register_controls() {
        $option_fild_groups = [];
        $groups =[];
        $filds = [];
        $fildsbygroup = [];

        foreach(acf_get_field_groups() as $group){
            $option_fild_groups[$group['ID']] = $group['title']. "-" .$group['ID'];
    
        foreach( acf_get_fields($group['ID']) as $main_fild){
           // var_dump($main_fild);
                if($main_fild['type'] === 'group'){
                $groups[$main_fild['name']] = $main_fild['name'];
                $fildsforgroup = [];
                    foreach($main_fild['sub_fields'] as $child_fild){    
                        $filds[$main_fild["name"]."_".$child_fild["name"]] = $child_fild['name'];
                       // array_push($fildsforgroup,)
                        $fildsforgroup [$main_fild["name"]."_".$child_fild["name"]] = $child_fild['name'];
                      //  $fildsbygroup[$main_fild["name"]] = array(
                       //     $main_fild["name"]."_".$child_fild["name"] => $child_fild['name']
                      //  );
                     }
                     $fildsbygroup[$main_fild["name"]] = $fildsforgroup;

                }
           }
         }      //   var_dump($filds);
         $this->add_control(
			'groups',
			[
				'label' => __( 'group', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => $groups,
            
			]
        );
        foreach($fildsbygroup as $group => $filds){
            $this->add_control(
                'fild'.$group,
                [
                    'label' => __( 'feild', 'plugin-domain' ),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'options' => $filds,
                    'condition' => [
                        'groups' => $group,
                    ],
                   
                ]
            );
        }
      /*   $this->add_control(
			'fild',
			[
				'label' => __( 'group', 'plugin-domain' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $filds,
                
            
			]
        );*/

        
		
		
    }
    protected function print_rep($fild,$sabs,$group_name){
        if(empty($group_name))
        $fild_name = $fild;
        else $fild_name = $group_name."_".$fild;
       
     //   echo $group_name;
   if( have_rows($fild_name,get_the_ID()) ):
  //  echo "rrrr sdfsdf";
   while ( have_rows($fild_name,get_the_ID()) ) : the_row();

        echo "<span class='one'>";
        foreach($sabs as $sab){
          //  echo $sab['name'];
            echo "<span>";
          $fffid =   get_sub_field($sab['name']);
          if(is_array( $fffid) && isset($fffid['url']) ){
              ?>
              <span class="pri_img">
              <a target="_blank" href="<?php echo $fffid['url']  ?>">
              להורדה
        <!--   <img width="150" height="150" src="<?php echo $fffid['url']  ?>" class="attachment-large size-large" alt="" sizes="(max-width: 920px) 100vw, 920px"> -->
            </a>
            </span>

              <?php
          }
          else  echo $fffid;
             
            echo "</span>";
        }
        echo "</span>";
   endwhile;

else :
echo "אין";
endif;

    }
    protected function print_fild($fild){
        switch( $fild ){
            case false :
            echo "לא";
            break;
            case true :
            echo "כן";
            break;
            default:
            the_field($fild,get_the_ID());
        }
    }
    protected function get_loop_acf($obj,$group_name = false){
        if($obj['type'] != "repeater" && isset($obj["sub_fields"]) && is_array($obj["sub_fields"]) && !empty($obj["sub_fields"])){
            foreach($obj["sub_fields"] as $one){
                    $this->get_loop_acf($one,$obj["name"]);
            }
        }
        else{
           // echo $obj['type'];
            if($obj['type'] =="repeater"){
              //  var_dump( $obj) ;
                $this->print_rep($obj['name'],$obj['sub_fields'],$group_name);

            }
            else{
                $filr_to_print =  get_field($obj['name'],get_the_ID());
                if($filr_to_print == "false")
                echo "לא";
                else if($filr_to_print == "true")
                echo "כן";
                else if(!is_array($filr_to_print))
                echo $filr_to_print;
                else{
                    foreach($filr_to_print as $one_fild){
                        echo $one_fild;
                    }
                }
              //  $this->print_fild(get_field($obj['name'],get_the_ID()));
//echo get_field($obj['name'],get_the_ID());
            }
           // var_dump( get_field($obj['key'],get_the_ID()));
         //  the_field($obj['name'],get_the_ID());
          //  var_dump($obj['key']);

        }    
    }  
	public function render() {
        $group =  $this->get_settings( 'groups' );
        $fild =  $this->get_settings( 'fild'.$group );
        $obj = get_field_object($fild);
  
        $this->get_loop_acf($obj);

      //  echo $fild;


	}
}