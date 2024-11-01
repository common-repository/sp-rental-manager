<?php


class sprm_seo_base{
	
		function __construct(){
		
	global $wp_rewrite,$wp_query;
		$this->rental_page =  $this->slug();
		
	}


	// called 'my_canonical'
	function my_rel_canonical() {
	  // original code
	
	  if ( !is_singular() )
		return;
	  global $wp_the_query;
	  

	  if ( !$id =  get_query_var('id') or  sp_rm_page_id() != $wp_the_query->queried_object_id  )
		return;
	 
	  // new code - if there is a meta property defined
	  // use that as the canonical url
	  $canonical = sprm_page(get_query_var('listing_title') ); 
	 
	  if( $canonical ) {
		echo "<link rel='canonical' href='$canonical' />\n";
		return;
	  }
	 
	  // original code
	  $link = get_permalink( $id );
	  if ( $page = get_query_var('cpage') )
		$link = get_comments_pagenum_link( $page );
	  echo "<link rel='canonical' href='$link' />\n";
	}
		
	


	function get_vars(){
		
	if ( get_option('permalink_structure') != '' ) { 
			
		$_GET['listing_id'] = get_query_var('listing_id');
		$_GET['listing_name'] = get_query_var('listing_name');
		
		do_action('wpfp_permalinks_get_vars');
				
	 }	
		
		
	}
		function slug_page_id() {
		 global $wpdb;
		 
		 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[rental_listing%' and post_type = 'page' and post_status = 'publish'", ARRAY_A);
							
		if($r[0]['ID'] != ""){

  		 return $r[0]['ID']; 
	
		}else{
		return false;	
		}
		}
	

	function slug() {
		 global $wpdb;
		 
		 $r = $wpdb->get_results("SELECT * FROM  " . $wpdb->prefix . "posts where post_content LIKE   '%[rental_listing%' and post_type = 'page' and post_status = 'publish'", ARRAY_A);
							
		if($r[0]['ID'] != ""){

  		 return $r[0]['post_name']; 
	
		}else{
		return false;	
		}
		}
	

	function search_services_params( $query_v ) {
	$query_v[] = "listing_id";
	$query_v[] = "listing_name";

	return $query_v;
}
function custom_rewrite_rules( $existing_rules ) {
	
	$new_rules = array(
		''.$this->rental_page.'/([^/]+)/([^/]+)/?$' =>
			'index.php?pagename='.$this->rental_page.'&listing_namee=$matches[1]&listing_id=$matches[2]',
	
		
	);
	
	
	$existing_rules = $new_rules + $existing_rules;

	return $existing_rules;
}

function flush_rules(){
	global  $wp_the_query;
	remove_action( 'wp_head', 'rel_canonical' );
	if(get_option('sprm_first_flush_rules') != 1){
		flush_rewrite_rules();
		update_option('sprm_first_flush_rules', '1');
	}
}

		function meta_title_parts( $title, $sep= false ) {
		
		global $paged, $page,$post,$wpdb;
	global $wp_query;
	

	
	$vars = $wp_query->query_vars;
	if(!isset($vars['listing_id'])){
	$vars['listing_id'] = '';	
	}
	if(sp_rm_page_id() == $post->ID && $vars['listing_id'] != ''){
	$r = $wpdb->get_results($wpdb->prepare("SELECT *  FROM ".$wpdb->prefix . "sp_rm_rentals where id = %d", $vars['listing_id'] ), ARRAY_A);	


	 $title['title']      = ''.$r[0]['name'].' '.__('Rental Listing ', 'sp-wpfh').' | '.  $title['title']  .'';	
	

	
	}
	return $title;
		
	}
function add_theme_support_child() {

    add_theme_support( 'title-tag' );

}


}

$sprm_seo_base = new sprm_seo_base;
add_action( 'after_setup_theme',  array($sprm_seo_base ,'add_theme_support_child'), 11 );
add_filter('rewrite_rules_array', array($sprm_seo_base ,'custom_rewrite_rules'));
add_filter('query_vars', array($sprm_seo_base , 'search_services_params'));
add_filter('document_title_parts',  array($sprm_seo_base, 'meta_title_parts'),999,2);
add_action('sprm_permalink_structure', array($sprm_seo_base , 'get_vars'));
add_action('init', array($sprm_seo_base , 'flush_rules'));
#add_filter('sprm_link_url', array($sprm_seo_base , 'permalink'), 10, 3);

add_action( 'wp_head',array($sprm_seo_base , 'my_rel_canonical') );

?>