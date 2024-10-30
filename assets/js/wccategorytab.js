if((typeof jQuery === 'undefined') && window.jQuery) {
	jQuery = window.jQuery;
} else if((typeof jQuery !== 'undefined') && !window.jQuery) {
	window.jQuery = jQuery;
}
var flg_v1 = 0; 
 
function WCPT_loadMoreProducts(category_id,limit,elementId,total,request_obj){
	if(flg_v1==1) return;
	jQuery(document).ready(function($){ 
			var root_element = $("#"+elementId).parent();
			if($("#"+elementId).parent().parent().hasClass("lt-tab"))
				root_element = $("#"+elementId).parent().parent(); 
			 
			if((category_id==='undefined')) category_id = 0; 
 			$(root_element).find(".item-posts").find(".ik-post-load-more").html("<div align='center'>"+$(".wp-load-icon").html()+"</div>");
			flg_v1 = 1;
			$.ajax({
				url: wccategorytab.wcpt_ajax_url, 
				data: {'action':'getMoreProducts',security: wccategorytab.wcpt_security,'limit_start' : limit,'total' : total,'category_id' : category_id,'hide_post_title' : request_obj.hide_post_title,'post_title_color' : request_obj.post_title_color,'category_tab_text_color' : request_obj.category_tab_text_color,'category_tab_background_color' : request_obj.category_tab_background_color,'header_text_color' : request_obj.header_text_color,'header_background_color' : request_obj.header_background_color,'display_title_over_image' : request_obj.display_title_over_image,'number_of_post_display' : request_obj.number_of_post_display,'vcode' : request_obj.vcode	},
				success:function(data) {     
					WCPT_printData(elementId,data,"loadmore");
				},error: function(errorThrown){ console.log(errorThrown);}
			});
	});
}
function WCPT_fillProducts(elementId,category_id,request_obj,flg_pr){
	if(flg_v1==1) return;
 	jQuery(document).ready(function($){
	
			$("#"+elementId).parent().parent().find(".pn-active").removeClass("pn-active");
			
	
			if($("#"+elementId).hasClass('pn-active') && flg_pr==1){
				$("#"+elementId).removeClass("pn-active");
				$("#"+elementId).parent().find(".item-posts").slideUp(600);
				return;
			}
			
			var root_element = $("#"+elementId).parent();
			if($("#"+elementId).parent().parent().hasClass("lt-tab"))
				root_element = $("#"+elementId).parent().parent();  
			 
			$("#"+elementId).addClass("pn-active");	
			 
			if(flg_pr==2){
				$(root_element).find(".ik-search-button").html("<br />"+$(".wp-load-icon").html()); 
			}
			else{  
				$("#"+elementId).find(".ik-load-content,.ik-post-no-items").remove();
				$("#"+elementId).find(".ld-pst-item-text").html("<div class='ik-load-content'>"+$(".wp-load-icon").html()+"</div>");
			}	 
			if((category_id==='undefined')) category_id = 0; 
 			flg_v1 = 1;
		 	$.ajax({
				url: wccategorytab.wcpt_ajax_url,
				security: wccategorytab.wcpt_security,
				data: {'action':'getProducts',security: wccategorytab.wcpt_security,flg_pr:flg_pr,'category_id' : category_id,'hide_post_title' : request_obj.hide_post_title,'post_title_color' : request_obj.post_title_color,'category_tab_text_color' : request_obj.category_tab_text_color,'category_tab_background_color' : request_obj.category_tab_background_color,'header_text_color' : request_obj.header_text_color,'header_background_color' : request_obj.header_background_color,'display_title_over_image' : request_obj.display_title_over_image,'number_of_post_display' : request_obj.number_of_post_display,'vcode' : request_obj.vcode},
				success:function(data) { 
					WCPT_printData(elementId,data,"fillpost"); 
				},error: function(errorThrown){ console.log(errorThrown);}
			});   
	});	 
}
function WCPT_printData(elementId,data,flg){
	jQuery(document).ready(function($){
		
	  	var root_element = $("#"+elementId).parent();
		if($("#"+elementId).parent().parent().hasClass("lt-tab"))
			root_element = $("#"+elementId).parent().parent(); 
		 
		if(flg=="loadmore"){
			$(root_element).find(".item-posts").find(".wp-load-icon").remove();
			$(root_element).find(".item-posts").find(".clr").remove();
			$(root_element).find(".item-posts").find(".ik-post-load-more").remove(); 
			$(root_element).find(".item-posts").append(data).fadeIn(400); 
			$(root_element).find(".item-posts").append("<div class='clr'></div>");
		}else{ 
			$("#"+elementId).find(".ik-load-content,.ik-post-no-items").remove(); 
			$(root_element).find(".item-posts").html(data).fadeIn(400);  
		}
		 
		flg_v1 = 0;	
	});	  
	wcpt_manage_grid_layout(elementId);
}
var flg_ms_hover = 0;
function wcpt_pr_item_image_mousehover(ob_pii){ 
	if(flg_ms_hover == 1) return;
	jQuery(document).ready(function($){
		$(ob_pii).find(".ov-layer").show();  
		$(ob_pii).find(".ov-layer").css("visibility","visible"); 
		$(ob_pii).find(".ov-layer").css("top","40");  
		flg_ms_hover = 1;
		if($.trim($(ob_pii).find(".ov-layer").html())!="")
			$(ob_pii).find(".ov-layer").animate({opacity:0.9,top:0},0); 
		else
			$(ob_pii).find(".ov-layer").animate({opacity:0.5,top:0},0); 
	});
} 
function wcpt_pr_item_image_mouseout(ob_pii){
	jQuery(document).ready(function($){ 
		$(ob_pii).find(".ov-layer").animate({opacity:0,top:40},0);
		flg_ms_hover = 0;
		$(ob_pii).find(".ov-layer").hide();
		$(ob_pii).find(".ov-layer").css("visibility","hidden");  
	});
}

