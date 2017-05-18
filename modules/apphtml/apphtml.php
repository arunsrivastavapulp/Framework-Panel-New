<?php
require_once ('config/db.php');

class Saveapphtml extends Db {

    var $db;

    function __construct() {
        $this->db = $this->dbconnection();
    }

    function check_user_exist($email, $custid) {
        $sql = "select id,first_name,avatar from author where custid=$custid";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function check_app_owner($app_id) {
        $sql = "select autherId from app_screenmapping_html where app_id=$app_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    function get_cuurent_app_html($app_id, $user_id) {
        $sql = "select * from app_screenmapping_html where app_id=$app_id and autherId= $user_id and deleted=0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $allpageDetails = array();
        foreach ($results as $key => $value) {

            $allpageDetails['storedPages'][] = array(
                'index' => $value['screen_sequence'],
                'contentarea' => $value['content_html'],
                'banner' => $value['banner_html'],
                'layouttype' => $value['layoutType'],
				'originalIndex'=>$value['PageIndex']
            );
            $allpageDetails['pageDetails'][] = array(
                'index' => $value['screen_sequence'],
                'name' => $value['title']
            );
            if ($value['navigation_html'] != "" || $value['navigation_html'] != NULL) {
                $allpageDetails['navbar'][] = $value['navigation_html'];
            }
        }
        return json_encode($allpageDetails);
    }
    
    function checkAdData()
    {
       $sql = "select * from adserver where deleted=0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);  
        return $results;
        
        
    }

}

?>