<?php
	if(!class_exists('sp_rm_developments_premium')){

	
	
		
	
	
class sp_rm_developments_premium{
	
	

		function ajax_delete($feature_id){
			global $wpdb;
			$message['message'] = 'Error, something went wrong';
			if(current_user_can('manage_options')){
			$wpdb->query($wpdb->prepare("DELETE FROM ".$wpdb->prefix . "sp_rm_rentals_features WHERE id = %d",$_POST['id']));
			
			$message['message'] = 'Deleted';
			$message['id'] = $_POST['id'];
			}
			
			echo json_encode($message);
			die(0);
		}
		function save_features($insert_id){
				global $wpdb;
			if($_POST['save'] != ""){
				
				
				
				#update existing features
				if(is_array($_POST['features'])){
					
				$features = array_filter($_POST['features']);				
					
					if(count($features)>0){
					foreach($features as $key=>$value){				
							if($value['name'] != '' && $value['value'] != ''){
								$update['meta_key'] = $value['name'];
								$update['meta_value'] = $value['value'];
								$where['id'] = $key;
								$wpdb->update("".$wpdb->prefix . "sp_rm_rentals_features",$update,$where);
								unset($update);unset($where);
							}
					}
					}
				
				}
				#insert new features
				unset($features);
		
				if(is_array($_POST['features_new'])){
					
				$features_new_names = array_filter($_POST['features_new']['name']);	
				$features_new_values = array_filter($_POST['features_new']['value']);	
				
					if(count($features_new_names)>0){
						
					foreach($features_new_names as $key=>$value){				
							if($value != '' && $features_new_values[$key] != ''){
								$insert['meta_key'] = $value;
								$insert['meta_value'] = $features_new_values[$key];
								$insert['post_id'] = $_POST['id'];
							foreach($insert as $key=>$value){ if(is_null($value)){ unset($insert[$key]); } }
								$wpdb->insert("".$wpdb->prefix . "sp_rm_rentals_features",$insert);
								unset($insert);
							}
					}
					}
				
				}
				
			}
			
		}
	
		function save_rentals($insert){
			
			if($_POST['save'] != ""){
				
				unset($insert['features']);
				unset($insert['features_values']);
				
				
			}
			return $insert;
		}
	
		function features_autocomplete(){
			global $wpdb;
			$features = $wpdb->get_results("SELECT DISTINCT(meta_key) FROM  ".$wpdb->prefix . "sp_rm_rentals_features order by meta_key", ARRAY_A);	
			
			
			$html .= ' <script>
			
			jQuery(function(){
  var currencies = [';
  
  for($i=0; $i<count($features); $i++){
    $html .='{ value: "'.$features[$i]['meta_key'].'", data: "'.$features[$i]['meta_key'].'" },
	';
  }

  
  
 $html .='
 				];
  jQuery(".features_tags").autocomplete({
    lookup: currencies,
    onSelect: function (suggestion) {
      var thehtml = "<strong>Feature Name:</strong> " + suggestion.value + " <br> <strong>Symbol:</strong> " + suggestion.data;
      jQuery("#outputcontent").html(thehtml);
    }
  });
  

});
			

  </script><div id="outputcontent"></div>';
  
  return $html;	
			
			
		}
		function features($html,$r){
			global $wpdb;
			unset($html);
			
				$features = $wpdb->get_results($wpdb->prepare("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_rentals_features where post_id = %d order by meta_key", $r['id']), ARRAY_A);	
			
				$features_content .='<tr>
		<td>'.__("Features","sp-rm").':</td>
	<td>';
	

	
	#$features_content .= $this->features_autocomplete();
	$features_content.= '<div id="sp_rm_feature_main" class="sp_rm_feature ui-state-default"><a href="javascript:sp_rm_add_feature_premium();"><img src="../wp-content/plugins/sp-rental-manager/images/add.png">Add Feature</a> </div><div class="sprm-sortable sprm-admin-features-list">';
	
	if($features != false){
	for($i=0; $i<count($features); $i++){
			
		
		
		$features_content.= '<div style="padding:5px;margin:5px 0px;background-color:#EFEFEF" id="sp_rm_feature_main" class="sp_rm_feature ui-state-default feature_id_'.$features[$i]['id'].'">Feature Name <input type="text" name="features['.$features[$i]['id'].'][name]" value="'.stripslashes($features[$i]['meta_key']).'" class="features_tags"> Feature Value <input type="text" name="features['.$features[$i]['id'].'][value]" value="'.stripslashes($features[$i]['meta_value']).'"> <a class="sp_rm_delete_feature" href="#" data-id="'.$features[$i]['id'].'"><img src="../wp-content/plugins/sp-rental-manager/images/del.png"></a></div>';
			
	}
	}
	$features_content.= '</div><div id="sp_rm_feature_end"></div>
	</td>
	</tr>';

		 return $features_content;
		}
	
	
}
if (!function_exists('sp_rm_admin_multiple_images')) {

if(!function_exists('init_sp_rm_google_maps')){
add_action('wp_rm_development_form', 'sp_rm_property_gps_coords', $r);
add_action('wp_rm_development_action', 'sp_rm_property_gps_coords_save', $id);	
}

}


function sp_rm_property_gps_coords_save($id){



	
	sp_rm_update_meta('rental_lon',$_POST['lon'],$id);	

	sp_rm_update_meta('rental_lat',$_POST['lat'],$id);	
	sp_rm_update_meta('price_format',$_POST['price_format'],$id);

	
}


function sp_rm_property_gps_coords($rental){

	$id = $rental[0]['id'];
	
	
	
	echo '<tr>
		<td>'.__("GPS Coordinates","sp-rm").'<br><em>Use this to over-ride the google maps address</em>:</td>
	<td>Latitude: <input type="text" name="rental_lat" value="'. sp_rm_get_meta('rental_lat',$id).'"> Longitude: <input type="text" name="rental_lon" value="'. sp_rm_get_meta('rental_lon',$id).'"></td>
	</tr>
	<tr>
		<td>'.__("Overide Price Format","sp-rm").'<br><em>You can use this to overide the price format, use [p] for the price</em></td>
	<td><input type="text" name="price_format" value="'. sp_rm_get_meta('price_format',$id).'"></td>
	</tr>
	';
	
	
	
}

function sp_rm_admin_multiple_images($id){
	
	
global $wpdb;

$r[0]['id'] = $id;

$content .='

<h1>Additional Images</h1>
<div>
<form name="image_form" action="admin.php?page=sp-rm-developments&function=manage-listing&id='.$_GET['id'].'&pics=1" method="post" enctype="multipart/form-data">

<input type="file" id=photo" name="photo" /> <input type="submit" name="add_image" value="Add Image">
</form>
</div>
';	
	
	
	
		
			if($_FILES['photo']['name'] != ""){			
     	$photo = wp_upload_bits($_FILES['photo']["name"], null, file_get_contents($_FILES['photo']["tmp_name"]));	
		
		add_option('sp_rm_images_'.$r[0]['id'].'_'.time().'',$photo['url']);
		}	
		
		
		
	
	
	if($_GET['delete-image'] != ""){
	$wpdb->query("DELETE FROM ".$wpdb->prefix . "options WHERE option_id = '".$wpdb->escape($_GET['delete-image'])."'	");		

	}
	
	$r_images = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "options where option_name LIKE  'sp_rm_images_".$r[0]['id']."_%'", ARRAY_A);
			


	$content .='

	
	  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>


<th>'.__("Image","sp-rm").'</th>
<th>'.__("Delete","sp-rm").'</th>
</tr>
	</thead><tbody>';	
	if(count($r_images) > 0){
	
	for($i=0; $i<count($r_images); $i++){
		$content .='<tr class="sp_rm_ai">


<td width="160"><img  src="'.sp_rm_thumbnail($r_images[$i]['option_value'],200, 150).'"></td>
<td><a href="admin.php?page=sp-rm-developments&function=manage-listing&id='.$_GET['id'].'&delete-image='.$r_images[$i]['option_id'].'&pics=1" class="button">'.__("Delete","sp-rm").'</a></td>
</tr>';
	}
	}else{
	$content .= '<tr class="sp_holder"><td colspan="2"><p style="color:red">No additional Images Added</p></td></tr>';	
	}
	
	
	$content .='</tbody></table>';
	
	
	return $content;	
	
	
}

	$sp_rm_developments_premium = new sp_rm_developments_premium;

	add_filter('rental_manager/admin/save', array($sp_rm_developments_premium, 'save_rentals'));
	add_filter('rental_manager/admin/features', array($sp_rm_developments_premium, 'features'),10,2);
	add_action('rental_manager/admin/after_save', array($sp_rm_developments_premium, 'save_features'));
	add_action('sp_rm/admin/listing/above_form','sp_rm_admin_multiple_images');
	add_action( 'wp_ajax_sp_rm_ajax_delete', array($sp_rm_developments_premium, 'ajax_delete') );
	add_action( 'wp_ajax_nopriv_sp_rm_ajax_delete',array($sp_rm_developments_premium, 'ajax_delete')  );
}