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
        add_shortcode('get_cpt_term', [$this,'get_cpt_term']);
        add_shortcode('go_to_edit_post', [$this,'go_to_edit_post']);
        add_shortcode('ststus_payment', [$this,'ststus_payment']);
        add_shortcode('get_progres', [$this,'get_progres']);
        
        add_shortcode('get_pdf', [$this,'get_pdf']);

         add_shortcode('status_cpt_tex', [$this,'status_cpt_tex']);
         add_shortcode('get_cpts_tabole', [$this,'get_cpts_tabole']);
         add_action( 'wp_ajax_foobar', [$this,'ajax_filter_cpts'] );
        add_action('wp_ajax_loadmore', [$this,'status_cpt_texfsdf']); 
        add_action('wp_ajax_nopriv_loadmore', [$this,'status_cpt_texfsdf']); 
        add_action('wp_ajax_update_status', [$this,'update_status']); 
        add_action('wp_ajax_save_tufes', [$this,'save_tufes']); 
        add_action('wp_ajaxnopriv_save_tufes', [$this,'save_tufes']); 

        add_action('wp_ajax_nopriv_update_status', [$this,'update_status']); 
        add_filter('wp_nav_menu_items', [$this,'add_to_menu'], 20, 2);

            }
            public function save_tufes(){
                if(isset($_POST['ajax_actio']) && $_POST['ajax_actio'] == "save"){
                update_post_meta((int)$_POST['post_id'], $_POST['page'],$_POST['data']);
                echo json_encode(array("status"=>true));
                wp_die();
                }
                if(isset($_POST['ajax_actio']) && $_POST['ajax_actio'] == "get"){
                $data = get_post_meta((int)$_POST['post_id'], $_POST['page'],true);
                if(empty($data ))
                echo json_encode(array("status"=>false));
                else echo json_encode(array("status"=>true,"data" => $data));
                wp_die();
                }

            }
            public function get_progres(){
                $pages = get_post_meta(get_the_ID(), 'pages', true);
                $progres = 0;
                if(is_array($pages)){
                    $array_unique = array_unique ( $pages);
                  $progres =  count($array_unique) / 6 * 100;
                }
                return "<div class='progres'><div class='progres_text'><p>". round( $progres)."% מולא"."</p></div><div class='inside' style=\"width: ".round( $progres)."%;\"></div></div>";
              //  return  round( $progres)."% מולא";
            } 
            public function ststus_payment(){
                  
                
                if(get_field('payment',get_the_ID()) == "payed"){
                    $status = get_post_status_object( get_post_status( get_the_ID()) );
                      return "סטטוס: " .$status->label;
                }
              else{
                  $print = "סטטוס: ממתין לתשלום";
                  $url = add_query_arg(array("tex_id" => get_the_id()), get_permalink(378));
                  $print .= "<a href='".$url."'> מעבר לתשלום</a>" ;
                  return $print;
              }
               
          
          return $print;
      }
            public function go_to_edit_post(){

              //  $terms = get_the_terms( get_the_ID() , 'taxes_year' );
                $print = '';
                if(get_post_status(get_the_ID()) === "new"){
                    $pages = get_post_meta(get_the_ID(), 'pages', true);
                    $page_to_ridirect = 40;
                    if(is_array($pages)){
                      $tufes = array(
                            40, 190,372,374,376,378
                        );
                        foreach($tufes as $one){
                            if(in_array($one,$pages))
                            continue;
                            else{
                                $page_to_ridirect = $one;
                                break;
                            } 
                        }
                        
                    }
                    
                  //  $print .= var_export($pages);
                    $url = add_query_arg(array("tex_id" => get_the_id()), get_permalink($page_to_ridirect));
                    $print .= "<a href='".$url."'>לעריכת התיק</a>" ;

                }
             
        
        return $print;
    }
            public function get_cpt_term($post_id = true){
                if($post_id == true)
                $post_id =  get_the_ID(); 
                $terms = get_the_terms((int)$post_id , 'taxes_year' );
                $print = '';
                if ( $terms != null ){
                foreach( $terms as $term ) {
                // Print the name method from $term which is an OBJECT
                $print .= $term->slug ;
                // Get rid of the other data stored in the object, since it's not needed
               // unset($term);
               }
               }
              // else return $post_id;
              return $print;
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
         $o .= '<div class=\'worrper\'>';
        if( have_rows($wporg_atts['main'],get_the_ID()) ):
        $in = 0;
        
        while ( have_rows($wporg_atts['main'],get_the_ID()) ) : the_row();
        $in++;
        $o .= '<div><p>&nbsp;'.$in.'.&nbsp;';
        $o .= get_sub_field($wporg_atts['sub']);
        $o .= '</p>';      
        if($wporg_atts['sub1'] != ''){
            $o .= '<p class=\'dis\'>&nbsp;&nbsp;';
            $o .= get_sub_field($wporg_atts['sub1']);
            $o .= '</p>';  
        }
        $o .= '</div>';

        endwhile;
                   
        else:
            $o .= '<p>עדיין ריק פה </p>';
                   
       endif;
       $o .= "</div>";
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
    //    ob_start();

      $output = ob_get_contents();
      ob_end_clean();
      return $output;
}
   public function add_to_menu($items ,  $args){
   // return "t";
 
      if($args->menu == "tufes" && isset($_GET['tex_id'])){
        $pages = get_post_meta((int)$_GET['tex_id'], 'pages', true);
        if(!is_array($pages))
        $tufes = array( get_permalink(40) );
        else{
        foreach($pages as $fild_pages){
            $tufes [] = get_permalink($fild_pages);
        }
    }
        $html = str_get_html($items);
       // return var_export($pages);
        $index = 0;
        $curren_page = false;
        foreach($html->find('a') as $element) {
            $class =  $element->class;
            $li_class = $html->find('li', $index)->class;
            if (strpos($element->class, 'elementor-item elementor-item-active') !== false )
            $curren_page = true;
            $href =  $element->href;
            if ( in_array( $href ,$tufes)) {
               $html->find('li', $index)->class = $li_class." fild_menu";
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
         protected function get_fam_sta($st){
             switch($st){
                 case "Married":
                 return "נשוי";
                 break; case "Married":
                 return "נשוי";
                 break;
                 case "Single":
                 return "רווק";
                 break;
                 case "Widower":
                 return "אלמן";
                 break; 
                 case "Divorced":
                 return "גרוש";
                 break; 
                 case "Separated":
                 return "פרוד";
                 break;
                           
                }
         }
         protected function get_tr_fa($fild){
             switch($fild){
                 case "true":
                 return "כן";
                 break;
                 case "false":
                 return "לא";
                 break;
                 default:
                 return $fild;
                 break;
             }
         }
         protected function get_repetter_shit($repeter,$repname,$post_id){
            $incomdata = get_field_object($repeter);
           //personal_Details_lerning_detail
           // var_dump($incomdata);
            if( have_rows($repname,$post_id) ):
                echo "<table>
                <tbody>";
            while ( have_rows($repname,$post_id) ) : the_row();
           // var_dump($incomdata["sub_fields"]);
            echo "<tr>";
            foreach($incomdata["sub_fields"] as $index => $fild){

                echo "<td>";
                echo $fild['label'];
                echo ": ";
                echo get_sub_field($fild['name']);
                echo "</td>";
            }
            endwhile; 	
            echo "</tr>";
            echo "</table>
            </tbody>";
            echo "<table>
            <tbody>";
            endif;
        
         }
         protected function get_incom($incum,$post_id){
             echo "<table>
             <tbody>";
            $incomdata = get_field_object($incum['key']);
           foreach($incomdata["sub_fields"] as $index => $fild){
                $val = get_field($incum['name']."_".$fild['name'],$post_id);
            
               // else $val = get_field($incum['name'],$post_id)[$fild['name']];
              //  $val = get_field("personal_Details_lerning_detail",$post_id);
      
                if($val == "")
                continue;
                if(empty($val))
                continue;
                if($val == "false")
                continue;
                // var_dump($fild['type'] );
                if($fild['type'] == "text" || $fild['type'] == 'date_picker' || $fild['type'] == 'number' || is_array($val)){
                  $value = "";
                  if(is_array($val) && $fild['type'] != 'group' && $fild['type'] != "repeater"){
                    $value .= "פירוט: ";
                    //var_dump($fild['type']);
                    foreach($val as $ind => $one_val){
                        $value .= $one_val.". ";
                                }
                    }
               else if($fild['type'] == "repeater"){
                   $this->get_repetter_shit($fild['key'],$incum['name']."_".$fild['name'],$post_id);
                   continue;
                 //  var_dump(get_field_object($fild['key']));
                }
                  else if($fild['type'] == 'group'){
                      if($fild['name'] == "chileds")
                      continue;
                       $sub_group = array("key" => $fild['key'],"name" => $incum['name']."_".$fild['name']);
                     
                     ///   echo "<table> <tbody><tr>" ;
                    
                       $this->get_incom($sub_group,$post_id);
                       echo "<table> <tbody>" ;
                     //  echo "</table></tbody><tr>";
                        continue;
                  }
                  else {
                    $value = $fild['label'].": ".$val;
                  }
                  ?>
                    <td colspan="2">
                    <p><span style="font-weight: 400;"><? echo $value; ?></span></p>
                    </td>
                    
            
                  <?php
                 }
              if($fild['type'] == "button_group" && !is_array($val)){
                ?>
                
                <tr>
                  <td >
                  <p><span style="font-weight: 400;"><? echo $fild['label']; ?></span></p>
                 </td>
                 <td>
                    <p><strong>כן</strong></p>
                   </td>
               </tr>
            
                <?
              }
            }
            
            echo "</table>
             </tbody>";
        }
         public function get_pdf(){
             if(!isset($_GET["tex_id"]))
             return "";
            $post_id = $_GET["tex_id"];
          //   $post_id = 2639;
            // $year = $this->get_cpt_term($post_id);
             $year = get_the_terms((int)$post_id , 'taxes_year' )[0]->slug;
             
             $personal = array();
             $partner_personal = array();
             $detales = array('first_name','last_name','id_num','gend','famely_status','birthday','father_name');

             foreach($detales as $one){
                $personal [$one] = get_field('personal_'.$one,$post_id);
                $partner_personal [$one] = get_field('partner_personal_'.$one,$post_id);
                if($one == "birthday"){
          

                $personal ['age'] = (int)date('Y', strtotime(str_replace("/","-",$personal[$one]))) - date('Y', strtotime("now"));
                $partner_personal ['age'] = (int)date('Y', strtotime($partner_personal[$one])) - date('Y', strtotime("now"));
            }
            }
             // return var_export($addres);
             $personal ['status'] = $this->get_fam_sta(   $personal ['famely_status']);
                 ob_start();
                   

             ?>
<p>&nbsp;</p>
<table>
<tbody>
<tr>
<td>
<p><strong>moneyback</strong></p>
</td>
<td>
<p><strong><? echo $personal['first_name']." ".$personal['last_name']; ?></strong></p>
</td>
<td>
<p><strong><? echo $year; ?></strong></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p><strong>פרטים אישיים:</strong></p>
<table>
<tbody>
<tr>
<td colspan="4">
<p><span style="font-weight: 400;">[שם] </span><strong><? echo $personal['first_name']; ?></strong></p>
</td>
<td colspan="6">
<p><span style="font-weight: 400;">[משפחה] </span><strong><? echo $personal['last_name']; ?></strong></p>
</td>
<td colspan="3">
<p><span style="font-weight: 400;">[ת.ז.] </span><strong><? echo  $personal['id_num']; ?></strong></p>
</td>
</tr>
<tr>
<td>
<p><strong><? echo $personal['gend']; ?></strong></p>
</td>
<td colspan="4">
<p><span style="font-weight: 400;">[מצב משפחתי] </span><strong><? echo $personal['status']; ?></strong></p>
</td>
<td colspan="3">
<p><span style="font-weight: 400;">[תאריך לידה] </span><strong><?  echo $personal['birthday']; ?></strong></p>
</td>
<td colspan="3">
<p><span style="font-weight: 400;">[גיל] <?  echo $personal['age']; ?></span></p>
</td>
<td colspan="2">
<p><span style="font-weight: 400;">[שם האב] </span><strong><? echo   $personal['father_name']; ?></strong></p>
</td>
</tr>
<tr>
<td colspan="3">
<p><strong>פרטי בן הזוג:</strong></p>
</td>
<td colspan="3">
<p><strong>[שם] <? echo   $partner_personal['first_name']; ?> </strong></p>
</td>
<td colspan="6">
<p><strong>[משפחה]<? echo $partner_personal['last_name']; ?></strong></p>
</td>
<td>
<p><strong>[ת.ז.] <? echo $partner_personal['id_num']; ?></strong></p>
</td>
</tr>
<tr>
<td colspan="2">
<p><strong><? echo $partner_personal['gend']; ?></strong></p>
</td>
<td colspan="5">
<p><span style="font-weight: 400;">[תאריך לידה] </span><strong><? echo $partner_personal['birthday']; ?></strong></p>
</td>
<td colspan="2">
<p><strong>[גיל]</strong><span style="font-weight: 400;"> <? echo $partner_personal['age']; ?></span></p>
</td>
<td colspan="4">
<p><span style="font-weight: 400;">[שם האב]</span><strong> <? echo $partner_personal['father_name']; ?></strong></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<table>
<tbody>
<tr>
    <?php
         $detales = array('adress_home','post','another_mailbox','adress_','post_2',
         'email','phone','mobile_phone','get_info','get_info_by');
         $address = array();
         foreach($detales as $one){
             $addres [$one] = get_field('address_group_'.$one,$post_id);
         }

    if(is_array($addres['another_mailbox'])&& in_array('יש לי כתובת אחרת למשלוח דברי דואר',$addres['another_mailbox']))
    $addres['another_mailbox'] = true;
    else $addres['another_mailbox'] = false;
    ?>
<td colspan="4">
<p><span style="font-weight: 400;">[כתובת מגורים] </span><strong> <? echo $addres['adress_home'] ?> </strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[מיקוד] </span><strong> <? echo $addres['post'] ?></strong></p>
</td>
</tr>
<? if( $addres['another_mailbox'] ){ ?>
<tr>
<td colspan="4">
<p><span style="font-weight: 400;">[כתובת למשלוח דואר] </span><strong><? echo $addres['adress_'] ?></strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[מיקוד] </span><strong><? echo $addres['post_2'] ?></strong></p>
</td>
</tr>
<? } ?>
<tr>
<td>
<p><span style="font-weight: 400;"><? echo $addres['email'] ?></span></p>
</td>
<td>
<p><span style="font-weight: 400;">[טל:] </span><span style="font-weight: 400;"><? echo $addres['phone'] ?></span><span style="font-weight: 400;">-</span><span style="font-weight: 400;">0000000</span></p>
</td>
<td>
<p><span style="font-weight: 400;">[נייד] </span><span style="font-weight: 400;"><? echo $addres['mobile_phone'] ?><</span><span style="font-weight: 400;">-</span><span style="font-weight: 400;">999999</span></p>
</td>
<td colspan="2">
<p>
<span style="font-weight: 400;">קבלת מידע: </span>
<?php 
$conen = "";
//              'get_info_by' => array ( 0 => 'דוא"ל', 1 => 'מסרון', ), )
if(is_array($addres['get_info_by'])){
foreach($addres['get_info_by'] as $one ){
    echo $conen;
    ?>
<span style="font-weight: 400;"><? echo $one; ?></span>
    <?
    $conen = ",";

}
}
else{
    ?>
<span style="font-weight: 400;">ללא</span>

    <?
}
?>


</p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<?php
     $detales = array('bank_name','brach_num','acc_num','owner_name');
     $address = array();
     foreach($detales as $one){
         $bank [$one] = get_field('bank_account_'.$one,$post_id);
     }

    ?>
<table>
<tbody>
<tr>
<td>
<p><strong><? echo $bank['bank_name'] ?> </strong></p>
</td>
<td>
<p><strong>סניף <? echo $bank['brach_num'] ?></strong></p>
</td>
<td>
<p><strong>חשבון <? echo $bank['acc_num'] ?></strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[ע"ש]</span><strong> <? echo $bank['owner_name'] ?></strong></p>
</td>
</tr>
</tbody>
</table>
<p>&nbsp;</p>
<p><strong>פרטי הכנסות:</strong></p>
<?php
    $how_is_reporting = get_field('how_is_how_is_reporting',$post_id);
     $howis_reg  = get_field('how_is_me_and_my_wife_howis_reg2',$post_id);
     $work_num = get_field('placeOfWork_num_jobs',$post_id);
    // return var_export($work_num);
    ?>
<table>
<tbody>
<tr>
<td>
<p><span style="font-weight: 400;">[הדו"ח על] </span><strong><? echo $how_is_reporting; ?></strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[מי בן הזוג הרשום] </span><strong><? echo $howis_reg; ?></strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[מקומות עבודה] </span><strong><? echo $work_num; ?></strong></p>
</td>
<td>
<p><span style="font-weight: 400;">[מקומות עבודה ב"ז]</span><strong> 1</strong></p>
</td>
<? 
    $work_pases = get_field('placeOfWork_bizz_names',$post_id);
   // return var_export($how_is_reporting);
    foreach($work_pases as $index => $one ){
        //partner_placeOfWork
        ?>
<tr>
<td colspan="4">
<p><span style="font-weight: 400;">[מקום עבודה <? echo $index + 1; ?>] </span><strong>חברת</strong> <strong><? echo  $one['name'];?></strong></p>
</td>
</tr>


        <?php
    }
    ?>
    <?php
    $partner_placeOfWork = get_field('partner_placeOfWork_bizz_names',$post_id);
    if(is_array($partner_placeOfWork)){
    foreach($partner_placeOfWork as $index => $one ){
         //partner_placeOfWork
         ?>
 <tr>
 <td colspan="4">
 <p><span style="font-weight: 400;">[מקום עבודה בן הזוג  <? echo $index + 1; ?>] </span><strong>חברת</strong> <strong><? echo  $one['name'];?></strong></p>
 </td>
 </tr>
 
 
         <?php
     }
    }
?>

</tbody>
</table>
<p>&nbsp;</p>
<p><span style="font-weight: 400;">שאלון הכנסות</span></p>

<?php
//$incum = "incum";
//$incomdata = get_field($incum,$post_id);
//var_dump($incom);
$incum =array("key" => "field_5c16c26a8dd3c","name" => "incum") ;
$this->get_incom($incum,$post_id);

?>



<p><strong>שאלון הכנסות בן הזוג</strong></p>

<?php
$incum =array("key" => "field_5c484b31ac382","name" => "partner_incum") ;
$this->get_incom($incum,$post_id);


?>

<?php 
 //var_dump(get_field('personal_Details_chileds_do_you_or_your_wife',$post_id));
if(get_field('personal_Details_chileds_do_you_or_your_wife',$post_id) == "true"){
$cildren  =  get_field('personal_Details_chileds_childs',$post_id);

?>
<table>
<tbody>
<tr>
<td>
<p><strong>שם </strong></p>
</td>
<td>
<p><strong>תאריך לידה</strong></p>
</td>
<td>
<p><strong>גיל</strong></p>
<p><strong>[בשנת המס]</strong></p>
</td>
<td>
<p><strong>תחת חזקתי</strong></p>
</td>
<td>
<p><strong>משתתף בכלכלתו</strong></p>
</td>
<td>
<p><strong>אני הורה יחיד</strong></p>
</td>
<td>
<p><strong>במוסד טיפולי</strong></p>
</td>
<td>
<p><strong>נטול יכולת</strong></p>
</td>
<td>
<p><strong>לקות למידה</strong></p>
</td>
</tr>

    <?php
        foreach($cildren as $child){

            ?>
<tr>
<td>
<p><span style="font-weight: 400;"><? echo $child['child_name'] ?></span></p>
</td>
<td>
<p><span style="font-weight: 400;"><?php echo $child['child_birthday']; ?> </span></p>
</td>
<td>
<p><span style="font-weight: 400;">
<?php echo (int)$year - (int)date('Y', strtotime(str_replace("/","-",$child['child_birthday'])));?>
</span></p>
</td>
<?php
$child_pro = array(
    "ילד זה נמצא תחת חזקתי",
    "אני משתתף בכלכלתו של ילד זה",
    "אני משמש כהורה יחיד לילד זה",
    "ילד זה נמצא במוסד לטיפול מיוחד",
    "ילד זה נטול יכולת",
    "ילד זה בעל לקות למידה",
);
foreach($child_pro as $one_ch){
    $v = "";
    if(is_array($child['how_hold_the_boy']) && in_array($one_ch,$child['how_hold_the_boy']))
    $v = "V";
    else if($one_ch == $child['how_hold_the_boy'])
    $v = "V";

?>
<td>
<p><span style="font-weight: 400;"><? echo  $v; ?></span></p>
</td>
<? } ?>

            <?php
        }
    ?>
</tbody>
</table>
<?php } ?>
<p>&nbsp;</p>
<p><strong>נתונים אישיים:</strong></p>

<?php
$incum =array("key" => "field_5c1a4bfe952c4","name" => "personal_Details") ;
$this->get_incom($incum,$post_id);


?>


<p>תרומות</p>
<table>
<tbody>
    <?php
    if(get_field('truma',$post_id) == "true"){
    ?>
<tr>
<td>
<p><span style="font-weight: 400;">האם תרמת השנה לעמותה עם סעיף 46</span></p>
</td>
<td>
<p><strong>כן</strong></p>
</td>
</tr>
    <?php } ?>
    <?php
    if(get_field('nispe',$post_id) == "true"){
    ?>
<tr>
<td>
<p><span style="font-weight: 400;">האם בשנת המס היו לך הוצאות להנצחת נספה בפעולות איבה?</span></p>
</td>
<td>
<p><strong>כן</strong></p>
</td>
</tr>
    <?php } ?>
</tbody>
</table>
<p>&nbsp;</p>


<p><strong>נספחים</strong></p>
<table>
<tbody>
<?php
  // if( /* $group_to_check === 'field_5c1b953adb990' || */ $group_to_check === 'field_5c1b970186b31'  || $group_to_check === 'field_5c24daa6a3f57'){
   $files_groups = array('field_5c1b953adb990','field_5c1b970186b31' ,'field_5c24daa6a3f57','field_5c52e2b12d4e0');
   foreach($files_groups as $group_to_check){
    $fild_obj = get_field_object($group_to_check);
    foreach($fild_obj['sub_fields'] as $one_fild){

      // if($fild_obj['type'] != "")
       // var_dump($one_fild["type"]);
        if($one_fild["type"] == "message")
        continue;
      if( apply_filters('check_uploud_filds_condition',$one_fild['key'],$post_id)){
       // var_dump($one_fild["type"]);
       $text_to_add_tolabel = '';
        if( have_rows($fild_obj['name']."_".$one_fild['name'],$post_id) ):
        while ( have_rows($fild_obj['name']."_".$one_fild['name'],$post_id))  : the_row();
        $sabs = get_field_object($one_fild['key'])['sub_fields'];
           
          //  if($one_fild == "tofes_aas")  
       //  var_dump($one_fild['name'] );
            if($one_fild['name'] == "tofes_aas") 
            $text_to_add_tolabel =  get_sub_field('name');
            if($one_fild['name'] == "eanlimodim_fiels") 
            $text_to_add_tolabel =  get_sub_field('detiels_lerning');
        //   $text_to_add_tolabel =  get_sub_field('detiels_lerning');

      //   var_dump($text_to_add_tolabel);
        foreach($sabs as $sab){
            $fffid =   get_sub_field($sab['name']);
            if(is_array( $fffid) && isset($fffid['url']) ){
                    $text = "צורף";
            }
            else  $text = "לא צורף";

        }
            ?>
          <tr>
          <td>
          <p><span style="font-weight: 400;"><?php echo $text; ?></span></p>
          </td>
          <td colspan="2">
          <p><span style="font-weight: 400;"><?php echo $one_fild['label']." ".$text_to_add_tolabel; ?></span></p>
          </td>
          </tr>
          <?php
        
        endwhile;
        
        else:

            
       endif; 
    } 
      

    }
    
    }


?>



</tbody>
</table>
<p>&nbsp;</p>
             <?php
                $output = ob_get_contents();
                ob_end_clean();
                return $output;
         }
}
new TaxesShortCode();