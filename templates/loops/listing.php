<?php
global $data,$wpdb;
?>

	<div class="sp_rm_listings">
							<?php do_action('sprm/loop/listing/top'); ?>
							<div class="sp_rm_listings_title"><a href="<?php echo sprm_page($data['result']['id']); ?>"><?php echo $data['result']['name']; ?></a></div>
                            <div class="sp_rm_listings_image" style="background-image:url(<?php echo $data['image']; ?>)"><a href="<?php echo sprm_page($data['result']['id']); ?>"><?php do_action('sprm/loop/listing/below_image'); ?></a></div>
							
							<div class="sp_rm_listings_inner">
                            
							<div class="sp_rm_listings_address"><a href="<?php echo sprm_page($data['result']['id']); ?>"><?php echo ''.$data['result']['address'].''.$data['result']['address2'].'  '.$data['result']['unit'].', '.$data['result']['city'].' '.$data['result']['state'].''; ?></a></div>
								<div class="sp_rm_listings_price"><?php echo ''.sp_rmc_format_price($data['result']['price'],sp_rm_get_meta('price_format',$data['result']['id'])).''; ?></div>
							<div class="sp_rm_listings_buttons">
							<a class="button"  href="<?php echo sprm_page($data['result']['id']); ?>"><?php _e("View","sp-rm") ?></a>
							<a class="button" style="margin-left:10px" href="<?php echo rm_get_application_link($data['result']['id']); ?>"><?php _e("Apply","sp-rm"); ?></a>
						
							</div>
                            </div>
							<?php do_action('sprm/loop/listing/bottom'); ?>
							<div style="clear:both"></div>
	</div>