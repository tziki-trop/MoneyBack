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
$( 'body' ).delegate( ".status select", "change", function() {
   var id = $(this).closest("tr").data("post-id");
   update_cpt_status(id ,this.value);
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
function update_cpt_status(id ,stsus){

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
          
                
                canBeLoaded = true; // the ajax is completed, now we can run it again
            }
    
    });
}
})( jQuery );