<?php
$IPaddress="182.74.47.177";
 $json       = file_get_contents("http://ipinfo.io/{$IPaddress}");
        $details    = json_decode($json);
        print_r($details);
?>