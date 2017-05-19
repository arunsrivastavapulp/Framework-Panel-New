<?php
//include the S3 class              
require_once('S3.php');
 
//AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIFSCRQOFUXP4HKCA');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
 
//instantiate the class
$s3 = new S3(awsAccessKey, awsSecretKey);
 
//we'll continue our script from here in step 4!
 
?>