function wcpt_cat_tab_ms_out(ob_ms_eff){
	jQuery(document).ready(function($){ 
		$(ob_ms_eff).removeClass("pn-active-bg"); 	
	});
}
function wcpt_cat_tab_ms_hover(ob_ms_eff){
	jQuery(document).ready(function($){ 
		$(ob_ms_eff).addClass("pn-active-bg"); 	
	});
}



function wcpt_manage_grid_layout( elementId ) {
 
	jQuery(document).ready(function($){
	
		var root_element = $("#"+elementId).parent();
		if($("#"+elementId).parent().parent().hasClass("lt-tab"))
			root_element = $("#"+elementId).parent().parent(); 		
		
		if($("#"+elementId).parent().parent().parent().hasClass("pane_style_7")) { 
			
			var cnt_width_lt_six = $("#"+elementId).parent().parent().width();
			var prod_item_height_lt_six = [];	 
			
			$(root_element).find(".item-posts").find(".ik-post-item").each(function(){		
				 
				if(cnt_width_lt_six > 1280)		
					$(this).css("width","305px");
				else if(cnt_width_lt_six <= 1280 && cnt_width_lt_six > 1024){	
					$(this).css("width","24%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}		
				else if(cnt_width_lt_six <= 1024 && cnt_width_lt_six > 700){	
					$(this).css("width","32%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}	
				else if(cnt_width_lt_six <= 700 && cnt_width_lt_six > 570){				
					$(this).css("width","49%");
					$(this).find(".ik-post-content").css("width","70%");
					$(this).find(".ik-post-image").css("width","25%"); 
				}
				else if(cnt_width_lt_six <= 570 && cnt_width_lt_six > 480){ 				
					$(this).css("width","49%");
					$(this).find(".ik-post-content").css("width","65%");
					$(this).find(".ik-post-image").css("width","30%"); 
				}
				else if(cnt_width_lt_six <= 479 ){ 				
					$(this).css("width","98%");
					$(this).find(".ik-post-content").css("width","65%");
					$(this).find(".ik-post-image").css("width","30%"); 
				}  
				 
				$(this).find(".ik-post-name").removeAttr( "style" );
				prod_item_height_lt_six.push( $(this).find(".ik-post-name").height() );  
				
			}); 
			 
			$(root_element).find(".item-posts").find(".ik-post-item").find(".ik-post-name").css("height",(Math.max.apply(Math,prod_item_height_lt_six))+"px");
					
		} else {
		
				var cnt_width = $("#"+elementId).parent().parent().width();
				var prod_item_height = [];	
		 
				$(root_element).find(".item-posts").find(".ik-post-item").each(function(){		
					
					if(cnt_width > 1280)		
						$(this).css("width","184px");
					else if(cnt_width <= 1280 && cnt_width > 1024)		
						$(this).css("width","15.5%");	
					else if(cnt_width <= 1024 && cnt_width > 768)	
						$(this).css("width","19%");
					else if(cnt_width <= 858 && cnt_width > 640)	
						$(this).css("width","24%");
					else if(cnt_width <= 640 && cnt_width > 480)	
						$(this).css("width","32%"); 
					else if(cnt_width <= 480 && cnt_width > 260)	
						$(this).css("width","49%");  
					else if(cnt_width <= 260)	
						$(this).css("width","99%");  	 
					
					$(this).find(".ik-post-name").removeAttr( "style" );	
					prod_item_height.push($(this).find(".ik-post-name").height());
					
				}); 
				
				$(root_element).find(".item-posts").find(".ik-post-item").find(".ik-post-name").css("height",(Math.max.apply(Math,prod_item_height))+"px");
		}
		
		var cnt_width = $("#"+elementId).parent().parent().width();
		if(cnt_width<=390 && cnt_width > 280){
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-title").css("width","82%");
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-category").css("width","82%");
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-category").css("padding-top","10px"); 
		}else if(cnt_width<=280){
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-title").css("width","82%");
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-category").css("width","82%");
		}else{
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-title").removeAttr("style");
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-category").removeAttr("style");
			$(root_element).find(".item-posts").find(".ik-post-category .ik-search-button").removeAttr("style");
		}
		
	});	

}

function wcpt_init_accordion(){
	jQuery(document).ready(function($){ 
		$(".wea_content .item-posts").each(function(){ 
			wcpt_manage_grid_layout($(this).parent().find(".pst-item").attr("id"));
		});
		 
		$(window).resize(function(){
			$(".wea_content .item-posts").each(function(){ 
					wcpt_manage_grid_layout($(this).parent().find(".pst-item").attr("id"));
			});
		});  
		
	});	
}	

if ( window.addEventListener ) { 
	window.addEventListener( "load", wcpt_init_accordion, false );
}
else 
{    
	if ( window.attachEvent ) { 
		  window.attachEvent( "onload", wcpt_init_accordion );
	} else {
		 if ( window.onLoad ) {
		   window.onload = wcpt_init_accordion;
		 }
	}	 
}