jQuery(document).ready(function(){
	jQuery('.color-field').wpColorPicker(); 
	jQuery(document).ajaxComplete(function(){  
		jQuery('.color-field').each(function(){
			var obj_parent = jQuery(this).parent().parent();
			jQuery(this).removeClass("wp-color-picker"); 
			jQuery(this).removeAttr("style");
			jQuery(this).show();
			jQuery(this).parent().find('.wp-picker-clear').remove();
			var hmt_color_picker_val =  jQuery(this).val();
			var hmt_color_picker = jQuery(this).parent().html();
			jQuery(obj_parent).html(hmt_color_picker); 
			jQuery(obj_parent).find(".color-field").wpColorPicker();   
		}); 
	});
});


function ck_category_check(ob_check) {
		var is_checked_len = jQuery(ob_check).parent().parent().find('input:checked').length; 
		if( is_checked_len == 0 ) {
			ob_check.checked = true;
		} 
} 