var form_submited = false;
(function( $ ) {
	'use strict';
	$(document).ready(function () {
		var postID = acf.get('post_id');
	
		$("#submit_acf_form a").click(function (e) { 
			debugger;
			e.preventDefault();
			$(".acf-button").click();
	
		});	
		jQuery(function($) {
			$('#acf-form').on('submit', (e) => {
				debugger;
				if(!$(e.target).hasClass("ajex")){
					return true;
				}
				if(form_submited){
					e.preventDefault();
					return false;
				}
				debugger;
			var speener =	$(e.target).find(".acf-spinner");
			//var speener =	$(this).find(".acf-spinner");
			speener.css("display","inline-block");
			speener.addClass("is-active");
				form_submited = true;
				
				let form = $(e.target);
				e.preventDefault();
				form.submit(function(event) { event.preventDefault(); submitACF_AJAX(this); return false;});
		
			});
		});
$(".reperter_section").each(function(index){
	$(this).find(".fil_val").each(function (param) { 
		debugger;

		var name = $(this).attr("name");
		var new_name = "reperter---" + name + "---0" ;
		$(this).attr("name",new_name);
});

});
$( "[data-fild]" ).each(function(index) {
	var fild = $(this).attr("data-fild");
	var value = $(this).attr("data-fild-val");
	var depens =	$("input[name='"+fild+"']");
	if(depens.length > 1)
	var depens =	$("input[name='"+fild+"']:checked");
  else
	var depens =	$("input[name='"+fild+"']");

	    if(depens.val() === value)
			$(this).css("display","block");
			else 	$(this).css("display","none");
  
});
$("input").change(function (e) { 
	//	debugger;
		var name = $(this).attr("name");

	var depens =	$("[data-fild='"+name+"']");
	var value  = $(this).val();
			$( depens ).each(function(index) {
				debugger;      
				if(value === $(this).attr("data-fild-val"))
				$(this).css("display","block");
				else 	$(this).css("display","none");

			});   

		e.preventDefault();
		
	});
$(".remove_row_to_re").click(function (e) { 
	var num = parseInt( $(this).closest(".re_buttons").prev(".reperter_section").attr("data-totel") );
   if(num > 0)
	 $(this).closest(".re_buttons").prev(".reperter_section").remove();
});
$(".add_row_to_re").click(function (e) { 

		debugger;
		var num = parseInt( $(this).closest(".re_buttons").prev(".reperter_section").attr("data-totel") );

		var clone = $(this).closest(".re_buttons").prev(".reperter_section").clone();
		$(clone).insertBefore($(this).closest(".re_buttons"));
		$(this).closest(".re_buttons").prev(".reperter_section").attr("data-totel",num + 1);
		var filds = $(this).closest(".re_buttons").prev(".reperter_section").find(".fil_val");
		$(filds).each(function(index) {
			debugger;
			var name =	$(this).attr("name");
			var input = name.split('---');
		var	index = parseInt(input[2]) + 1;
			var new_name = input[0] + "---" + input[1] + "---" + index ;
			$(this).attr("name",new_name);

		});


		//e.preventDefault();
		var name =	$(this).closest(".elementor-widget-container").find(".worrper_new_input").last().find("input").attr("name");
var clone =		$(this).closest(".elementor-widget-container").find(".repet").first().clone();
$(clone).insertAfter($(this).closest(".elementor-widget-container").find(".repet").last());
//$(this).closest(".elementor-widget-container").append(clone);
	var input = name.split('---');
	var index = parseInt(input[2]) + 1;
	var new_name = input[0] + "---" + input[1] + "---" + index ;
$(this).closest(".elementor-widget-container").find(".worrper_new_input").last().find("input").attr("name",new_name);



});
/*	$('.one_year').click(function() {
			
		   if($(this).find('input').is(':checked')) {
			$(this).closest("form").find("label").css ("background-color","transparent");
			 $ (this).find("label").css ("background-color","#E7B007");
		   }
		});  
	
});*/
	function submitACF_AJAX(form) {
		debugger;
		var data = new FormData(form);
		//acf.lockForm( form );
		//acf.validation.toggle( form, 'lock' );
	//	acf.validation.lockForm( form );
		//acf.validation.showSpinner($spinner);
		$.ajax({
		type: 'POST',
		url: window.location.href,
		data: data,
		processData: false,
		contentType: false
		})
		.done(function(data) {
			form_submited = false;
	     
			$(form).find(".acf-spinner").css("display","none");

			$(form).find(".acf-spinner").removeClass("is-active");
			$(form).trigger('acf_submit_complete_', data);
	  })
		.fail(function(error) {
		$(form).trigger('acf_submit_fail', error);
	  });
	}
  
	function renderPage() {
		// initialize the acf script
		acf.do_action('ready', $('body'));
	  
		// will be used to check if a form submit is for validation or for saving
		let isValidating = false;
	  
		acf.add_action('validation_begin', () => {
		  isValidating = true;
		});
	  
		acf.add_action('submit', ($form) => {
		  isValidating = false;
		});
	  
		$('.acf-form').on('submit', (e) => {
			debugger;
		  let $form = $(e.target);
		  e.preventDefault();
		  // if we are not validating, save the form data with our custom code.
		  if( !isValidating ) {
			// lock the form
			acf.validation.toggle( $form, 'lock' );
			$.ajax({
			  url: window.location.href,
			  method: 'post',
			  data: $form.serialize(),
			  success: () => {
				// unlock the form
				acf.validation.toggle( $form, 'unlock' );
			  }
			});
		  }
		});
	  }
	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );
//listens to all acf_forms on page.

  

