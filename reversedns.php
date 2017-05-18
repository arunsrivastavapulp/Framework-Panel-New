<?php
	$ua = $_SERVER['HTTP_USER_AGENT'];
	if(stristr($ua, 'msnbot') || stristr($ua, 'Googlebot') || stristr($ua, 'Yahoo Slurp')){
		$ip = $_SERVER['REMOTE_ADDR'];
	$hostname = gethostbyaddr($ip);
	if(!preg_match("/\.googlebot\.com$/", $hostname) &&!preg_match("/search\.live\.com$/", $hostname) &&!preg_match("/crawl\.yahoo\.net$/", $hostname)) {
		$block = TRUE;
		$URL="/";
		header ("Location: $URL");
		exit;
	} 
	else {
		$real_ip = gethostbyname($hostname);
		if($ip!= $real_ip){
		$block = TRUE;
		$URL="/";
		header ("Location: $URL");
		exit;
	} 
	else {
		$block = FALSE;
		}
	}
}
?>