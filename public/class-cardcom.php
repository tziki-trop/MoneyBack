<?php
//class-cardcom.php
class Cardcom_Public {
    public function __construct(){
        // $this->set_mail_types();
         $this->add_wp_actions();
      }
      public function add_wp_actions(){
       //  add_action('after_setup_theme', [$this,'remove_admin_bar']);
         add_action( 'init', [$this,'add_endpoint' ]);
         add_action( 'elementor_pro/forms/validation', [$this,'loop_form_and_send_data'],10,2 ); 
       //  add_action( 'elementor_pro/posts/query/owner_business',[$this,'user_query']);
        // $this->loader->add_action( 'elementor_pro/forms/validation', $plugin_public, 'loop_form_and_send_data',10,2 );
         //$this->loader->add_action('init', $plugin_public, 'reg_cpt');
         add_filter( 'query_vars', [$this, 'add_query_vars'],0 );
         add_action( 'parse_request', [$this,'sniff_requests' ]);

       //  $this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_vars',0 );
        // $this->loader->add_action('parse_request', $plugin_public, 'sniff_requests');
       //  $this->loader->add_action('init', $plugin_public, 'add_endpoint',0);
        // $this->loader->add_action('make_card_com_payment', $plugin_public, 'ask_payment_with_token',10,1);
      }
    public function loop_form_and_send_data ( $record, $ajax_handler ) {

    $send = $record->get_form_settings( 'form_id' );
    if($send != "donation")
    return;

    //$ajax_handler->add_error_message("sdfsdfsd");

    $raw_fields = $record->get( 'fields' );
	$fields = [];
	foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = (string)$field['value'];
    }
    update_post_meta($fields['pid'],'all_true' , true);
    update_post_meta($fields['pid'],'takanon' , true);
    update_post_meta($fields['pid'],'invoice_mail' , true);
    $pages = get_post_meta($fields['pid'], 'pages', true);
   
    if(is_array($pages))
    $pages [] = 378;
    else $pages = array(378);
    update_post_meta($fields['pid'], 'pages', $pages);
     $post_param = [];
     $res = $this->get_url($fields,$fields['pid']);
    // $ajax_handler->add_error_message($res);

     //add_post_meta($pid,'error_log',$res['output']);
     if(isset($res['status']) && $res['status'])
    // $ajax_handler->add_success_message($res['output'][0]);

    $ajax_handler->add_response_data( 'redirect_url_to', $res['output'] );

    

}
     public function add_query_vars($vars){
              array_push($vars,'cardcom');             
		return $vars;
	}
    public function add_endpoint(){
        add_rewrite_rule('^cardcomres','index.php?cardcom=1','top');
	}
    public function sniff_requests(){
		global $wp;
		if(isset($wp->query_vars['cardcom'])){
            $this->get_res();
        }
     
    }
    public function get_res(){
     $url = "https://secure.cardcom.solutions/Interface/BillGoldGetLowProfileIndicator.aspx";
        if(isset($_GET['lowprofilecode'])){
            $settings = $this->get_campny_settings();     
			$settings['lowprofilecode'] = $_GET['lowprofilecode'];
            $operation = $_GET['Operation'];
			$url = add_query_arg($settings,$url);
			$response = wp_remote_get( $url);
			$body = wp_remote_retrieve_body($response);
            $output = array();
            parse_str($body,$output);
            //ResponseCode
            // error_log( print_r($output, TRUE) );
            if(array_key_exists('ResponseCode',$output)){
                if((int)$output['ResponseCode'] != 0 ){
                    echo "error";
                    wp_die();
                }
            }
              if((int)$operation === 1) {
                  if((int)$output['DealResponse'] != 0 ){
                    echo "error";
            		wp_die();
                  }
              }
              if(array_key_exists('ReturnValue',$output)){
                  $id =   (int)$output['ReturnValue'];
                  
                  return update_post_meta($id,'payment',"payed");

              }
                header("HTTP/1.1 200 OK");
                wp_die();
        }
}
 
    protected function set_invoic($user,$invoice,$settings = []){
        
     	$settings['InvoiceHead.CustName'] = $user['name'];
			$settings['InvoiceHead.Email'] = $user['email'];
			$settings['InvoiceHead.CoinID'] = 1;
			$settings['InvoiceHead.SendByEmail'] = true;
			$index = 1;
            
			foreach($invoice as $line){
				$settings['InvoiceLines'.$index.'.Description'] = $line['description'];
				$settings['InvoiceLines'.$index.'.Price'] = $line['price'];
				$settings['InvoiceLines'.$index.'.Quantity'] = $line['quantity'];
				$index++;
			}
        return $settings;
    }
    public function get_url($user,$id, $invoice = false){
      //  $curauth = get_user_by('ID', $post_author_id);
        $user = get_user_by('ID', get_current_user_id());
	//	$user_nicename    = $curauth->user_nicename;
	//	$display_name     = $curauth->display_name;
	//	$user_description = $curauth->user_description;
	//	$user_email       = $curauth->user_email;
	///	$user_url         = $curauth->user_url;
	//	$user_website     = $curauth->website_name;
//		$user_twitter     = $curauth->twitter;
	  //  $author_id = $post->post_author;
      //  $author = get_the_author_meta('display_name', $author_id); 
      //  $ajax_handler->add_error_message($curauth->user_email);
        $user = get_user_by('ID', get_current_user_id());
        $settings = $this->get_campny_settings(); 
        $invoice = [];
        $invoice[0] = array('price' => 145,'quantity' => 1,'description' => "תשלום עבור בקשת החזר מס");
       if($invoice != false)
       $settings = $this->set_invoic(array('name'=> $user->display_name,'email' => $user->user_email),$invoice,$settings);
        $settings['ReturnValue'] = $id;
        $settings['CardOwnerName'] = $user->display_name;
        $settings['CardOwnerEmail'] = $user->user_email;
       // $settings['CardOwnerPhone'] = $user['phone'];
        $settings['ProductName'] = "תשלום עבור בקשת החזר מס";
		$settings['Operation'] = 1;
		$settings['SumToBill'] = 145;
		$settings['APILevel'] = 10;
		$settings['CoinID'] = 1;
		$settings['codepage'] = '65001';
        $settings['IndicatorUrl'] = get_site_url()."/cardcomres";
     //   return json_encode($settings);
    	return $this->make_request('ResponseCode',$settings);
	
	}
    protected function make_request($errorparameter,$settings){
    
        $RequestBody =  http_build_query($settings);
        $args = array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $RequestBody,
        'cookies' => array()
        );
        $url = "https://secure.cardcom.solutions/Interface/LowProfile.aspx";

        $response =  wp_remote_post($url,$args);

        $body = wp_remote_retrieve_body($response);
           if ( is_wp_error( $body ) ) {
			    return array('status' => false, 'output' => "wp_error");
           }
        $output = array();
        parse_str($body,$output);
        if($output[$errorparameter] != 0){
            return array('status' => false, 'output' => $output['Description']);
        }
        
        return array('status' => true, 'output' => $output['url']);
    }
    protected function get_campny_settings($settings = []){
        $settings['Language'] = "he";
        
        $settings['ErrorRedirectUrl'] = get_permalink(2628);

        $settings['SuccessRedirectUrl'] = get_permalink(2624);
        $settings['TerminalNumber'] = 66659;
            //    $settings['TerminalNumber'] = 1000;
              //  $settings['UserName'] = "barak9611";

                
		$settings['UserName'] = "R6ulivqrnuIJ9fDjay4M";
		return $settings;
    }
}
new Cardcom_Public();
