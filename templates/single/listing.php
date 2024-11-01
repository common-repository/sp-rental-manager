<?php
global $data,$wpdb;
?>

<?php  cdm_rm_get_template('parts/breadcrumb.php');	?>
<div class="rm_listing_item">
     <h1><?php echo $data['result']['name']; ?></h1>
     <p><i class="fa fa-dollar-sign"></i> Price: <?php echo ''.sp_rmc_format_price($data['result']['price'],sp_rm_get_meta('price_format',$data['result']['id'])).''; ?></p>
    <div class="rm_listing_main_img">
    <div id="sp_rm_gallery">
        
       
           
           
           <div id="sp_rm_gallery_images">
		<div id="sp_rm_gallery_main_image">
		<img src="<?php echo sp_rm_thumbnail($data['result']['photo'],get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h'));  ?>" class="sp_main_image">
		</div>
		<div id="sp_rm_thumbs">
		
		
		<?php	if(count($data['gallery']) > 0){ ?>
	<span><a href="#" data-href="<?php echo sp_rm_thumbnail($data['result']['photo'],get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h')); ?>" class="sp_rm_change_image"><img src="<?php echo sp_rm_thumbnail($data['result']['photo'],get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h')); ?>"></a></span>
 <?php	foreach($data['gallery'] as $gallery_image){ ?>
		
	<span><a href="#" data-href="<?php echo sp_rm_thumbnail($gallery_image,get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h')); ?>" class="sp_rm_change_image"><img src="<?php echo sp_rm_thumbnail($gallery_image,get_option('sp_rm_list_thumb_size_w'), get_option('sp_rm_list_thumb_size_h')); ?>"></a></span>
			<?php
            }	
			}
		?>
		
		<div style="clear:both"></div></div>
		<div style="clear:both"></div>
		</div>
		
		
        
        
           
            <?php 
			
			do_action('sprm/single/after_image'); ?>
            </div>
    </div>
	<?php if($data['result']['description']){ ?>
    <h3>Listing Information</h3>
	<p><?php echo stripslashes($data['result']['description']); ?></p>
  	<?php  cdm_rm_get_template('parts/social.php');	?>
  
    <?php } ?>
    
    
    <?php 
		
	$features = $wpdb->get_results($wpdb->prepare("SELECT *  FROM  ".$wpdb->prefix . "sp_rm_rentals_features where post_id = %d order by meta_key",$data['result']['id']), ARRAY_A);	
		

	if(count($features)>0){ ?>
    <h3>Details and Features</h3>
	 <div class="rm_listing_features">
    
	
	<?php
		


	

	for($i=0; $i<count($features); $i++){
			
		
		
		echo  '<div class="left-listing-row">
				<div class="left-column-listing"><strong>'.stripslashes($features[$i]['meta_key']).'</strong></div>
				<div class="right-column-listing">'.stripslashes($features[$i]['meta_value']).'</div>
				<div style="clear:both"></div>
				</div>
		';
	
	}
		
	?>
        
     
     </div>
     <?php } ?>
     <?php do_action('sp_rm/listing/above_application',$data); ?>
     <div class="sp_rm_listings_apply">
     <?php
	 if(get_option('sprm_download_application') != ''){
	echo '<a class="button" style="margin-left:20px" href="'.get_option('sprm_download_application').'" target="_blank">'.__("Download Application","sp-rm").'</a>';	
	}else{
	echo '<a class="button" style="margin-left:20px" href="'.rm_get_application_link($data['result']['id']).'">'.__("Submit An Application","sp-rm").'</a>';
	}
	?>
     </div>

<?php do_action('sp_rm/listing/bottom',$data); ?>
	<?php do_action('sp_rm/listing/below',$data); ?>
</div>




