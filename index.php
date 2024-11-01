<?php
/*
Plugin Name: SP Rental Manager
Plugin URI: http://www.smartypantsplugins.com
Description: A wordpress plugin to manage rental properties
Author: SmartyPants
Version: 1.5.3
Author URI: http://www.smartypantsplugins.com
*/

load_plugin_textdomain( 'sp-rm', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );



add_action('plugins_loaded', 'sp_rental_manager_init');



$rm_preu_ver = 'Free version';

function sp_rental_manager_init(){
include 'includes/thumbs.php';

if(!function_exists('_recaptcha_qsencode')){
include 'includes/recaptcha.php';
}

include 'includes/pagination.php';
include 'includes/functions.php';
include 'includes/seo.php';
include 'admin/addons.php';
include 'admin/options.php';
include 'admin/listings.php';
include 'admin/developments-new.php';
include 'admin/developments.php';
include 'user/customizer.php';
include 'user/applications.php';
include 'user/shortcodes.php';
include 'admin/applications.php';

include 'admin/developments-premium.php';
add_action('admin_menu', 'sp_rm_menu');

global $sp_rm_version;
$sp_rm_version = "1.5.1";
define('SALT', '08934587973238746238746237'); 
define('SP_CDM_RM_TEMPLATE_DIR', plugin_dir_path(__FILE__) . 'templates/');
define('SP_CDM_RM_PLUGIN_DIR', plugin_dir_path(__FILE__) . 'templates/');
define('SP_CDM_RM_DIR_URI', plugins_url('',__FILE__));
define('SP_RM_STORE_URL','https://smartyrentalmanager.com');


function sp_rm_listings_install() {
   global $wpdb;
   global $sp_rm_listings,$sp_rm_version  ;

      
if(get_option('sp_rm_init_install') == ''){
$sql = "

CREATE TABLE ".$wpdb->prefix . "sp_rm_applications (
  id int(11) NOT NULL AUTO_INCREMENT,
  property varchar(255) NOT NULL,
  date date NOT NULL,
  address1 text NOT NULL,
  address2 text NOT NULL,
  employ1 text NOT NULL,
  employ2 text NOT NULL,
  name varchar(255) NOT NULL,
  s blob NOT NULL,
  dob varchar(255) NOT NULL,
  phone varchar(255) NOT NULL,
  cell varchar(255) NOT NULL,
  children text NOT NULL,
  ref1 text NOT NULL,
  ref2 text NOT NULL,
  rel1 text NOT NULL,
  sign varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ;


CREATE TABLE  ".$wpdb->prefix . "sp_rm_developments (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  PRIMARY KEY (id)
) ;


CREATE TABLE ".$wpdb->prefix . "sp_rm_rentals (
  id int(11) NOT NULL AUTO_INCREMENT,
  name varchar(255) NOT NULL,
  unit varchar(255) NOT NULL,
  address varchar(255) NOT NULL,
  city varchar(255) NOT NULL,
  state varchar(255) NOT NULL,
  price int(11) NOT NULL,
  description text NOT NULL,
  status int(1) NOT NULL,
  date date NOT NULL,
  photo varchar(255) NOT NULL,
  did varchar(255) NOT NULL,
  features text NOT NULL,
  features_values text NOT NULL,
  PRIMARY KEY (id)
)  ;

CREATE TABLE ".$wpdb->prefix . "sp_rm_rentals_features (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) DEFAULT NULL,
  `meta_value` longtext,
  PRIMARY KEY (`id`)
) ;

";




   require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
   dbDelta($sql);
 
   add_option("sp_rm_version", $sp_rm_version );
   update_option('sp_rm_init_install', 1);
}
}


add_action('init','sp_rm_upgrades');



if(get_option('sp_rm_version') != ''){
sp_rm_listings_install();	
}




function sp_rm_upgrades(){
	global $wpdb;
   global  $sp_rm_version ;
   
   $update_version = false;
   
  
   
   
   if(get_option('sp_rm_version') < '1.2.8' or  $sp_rm_version or $_GET['force_upgrade'] == 1){
  
	#$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_rm_rentals` ADD `features` text NOT NULL;'); 
	#$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_rm_rentals` ADD `features_values` text NOT NULL;');	
	$update_version = true;
   }
   
   if($update_version == true){
	
	update_option("sp_rm_version", $sp_rm_version );  
	   
   }
   
   
   if(get_option('sp_rm_convert_column')!=2){
		$wpdb->query('ALTER TABLE `'.$wpdb->prefix . 'sp_rm_rentals` MODIFY price INT(11);');    
		
		update_option('sp_rm_convert_column', 2);
	}
}

register_activation_hook(__FILE__,'sp_rm_listings_install');


if(get_option('sp_rm_list_thumb_size_w') == ""){
	
		update_option( 'sp_rm_list_thumb_size_w',500 ); 
		update_option( 'sp_rm_list_thumb_size_h',500 ); 
		update_option( 'sp_rm_thumb_size_w',150 ); 
		update_option( 'sp_rm_thumb_size_h',150 ); 	
	
}
//load javascript
function sp_rm_init() {
	
	  wp_enqueue_script('jquery');
	
	
	if (!is_admin()) {
		  wp_enqueue_script('sp_rm_validation', ''.get_bloginfo('wpurl').'/wp-content/plugins/sp-rental-manager/js/validation.js');
		   wp_enqueue_script('sp_rm_scripts', ''.get_bloginfo('wpurl').'/wp-content/plugins/sp-rental-manager/js/scripts.js');
		    wp_enqueue_script('jquery-ui-core');
	  
	  wp_enqueue_script('jquery-ui-widget');
	 wp_enqueue_script('jquery-ui-mouse');
	 wp_enqueue_script('jquery-ui-slider'); 
	 
		   
	}else{
	  wp_enqueue_script('sp_rm_js', ''.get_bloginfo('wpurl').'/wp-content/plugins/sp-rental-manager/js/admin.js');
	  wp_enqueue_script('jquery-ui-core');
	  wp_enqueue_script('jquery-ui-slider');
	  wp_enqueue_script('jquery-ui-draggable');
	  wp_enqueue_script('jquery-ui-droppable');
	  wp_enqueue_script('jquery-ui-selectable');
	   wp_enqueue_script('jquery-ui-sortable');
	   wp_enqueue_script('thickbox');
	}
	
	
}
//load css files
function sp_rm_load_css(){

wp_enqueue_style('jquery-ui','//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css');
		wp_enqueue_style('jquery-ui-theme','//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css');

	if (!is_admin()) {

   	wp_enqueue_style('sprm-css', plugins_url('style.css', __FILE__));
	
	
	wp_enqueue_style('font-awesome', plugins_url('css/font-awesome.all.min.css', __FILE__),'1.4.0');	
 	wp_enqueue_style('wpfh-social-share-css', plugins_url('css/jssocials.css', __FILE__),'1.4.0');	
	wp_enqueue_style('wpfh-social-share-css-theme', plugins_url('css/jssocials-theme-flat.css', __FILE__));	
		$localize  = array();			
		$localize['ajax_url'] = admin_url('admin-ajax.php');
		$localize['async_url'] = admin_url('async-upload.php');
	
			$localize['jssocials'] = array('email'=>__('E-mail', 'sp-wpfh'),
											'tweet'=>__('Tweet', 'sp-wpfh'),
											'like'=>__('Like', 'sp-wpfh'),
											'share'=>__('Share', 'sp-wpfh'),
											'pinit'=>__('Pin it', 'sp-wpfh'),
											'telegram'=>__('Telegram', 'sp-wpfh'),
											'whatsapp'=>__('WhatsApp', 'sp-wpfh'),
											);
			
			
			 wp_enqueue_script('wprm-social-shares-js', plugins_url('js/jssocials.min.js', __FILE__), array('jquery'),'1.4.0');	
			 wp_localize_script('wprm-social-shares-js','wprm_object',$localize );	
	
	}else{
		
		
		
wp_enqueue_style('sprm-css', plugins_url('admin.css', __FILE__));
			
			add_thickbox();
	}
}

add_action('wp_enqueue_scripts', 'sp_rm_load_css');	

 add_action( 'admin_enqueue_scripts', 'sp_rm_init' );
  add_action( 'wp_enqueue_scripts', 'sp_rm_init' );

add_action( 'admin_enqueue_scripts', 'sp_rm_load_css' );

function sp_rm_menu() {
	
	
	

	  add_menu_page( 'SpRm', ''.__("Rentals","sp-rm").'',  'manage_options', 'SpRm', 'SpRmOptionsPage');
	  do_action('sprm_admin_menu');
	  
	  
	  add_submenu_page( 'SpRm', ''.__("Applications","sp-rm").'', ''.__("Applications","sp-rm").'', 'manage_options', 'sp-rm-applications', 'sp_rm_applications_admin');
	  do_action('sp_rm_menu');
}

do_action('sprm_load_addons');
}