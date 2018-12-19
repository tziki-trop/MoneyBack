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
        
		
		
	}

	public function render() {
        $param_name = $this->get_settings( 'get_name' );
        $terms = get_terms( array(
            'taxonomy' => $param_name,
            'hide_empty' => false,
		) );
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
       /* echo "<select name=\"form_fields[year]\">";
        foreach($terms as $term){
            echo "<option value='".$term->term_id."'>".$term->name."</option>";
        }
        echo "</select>";
  */
	}
}