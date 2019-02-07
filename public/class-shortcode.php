<?php
namespace Taxesshortcode;
use WP_Error; 
use WP_Query;

class TaxesShortCode {
    public function __construct(){
        $this->add_wp_actions();
     }
     public function add_wp_actions(){
        add_shortcode('add_filter', [$this,'add_filter']);
        add_shortcode('acf_repeter', [$this,'acf_repeter']);

         add_shortcode('status_cpt_tex', [$this,'status_cpt_tex']);
         add_shortcode('get_cpts_tabole', [$this,'get_cpts_tabole']);
         add_action( 'wp_ajax_foobar', [$this,'ajax_filter_cpts'] );
        add_action('wp_ajax_loadmore', [$this,'status_cpt_texfsdf']); 
        add_action('wp_ajax_nopriv_loadmore', [$this,'status_cpt_texfsdf']); 
        add_action('wp_ajax_update_status', [$this,'update_status']); 
        add_action('wp_ajax_nopriv_update_status', [$this,'update_status']); 
        add_filter('wp_nav_menu_items', [$this,'add_to_menu'], 20, 2);

            }
     public function acf_repeter( $atts = [] ){
        $atts = array_change_key_case((array)$atts, CASE_LOWER);
 
        // override default attributes with user attributes
        $wporg_atts = shortcode_atts([
                                         'main' => '',
                                         'sub' => '',
                                         'sub1' => '',
                                     ]
                                     , $atts);
         $o = '';
        if( have_rows($wporg_atts['main'],get_the_ID()) ):
        $in = 0;
         $o .= '<div>';
        while ( have_rows($wporg_atts['main'],get_the_ID()) ) : the_row();
        $in++;
        $o .= '<p>&nbsp;'.$in.'.&nbsp;';
        $o .= get_sub_field($wporg_atts['sub']);
        $o .= '</p>';      
        if($wporg_atts['sub1'] != ''){
            $o .= '<p class=\'dis\'>';
            $o .= get_sub_field($wporg_atts['sub1']);
            $o .= '</p>';  
        }
                    endwhile;
                    $o .= '</div>';
                else:
                    $o .= '<p>עדיין ריק פה </p>';
   
       endif;
       return $o;
              
      }
public function add_filter(){
    ob_start();
    ?>
    <form class="elementor-form three_row" method="post" name="New Form">
	<div class="elementor-form-fields-wrapper elementor-labels-above">
		<div class="elementor-field-type-number elementor-field-group elementor-column elementor-field-group-pid elementor-col-100">
					<label for="form-field-pid" class="elementor-field-label">מספר תיק</label><input type="number" name="form_fields[pid]" id="form-field-pid" class="elementor-field elementor-size-sm  elementor-field-textual" placeholder="מספר תיק" min="" max="">				</div>
		<div class="elementor-field-type-date elementor-field-group elementor-column elementor-field-group-from_date elementor-col-100">
					<label for="form-field-from_date" class="elementor-field-label">מתאריך</label><input type="date" name="form_fields[from_date]" id="form-field-from_date" class="elementor-field elementor-size-sm elementor-field-textual elementor-date-field flatpickr-input" placeholder="מתאריך" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">				</div>
		<div class="elementor-field-type-date elementor-field-group elementor-column elementor-field-group-to_date elementor-col-100">
					<label for="form-field-to_date" class="elementor-field-label">עד תאריך</label><input type="date" name="form_fields[to_date]" id="form-field-to_date" class="elementor-field elementor-size-sm elementor-field-textual elementor-date-field flatpickr-input" placeholder="מתאריך" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}">				</div>
								<div class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-status elementor-col-100">
					<label for="form-field-status" class="elementor-field-label">סטטוס</label>		<div class="elementor-field elementor-select-wrapper ">
		<select name="form_fields[status]" id="form-field-status" class="elementor-field-textual elementor-size-sm">
		<option value="any">- בחר אפשרות -</option>
        <?php 
    foreach( $this->get_cpt_by_status() as $status => $args ){
      //  var_export($args);
        ?>
		<option value="<?php echo $status; ?>"><?php echo $args['name']; ?></option>
        <?php 
    }
     ?>
        </select>
		</div>
						</div>
								<div class="elementor-field-type-select elementor-field-group elementor-column elementor-field-group-cpa elementor-col-100">
					<label for="form-field-cpa" class="elementor-field-label">משוייך ל:</label>		<div class="elementor-field elementor-select-wrapper ">
		<select name="form_fields[cpa]" id="form-field-cpa" class="elementor-field-textual elementor-size-sm">
        <option value="">- בחר אפשרות -</option>
        <?php 
        $blogusers = get_users( [ 'role__in' => [ 'Administrator', 'cpa' ] ] );
// Array of WP_User objects.
foreach ( $blogusers as $user ) {
    ?>
    <option value="<?php echo $user->ID; ?>"><?php echo $user->display_name; ?></option>
    <?php 
}
?>
        </select>
		</div>
						</div>
		<div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100">
		<button type="submit" class="elementor-button elementor-size-sm">
		<span>
		<span class="elementor-button-text">סינון</span>
		</span>
		</button>
		</div>
	</div>
</form>

