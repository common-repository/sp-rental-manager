<?php

if(!function_exists('sp_rm_search_ajax')){
	
	function sp_rm_search_ajax(){
		
		global $wpdb;

		
		echo sp_rm_get_listings(false,$_POST['search']);
		die(0);
	}
		add_action( 'wp_ajax_sp_rm_search_ajax','sp_rm_search_ajax' );
	add_action( 'wp_ajax_nopriv_sp_rm_search_ajax','sp_rm_search_ajax' );
}

















if(!function_exists('sp_rmc_get_search_dropdown')){
function sp_rmc_get_search_dropdown($meta_key,$atts){
	global $wpdb;
	
		
		if($atts['development'] != ''){
			
		$dev_ids = sp_rm_c_get_development_properties($atts['development']);
		if($dev_ids != ''){
		$and .= ' AND post_id IN ('.$dev_ids.') ';	
		}
		}else{
			
		$and = '';	
		}
	
		$query = "SELECT  DISTINCT(meta_value), meta_key FROM ".$wpdb->prefix . "sp_rm_rentals_features where meta_key = '".$meta_key."' ".$and." order by meta_value";
		
		$r = $wpdb->get_results($query, ARRAY_A);
	if($r != false){
		$c.=' <select name="search['.$meta_key.']" id="search-'.sanitize_title($meta_key).'" class="search-select-box">
			<option value="" selected="selected">'.$meta_key.'</option>
			 ';
		
		for($i=0; $i<count($r ); $i++){
			if($r[$i]['meta_value']!= ''){
			$c .= '<option value="'.$r[$i]['meta_value'].'">'.$r[$i]['meta_value'].'</option>';
			}
		}
		$c .= '</select> ';
		
		return $c;
	}else{
	return false;	
	}
}

}
if(!function_exists('sp_rmc_get_search_price_dropdown')){
function sp_rmc_get_search_price_dropdown($atts){
	global $wpdb;
		$dev_ids = array();
		
			if($atts['development'] != ''){
			
		$dev_ids = explode(",",sp_rm_c_get_development_properties($atts['development']));
	
		}
		
		$r = $wpdb->get_results("SELECT DISTINCT(price),id  FROM ".$wpdb->prefix . "sp_rm_rentals order by price", ARRAY_A);
		if($r != false){
		$c.=' <select name="search[search-price]" id="search-price" class="search-select-box">
			<option value="" selected="selected">Search by Price</option>
			 ';
		
		for($i=0; $i<count($r ); $i++){
		
		if(count($dev_ids) > 0){
		if(in_array($r[$i]['id'],	$dev_ids )){
		$prices[]  =  $r[$i]['price'];
		}		
		}else{
		$prices[]  =  $r[$i]['price'];	
		}
		}
		$prices = array_unique($prices);
		sort($prices, SORT_NUMERIC);
			foreach($prices as $value){
			$c .= '<option value="'.$value.'">'.$value.'</option>';
			}
		
		$c .= '</select> ';
		
		return $c;
	}else{
	return false;	
	}
}

}
if(!function_exists('sp_rm_search_engine')){
	
	
	
	
	function sp_rm_search_engine($content,$args){
		global $wpdb;
				

			$atts = shortcode_atts( array(
		'development' => '',
		'sort' => 'DESC',
		'orderby' => 'id'
	), $args, 'sp_rm_show_available_listings' );	
	
	
				
		if(get_query_var('listing_id') == '')
		
		$search_fields = get_option('sp_rm_meta_search_fields', 'search-name,search-price');
		
		if($search_fields  != ''){
		$search_arr = explode(",",$search_fields);	
		}
		if(is_array($search_arr)){
		if(count($search_arr)>0){
		$content .= '
		
		<script type="text/javascript">
		
		function sp_rm_search_init(){
			
			
			
				jQuery(".sp_rm_listings_content").html("<div style=\"margin:50px;text-align:center\"><img src=\"'.SP_CDM_RM_DIR_URI.'/images/spinner.gif\"></div>");
			var data = jQuery(".rm_search_form").serialize();
	
	jQuery.post("'.admin_url( 'admin-ajax.php' ).'", data, function(response) {
		jQuery(".sp_rm_listings_content").html(response);
	});
				
		}
		
		jQuery(document).ready(function($) {
			
			
			
			jQuery(".rm_search_input, .search-select-box").bind("keyup change", function() { 	
			
			sp_rm_search_init();
		
			
			});
			
			
			
		jQuery( "#slider-range" ).slider({
      range: true,
      min: 0,
      max: 500,
      values: [ 75, 300 ],
      slide: function( event, ui ) {
        $( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      },
	  stop: function(event,ui){
			 sp_rm_search_init();  
	  }
	  
    });
    $( "#amount" ).val( "$" + $( "#slider-range" ).slider( "values", 0 ) +
      " - $" + $( "#slider-range" ).slider( "values", 1 ) );
			
			
		
	

		});
		</script>';
		
		$content = apply_filters('sprm/listings/loop/above_search',$content,$atts);
		$content .='<form class="rm_search_form">
		<input type="hidden" name="search[sort]" value="'.$atts['sort'].'">
		<input type="hidden" name="search[orderby]" value="'.$atts['orderby'].'">
		<input type="hidden" name="action" value="sp_rm_search_ajax">';
	
		if($atts['development'] != ''){
		$content .='<input type="hidden" name="search[search-development]" value="'.$atts['development'].'">';	
		}
		$content .='<h2>'.__("Search","sp-rm").'</h2>
				';
				
				if(in_array('search-name', $search_arr)){
				$content .='	<div class="search-row"><input placeholder="Search by name..." class="rm_search_input" type="text" name="search[search-title]" value=""></div>';
				unset($search_arr['search-name']);
				}
			
			$content = apply_filters('sp_rm/listings/search/under_name',$content,$atts);
			
				$content .='
				<div class="search-row">';
				
				if(in_array('search-price', $search_arr)){
				
				$prices = $wpdb->get_results("SELECT DISTINCT(price),id  FROM ".$wpdb->prefix . "sp_rm_rentals order by price", ARRAY_A);
				#$low_price = $wpdb->get_results("SELECT DISTINCT(price),id  FROM ".$wpdb->prefix . "sp_rm_rentals order by price ASC limit 1", ARRAY_A);
				
				$high_price = false;
				$low_price = false;
				$list_prices = array();
				if($prices){
				
						foreach($prices as $price){
						$list_prices[]= $price['price'];	
						}
			sort($list_prices);
				$low_price = $list_prices[0];
				
				$high_price = end($list_prices);
				}
				
				if($high_price != false && $low_price != false){
				$content .= '
		
		<script type="text/javascript">
		
		
		
		jQuery(document).ready(function($) {
			
			
			
		jQuery( "#slider-range" ).slider({
      range: true,
      min: '.$low_price.',
      max: '.$high_price.',
      values: [ '.$low_price.', '.$high_price.' ],
      slide: function( event, ui ) {
		  
		  jQuery("#amount_to").val(ui.values[ 1 ]);
		    jQuery("#amount_from").val(ui.values[ 0 ]);
       jQuery( ".sp-rm-price-range" ).html( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
      },
	
  stop: function( event, ui ) {
	  sp_rm_search_init();
	 
}
    });
 jQuery( ".sp-rm-price-range" ).html( "$" +  '.$low_price.' + " - $" + '.$high_price.' );
	 jQuery("#amount_to").val( '.$high_price.');
		    jQuery("#amount_from").val('.$low_price.');		
			
		
	

		});
		</script>';
		
				
				
				
				$content .='<label for="amount">Price range: <span class="sp-rm-price-range"></span></label>
  <input type="hidden" id="amount_to" name="search[amount_to]">
  <input type="hidden" id="amount_from"  name="search[amount_from]">
  <div id="slider-range"></div>
  ';
				}
				unset($search_arr['search-price']);
				}
			$content = apply_filters('sp_rm/listings/search/under_price',$content,$atts);	
				
				if(count($search_arr) > 0){
					foreach($search_arr as $search_field){
						
						$content .=sp_rmc_get_search_dropdown($search_field,$atts);
						
					}
					
				}
				
				$content .='</div>';
				
			
		
		
		
		$content .='</form>';
		$content = apply_filters('sprm/listings/loop/below_search',$content,$atts);
		}
		}
		return $content;
		
	}
	add_filter('rental_manager/listings/top', 'sp_rm_search_engine', 10,2);

}

function sp_rm_has_feature($post_id,$feature, $value){
	
	global $wpdb;	
	$r_features_search = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals_features where post_id = '".$post_id."' AND meta_key = '".$feature."'  AND meta_value = '".$value."' ", ARRAY_A);			
	
	
	if($r_features_search == false){
		
		return false;
	}else{
		
		return true;
	}
	
	
}
function sp_rm_get_listings($search = array(),$atts ){
	global $wpdb,$data;
	$search_main = '';
	$search_query = '';
	$q = array();
	$search = $_POST['search'];
	
	$dev = $search['development'];
	$search['search-development'] = $dev;
	

	if(is_array($search)){
	
		if($search['search-title'] != ''){
			
		$search_main.= ' AND name LIKE "%'.$search['search-title'].'%" ';	
		unset($search['search-title'] );
		}
		if($search['search-development'] != ''){
			
		$dev = $search['search-development'];
		unset($search['search-development'] );
		}
		if(strlen($search['zip']) <>5 || $search['zip_distance'] == ''){
			unset($search['zip']);
			unset($search['zip_distance']);
		}
		if($search['amount_to'] != '' && $search['amount_from'] != ''){
		$search_from = $search['amount_from'] - get_option('sp_rm_premium_price_variance', '100');	
		$search_to = $search['amount_to'] + get_option('sp_rm_premium_price_variance', '100');
		$search_main.= ' AND price BETWEEN "'.$search_from.'" AND "'.$search_to.'"';	
		unset($search['search-price'] );
		}
		
		
	}
	
	if($atts['development'] != ''){
		
		$search_main.= ' AND did= "'.$atts['development'].'" ';	
	}
	

if($search['sort'] !=  ''){
			
		$orderby = ' ORDER BY '.$search['orderby'].' '.$search['sort'].'';	
		}else{
	
		$orderby='';
		if($atts['orderby'] != ''){
		$orderby  = ' ORDER BY '.$atts['orderby'].'';	
		}
		if($atts['sort'] != ''){
		$orderby  .= ' '.$atts['sort'].'';	
		}
		
		if($orderby == ''){
		$orderby = ' order by did,name ';	
		}
		}
		
	$search_main = apply_filters('sprm/main_query/search',$search_main,$search,$atts);
	$orderby = apply_filters('sprm/main_query/search/order_by',$orderby,$search,$atts);
	$query = "SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals  where  status = 0 ".$search_main ." ".$orderby." ";

	$results = $wpdb->get_results($query.'', ARRAY_A);
	
		$pagination = new SP_RM_Pagination();
		if (isset($_GET['pagenum'])){   $page = (int) @$_GET['pagenum'];}else{ $page = 1; }
	
			$pagination->setLink("?pagenum=%s");
			$pagination->setPage($page);
			$pagination->setSize(get_option('sp_rm_display_num',15));
		
			$pagination->setTotalRecords(count($results));
	
	

	
	
	

	unset($search['sort']);
	unset($search['amount_to']);
	unset($search['amount_from']);
	unset($search['orderby']);

	$r = $wpdb->get_results($query.$pagination->getLimitSql(), ARRAY_A);				
	$r_full = $wpdb->get_results($query, ARRAY_A);	
	$r = apply_filters('sprm/main_query/filter_results',$r,$search_main,$search,$atts);
	
 $search= @array_filter($search);   

	if(count($search)>0){
		$total_features = count($search);
		
		
	
	for($i=0; $i<count($r); $i++){
			
		$count = 0;
		foreach($search as $key=>$value){
		if($key != 'search-price' && $key != 'search-title'){
			if($value != ''){
				if(sp_rm_has_feature($r[$i]['id'],$key, $value) == true){
					
					
					$count += 1;
				}
			}
		}	
		}
		
		if($count == $total_features){
		$q[$i] = $r[$i];	
		unset($count);	
		}
	
	}
		unset($r);
		
		$r = array_values($q);
		

	}
		
		
	
		if(count($r)== 0){
			$content .= '<div class="sprm_error">'.__("No Rentals Found!","sp-rm").'</div>';
			
		}else{
		
	$listings_template .= '<div id="sp_rm_listings">';
	$listings_template = apply_filters('sprm/listings/above_loop',$listings_template, $r_full,$atts);
	for($i=0; $i<count($r); $i++){
		
		
		
			$data = array();
			if($r[$i]['photo'] == ""){
			$data['image'] = ''.get_bloginfo("wpurl").'/wp-content/plugins/sp-rental-manager/images/no_house.jpg';
			}else{
			$data['image'] = sp_rm_thumbnail($r[$i]['photo'],get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h'));	
			}
			$data['result'] = $r[$i];
			$data['search'] = $search;
			$listings_template .= cdm_rm_get_template('loops/listing.php' ,true);
		
		
				}
				
	
				
		}
		$listings_template = apply_filters('sprm/listings/below_loop',$listings_template,$r_full,$atts);
		$listings_template .='</div>';
		$listings_template = apply_filters('sp_rm_listings_template',$listings_template, $r,$atts);
		
		
		$content .=$listings_template;
		$content .=' <div style="clear:both"></div><div class="sp-rm-pagination">';
		$content .= $pagination->create_links();
        $content .= '</div>';
		
		
		return $content;
	
}
function sp_rm_check_permalinks(){
	
	
	global $wpdb;

	
	 if ( get_option('permalink_structure') == '' ) { 
	 
	 
	
	 return '&page_id='.$_GET['page_id'].'';
	 
	  } 
	
	
}

function sp_rm_show_available_listings( $args){
	
	global $wpdb,$data;
	
	$atts = shortcode_atts( array(
		'development' => '',
		'sort' => 'DESC',
		'orderby' => 'id'
	), $args, 'sp_rm_show_available_listings' );
	
	$content = '';
	$content .= apply_filters('rental_manager/listings/top', $content,$atts);
	
	$content .='<div id="rental_listings">';
	
	
	if(get_query_var('listing_id') != ""){
		
	$query = $wpdb->prepare("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals where id = %d order by ".$atts['orderby']." ".$atts['sort']."",get_query_var('listing_id'));
	
	$r = $wpdb->get_results($query, ARRAY_A);			
	
		if($r[0]['unit'] != ''){
		$unit = '#'.$r[0]['unit'].'';	
		}
	
	$data = array();
	if($r[$i]['photo'] == ""){
			$data['image'] = ''.get_bloginfo("wpurl").'/wp-content/plugins/sp-rental-manager/images/no_house.jpg';
			}else{
			$data['image'] = sp_rm_thumbnail($r[0]['photo'],get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h'));	
	}
	$data['result'] = $r[0];
	
	
	$gallery = array();
	$r_images_old = $wpdb->get_results("SELECT *  FROM ".$wpdb->prefix . "options where option_name LIKE  'sp_rm_images_".$r[0]['id']."_%'", ARRAY_A);
	if($r_images_old  != false){
		foreach($r_images_old  as $old_images){
			
			$gallery[] = $old_images['option_value'];	
		}
	}
	$r_images_new = get_option('sp_rm_images_'.$r[0]['id'].'');
	if($r_images_new != ''){
		foreach($r_images_new as $new_images){
			
		$gallery[] = $new_images;	
		}
	}
	
	$data['gallery'] = $gallery;
	
	$content  .= cdm_rm_get_template('single/listing.php' ,true);	
		




	
	$body_content = apply_filters('rental_manager/shortcode/body_content',$body_content, $r[0]);
	$content .= $body_content;
	
	
	
	
	
	
		
		
	}else{
	
	$dev = $atts;
	
	
	$content .='<div class="sp_rm_listings_content">';
	$content .=sp_rm_get_listings($dev,$atts );
	$content .='</div>';
	
	}
	$content .='</div>';
	return $content;
}

add_shortcode( 'rental_listing', 'sp_rm_show_available_listings' );
