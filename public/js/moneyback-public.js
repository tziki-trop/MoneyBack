var form_submited = false;
(function( $ ) {
	'use strict';
	$(document).ready(function () {
			$(".tab_title").click(function(){
				
				$(this).closest(".father_box").find(".tab_mida").slideToggle();
				//$(this).find(".tab_mida").toggle();
				
			});
		
			$( document ).on('submit_success', function(e ,b){
				debugger;
				var ddd = b;
				var url  = b.data.redirect_url_to;
				var id =$(e.target).attr("id");
				if(id === "donation"){
					$("#open_payment").click();
				$("#payment").attr("src",url);
				
				e.preventDefault();
				e.stopPropagation();
				e.stopImmediatePropagation();
				
				}
				if(id === "add_note_to_client" || id === "send_messege_to_client"){
					location.reload();
				}
				
				// form has been submitted do your tracking here...
			});
	
		var data  = findGetParameter("tex_id");
		

		if(localStorage[window.location.href] != undefined){
			var param = JSON.parse(localStorage[window.location.href]);
			
			param.forEach(element => {
				if(element.type === "reg"){
					switch(element.input_type){
						case "radio" : 
						
						if(element.val != undefined){
							
							$("input[name='"+element.key+"'][value='"+element.val+"']").click();

						}
						break;
						case "hidden" : 
						break;
						case "file" : 
						break;
						default:
						$("input[name='"+element.key+"']").attr("value",element.val);
						break;
					}

				}
				//repeater num 					input_num = $(this).find(".acf-repeater").length;

				else if(element.type === "repeater"){
				//	
				//	input_num = $(this).closest(".acf-repeater").find(".acf-row").length;

					if(parseInt(element.num) < $(this).closest(".acf-repeater").find(".acf-row").length){
						var add_in = parseInt(element.num) < $(this).closest(".acf-repeater").find(".acf-row").length;
						for (i = 0; i < add_in; i++) {
							$(this).closest(".acf-repeater").find("[data-event='add-row']").click();
						}
					}
				}
			});
			//var param = getFormCookie(window.location.href);

		}
		//btn_get
		$("#btn_get").click(function (e) { 
			savetofesajax("get");
		});

		$("#btn_save").click(function (e) { 
			e.preventDefault();
		//.acf-input
			var paranterss = [];
			$( ".acf-input" ).each(function(index) {
				var type = "reg";
				var input_num = 1;
				if($(this).find(".acf-repeater").length > 0){
					type = "repeater";
					
				    var fff = 	$(this).closest(".acf-repeater").find(".acf-row").last();
					input_num = $(this).closest(".acf-repeater").find(".acf-row").last().data("id");
				}
				//repeater num 					input_num = $(this).find(".acf-repeater").length;

			//	
				$(this).find("input").each(function(index) {
					var input_key = $(this).attr("name");
					var input_val = $(this).val();
					var input_type = $(this).attr("type");
					var input_val = '';
					switch(input_type){
						case "radio" : 
						//
						input_val = $(this).attr("checked");
						
						if(input_val == "checked"){
							input_val = $(this).attr("value");

						}
						
						//value="Separated"
						break;
						default:
						input_val = $(this).val();
						break;
					}
					//
					var fild = {"type": type,"key": input_key,"val": input_val,"num": input_num,"input_type":input_type};
					paranterss.push( fild );
				});
			  
				//paranterss[] = fild;
			});
			
			var data  = findGetParameter("tex_id");
			//save_tufes
			savetofesajax("save",JSON.stringify(paranterss));
			//localStorage[window.location.href] = JSON.stringify(paranterss);
			//setFormCookie(JSON.stringify(paranterss),data.tex_id);
		
			
		});
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
$(".acf-file-uploader input[type='file']").each(function(index){
	var button = "<div class = \"uploud_worpper\"><button class=\"uploud_button\" type=\"button\">העלה קובץ</button><span class=\"file_name\"></span></div>";
	$(button).insertAfter($(this));
	$(this).hide();

});
$( 'body' ).delegate( ".acf-field input", "change", function(e) {
//	debugger;
	var is_ok = true;
	var fields = $(".acf-field")
	.find("select, textarea, input").serializeArray();

$.each(fields, function(i, field) {
//	debugger;
if (!field.value && $("input[name='"+field.name+"']").prop('required')){
	if($("input[name='"+field.name+"']").attr("type") === "radio"){
		var radio_buttons = $("input[name='"+field.name+"']");
         if( radio_buttons.filter(':checked').length == 0)
			is_ok = false;
			} 
else if($("input[name='"+field.name+"']").attr("type") === "hidden"){

}
			else{
	//debugger;
	is_ok = false;
}

}

 // alert(field.name + ' is required');
}); 

if(is_ok){
	debugger;
	$("#triger_acf").addClass("activ");
}
console.log(fields);
	//$(e.target).closest("label").find(".file_name").html($(e.target).val());
});
$( 'body' ).delegate( ".acf-file-uploader input[type='file']", "change", function(e) {
	
	$(e.target).closest("label").find(".file_name").html($(e.target).val());
});
$( 'body' ).delegate( ".acf-file-uploader .uploud_button", "click", function(e) {
	
	$(e.target).closest("label").find("input").click();
});




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

$(".acf-field-5c1e8bb67e0fd input").each(function(index){
			$(this).attr("readonly",true);
			$(this).prop('readonly', true);
});
$(".tf input").each(function(index){
//	var inputs = $(this).find("input");
	$(this).attr("required",true);
});
$("input[name='acf[field_5c48caf5fdb9f][field_5c476b79f5688]']").each(function(index) {
	debugger;
    $(this).attr("required",true);
});
$(".fam_status input").change(function (e) {

var val = $(this).closest (".fam_status").find("input:checked").val();
if(val === "Married"){
	var req = true;
}
else{
	var req = false;
}
//how_is_reportin

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
function savetofesajax(ajax_actio,data){
	var data = {
        'action': 'save_tufes',
        'ajax_actio': ajax_actio,
        'post_id' : findGetParameter("tex_id").tex_id,
		'page' : window.location.href,
		'data' : data
	};
	debugger;
	jQuery.ajax({
        url : "/wp-admin/admin-ajax.php",
        data:data,
        dataType: 'json',
        type:'POST',
        beforeSend: function( xhr ){
        // canBeLoaded = false; 
        },
        success:function(data){
			debugger;
			if(ajax_actio === "get" && data.status == true)
			add_data_to_filds(data.data);
           //canBeLoaded = true; // the ajax is completed, now we can run it again
            }
      //  }
    });
}
function add_data_to_filds(data){
	data = JSON.parse(data);
	data.forEach(element => {
		if(element.type === "reg"){
			switch(element.input_type){
				case "radio" : 
				
				if(element.val != undefined){
					
					jQuery("input[name='"+element.key+"'][value='"+element.val+"']").click();

				}
				break;
				case "hidden" : 
				break;
				case "file" : 
				break;
				default:
				jQuery("input[name='"+element.key+"']").attr("value",element.val);
				break;
			}

		}
		else if(element.type === "repeater"){
	    if(parseInt(element.num) < $(this).closest(".acf-repeater").find(".acf-row").length){
				var add_in = parseInt(element.num) < jQuery(this).closest(".acf-repeater").find(".acf-row").length;
				for (i = 0; i < add_in; i++) {
					jQuery(this).closest(".acf-repeater").find("[data-event='add-row']").click();
				}
			}
		}
	});
}
function setFormCookie(value,url) {
	var expires = new Date();
	expires.setTime(expires.getTime() + (1 * 24 * 60 * 60 * 1000));
	document.cookie = url + '=' + value + ';expires=' + expires.toUTCString();
}

function getFormCookie(url) {
	var keyValue = document.cookie.match('(^|;) ?'+ url +'=([^;]*)(;|$)');
	return keyValue ? keyValue[2] : null;
}
function findGetParameter(parameterName) {
	var exist = false;
    		  var paranterss = {};
    var result = null,
        tmp = [];
    location.search
        .substr(1)
        .split("&")
        .forEach(function (item) {
		if(!exist && item === "")
			return false;
		exist = true;
		//
          tmp = item.split("=");
          paranterss[tmp[0]] = decodeURIComponent(tmp[1]);
          if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
        });
	if(exist)
    return paranterss;
	else return false;
}
  

