<?php
if (!function_exists('SpRmNavigationMenu')) {
function SpRmNavigationMenu(){
	
	
if($_GET['sp_rm_hidemessage'] == 1){
	
$content .='		
			<script type="text/javascript">
				jQuery(document).ready( function() {
				 sp_cu_dialog("#sp_rm_cdm_ignore",400,200);
			 
				});
			</script>

			<div style="display:none">
			
			<div id="sp_rm_cdm_ignore">
			<h2>It\'s OK!</h2>
			<p>Hey no hard feelings, we hate nag messages too! If you change your mind and want to give us some love checkout the settings page for a link to the our website!</p>
			</div>
		    </div>';	
			update_option("sp_rm_cdm_ignore",1);
}

if($_GET['sp_rm_hidemessage'] == '2'){
	
update_option("sp_rm_cdm_ignore",0);	
}
if(RM_PREMIUM != 1 && get_option("sp_rm_cdm_ignore") != 1){
	
	$content .='	
	<div class="sprm-nag">
	<h2 style="color:#FFF">Need more features? Checkout our addons!</h2>
<br />
<a href="admin.php?page=sp-rm-addons" class="button">View Addons & Licenses </a>  <a style="margin-left:10px" href="http://smartypantsplugins.com/donate/" target="_blank" class="button">Click here to donate</a> <a href="admin.php?page=SpRm&sp_rm_hidemessage=1"  class="button" style="margin-left:10px">Click here to ignore us!</a></p>
	</div>';

}

	
	
if ( !class_exists( 'Theme_My_Login' ) && get_option('sp_rm_require_reg')  == 1 ){
$content .='<div class="sprm-error">This plugin requires theme my login and registered users to be enabled to work properly. <a href="http://wordpress.org/extend/plugins/theme-my-login/">Click here to download</a></div>';
}
	
	if ( get_option('sp_rm_application_link') == '' ){
$content .='<div class="sprm-error">Your application page is missing, please add a page with the shortcode [sp_rm_listing_applications] and <a href="admin.php?page=SpRm">click here to update the url.</a></div>';
}
		if ( get_option('sp_rm_application_ty') == '' ){
$content .='<div class="sprm-error"">Your thank you page is missing! <a href="admin.php?page=SpRm">click here to update the url.</a></div>';
}
	
		do_action('sp_rm_error_message');
	
	
$content .='	<div class="sprm-admin-menu">
  <a class="button" href="admin.php?page=SpRm">'.__("Edit Options","sp-rm").'</a>';
  
  
  if(class_exists('sprm_FormBuilderUser')){
	  $content .=' <a class="button" href="admin.php?page=sp-rm-custom-applications">'.__("Applications","sp-rm").'</a> '; 
  }else{
	 $content .=' <a class="button" href="admin.php?page=sp-rm-applications">'.__("Applications","sp-rm").'</a> '; 
  }
  $content .='

';

$menu_filter = '';
$menu_filter .= apply_filters('sp_rm_menu_item', $menu_filter);



$content .=''.$menu_filter .'
  <div style="clear:both"></div></div> ';	
	
	return $content;
}
function SpRmOptionsPage(){
	
	
	global $wpdb;
	
	
	if($_GET['save_mmis'] == 1){
		

		
		update_option( 'sp_rm_from_email',esc_html($_POST['sp_rm_from_email']) );
		update_option( 'sp_rm_bcc_email',esc_html($_POST['sp_rm_bcc_email']) );
		
		update_option( 'sp_rm_application_link',esc_html($_POST['sp_rm_application_link']) ); 
		update_option( 'sp_rm_application_ty',esc_html($_POST['sp_rm_application_ty']) ); 
		update_option( 'sp_rm_application_emails',esc_html($_POST['sp_rm_application_emails']) ); 
		update_option( 'sp_rm_application_disclaimer',$_POST['sp_rm_application_disclaimer'] ); 
		
		
		update_option( 'sp_rm_list_thumb_size_w',esc_html($_POST['sp_rm_list_thumb_size_w']) ); 
		update_option( 'sp_rm_list_thumb_size_h',esc_html($_POST['sp_rm_list_thumb_size_h']) );
		update_option( 'sp_rm_display_num',esc_html($_POST['sp_rm_display_num']) ); 
		
		 
		update_option( 'sp_rm_thumb_size_w',esc_html($_POST['sp_rm_thumb_size_w']) ); 
		update_option( 'sp_rm_thumb_size_h',esc_html($_POST['sp_rm_thumb_size_h']) ); 
		update_option( 'sp_rm_premium_price_variance',esc_html($_POST['sp_rm_premium_price_variance']) ); 
		
			
			update_option('sp_rm_meta_search_fields', $_POST['sp_rm_meta_search_fields']);
			update_option('sp_rm_premium_price_format', $_POST['sp_rm_premium_price_format']);
			update_option('sprm_recaptcha_public_key', $_POST['sprm_recaptcha_public_key']);
			update_option('sprm_recaptcha_private_key', $_POST['sprm_recaptcha_private_key']);
			update_option('sp_rm_premium_license', $_POST['sp_rm_premium_license']);
			update_option('sprm_old_application', $_POST['sprm_old_application']);
				if($_FILES['sprm_download_application']['name'] != ""){			
     	$sprm_download_application = wp_upload_bits($_FILES['sprm_download_application']["name"], null, file_get_contents($_FILES['sprm_download_application']["tmp_name"]));		
		 update_option('sprm_download_application',$sprm_download_application['url']);
		}
		
		if($_POST['remove_application'] == 1){
			delete_option('sprm_download_application');
		}
			
			
			if($_POST['sprm_recaptcha'] == "1"){update_option('sprm_recaptcha','1' ); }else{update_option('sprm_recaptcha','0' );	}	
			if($_POST['sprm_old_application'] == "1"){update_option('sprm_old_application','1' ); }else{update_option('sprm_old_application','0' );	}	
			if($_POST['sp_rm_listings_premium_layout'] == "1"){update_option('sp_rm_listings_premium_layout','1' ); }else{update_option('sp_rm_listings_premium_layout','0' );	}	
		
		
		 
			  update_option( 'sp_rm_gmap_api',esc_html($_POST['sp_rm_gmap_api']) ); 
			   update_option( 'sp_rm_gmap_width',esc_html($_POST['sp_rm_gmap_width']) ); 
			    update_option( 'sp_rm_gmap_height',esc_html($_POST['sp_rm_gmap_height']) ); 
				 update_option( 'sp_rm_gmap_zoom',esc_html($_POST['sp_rm_gmap_zoom']) ); 
			
		
				if($_POST['dlgrl_enable_ssn'] == "1"){	update_option('dlgrl_enable_ssn','1' ); 				}else{					update_option('dlgrl_enable_ssn','0' ); 				}
				if($_POST['sp_rm_require_reg'] == "1"){	update_option('sp_rm_require_reg','1' ); 				}else{					update_option('sp_rm_require_reg','0' ); 				}	
				
				
			
			
	}
	
		if(get_option('sprm_recaptcha') == 1){ $sprm_recaptcha = ' checked="checked" ';	}else{ $sprm_recaptcha = '  '; }
	if(get_option('sprm_old_application') == 1){ $sprm_old_application = ' checked="checked" ';	}else{ $sprm_old_application = '  '; }
	if(get_option('sp_rm_listings_premium_layout') == 1){ $sp_rm_listings_premium_layout = ' checked="checked" ';	}else{ $sp_rm_listings_premium_layout = '  '; }
	
	if(get_option('sprm_download_application') != ''){
	$application = '<div style="padding:5px;background:#EFEFEF;margin:5px"><a href="'.get_option('sprm_download_application').'">View Uploaded Application</a> <input style="margin-left:15px" type="checkbox" name="remove_application" value="1"> Check to remove application</div>';	
	}
	
	
	
	if(get_option('dlgrl_enable_ssn') == 1){	 $enablessn = ' checked="checked" ';	}else{		 $enablessn = '  ';}
	if(get_option('sp_rm_require_reg') == 1){	 $sp_rm_require_reg = ' checked="checked" ';	}else{		 $sp_rm_require_reg = '  ';}
	
	
	
echo '<h1>Options Page</h1>'. SpRmNavigationMenu().'';

	
	


	echo '<h2>Settings</h2>
	';
	
	




echo '
	
	<form action="admin.php?page=SpRm&save_mmis=1" method="post" enctype="multipart/form-data">
	 <table class="wp-list-table widefat fixed posts" cellspacing="0">
    
	   <tr>
    <td width="300"><strong>'.__("Application From email","sp-rm").'</strong><br><em>'.__("From Email for Applications.","sp-rm").'</td>
    <td><input type="text" name="sp_rm_from_email"  value="'.get_option('sp_rm_from_email',get_bloginfo( 'admin_email' )).'"size=80" > </td>
  </tr>
    <tr>
    <td width="300"><strong>'.__("BCC Emails","sp-rm").'</strong><br><em>'.__("Additional Emails you want the application to go .","sp-rm").'</em></td>
    <td><input type="text" name="sp_rm_application_emails"  value="'.get_option('sp_rm_application_emails').'"  size=80"> </td>
  </tr>
	   <tr>
    <td width="300"><strong>'.__("List view image","sp-rm").'</strong><br><em>'.__("Size of the thumbnail for the listing of all available apartments.","sp-rm").'</td>
    <td>Width:<input type="text" name="sp_rm_thumb_size_w"  value="'.get_option('sp_rm_thumb_size_w').'"  size=15"> Height:<input type="text" name="sp_rm_thumb_size_h"  value="'.get_option('sp_rm_thumb_size_h').'"  size=15"> </td>
  </tr>
    
	 <tr>
    <td width="300"><strong>'.__("Listings per page","sp-rm").'</strong><br><em>'.__("The amount of listings per page, the rest are paginated.","sp-rm").'</td>
    <td><input type="number" name="sp_rm_display_num"  value="'.get_option('sp_rm_display_num',15).'"  size=15"> </td>
  </tr>
	
          <tr>
    <td width="300"><strong>'.__("Listing Page image","sp-rm").'</strong><br><em>'.__("Size of the image for the actual listing page.","sp-rm").'</td>
    <td>Width:<input type="text" name="sp_rm_list_thumb_size_w"  value="'.get_option('sp_rm_list_thumb_size_w').'"  size=15"> Height:<input type="text" name="sp_rm_list_thumb_size_h"  value="'.get_option('sp_rm_list_thumb_size_h').'"  size=15"> </td>
  </tr>
    
  
   
         <tr>
    <td width="300"><strong>'.__("Application full url","sp-rm").'</strong><br><em>'.__("This is the full url to your applications page which is needed to redirect users who are applying for the application. Please put the shortcode [sp_rm_listing_applications] on the page.","sp-rm").'</td>
    <td><input type="text" name="sp_rm_application_link"  value="'.get_option('sp_rm_application_link').'"  size=80"> </td>
  </tr>
       <tr>
    <td width="300"><strong>'.__("Thank you page","sp-rm").'</strong><br><em>'.__("Full url for the thank you page after the user submits an app.","sp-rm").'</em></td>
    <td><input type="text" name="sp_rm_application_ty"  value="'.get_option('sp_rm_application_ty').'"  size=80"> </td>
  </tr>
  
  
    <tr>
    <td width="300"><strong>'.__("Require Registration?","sp-rm").'</strong><br><em>'.__("Requiring registration is a good tool for marketing and keeping track of people who are submitting applications. This may open for more opportunity in development down the road.","sp-rm").'</em></td>
    <td><input type="checkbox" name="sp_rm_require_reg"   value="1" '. $sp_rm_require_reg.'> </td>
  </tr>
  
  
     <tr>
    <td width="300"><strong>'.__("Enable SSN?","sp-rm").'</strong><br><em>'.__("Would you like to take the users social security number? Please only use this features if you are using an SSL Certificate as you are responsibile for your own data. The SSN is encrypted into the database using  advanced binary encryption methods.","sp-rm").'</em></td>
    <td><input type="checkbox" name="dlgrl_enable_ssn"   value="1" '. $enablessn.'> </td>
  </tr>
  
   <tr>
    <td width="300"><strong>'.__("Disclaimer","sp-rm").'</strong><br><em>'.__("This is the disclaimer on the application (legal terms)","sp-rm").'</em></td>
    <td><textarea style="width:100%;height:100px" name="sp_rm_application_disclaimer" >'.stripslashes(get_option('sp_rm_application_disclaimer')).'</textarea> </td>
  </tr>';
  

	if(function_exists('sp_rm_premium_settings')){
		
	echo sp_rm_premium_settings();	
		
	}
	
	 echo '<tr>
    <td width="300"><strong>'.__("Google Maps API Key","sp-rm").'</strong><br><em>'.__("Use this funciton only if you want to integrate google maps into your posts. Remove it to disable google maps!","sp-rm").' </em></td>
    <td><input type="text" name="sp_rm_gmap_api"  value="'.get_option('sp_rm_gmap_api').'"  size=80"> <a href="https://code.google.com/apis/console/" target="_blank">Click here to get a key</a></td>
  </tr>
  <tr>
    <td width="300"><strong>'.__("Google Map Size","sp-rm").'</strong><br><em>'.__("This is the settings for the map size of the goolge map box,numbers only. Sizes are in pixels.","sp-rm").' </em></td>
    <td>Width: <input type="text" name="sp_rm_gmap_width"  value="'.get_option('sp_rm_gmap_width').'"  size=10">px <br>height: <input type="text" name="sp_rm_gmap_height"  value="'.get_option('sp_rm_gmap_height').'"  size=10">px <br> Zoom: <input type="text" name="sp_rm_gmap_zoom"  value="'.get_option('sp_rm_gmap_zoom').'"  size=10"> Zoom levels between 0 (the lowest zoom level, in which the entire world can be seen on one map) to 21+ (down to individual buildings) </td>
  </tr>
  
  ';  
	  

  
 
  echo '
    <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="save_options" value="'.__("Save Options","sp-rm").'"></td>
  </tr>


      <tr>
    <td width="300"><strong>Price Format</strong><br><em>Price Format use [p] for the price.</em></td>
    <td><input type="text" name="sp_rm_premium_price_format"    value="'.get_option('sp_rm_premium_price_format', '$[p] /Month').'"  size="80">  </td>
  </tr>
    <tr>
    <td width="300"><strong>Price Variance</strong><br><em>Set a price variance and search results will return results within the variance. Example: set to 100 and user searches 1500 and the search results return 1400-1600.</em></td>
    <td><input type="number" name="sp_rm_premium_price_variance"    value="'.get_option('sp_rm_premium_price_variance', '100').'"  size="80">  </td>
  </tr>
     <tr>
    <td width="300"><strong>Meta Search Fields</strong><br><em>Search fields in addition to name and price, seperate with a comma and use the meta name. Use search-name and search-price for name and price search.</a></em></td>
    <td><input type="text" name="sp_rm_meta_search_fields"    value="'.get_option('sp_rm_meta_search_fields', 'search-name,search-price').'"  size="80">  </td>
  </tr>
	<tr>
    <td width="300"><strong>Enable reCaptcha?</strong><br><em>Check this to enable captcha code for applications.</a></em></td>
    <td><input type="checkbox" name="sprm_recaptcha"   value="1" '. $sprm_recaptcha.'><br>Site  Key: <input style="width:400px" type="text" name="sprm_recaptcha_public_key" value="'.get_option('sprm_recaptcha_public_key').'"><br>Secret Key: <input type="text" name="sprm_recaptcha_private_key" value="'.get_option('sprm_recaptcha_private_key').'" style="width:400px"> </td>
  </tr>

    <tr>
    <td width="300"><strong>Default Application</strong><br><em>Use this option if you want to over-ride the default application with a custom application download. For instance if you want to offer a pdf download instead.</a></em></td>
    <td><input type="file" name="sprm_download_application"  > '.$application.' </td>
  </tr>
   <tr>
    <td>&nbsp;</td>
    <td><input type="submit" name="save_options" value="'.__("Save Options","sp-rm").'"> </td>
  </tr>
</table>

';

 do_action('sprm_options_page');


echo '</form>
	
	';
	
	
	

}
}
?>