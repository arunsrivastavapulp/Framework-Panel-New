<?php
require_once ('config/db.php');
$db  = new db();
$img = $_POST['oldimage'];

$basicUrl  = $db->siteurl();
$image = str_replace($basicUrl, './', $img);
@unlink($image);

echo 1;