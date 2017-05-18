<?php

$user_agent     =   $_SERVER['HTTP_USER_AGENT'];

function getOS() { 

    global $user_agent;

    $os_platform    =   "Unknown OS Platform";

    $os_array       =   array(
                            '/windows nt 10/i'     =>  'Windows 10',
                            '/windows nt 6.3/i'     =>  'Windows 8.1',
                            '/windows nt 6.2/i'     =>  'Windows 8',
                            '/windows nt 6.1/i'     =>  'Windows 7',
                            '/windows nt 6.0/i'     =>  'Windows Vista',
                            '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
                            '/windows nt 5.1/i'     =>  'Windows XP',
                            '/windows xp/i'         =>  'Windows XP',
                            '/windows nt 5.0/i'     =>  'Windows 2000',
                            '/windows me/i'         =>  'Windows ME',
                            '/win98/i'              =>  'Windows 98',
                            '/win95/i'              =>  'Windows 95',
                            '/win16/i'              =>  'Windows 3.11',
                            '/macintosh|mac os x/i' =>  'iPhone',
                            '/mac_powerpc/i'        =>  'iPhone',
                            '/linux/i'              =>  'Linux',
                            '/ubuntu/i'             =>  'Ubuntu',
                            '/iphone/i'             =>  'iPhone',
                            '/ipod/i'               =>  'iPhone',
                            '/ipad/i'               =>  'iPhone',
                            '/android/i'            =>  'Android',
                            '/blackberry/i'         =>  'BlackBerry',
                            '/webos/i'              =>  'Mobile'
                        );

    foreach ($os_array as $regex => $value) { 

        if (preg_match($regex, $user_agent)) {
            $os_platform    =   $value;
        }

    }   

    return $os_platform;

}


$user_os        =   getOS();


if($user_os == 'Android' )
{
?>
<script type="text/javascript">
            window.location.href = "https://play.google.com/store/apps/details?id=com.pulp.wizard"
        </script>
<?php
}
elseif($user_os == 'iPhone')
{
?>
<script type="text/javascript">
            window.location.href = "https://itunes.apple.com/us/app/instappy/id1053874135?mt=8"
        </script>
<?php


}
else
{

?>
<script type="text/javascript">
            window.location.href = "https://play.google.com/store/apps/details?id=com.pulp.wizard"
        </script>
<?php
}



?>