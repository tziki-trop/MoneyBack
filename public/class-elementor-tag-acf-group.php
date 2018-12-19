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

	public function render() {
        $group =  $this->get_settings( 'groups' );
        $fild =  $this->get_settings( 'fild'.$group );
      //  echo $fild;
        the_field($fild,get_the_ID());

	}
}