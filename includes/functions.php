<?php


function sp_rm_get_listing($id){
	global $wpdb;
	$r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals where id =%d",intval($id)), ARRAY_A);
	if($r == false){
	return false;	
	}
	return $r[0];
}
function sprm_theme_settings(){
	
	return get_option('smartyrentalmanager_customizer');
}

function sp_rm_c_get_development_properties($did){
		global $wpdb;
		
		
		$query = "SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals  where  status = 0 and did = '".$did."' order by name";

		$r = $wpdb->get_results($query, ARRAY_A);	
		if($r == false){
		
		return false;
		}else{
			for($i=0; $i<count($r ); $i++){
			
			$id[]  = $r[$i]['id'];
				
			}
			
		
		return implode(",", $id);
		}
		
		
	
}

function wp_rm_rental_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

function rm_get_application_link($id){
	
	
	
	$link = ''.get_option('sp_rm_application_link').'?listing_id='.$id.''.sp_rm_check_permalinks().'';
	$link = apply_filters("sprm/application_link",$link,$id);
	
	return $link;
}

function rm_admin_uploader($name,$r,$value=''){
	
	
	$html = "
	

	";
		if($value != ''){
				$image = '<img src="'.$value.'" height="150">';	
					
				}else{
				$image = '';	
				}
		
			
			
			$html .= ' <div class="wpfh-metabox-table">
	<input id="_unique_name" name="'.$name.'" class="'.$name.'" type="hidden" value="'.@$value.'" />
	<a  href="#" id="_unique_name_button" class="button rm-upload-photo" name="_unique_name_button" type="text"  />'.__('Upload','sp-wpfh').'</a>
		<a href="#"id="_unique_remove_button" class="button rm-remove-photo" name="_unique_remove_button" type="text">'.__('Remove','sp-wpfh').'</a>';
		
	$gallery = '';
		if($r){
			
			$gallery_images = get_option('sp_rm_images_'.$r[0]['id'].'');	
			
				if($gallery_images){
					
						foreach($gallery_images as $gal){
							
							
				$gallery .='<div class="sp-rm-gallery-item"><img src="'.$gal.'" ><br><input type="hidden" name="gallery[]" value="'.$gal.'"><a href="#" class="sp-rm-gallery-item-remove button">Remove</a></div>';	
						}
				}
		}
	$html .=  '<div class="preview-image" style="margin:10px">'.@$image.'</div>
	
	
		<div class="rm-gallery-images">
		 <a href="#" id="" class="button rm-add-gallery-photo-to-gallery"  type="text" data-target="'.$name.'"  data-id="'.$id.'" >'.__('Add to gallery','sp-wpfh').'</a>
		 
		 <div class="rm-gallery-images-display">'.$gallery.'</div>
		<div style="clear:both"></div>
		</div>
	</div>';
	
	return $html;
}


function sp_rmc_format_price($price,$overide = false){
	
	$price_template = get_option('sp_rm_premium_price_format', '$[p] /Month');
	if($overide != false){
	$price_template = $overide ;
	}
	
	$price_template = str_replace('[p]', $price, $price_template);
	
	
	return $price_template;
}


		function cdm_rm_locate_template( $template_name, $template_path = '', $default_path = '' ) {
			
			if ( ! $template_path ) {
				$template_path =SP_CDM_RM_TEMPLATE_DIR;
			}
		
			if ( ! $default_path ) {
				$default_path = SP_CDM_RM_PLUGIN_DIR;
			}
		
			// Look within passed path within the theme - this is priority.
			$template = locate_template(
				array(
					trailingslashit( $template_path ) . $template_name,
					$template_name
				)
			);
			
			
			// Get default template/
			if ( ! $template  ) {
				$template = $default_path . $template_name;
			}
				if(file_exists( get_stylesheet_directory() . '/sp-rental-manager/'.$template_name)){					
					$template = get_stylesheet_directory() . '/sp-rental-manager/'.$template_name;
				}	
				
				
			// Return what we found.
			return apply_filters( 'cdm_rm_locate_template', $template, $template_name, $template_path );
		}
			
		function cdm_rm_get_template( $template_name, $return = false, $args = array(), $template_path = '', $default_path = '' ) {
			if ( ! empty( $args ) && is_array( $args ) ) {
				extract( $args );
			}
	
			$located = cdm_rm_locate_template( $template_name, $template_path, $default_path );
			
			if ( ! file_exists( $located ) ) {
				echo 'Template not found: '.$located.'';
				return;
			}
		
			// Allow 3rd party plugin filter template file from their plugin.
			$located = apply_filters( 'cdm_rm_get_template', $located, $template_name, $args, $template_path, $default_path );
		
			do_action( 'cdm_rm_before_template_part', $template_name, $template_path, $located, $args );
			if($return == true){
			 ob_start();
			include( $located );
			 return ob_get_clean();	
			}else{
			include( $located );
			}
			do_action( 'cdm_rm_after_template_part', $template_name, $template_path, $located, $args );
		}
			


