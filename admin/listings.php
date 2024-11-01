<?php


new sp_rm_admin_listings;


class sp_rm_admin_listings{
	
	
	function __construct(){
		
		add_action('sprm_admin_menu', array($this,'menu'));
		add_filter('sp_rm_menu_item', array($this,'topmenu'));
	}
	
	function menu(){
	
	
	add_submenu_page( 'SpRm', ''.__("Listings","sp-rm").'', ''.__("Listings","sp-rm").'', 'manage_options', 'sp-rm-listings', array($this,'view'));	
		
	}
	function topmenu($menu){
		
		$menu .= '<a class="button" href="admin.php?page=sp-rm-listings">'.__("Listings","sp-rm").'</a>';
		return $menu;
	}
	function edit(){
		
		
global $wpdb;

		
		 wp_enqueue_media();
		add_action('admin_init', 'editor_admin_init');
	add_action('admin_head', 'editor_admin_head');
		
if($_POST['save'] != ""){
	
	
	
	
		$insert['name'] = $_POST['dev-name'];
		$insert['address'] = $_POST['address'];
		$insert['unit'] = $_POST['unit'];
		$insert['state'] = $_POST['state'];
		$insert['city'] = $_POST['city'];
		$insert['price'] = $_POST['price'];
		
		$insert['did'] = $_POST['did'];
		$insert['description'] = $_POST['description'];
		
		$insert['features'] = @serialize(@array_filter($_POST['features']));
		$insert['features_values'] = @serialize(@array_filter($_POST['features_value']));
		
		$insert['photo'] = $_POST['photo'];	
		
		$insert = apply_filters('rental_manager/admin/save', $insert);
		
		
		if($_POST['id'] != ""){
		$where['id'] =$_POST['id'] ;
	    $wpdb->update(  "".$wpdb->prefix . "sp_rm_rentals", $insert , $where );	
		$insert_id = $_POST['id'];
		}else{
		foreach($insert as $key=>$value){ if(is_null($value)){ unset($insert[$key]); } }

		$wpdb->insert( "".$wpdb->prefix . "sp_rm_rentals",$insert );
		$insert_id = $wpdb->insert_id;
		}
		
		if(is_array($_POST['gallery']) && count($_POST['gallery']) >0){
			
					update_option('sp_rm_images_'.$insert_id.'', $_POST['gallery']);
		}
		do_action('rental_manager/admin/after_save', $insert_id);
		
	do_action('wp_rm_development_action',$_POST['id']);
	sp_rm_redirect('admin.php?page=sp-rm-listings');
	
}



if($_GET['id'] != ""){
	
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals where id = '".$wpdb->escape($_GET['id'])."'", ARRAY_A);
	
	$dev = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_developments  where id = '".$wpdb->escape($r[0]['did'])."'", ARRAY_A);	
		
}


$devs = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_developments order by name", ARRAY_A);	

if($r[0]['id'] != "" && $_GET['pics'] == 1){
	
		echo '<div style="padding:10px;"><a href="admin.php?page=sp-rm-listings&function=manage-listing&id='.$_GET['id'].'" class="button">&laquo; Back to listing</a></div>';
	echo sp_rm_admin_multiple_images($r[0]['id']);


}else{

echo '<div style="padding:10px;"><a href="admin.php?page=sp-rm-listings&function=manage-listing&id='.$_GET['id'].'&pics=1" class="button">Add more images</a></div>';

?>

<script>
  jQuery( function($) {
    $( ".sprm-sortable" ).sortable();
  
  } );
  </script>

<?php

echo  ''. $portfolio_list_dev .'



<form action="admin.php?page=sp-rm-listings&function=manage-listing" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="'.$r[0]['id'].'">
  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	
	<tbody>
	<tr>
	<td style="width:150px">'.__("Name","sp-rm").':</td>
	<td><input type="text" name="dev-name" value="'.$r[0]['name'].'"></td>
	</tr>
		<tr>
	<td>'.__("Development","sp-rm").':</td>
	<td><select name="did"><option value="'.$dev[0]['id'].'" selected="selected">'.$dev[0]['name'].'</option>
	
	
	';
	
	for($i=0; $i<count($devs); $i++){
		echo  '<option value="'.$devs[$i]['id'].'">'.$devs[$i]['name'].'</option>';
	}
	
	echo '</select></td>
	</tr>
	
	<tr>
		<td>'.__("Address","sp-rm").':</td>
	<td><input type="text" name="address" value="'.$r[0]['address'].'"></td>
	</tr><tr>
		<td>'.__("Unit","sp-rm").':</td>
	<td><input type="text" name="unit" value="'.$r[0]['unit'].'"></td>
	</tr><tr>
		<td>'.__("City","sp-rm").':</td>
	<td><input type="text" name="city" value="'.$r[0]['city'].'"></td>
	</tr><tr>
		<td>'.__("State","sp-rm").':</td>
	<td><input type="text" name="state" value="'.$r[0]['state'].'"></td>
	</tr><tr>
		<td>'.__("Price","sp-rm").':</td>
	<td><input type="text" name="price" value="'.$r[0]['price'].'"></td>
	</tr>
	    <tr>
	<td>'.__("Featured Image","sp-rm").'</td>
	<td> '. rm_admin_uploader('photo',$r,$r[0]['photo']).'


	
</td>
<tr>
		<td>'.__("Description","sp-rm").':</td>
	<td>';
	
	wp_editor( stripslashes($r[0]['description']),"description");
echo '</td>
	</tr>';
	
	$features_content .='<tr>
		<td>'.__("Features","sp-rm").':</td>
	<td>';
	
	$features = unserialize($r[0]['features']);
	$features_values = unserialize($r[0]['features_values']);
	
	
	$features_content.= '<div id="sp_rm_feature_main" class="sp_rm_feature">Feature Name <input type="text" name="features[]" value="'.stripslashes($features[0]).'"> Feature Value <input type="text" name="features_value[]" value="'.stripslashes($features_values[0]).'"> <a href="javascript:sp_rm_add_feature();"><img src="../wp-content/plugins/sp-rental-manager/images/add.png"></a></div><div class="sprm-sortable">';
	$i= 1;
	if($features[0] != ""){
	
	
	foreach( $features as $key => $value){
	
		
		$features_content.= '<div id="sp_rm_feature_main" class="sp_rm_feature ui-state-default">Feature Name <input type="text" name="features[]" value="'.stripslashes($features[$i]).'"> Feature Value <input type="text" name="features_value[]" value="'.stripslashes($features_values[$i]).'"></div>';
			$i++;
	}
	}
	$features_content.= '</div><div id="sp_rm_feature_end"></div>
	</td>
	</tr>';
	$features_content= apply_filters('rental_manager/admin/features', $features_content,$r[0]);
	echo $features_content;
	do_action('wp_rm_development_form',$r);
  echo '<tr>
  
	<td></td>
	<td><input type="submit" name="save" value="'.__("Save","sp-rm").'"></td>
	</tr>
	</tbody>
</table>
</form>
<p><br></p>


';	

if($_GET['id'] != ""){
	
	  if(class_exists('sprm_FormBuilderPremium')){
		  
		  $sprm_FormBuilderPremium = new sprm_FormBuilderPremium;
		echo $sprm_FormBuilderPremium->ApplicationView($r[0]['id']);  
	  }else{
		echo  sp_rm_show_applications($r[0]['id']);  
	  }
	
	
	
	
}

}
	return $content;
	}
	function view(){
		
	
				global $wpdb;
		
		
		
		if($_GET['function'] == 'delete-development'){
				
			$wpdb->query("DELETE FROM ".$wpdb->prefix . "sp_rm_developments WHERE id = ".$wpdb->escape($_GET['id'])."	");	
			
		}
		
		if($_GET['function'] == 'delete-listing'){
				
			$wpdb->query("DELETE FROM ".$wpdb->prefix . "sp_rm_rentals WHERE id = ".$wpdb->escape($_GET['id'])."	");	
			
		}
		
		if($_GET['function'] == 'publish'){
				$insert['status'] = 	$_GET['publish'];	
	
	
	
		$where['id'] =$_GET['id'] ;
	    $wpdb->update(  "".$wpdb->prefix . "sp_rm_rentals", $insert , $where );	
			}
		
		
		echo  '	<h1>'.__("Listings","sp-rm").'</h1> '. SpRmNavigationMenu().'';
		
		if($_GET['function'] == 'manage-listing'){		
			
			echo  $this->edit();		
			
		}else{
		
		
				
		$r = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_rentals order by name", ARRAY_A);	
			
			

			
				echo '
	<div class="sprm-admin-submenu">
	 <a class="button" href="admin.php?page=sp-rm-listings&function=manage-listing">'.__("Add Listing","sp-rm").'</a>
	 </div>
	  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>

<th width="50">'.__("ID","sp-rm").'</th>
<th >'.__("Applications","sp-rm").'</th>
<th>'.__("Name","sp-rm").'</th>
<th>'.__("Address","sp-rm").'</th>
<th width="450">'.__("Action","sp-rm").'</th>
</tr>
	</thead><tbody>';	
	
	
	for($i=0; $i<count($r); $i++){
		
			$ree = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_applications where property = ".$r[$i]['id']."", ARRAY_A);		
		if($r[$i]['status'] == 0){
	$publish = '<a class="button" style="margin-left:20px" href="admin.php?page=sp-rm-listings&function=publish&publish=1&id='.$r[$i]['id'].'">'.__("Unpublish","sp-rm").'</a>';	
		$bg= '';
	}else{
		$publish = '<a class="button" style="margin-left:20px" href="admin.php?page=sp-rm-listings&function=publish&publish=0&id='.$r[$i]['id'].'" >'.__("Publish","sp-rm").'</a>';		
	$bg= ' style="background-color:#ffe2e2" ';
	}
		echo '<tr '.$bg.'>
		<td>'.$r[$i]['id'].'</td>';
		
		  if(class_exists('sprm_FormBuilderPremium')){
			$sprm_FormBuilderPremium = new sprm_FormBuilderPremium;
			 echo '<td>'.apply_filters('sp_rm/admin/listings/view_entries/counts',$sprm_FormBuilderPremium->applicationCount($r[$i]['id']),$r[$i]).'</td>'; 
		  }else{
			  echo '<td>'.apply_filters('sp_rm/admin/listings/view_entries/counts',count($ree),$r[$i]).'</td>';
		  }
	
	
	
		echo '
		<td>'.$r[$i]['name'].'</td>
		<td>'.$r[$i]['address'].'</td>
		<td><a  class="button" href="admin.php?page=sp-rm-listings&function=delete-listing&id='.$r[$i]['id'].'">'.__("Delete","sp-rm").'</a>  
	<a class="button" style="margin-left:20px" href="admin.php?page=sp-rm-listings&function=manage-listing&id='.$r[$i]['id'].'">'.__("View","sp-rm").'</a> 
		'.apply_filters("sp_rm/admin/listings/view_entries/button",'<a class="button" style="margin-left:20px" href="'.admin_url('admin-ajax.php?action=sp_rm_download_applications&id='.$r[$i]['id'].'').'" target="_blank">'.__("Download Applications","sp-rm").'</a>',$r[$i]).'
	'.	$publish .'
	</td>
		</tr>';
		
				}
				
			echo  '</tbody></table>';
						
		}
		
		
	}
}