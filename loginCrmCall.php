<?php
session_start();

        $loginTime = date('Y-m-d h:i:s', time());
        $logout_time = '';
        $email=$_SESSION['username'];
        $customerid = $_SESSION['custid'];
        
        $mainurl = 'http://182.74.47.179/universus/pulp_login_api.php?customerid='.$customerid.'&login_time='.$loginTime.'&logout_time='.$logout_time.'&email='.$email;

        $url = str_replace(' ', '-', $mainurl);
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);
        
?>