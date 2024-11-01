jQuery(document).ready(function() {

	
	
	
	
	jQuery('.sp_rm_change_image').on('click', function(event) { 		
		 event.preventDefault();
		jQuery('.sp_main_image').attr('src', jQuery(this).attr('data-href'));
	
	})





jQuery("#sprm_share").jsSocials({
    shares: ["twitter", "facebook", "googleplus", "linkedin", "pinterest"]
});

;});