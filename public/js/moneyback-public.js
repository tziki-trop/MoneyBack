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
		$('.one_year').click(function() {
			
		   if($(this).find('input').is(':checked')) {
			$(this).closest("form").find("label").css ("background-color","transparent");
			 $ (this).find("label").css ("background-color","#E7B007");
		   }
		});  
	
});
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

  

