<?php
//define db user name
define('USERNAME', 'hemant');
//define db password
define('PASSWORD', 'Fdfjkhg%#$#@4312AS');
//define db hostname
define('HOST', 'pulpstrategyinstance.cil4anb91ydi.us-west-2.rds.amazonaws.com');
//define database name
define('DB', 'app_design_new');

//define db class
class DB {

    private $urL = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/panel/frameworkphp/";
    private $catalogue_urL = "http://ec2-52-10-50-94.us-west-2.compute.amazonaws.com/framework/";

    public function dbconnection() {

        $user = USERNAME;
        $pass = PASSWORD;
        $host = HOST;
        $db = DB;
        try {
            $db = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch (PDOException $e) {

            echo "DB Error" . $e->getMessage();
        }
    }

    public function siteurl() {
        return $this->urL;
    }

    public function ip_details($IPaddress) {
        $json = file_get_contents("http://ipinfo.io/{$IPaddress}");
        $details = json_decode($json);
        return $details;
    }
    
    public function getcurrencyid($country) {
        $db = $this->dbconnection();
        $sql = "SELECT * FROM currency_type WHERE country='$country'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    public function get_country() {
        session_start();
      $IPaddress = $_SERVER['REMOTE_ADDR'];     
        // $details    =   ip_details("182.74.217.98");
//        $details = $this->ip_details("12.215.42.19");
//        
      $details = $this->ip_details($IPaddress);
      
      $country = $details->country;
//    $country = "USA";
//    $country = "IN";
        if ($country == "IN") {

            $countryName = $this->getcurrencyid($country);
            $_SESSION['country'] = $countryName['country'];
            $_SESSION['currencyid'] = $countryName['id'];
            $_SESSION['currency'] = $countryName['Name'];
            $checkcountry = $countryName['id'];
            $currency = $countryName['id'];
            $currencyIcon = $countryName['Name'];
        } else if ($country == "US") {

            $countryName = $this->getcurrencyid($country);
            $_SESSION['country'] = $countryName['country'];
            $_SESSION['currencyid'] = $countryName['id'];
            $_SESSION['currency'] = $countryName['Name'];
            $checkcountry = $countryName['id'];
            $currency = $countryName['id'];
            $currencyIcon = $countryName['Name'];
        } else {
           $country = "IN";
            $countryName = $this->getcurrencyid($country);
            $_SESSION['country'] = $countryName['country'];
            $_SESSION['currencyid'] = $countryName['id'];
            $_SESSION['currency'] = $countryName['Name'];
            $checkcountry = $countryName['id'];
            $currency = $countryName['id'];
            $currencyIcon = $countryName['Name'];
        }
    }

    public function catalogue_url() {
        return $this->catalogue_urL;
    }

    public function restrictPages() {
        $pages = array(
            'panel.php', 'catalogue.php'
        );
        return $pages;
    }

    public function meta_tags($page, $tag) {
        $db = $this->dbconnection();
        $sql = "select $tag from app_meta_tags where url='$page'";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results[$tag];
    }

}

?>