<?php
require_once ('config/db.php');

$ip = $_SERVER['REMOTE_ADDR'];

if ($ip == "") {
        $ip_number= 0;
    } else {
        $ips = split("\.", "$ip");
        $ip_number= ($ips[3] + $ips[2] * 256 + $ips[1] * 65536 + $ips[0] * 16777216);
    }

echo $ip_number;
$db = new DB();
    $mysqli = $db->dbconnection();
    $query = "SELECT iso_code FROM ip2country_cur WHERE ip_to>=$ip_number AND ip_from<=$ip_number LIMIT 0,1";
    $stmt = $mysqli->prepare($query);
    $stmt->execute();
    $country = $stmt->fetch(PDO::FETCH_ASSOC);
    echo  $country['iso_code'];

?>
Your IP is : <?php echo $ip; ?>