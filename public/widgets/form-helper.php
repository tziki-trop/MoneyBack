<?php
namespace New_Form;
use WP_Query;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class New_Form_helper {
    protected $type;
   // protected $settings = [];
   public function __construct($type) {
      $this->type =  $type;
    }
    
   
protected function set_query($settings){
      $args = array('post_status' => 'publish');    
      $args['post_type'] = 'new_form_elementor';
      $args['post__in'] =  array ((int)$settings['forms']);
        return $args;
}           
    //repeater  ddddd

    

 protected function get_form_torender($settings,$id){
           $args = $this->set_query($settings);
        if($args === false)
            return;
       $query = new WP_Query($args);
         if( !$query->have_posts() )
             return;
             while ($query->have_posts()) : $query->the_post(); 
                     ?>
                    <div class="one_form">
                    <form class="new_form_ele" data-form-id="<?php echo get_the_ID();?>" data-element-id="<?php echo $id;?>">
                	      <?php wp_nonce_field( 'validate', 'new_form_nonce' ); ?>
						<?php
							$elementor  = get_post_meta( get_the_ID(), '_elementor_edit_mode', true );							
							if ( $elementor ) {
								$frontend = new \Elementor\Frontend;
								echo $frontend->get_builder_content( get_the_ID(), true );
							}
                            else {
                                the_content();
							}
						?>
                      </form>
					</div>
                    <?php
                endwhile; 
 }

 private function get_one_fild($field){
    switch ($field['filed_type']) {		   
        case "textarea":
          $this->textarea_field($field);
        break;
        case "select":
          $this->select_field($field);
        break;
        case "true_false":
         $this->true_false_field($field);
        break;
        case "checkbox" :
        $this->checkbox_field($field);
        break;
        default:
        $this->default_field($field);
         
    }
 }
private function set_feiled($field,$id){
    if( empty($field['filed_name']))
        $field['filed_name'] = $id;
        $field['filed_id'] = $field['filed_name'] ;

    if($field['js_conditition'] === "true"){
        ?>
            <span class='js_conditition' data-is-hidden='true'
             style="display: none;"
             data-fild="<?php echo $field['js_conditition_fild'];?>"
             data-fild-val="<?php echo $field['js_conditition_val'];?>" >
    
        <?php
    }

    if(isset($_GET['tex_id'])){
        $val = get_post_meta($_GET['tex_id'],$field['filed_name'], false);
        if(empty($val))
        $val [] = '';
       //var_dump($val);
    }
    else  $val [] = '';
    if( $field['reperter'] === "true"){
        $name = $field['filed_name'];
      foreach($val as $index => $value){
        if($index === 0)
        echo "<span class='repet'>";
        $field['filed_name'] = "reperter---".$name."---".$index;
        $field['filed_value'] = $value;
        $this->get_one_fild($field);
        if($index === 0)
        echo "</span>";
      }
      $this->get_add_remove();

    }
    else {
        $field['filed_value'] = $val[0];
        $this->get_one_fild($field);
    }
    if($field['js_conditition'] === "true")
    echo "</span>";
    return true;
   }
   private function get_add_remove(){
       ?>
        <div class="re_buttons">

        <div class="add_row_to_re">add</div>
        <div class="remove_row_to_re">remove</div>
       </div>
       <?php
   }
    private function default_field($field){
 
        ?>
            <div class="worrper_new_input <?php echo $field['filed_type']; ?> <?php echo $field['label_type']; ?>">
                <div class="label fild_<?php echo $field['filed_name']; ?> ">
                <label for="<?php echo $field['filed_id']; ?>">
                <i class="<?php echo $field['filed_icon']; ?>" aria-hidden="true"></i>
                 <?php echo $field['filed_label']; ?>
                </label>
                </div>
                <div class="filed fild_<?php echo $field['filed_name']; ?> ">
                    <input class="fil_val" type="<?php echo $field['filed_type']; ?>"
                     name="<?php echo $field['filed_name']; ?>" id="<?php echo $field['filed_id']; ?>"
                     value="<?php echo $field['filed_value']; ?>">
                </div>
            </div>

        <?php
    }
    private function checkbox_field($field){
        if(	$field['multy_coisec'] === 	"checkbox")
        $type = "checkbox";
        else  $type = "radio";
        ?>
        <div class="worrper_new_input <?php echo $field['filed_type']; ?> <?php echo $field['label_type']; ?>">
       <?php
            foreach($field['checkbox_option'] as $option)
            {
              ?>
              <div class="one_ch">
            <label for="<?php echo $field['filed_id']; ?>_<?php echo $option['option_val']; ?>">
               <input class="fil_val" type="<?php echo $type; ?>" class="as_button" id="<?php echo $field['filed_id']; ?>_<?php echo $option['option_val']; ?>" 
                   name="<?php echo $field['filed_name']; ?>" <?php echo $field['required']; ?> <?php echo $field['readonly']; ?>  
                   value="<?php echo $option['option_val']; ?>"
                 <?=$field['filed_value'] == $option['option_val'] ? 'checked' : '';?>/>
                <span>
                    <i class="<?php echo $field['icon_label']; ?> <?php echo $option['option_icon']; ?>" aria-hidden="true"></i><?php echo $option['option_name']; ?>
               </span>
             </label>
             </div>
            <?php 
              }
        ?>
         </div>

        <?php
    }
    private function select_field($field){
	
        ?>
        <div class="worrper_new_input <?php echo $field['filed_type']; ?> <?php echo $field['label_type']; ?>">
        <div class="label <?php echo $field['filed_name'];?>">
        <label for="<?php echo $field['filed_type']; ?>">
        <i class="<?php echo $field['filed_icon']; ?>" aria-hidden="true"></i><?php echo $field['filed_label']; ?></label>
        </div>
        <select class="fil_val" name="<?php echo $field['filed_name']; ?>"id="<?php echo $field['filed_id']; ?>"
         <?php echo $field['required']; ?> <?php echo $field['readonly']; ?>>
         <?php
            foreach($field['select_option'] as $option)
            {
              ?>
              <option value="<?php echo $option['option_val']; ?>"<?=$field['filed_value'] == $option['option_val'] ? ' selected="selected"' : '';?>><?php echo $option['option_name']; ?></option>
              <?php
            }
        ?>
         </select>
         </div>
         </div>

        <?php
    }
    private function true_false_field($field){
        //$field['filed_value'] = true;
        if($field['filed_value'] == true)
          $ceck  = " checked ";
          else $ceck = '';
         ?>
        <div class="worrper_new_input <?php echo $field['filed_type']; ?> <?php echo $field['label_type']; ?>">
        <div class="label <?php echo $field['filed_name'];?>">
        <div class="filed <?php echo $field['filed_name'];?>">

        <label class="switch">
       <input <?php echo $ceck ?> type="checkbox" name="<?php echo $field['filed_name']; ?>"><span class="slider"></span></label>
       </div></div></div>
        <?php

    }    
	private function textarea_field($field){

        ?>
        <div class="worrper_new_input <?php echo $field['filed_type']; ?> <?php echo $field['label_type']; ?>">
        <div class="label <?php echo $field['filed_name'];?>">
        <label for="<?php echo $field['filed_type']; ?>">
        <i class="<?php echo $field['filed_icon']; ?>" aria-hidden="true"></i><?php echo $field['filed_label']; ?></label>
        </div>
        <textarea class="fil_val" rows="<?php echo $field['filed_rows']; ?>"
         name="<?php echo $field['filed_name']; ?>"
         id="<?php echo $field['filed_id']; ?>"
         <?php echo $field['required']; ?> <?php echo $field['readonly']; ?>>
         <?php echo $field['filed_value']; ?>
         </textarea></div></div>

        <?php
	}
	private function conditition($field){
        if($field['conditition'] != "true")
        return true;
        if(!isset($_GET['tex_id']))
        return false;
        $post_id  = $_GET['tex_id'];
        $val = get_post_meta($post_id, $field['conditition_fild'], true);
        foreach($field['conditition_val'] as $val_to_check){
        if($val === $val_to_check['one_conditition_val'])
        return true;
        }
        return false;
    }
	
  public function to_render($settings,$id){
         switch ($this->type) {

            case "forms":
            $this->get_form_torender($settings,$id);  
            break;
            case "input":
            if($this->conditition($settings))
            $this->set_feiled($settings,$id);
            break;           
            default:
           return false;    
          }
         }
  
}