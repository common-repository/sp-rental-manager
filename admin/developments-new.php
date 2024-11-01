<?php


new sp_rm_admin_developments;


class sp_rm_admin_developments{
	
	
	function __construct(){
		
		add_action('sprm_admin_menu', array($this,'menu'));
		add_filter('sp_rm_menu_item', array($this,'topmenu'));
	}
	
	function menu(){
	
	
	add_submenu_page( 'SpRm', ''.__("Developments","sp-rm").'', ''.__("Developments","sp-rm").'', 'manage_options', 'sp-rm-developments', array($this,'view'));	
		
	}
	function topmenu($menu){
		
		$menu .= ' <a class="button" href="admin.php?page=sp-rm-developments">'.__("Developments","sp-rm").'</a> ';
		return $menu;
	}
	function edit(){
		
		
global $wpdb;






	
if($_POST['save'] != ""){
	
	
	
	
		$insert['name'] = $_POST['dev-name'];

		
		if($_POST['id'] != ""){
		$where['id'] =$_POST['id'] ;
	    $wpdb->update(  "".$wpdb->prefix . "sp_rm_developments", $insert , $where );	
		}else{
	foreach($insert as $key=>$value){ if(is_null($value)){ unset($insert[$key]); } }
		$wpdb->insert( "".$wpdb->prefix . "sp_rm_developments",$insert );
		}
	
	sp_rm_redirect('admin.php?page=sp-rm-developments');
	
}



if($_GET['id'] != ""){
	
	$r = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_developments where id = '".$wpdb->escape($_GET['id'])."'", ARRAY_A);		
}

$content .= ''. $portfolio_list_dev .'
<form action="admin.php?page=sp-rm-developments&function=manage-development" method="post">
<input type="hidden" name="id" value="'.$r[0]['id'].'">
  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	
	<tbody>
	<tr>
	<td>'.__("Name","sp-rm").':</td>
	<td><input type="text" name="dev-name" value="'.$r[0]['name'].'"></td>
	</tr>
		<tr>
	<td></td>
	<td><input type="submit" name="save" value="Save"></td>
	</tr>
	</tbody>

</form>




';	
echo $content;

	}
	function view(){
		
	
				global $wpdb;
		
		echo  '	<h1>'.__("Listings","sp-rm").'</h1> '. SpRmNavigationMenu().'';
		
		
		if($_GET['function'] == 'delete-development'){
				
				$wpdb->query("DELETE FROM ".$wpdb->prefix . "sp_rm_developments WHERE id = ".$wpdb->escape($_GET['id'])."	");
		}
		
		
		if($_GET['function'] == 'manage-development'){		
			
			echo  $this->edit();		
			
		}else{
		
			$r = $wpdb->get_results("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_developments order by name", ARRAY_A);	
			
			

		
			
			
			
			
				echo '

		<div class="sprm-admin-submenu"> <a class="button" href="admin.php?page=sp-rm-developments&function=manage-development">'.__("Add Development","sp-rm").'</a></div>
	  <table class="wp-list-table widefat fixed posts" cellspacing="0">
	<thead>
	<tr>

<th width="50">'.__("ID","sp-rm").'</th>
<th>'.__("Name","sp-rm").'</th>
<th>'.__("Action","sp-rm").'</th>
</tr>
	</thead><tbody>';	
	
	
	for($i=0; $i<count($r); $i++){
		
		echo '<tr>
		<td>'.$r[$i]['id'].'</td>
		<td>'.$r[$i]['name'].'</td>
	
		<td><a  class="button" href="admin.php?page=sp-rm-developments&function=delete-development&id='.$r[$i]['id'].'">'.__("Delete","sp-rm").'</a>  
	<a class="button" style="margin-left:20px" href="admin.php?page=sp-rm-developments&function=manage-development&id='.$r[$i]['id'].'">'.__("View","sp-rm").'</a> 

	
	 </td>
		</tr>';
		
				}
				
				echo  '</tbody></table>';
				
	
						
		}
		
		
	}
}