    <?php
      $output = ob_get_contents();
      ob_end_clean();
      return $output;
}
   public function add_to_menu($items ,  $args){
   // return "t";

      if($args->menu == "tufes" && isset($_GET['tex_id'])){
    //  var_dump(  $args);
      //  $htmlpres = new \simple_html_dom(); 
        $html = str_get_html($items);
      //  return var_export($html);
        $index = 0;
        $curren_page = false;
        foreach($html->find('a') as $element) {
            if (strpos($element->class, 'elementor-item elementor-item-active') !== false )
            $curren_page = true;
            $href =  $element->href;
            if ( !$curren_page ) {
               
                $href_up =  $href."?tex_id=".$_GET['tex_id'];
                $html->find('a', $index)->href = $href_up;
              //  break;       // echo 'true';
            }
            else {
               
               // $href_up =  "";
                $html->find('a', $index)->href = '';
            }
          
            $index++;
        }
        $index = 0;
        $curren_page = false;
        foreach($html->find('a') as $element){
            $class =  $element->class;
            $li_class = $html->find('li', $index)->class;
            if (strpos($class, 'elementor-item elementor-item-active') !== false) {
                $html->find('li', $index)->class = $li_class." activ_menu";
                break;       // echo 'true';
            }
            //$li_class = $html->find('li', $index)->class;

            $html->find('li', $index)->class = $li_class." activ_menu";
            $index++;
         }
        $html_en = $html->save();
   

         return $html_en;

       }
       else return $items;

   }
    public function update_status(){
        if(isset($_POST['status'])){
           $res =  wp_update_post(array('ID' => $_POST['post_id'],'post_status' => $_POST['status']));
        }
        else $res = false;
        echo json_encode(array("status" => $res));
        wp_die();

    }      
    public  function status_cpt_texfsdf(){
              $args = array(
                    'posts_per_page' => (int)$_POST['posts_per_page'],
                    'post_type' => 'taxes',
                    'nopaging' => false
                  );
                if(isset($_POST['post_status']))
                $args['post_status'] = $_POST['post_status'];
                if(isset($_POST['post_id']) && $_POST['post_id'] != '')
                $args['post__in'] = array( (int)$_POST['post_id']);
                $meta_query = array();
               /* 'cpa' : cpa,
                'from' : from,
                'to' : to*/
                if(isset($_POST['cpa']) && $_POST['cpa'] != 'any')
                $meta_query [] =
                    array(
                        'key'     => 'cpa',
                        'value'   => $_POST['cpa'],
                        'compare' => 'LIKE',
                    );
      
                $date_query = array();  
                 if(isset($_POST['from']) && $_POST['from'] != ''){
                    $date_query ['after'] = $_POST['from'];
                 }
                 if(isset($_POST['to']) && $_POST['to'] != ''){
                    $date_query ['before'] = $_POST['to'];
                 }
                 $meta_query [] = array( 'relation'  => 'AND' );
                 if(!empty($date_query))
                $args['date_query'] = $date_query;
                $meta_query [] = array(
                    'key'     => 'payment',
                    'value'   => 'payed',
                    'compare' => 'LIKE',
                );
                $args['meta_query'] = $meta_query;
                $args['paged'] = (int)$_POST['page'] + 1;
                $wp_query = new WP_Query( $args );
                if( $wp_query->have_posts() ) :
                ob_start();
                while ($wp_query->have_posts()) : $wp_query->the_post();
               // while( have_posts() ): the_post();
                $this->get_one_row();
                //$res[get_the_ID()] =  $output;
                endwhile;
                $output = ob_get_contents();
                ob_end_clean();
                endif;
                echo json_encode(array('max_page' => $wp_query->max_num_pages,'content' => $output ));
              //  echo $output;
                wp_die();
            }
  
    public function status_cpt_tex(){
        if( !is_user_logged_in() ) 
        return '';
        $statuses = $this->get_cpt_by_status();
         $user = wp_get_current_user();
         $role = ( array ) $user->roles;
         if(in_array("cpa",$role)){
            unset($statuses['new']);
         }
        ob_start();
    
        foreach( $statuses as $status => $args ){
            
            ?>
            <div class="one_status <?php echo $status; ?>" data-ststus-name="<?php echo $status; ?>"
             style="background-color: <?php echo $args['color']; ?>;">
                <a href="">
                <?php echo $args['name']."(".$args['count'].")"; ?>
                </a>
  
            </div>
        <?php
  
        }
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
     }
    protected function get_status_select($value){
         $statuses = $this->get_cpt_by_status();
        ?>
        <select  name="status">

        <?php foreach($statuses as $option => $name){ ?>
        <option value="<?php echo $option; ?>"<?=$value == $option ? ' selected="selected"' : '';?>><?php echo $name['name']; ?></option>
        <?php } ?>
        </select>
        <?php

     }
    protected function get_one_row(){
         ?>
            

        <tr data-post-id="<?php echo get_the_ID(); ?>">
        <td class="customer-name">
            <a href="<?php echo get_permalink(); ?>" title=""><span> </span>
            <span class="print-hidden"><?php the_field('personal_first_name'); echo " "; the_field('personal_last_name'); ?> </span>
            </a>
        </td>

        <td class="pid"><a href="<?php echo get_permalink(); ?>"><span><?php echo get_the_ID(); ?></span></a></td>
        <td class="submittion"><span><? echo get_the_date(); ?></span></td>
        <td class="tax-year"><span>
        <? echo get_the_terms(get_the_ID(),"taxes_year")[0]->name; ?> 
        </span></td>
        <td class="assign-to"><span>
        
        <?php echo get_field('cpa')['display_name']; ?>
        </span></td>
         <td class="status">
             <? $this->get_status_select(get_post_status(get_the_ID())); ?>
        </td>
        </tr>


        <?php
     }
    public function get_cpts_tabole(){
        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
        $args = array(
            'posts_per_page' => 10,
            'post_type' => 'taxes',
            'paged'          => $paged,
            'meta_query' => array(
                array(
                    'key'     => 'payment',
                    'value'   => 'payed',
                    'compare' => 'LIKE',
                )
            )
              
          );
        $wp_query = new WP_Query( $args );
        if( $wp_query->have_posts() ){
            ob_start();
            ?>
         <table>
         <thead>
            <tr>
                <th width="300">שם לקוח</th>
                <th width="100">מס' תיק</th>
                <th width="120">נוצר בתאריך</th>
                <th width="100">שנת מס</th>
                <th width="300">משויך ל</th>
                <th width="200">סטטוס</th>
            </tr>
        </thead>
        <tbody>
            <?php
         while ($wp_query->have_posts()) : $wp_query->the_post();
      
         $this->get_one_row();
            endwhile;
        
            ?> 
            </table>
       
            <?php
       
             $output = ob_get_contents();
             ob_end_clean();
             wp_localize_script( 'load_mb_ajax', 'misha_loadmore_params', array(
                'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
                'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
                'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
                'max_page' => $wp_query->max_num_pages,
                'posts_per_page' => 10,
                'post_status' => 'any',

            ) );
             return $output;
        }
        return '';
     }
    protected function get_cpt_by_status(){
             $statuses = [];
            if( have_rows('cpt_status','option') ):
            while ( have_rows('cpt_status','option') ) : the_row();
             if( have_rows('status') ): 
                    while( have_rows('status')) : the_row();
                    $status =[];
                    $status['name'] =  get_sub_field('name');
                    $status['val'] =  get_sub_field('val'); 
                    $args = array(
                        'post_status' => $status['val'],
                        'post_type' => 'taxes'
                      );
                      $the_query = new WP_Query( $args );
                      $status['count'] = $the_query->found_posts;
                      $status['color'] = get_sub_field('color');
                      $statuses[$status['val']] = $status;

                    endwhile; 	
            endif;
            endwhile;
            wp_reset_query();
            endif;
            return $statuses;
         }
}
new TaxesShortCode();