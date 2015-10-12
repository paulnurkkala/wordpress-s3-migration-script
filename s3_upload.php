<?php

	/*
		author: @paulnurkkala
		description: This file will take all 

		//sample example metafield for this called amazonS3_info
		array (
		  'bucket' => 'crosswinds-prod',
		  'key' => 'wp-content/uploads/2015/10/08164415/fpv-range.png',
		  'region' => '',
		)
	*/


	//load up wordpress API so that all wordpress functionality is available in this script.
	require( '../wp-load.php' );

	$metafield_name = 'amazonS3_info'; 
	$s3_base_http   = "https://";
	$s3_base_url    = '.s3.amazonaws.com'; 
	$bucket_name    = '';
	$bucket_url     = '';
	$region         = '';
	$debug_mode     = true; 
	$siteurl        = site_url();
	$siteurl        = $siteurl . '/';


	print $siteurl; 
	print PHP_EOL;

	//the first thing we need to do is get the bucket name from the user 
	echo "Type the name of the S3 Bucket you're going to be using: ";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	$bucket_name = trim($line); 

	if($debug_mode){
		//default fallback for testing 
		if( ! $bucket_name ){
			$bucket_name = "pnurkkala-test";
		}
	}


	$bucket_url = $s3_base_http . $bucket_name . $s3_base_url;

	print "Using Bucket Name: " . $bucket_name; 
	print PHP_EOL;

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


	foreach ($images as $image) {
		$proper_s3_url = str_replace( $siteurl , '', $image->guid );

		/*
		print "GUID: ";
		print $image->guid;
		print PHP_EOL;

		print "Siteurl: ";
		print $siteurl;
		print PHP_EOL;

		print "Fixed URL: ";
		print $proper_s3_url;
		print PHP_EOL;
		*/


		$metadata = array(
			'bucket' => $bucket_name,
			'key'    => $proper_s3_url,
			'region' => $region
		);

		//$serialized_metadata = serialize($metadata);
		//print $serialized_metadata; 
		//print PHP_EOL;

		add_post_meta($image->ID, $metafield_name, $metadata);
	}

?>
