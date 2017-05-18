<?php
function ip_details($IPaddress) 
    {
        $json       = file_get_contents("http://ipinfo.io/{$IPaddress}");
        $details    = json_decode($json);
        return $details;
    }

    $IPaddress  =   $_SERVER['REMOTE_ADDR'];
    // $details    =   ip_details("182.74.217.98");
    // $details    =   ip_details("12.215.42.19");
    $details    =   ip_details($IPaddress);

   $country = $details->country;
   if($country=="IN"){
       echo 'India';
   } else{
       echo 'USA';
   }
?>

