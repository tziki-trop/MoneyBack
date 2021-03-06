<?php
namespace mb\Tags;
use Elementor\Core\DynamicTags\Tag;
if ( ! defined( 'ABSPATH' ) ) exit;
class TZT_Tag_all_terms extends \Elementor\Core\DynamicTags\Tag {

	public function get_name() {
		return 'all-terms-select';
	}

	public function get_title() {
		return __( 'all terms', 'elementor-pro' );
	}

	public function get_group() {
		return 'meta-variables';
	}

	public function get_categories() {
		return [ \Elementor\Modules\DynamicTags\Module::TEXT_CATEGORY ];
	}
protected function _register_controls() {

		
     
		$this->add_control(
			'get_name',
			[
				'label' => __( 'select textemony', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => get_taxonomies()
				
			]
		);
		$this->add_control(
			'type',
			[
				'label' => __( 'select textemony', 'elementor-pro' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
					"select" => __("select","td"),
					"buttongroup" => __("button group","td")
				]
				
			]
		);

        
		
		
	}

	public function render() {
        $param_name = $this->get_settings( 'get_name' );
        $terms = get_terms( array(
            'taxonomy' => $param_name,
            'hide_empty' => false,
		) );
		if($this->get_settings( 'type' ) === "select"){
		?>
			<div class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-8d26fa3 elementor-col-100 elementor-field-required">
					<label for="form-field-8d26fa3" class="elementor-field-label">סיסמה 2</label>		<div class="elementor-field elementor-select-wrapper ">
			<select name="form_fields[8d26fa3]" id="form-field-8d26fa3" class="elementor-field-textual elementor-size-sm" required="required" aria-required="true">
				<?php
 			foreach($terms as $term){
			echo "<option value='".$term->term_id."'>".$term->name."</option>";
			}
				?>
			</select>
			</div>
			</div>

		<?php
		}
		if($this->get_settings( 'type' ) === "buttongroup"){
			?>
		<div class="elementor-field-group elementor-column elementor-field-type-radio elementor-field-group-message elementor-col-100 elementor-field-required">							
			<div class="elementor-field-subgroup  ">
			<?php
				$index = 0;
				 foreach($terms as $term){
					 //  add_filter('check_if_cpt_exsist', [$this,'check_if_cpt_exsist'], 10, 2);

				//	$year_exist = apply_filters('check_if_cpt_exsist',get_current_user_id(), $term->term_id);
				//	if($year_exist)
				//	continue;
					 ?> 
				<div class="one_year">  
				<span class="elementor-field-option">
				<input value="<?php echo $term->term_id; ?>" type="radio" id="form-field-message-<?php echo $index; ?>" name="form_fields[message]" required="">
				<label for="form-field-message-<?php echo $index; ?>"><?php echo $term->name; ?></label>
				</span>
				 </div>
					 <?php
					 $index ++;
				}
					?>
						</div>
					</div>
			<?php
			} 
	}
}