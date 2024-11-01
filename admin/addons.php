<?php

new sp_rm_premium_addons;

class sp_rm_premium_addons{
	
	
			
			
			function __construct(){
				
		
				
				add_action('sprm_admin_menu', array($this,'menu'));
				add_filter('sp_rm_menu_item', array($this,'topmenu'));
				add_action( 'wp_ajax_sp_rm_activate_addon',array($this,'activate'));
			}
			function activate(){
			$message =array();
			$message['post'] = $_POST;
			$license_key = $_POST['license_key'];
			$product_name = $_POST['name'];
			$product_slug = $_POST['slug'];
			// data to send in our API request
			
			$edd_action = $_POST['set_action'];	
			$api_params = array(
				'edd_action'=> $edd_action,
				'license' 	=> trim( $license_key),
				'item_name' => urlencode( $product_name  ) // the name of our product in EDD
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params,SP_RM_STORE_URL ), array( 'timeout' => 15, 'body' => $api_params, 'sslverify' => false ) );
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			$message['result'] = $license_data;
			
			if($license_data->success == false){
			$message['error'] = 	'License error, please check your license and try again.';
			}else{
			$message['error'] = '';	
			}
			update_option( 'sp-rm-'.$product_slug.'-license-info', $license_data );
			update_option('sp-rm-'.$product_slug.'-license',$_POST['license_key'] );
			
			echo json_encode($message);
			die();
				
			}
			
			function license_status($addon){
				
		
			// data to send in our API request
			$api_params = array(
				'edd_action'=> 'check_license',
				'license' 	=> trim( $addon['license_key']),
				'item_name' => urlencode( $addon['name'] ) // the name of our product in EDD
			);

			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params,SP_RM_STORE_URL ), array( 'timeout' => 15, 'body' => $api_params, 'sslverify' => false ) );
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			
			
			
			update_option( 'sp-rm-'.$product_slug.'-license-info', $license_data );
			
			
			return $license_data;
			
				
				
			}
			function deactivate(){
				
				
			}
			function menu(){
				
			  add_submenu_page( 'SpRm', ''.__("Addons","sp-rm").'', ''.__("Addons","sp-rm").'', 'manage_options', 'sp-rm-addons', array($this,'view'));	
			}
			function topmenu($menu){
		
			$menu .= '<a class="button" href="admin.php?page=sp-rm-addons">'.__("Addons","sp-rm").'</a> ';
			return $menu;
			}
			
			
			function view(){
				
				
				
				echo  '	<h1>'.__("Listings","sp-rm").'</h1> '. SpRmNavigationMenu().'';
				
				
				$addons = apply_filters('sprm/addons', array());
				
					if(count($addons)==0){
						
					
					echo '<div class="sprm-error"><p>You do not have any addons activated</p></div>';
					
					}else{
					
					
					
					foreach($addons as $addon){
						
							$license_status = $this->license_status($addon);
							
							if($license_status->license == 'active' || $license_status->license == 'valid' ){
							
							$status = '<p style="color:green">License Active! Expires: '.date("m/d/Y", strtotime($license_status->expires)).'</p>';	
							}elseif($license_status->license == 'inactive'){
							$status = '<p style="color:red">License not activated.</p>';
								
							}else{
							$status = '<p style="color:red">License error, please check your license and try again.</p>';
							}
							echo '<div class="sp-rm-addon-item sprm-addon-container-'.$addon['slug'].'"><form action="" method="post" class="sp-addon-activate sp-addon-'.$addon['slug'].'">
								  <input type="hidden" name="action" value="sp_rm_activate_addon">
								  <input type="hidden" name="slug" value="'.$addon['slug'].'">
								   <input type="hidden" name="name" value="'.$addon['name'].'">
								  <h4>'.$addon['name'].'</h4>
								  <p>Activate the license for '.$addon['name'].'. </p>
								  <input type="text" name="license_key" value="'.$addon['license_key'].'" style="width:300px"> ';
								  if($license_status->license == 'valid'){
									echo '
									<input type="hidden"  class="set_action"   name="set_action" value="deactivate_license">
									<input type="submit" name="save" value="Deactivate" class="sprm-activate-button button">';  
								  }else{
									  
									echo '
									<input type="hidden"  class="set_action"   name="set_action" value="activate_license">
									<input type="submit" name="save" value="Activate" class="sprm-activate-button button">';  
									  
								  }
								
								  echo';
								  <div class="sp-rm-license-message-'.$addon['slug'].'">'.$status .'</div>
									</div>
								</form>';	
						
						
					}
						
						
					}
					
					do_action('sprm/addons/forms', $addons);
					
					echo '<div class="sp-rm-addon-purchase-items"><h1>Available Addons</h1>';
					
					
					$request = wp_remote_get('https://smartyrentalmanager.com/addons.php');
					$available_addons =json_decode($request['body']);
					
				
						
				foreach($available_addons as $a){
					if($a->image){
						$image = '<p class="sp-rm-addon-purchase-item-image"><img src="'.$a->image.'"></p>';
					}else{
						$image = '';	
					}
						
						echo '<div class="sp-rm-addon-purchase-item">
								<h3 class="sp-rm-addon-purchase-item-name">'.$a->name.'</h3>
								'.$image.'
								<p class="sp-rm-addon-purchase-item-desc">'.$a->description.'</p>
								<div class="sp-rm-addon-purchase-item-price">'.$a->price.' <a href="'.$a->url.'" target="_blank" class="button">Purchase</a></div>
								</div>';	
					
				}
				
				echo '<div style="clear:both"></div></div>';
				
			}
	
	
	
}