function wp_community_rental_crypt( $string, $action = 'e' ) {
    // you may change these values to your own
    $secret_key = 'my_simple_secret_key';
    $secret_iv = 'my_simple_secret_iv';
 
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $key = hash( 'sha256', $secret_key );
    $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
 
    if( $action == 'e' ) {
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
    }
    else if( $action == 'd' ){
        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );
    }
 
    return $output;
}

function sp_rm_delete_meta($option_name,$id){
	global $wpdb;
	$wpdb->query("DELETE FROM ".$wpdb->prefix . "sp_rm_rentals_meta WHERE post_id = ".$wpdb->escape($id)." and meta_key = '".$option_name."'");	
	
	
}
function sp_rm_get_meta($option_name,$id){
	global $wpdb;
	
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals_meta where post_id = '".$wpdb->escape($id)."' and meta_key = '".$option_name."'", ARRAY_A);
	if(count($r) > 0){
		return $r[0]['meta_value'];
	}else{
		return false;
	}
}
function sp_rm_update_meta($option_name,$option_value,$post_id,$multi = false){
	global $wpdb;
	
	
	$r_find = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals_meta where post_id = '".$wpdb->escape($post_id)."' and meta_key = '".$option_name."'", ARRAY_A);
	
	if(count($r_find) == 0){
	sp_rm_add_meta($option_name,$option_value,$post_id,$multi);
	}else{
		
	$update['meta_value'] = $option_value;

	
	$where['post_id'] = $post_id;
	$where['meta_key'] = $option_name;
	
	
	$wpdb->update("".$wpdb->prefix . "sp_rm_rentals_meta",$update,$where);
	}
}


function sp_rm_add_meta($option_name,$option_value,$post_id,$multi = false){
	
	global $wpdb;
	
	
	$insert['meta_key'] = $option_name;
	$insert['meta_value'] = $option_value;
	$insert['post_id'] = $post_id;
	foreach($insert as $key=>$value){ if(is_null($value)){ unset($insert[$key]); } }
	$wpdb->insert("".$wpdb->prefix . "sp_rm_rentals_meta", $insert);
	
}
function sp_rm_thumbnail($url,$w,$h){
	global $wpdb;
	$params = array('width' => 400, 'height' => $h,'width' => $w, 'crop' => true);

			return bfi_thumb($url, $params);
}


function sp_rm_html_mail(){
    return "text/html";
}
function dlgAdminEmail($subject,$body,$to=''){
	
				
			$headers = array();
			$from_email = get_option('sp_rm_from_email',get_bloginfo( 'admin_email' ));
			if($to == ''){
			$to = $from_email;	
			}
			
			#$headers[] = 'From: '.$from_email.'';
	    #
			
			
			$bcc_email = get_option('sp_rm_application_emails');
			if($bcc_email != ''){
			$headers[] = 'Bcc: '.$bcc_email.'';
			}
		
			add_filter( 'wp_mail_content_type','sp_rm_html_mail' );
			wp_mail($to,
					$subject,
					$body,
					$headers
					);
					
			remove_filter( 'wp_mail_content_type','sp_rm_html_mail' );
}


function sp_rm_redirect($url){
	
echo  '<script type="text/javascript">
<!--
window.location = "'.$url.'"
//-->
</script>';	
	
}




function sp_rm_encrypt($text) 
{ 
    return wp_community_rental_crypt($text, 'e' );
} 

function sp_rm_decrypt($text) 
{ 
    return wp_community_rental_crypt($text, 'd' );
} 



function sp_rm_page_id() {
		 global $wpdb;
		 
		 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[rental_listing%' and post_type = 'page' and post_status = 'publish'", ARRAY_A);
							
		if($r[0]['ID'] != ""){

  		 return $r[0]['ID']; 
	
		}else{
		return false;	
		}
		}
	
function sprm_page($rental_id){
	global $wpdb;
	if($rental_id == false){
		return get_permalink(sp_rm_page_id());
		
	}
		$r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals where id = %d",$rental_id), ARRAY_A);
	if ( get_option('permalink_structure') != '' ) { 
	 	$url = get_permalink(sp_rm_page_id()).''.sanitize_title($r[0]['name']).'/'.$r[0]['id'].'/';
		

	}else{
		
	$url = get_permalink(sp_rm_page_id()).'&listing_id='.$rental_id.'';	
	}
	
	return $url;
	}
		