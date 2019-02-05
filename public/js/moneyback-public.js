var form_submited = false;
(function( $ ) {
	'use strict';
	$(document).ready(function () {
		var postID = acf.get('post_id');
		var instance = new acf.Model({
			events: {
				'change': 'onChange',
				'change input[type="radio"]': 'onChangeText',
			},
			onChange: function(e, $el){
				//
			//	e.preventDefault();
			//	var val = $el.val();
				// do something
			},
			onChangeText: function(e, $el){
			//	
				$($el).closest(".acf-button-group").find("label").not(".selected").addClass("activ");

				// do something for just text inputs and then call the normal change callback
				this.onChange(e, $el);
			}
		});
		//add req
$(".acf-field.tf").each(function(index){
	var input = $(this).find("input").first();
	input.attr("required",true);



});
$(".reperter_section").each(function(index){
$(this).find(".fil_val").each(function (param) { 
		

		var name = $(this).attr("name");
		var new_name = "reperter---" + name + "---0" ;
		$(this).attr("name",new_name);
});

});
$(".fam_status input").change(function (e) {

var val = $(this).closest (".fam_status").find("input:checked").val();
if(val === "Married"){
	var req = true;
}
else{
	var req = false;
}
$(".req input").each(function(index) {
    $(this).attr("required",req);
});


//	var selected_value = $(this+":checked").val();
//	e.preventDefault();
	
});
$("#triger_acf").click(function (e) { 
	$(".acf_submit").click();
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
$('.acf-form').on('click', '[data-event="remove-row"]', function(e) {
	$(this).click();
 });
 $(".qm").click(function(){
	$(".qm").find(".acf-input").hide();
	$(this).find(".acf-input").toggle();
	
});
$("#acf-field_5c16c13e76e20-field_5c16c25176e21").change(function (e) { 
	
   var father =	$(this).closest(".acf-fields");
 var amount =   father.find("tbody").find("tr").length - 1;
 var val =  parseInt($(this).val());
 if(val <= 0 )
 return true;
 if(val == amount )
 return true;
 if(val > amount ){
	 var hmt = val - amount -1;
	for ( var i = amount; i < val; i++) { 
		father.find(".-plus").last().click();
	  }
 }
 else{
	var hmt = amount - val;
	for (var i = 0; i < hmt; i++) { 
		//if(father.find("tbody").find("tr").length > 0)
		father.find(".-minus:eq("+i+")").first().click();	  
 }
}
	e.preventDefault();
	
});
$("input").change(function (e) { 
	//	
		var name = $(this).attr("name");

	var depens =	$("[data-fild='"+name+"']");
	var value  = $(this).val();
			$( depens ).each(function(index) {
				      
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

		
		var num = parseInt( $(this).closest(".re_buttons").prev(".reperter_section").attr("data-totel") );

		var clone = $(this).closest(".re_buttons").prev(".reperter_section").clone();
		$(clone).insertBefore($(this).closest(".re_buttons"));
		$(this).closest(".re_buttons").prev(".reperter_section").attr("data-totel",num + 1);
		var filds = $(this).closest(".re_buttons").prev(".reperter_section").find(".fil_val");
		$(filds).each(function(index) {
			
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
});
})( jQuery );

  

