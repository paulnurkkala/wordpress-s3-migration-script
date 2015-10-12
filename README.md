# WordPress S3 Migration Script
Everything here assumes that you have already installed the plugin, added your S3 bucket, and are successfully able to upload NEW content to the S3 bucket. This is all standard functionality of the application, but if you're having trouble with it or haven't done it yet, [click here](http://premium.wpmudev.org/blog/moving-wordpress-media-to-amazon-s3/). 

 * gather all your static files from your previous site in one wp-content folder 

 * use [AWS CLI](https://aws.amazon.com/cli/) to upload those files to your S3 account

   * You want the structure of the root of your bucket to be root/wp-content/uploads/etc..

   * The command I would run on my local, sending to a bucket called "pnurkkala-test" would be: 

>aws s3 cp uploads/* s3://pnurkkala-test/wp-content/uploads/ --recursive 

 * Once this is complete, you have to make sure that all your files are made "public". To do this, go into Amazon S3, click on your bucket, and then right click on the wp-content folder. Click "make public" and it will recursively apply a setting to all files in the folder that will make them accessable by outside sources. There may be a way to do this with the aws cli as you're uploading it, but I haven't found it. Warning, this can take a long time. 

 * Now that you've got all this going, the next step is to check some settings on the WordPress Offload S3 plugin.

     * Go into the offload S3 plugin settings (it creates an item on the menu)

     * S3 and Cloudfront

     * Scroll to Advanced Options

     * Find "Object Versioning" and set it to "Off" 

     * It's on by default, and it causes some funkiness in the URL that is hard to predict with a script. I believe you can turn it on after you get everything working, but until then it's going to get in the way of the script doing its job.

* now is the time for some real fun. Download and install this script in the root of your wordpress instance

* If you run through vagrant, navigate to that folder via vagrant ssh

* Clone this repository in the root of your wordpress site

* run the script: 

> php s3_upload.php

* It should ask you for the bucket name, which you can give it, assuming that you have access to the bucket.

* This script will go through all media uploads that are currently on your computer and add meta data to each that redirects 
them from the local webserver to s3 for serving.

* If something goes wrong or you accidentally type the wrong bucket name, or want to revert back to serving from your local container, you can run the s3_delete script, which will get rid of all instances of this metadata on your site
