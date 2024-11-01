	
	
 jQuery(document).ready(function($){


	 if(wp.media){
	var _custom_media = true,
	_orig_send_attachment = wp.media.editor.send.attachment;
	 }
 		$('.wpfh-metabox-table .wpfh-remove-photo').click(function(e) {
			
			var container = $(this).parent();
			
				$('.preview-image', container).html(' ');
				$('#_unique_name', container).val('');
			
			return false;
		});
 
 
 $( document ).on( "click", ".sp-rm-gallery-item-remove", function() {

	 $(this).parent().remove();
	return false; 
 });
 	$('.rm-add-gallery-photo-to-gallery').click(function(e) {
		
		var container = $(this).parent();
		var preview_image = $(this).next('.preview-image');
		var send_attachment_bkp = wp.media.editor.send.attachment;
		
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				
				$(".rm-gallery-images-display",container).append('<div class="sp-rm-gallery-item"><img src="'+ attachment.url + '" ><br><input type="hidden" name="gallery[]" value="'+ attachment.url + '"><a href="#" class="sp-rm-gallery-item-remove button">Remove</a></div>');

			console.log(attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
		return false;
	});
	
	$(".sp-addon-activate").on("submit",function(){
		
		var form_data = $( this ).serializeArray();
		console.log(form_data);
			jQuery.post(ajaxurl,form_data, function(response) {
					var obj = $.parseJSON(response);
				
					if(obj.error != ''){
						
					$('.sp-rm-license-message-'+obj.post.slug).html('<p style="color:red">'+ obj.error+'</p>');	
					}else{
					
					
					var container = $('.sprm-addon-container-'+obj.post.slug);	
					
					console.log(obj.result.license);
					if(obj.result.license == 'valid'){
					$('.sp-rm-license-message-'+obj.post.slug).html('<p style="color:green">License Active! Expires: '+ obj.result.expires+'</p>');	
					$(".sprm-activate-button",container).val('Deactivate');
					$(".set_action",container).val('deactivate_license');
					}else{
					$('.sp-rm-license-message-'+obj.post.slug).html('<p style="color:red">License not activated.</p>');		
					$(".sprm-activate-button",container).val('Activate');
					$(".set_action",container).val('activate_license');
					}
					}
				});
		
		
		return false;
	});
 
	$('.rm-upload-photo').click(function(e) {
		
		var container = $(this).parent();
		var preview_image = $(this).next('.preview-image');
		var send_attachment_bkp = wp.media.editor.send.attachment;
		
		var button = $(this);
		var id = button.attr('id').replace('_button', '');
		_custom_media = true;
		wp.media.editor.send.attachment = function(props, attachment){
			if ( _custom_media ) {
				$('#_unique_name', container).val(attachment.url);
				$('.preview-image', container).html('<img src=\"'+ attachment.url + '\"  height=\"150\">')
				wpfh_upload_profile_photo_to_gallery('".wpfh_obit_page_id()."',attachment.url);
			} else {
				return _orig_send_attachment.apply( this, [props, attachment] );
			};
		}
 
		wp.media.editor.open(button);
		return false;
	});
 
	$('.add_media').on('click', function(){
		_custom_media = false;
	});
});



	function sp_rm_add_feature(){
		
		jQuery('#sp_rm_feature_end').before('<div id="sp_rm_feature_main" class="sp_rm_feature">Feature Name <input type="text" name="features[]" value="" > Feature Value <input type="text" name="features_value[]" value=""></div>');
	}
	
			jQuery(document).ready(function() {

	
	
	jQuery('.sp_rm_delete_new_feature').on('click', function() { 	
		var feature_container = jQuery(this).parent();
			feature_container.fadeOut(function(){
			feature_container.remove();	
			
			});
		return false;
	});
	
			jQuery('.sp_rm_delete_feature').on('click', function() { 	
			
			var data_id =  jQuery(this).attr('data-id');
			
				var data = {
					'action': 'sp_rm_ajax_delete',
					'id':  data_id
				};
		
				
				jQuery.post(ajaxurl, data, function(response) {
					jQuery('.feature_id_' +  data_id).fadeOut();
				});
				
				return false;
			});
	
	});

	
	

	function sp_rm_add_feature_premium(){
		
		jQuery('.sprm-admin-features-list').append('<div style="padding:5px;margin:5px 0px;background-color:#EFEFEF" id="sp_rm_feature_main" class="sp_rm_feature ui-state-default">Feature Name <input type="text" name="features_new[name][]" value="" class="features_tags"> Feature Value <input type="text" name="features_new[value][]" value=""> <a class="sp_rm_delete_new_feature" href="#" ><img src="../wp-content/plugins/sp-rental-manager/images/del.png"></a></div>');
	}
	
