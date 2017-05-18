<?php
//include the S3 class              
require_once('S3.php');
 
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJOY2LSITR6L6KNNQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
 
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
 
//we'll continue our script from here in step 4!
 
?>