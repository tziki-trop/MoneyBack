var canBeLoaded = true;
var filterActiv = false;
(function( $ ) {
	'use strict';
$(document).ready(function () {
    if(typeof misha_loadmore_params === "undefined")
    return;
//.tf .acf-button-group
$("input[type=radio]").change(function (e) { 
  //  e.preventDefault();
  debugger;
    $(this).closest(".tf").find("label").not(".selected").addClass("label_blue");
});
$(".elementor-field-group button").click(function (e) {
    filterActiv = true;
    if(!canBeLoaded)
    return true;
    
    var ststus  = $("#form-field-status").val();
    var post_id  = $("#form-field-pid").val();
    var cpa  = $("#form-field-cpa").val();
    var from  = $("#form-field-from_date").val();
    var to  = $("#form-field-to_date").val();

    misha_loadmore_params.current_page = 0;
    e.preventDefault();
    var data = {
        'action': 'loadmore',
        'page' : misha_loadmore_params.current_page,
        'post_status' : ststus,
        'post_id' : post_id,
        'cpa' : cpa,
        'from' : from,
        'to' : to

    };
   
    get_ajax_cpt(data , true);
    
});
//$('input').on('focusin', function(){
$( 'body' ).delegate( ".status select", "focusin", function() {

    console.log("Saving value " + $(this).val());
    debugger;
    if (typeof $(this).attr('data-old_val') == typeof undefined || $(this).attr('data-old_val') === false)
    $(this).attr('data-old_val', $(this).val());
});
$( 'body' ).delegate( ".status select", "change", function() {
    var td_worrper = $(this).closest("tr");
    var hold_val =  $(this).attr('data-old_val');
   var id = $(this).closest("tr").data("post-id");
   update_cpt_status(id ,this.value , td_worrper , hold_val) ;
  });
$(".one_status").click(function (e) {
    if($(this).hasClass("activ")){

    e.preventDefault();
    return true;
    }
    $(".one_status").removeClass("activ");
    $(this).addClass("activ");
    if(!canBeLoaded)
    return true;
    
    if(misha_loadmore_params.post_status === $(this).data("ststus-name")){
        return  true;
    }
    misha_loadmore_params.post_status = $(this).data("ststus-name");

    misha_loadmore_params.current_page = 0;
    e.preventDefault();
    var data = {
        'action': 'loadmore',
        'query': misha_loadmore_params.posts,
        'page' : misha_loadmore_params.current_page,
        'post_status' : misha_loadmore_params.post_status
    };
   
    get_ajax_cpt(data , true);
    
});
$(".one_status").click(function (e) {
    if($(this).hasClass("activ")){

    e.preventDefault();
    return true;
    }
    $(".one_status").removeClass("activ");
    $(this).addClass("activ");
    if(!canBeLoaded)
    return true;
    
    if(misha_loadmore_params.post_status === $(this).data("ststus-name")){
        return  true;
    }
    misha_loadmore_params.post_status = $(this).data("ststus-name");

    misha_loadmore_params.current_page = 0;
    e.preventDefault();
    var data = {
        'action': 'loadmore',
        'query': misha_loadmore_params.posts,
        'page' : misha_loadmore_params.current_page,
        'post_status' : misha_loadmore_params.post_status
    };
   
    get_ajax_cpt(data , true);
    
});
$(window).scroll(function(){
    //if(filterActiv)
   // return;
   
   if(parseInt(misha_loadmore_params.current_page) + 1 >= parseInt( misha_loadmore_params.max_page ))
   return;
    if(!canBeLoaded)
    return;
    var data = {
          'action': 'loadmore',
          'query': misha_loadmore_params.posts,
          'page' : misha_loadmore_params.current_page,
          'post_status' : misha_loadmore_params.post_status
      };
      
     
   
      get_ajax_cpt(data ,false);
  });
});
function get_ajax_cpt(data ,replase){
    $.ajax({
        url : misha_loadmore_params.ajaxurl,
        data:data,
        dataType: 'json',
        type:'POST',
        beforeSend: function( xhr ){
          canBeLoaded = false; 
        },
        success:function(data){
          //  debugger;
          misha_loadmore_params.max_page = data.max_page;
          misha_loadmore_params.current_page++;
          if(replase){
                    $("table").find("tbody").empty();
                    $("table").find("tbody").append(data.content);
                 }
                 else{
                 var last = $("table").find("tr").last();
                 $(data.content).insertAfter(last);
                 }
                canBeLoaded = true; // the ajax is completed, now we can run it again
            }
      //  }
    });
}
function update_cpt_status(id ,stsus, td_wor , hold){
    td_wor.append("<i class='fa fa-spinner fa-spin'></i>");
    var data = {
        'action': 'update_status',
        'post_id' : id,
        'status' : stsus
    };
    
    $.ajax({
        url : misha_loadmore_params.ajaxurl,
        data:data,
        type:'POST',
        beforeSend: function( xhr ){
          canBeLoaded = false; 
        },
        success:function(data){
        debugger;
         var hold_count = $("div[data-ststus-name='"+hold+"']").find(".count").text();
         $("div[data-ststus-name='"+hold+"']").find(".count").text(parseInt(hold_count) - 1);
         var stsus_coint = $("div[data-ststus-name='"+stsus+"']").find(".count").text();
         $("div[data-ststus-name='"+stsus+"']").find(".count").text(parseInt(stsus_coint) + 1);
         td_wor.find("select").attr("data-old_val",stsus);
            td_wor.find("i").remove();
            canBeLoaded = true; // the ajax is completed, now we can run it again
            }
    
    });
}
})( jQuery );