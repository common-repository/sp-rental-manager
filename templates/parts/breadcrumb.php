<?php
global $wpdb,$data;
?>

<div class="sp_rp_bread">
	<span><a href="<?php echo sprm_page(false); ?>">Back to Listings</a></span> &raquo; <span><?php echo ''.$data['result']['address'].' '.$unit .' '.$data['result']['city'].' '.$data['result']['state'].''; ?></span>
</div>