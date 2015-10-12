<?php
	/*
		author: @paulnurkkala
		description: While the s3_upload script maps the s3 buckets with the local, this does the opposite and un-maps for another attempt at the script 

	*/

	//load up wordpress API so that all wordpress functionality is available in this script.
	require( '../wp-load.php' );

	$metafield_name = 'amazonS3_info'; 

	//build query to get all images 
	$query_images_args = array(
	    'post_type'      => 'attachment', 
	    'post_mime_type' =>'image', 
	    'post_status'    => 'inherit', 
	    'posts_per_page' => -1
	);

	//fetch all images 
	$query_images = new WP_Query( $query_images_args );
	$images       =  $query_images->posts; 


	//loop through all images and delete the wp meta 
	foreach ($images as $image) {
		delete_post_meta($image->ID, $metafield_name);
	}
		s3_remove.php