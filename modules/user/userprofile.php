<?php

/*
  Functionality : Fetch user profile
  Author : Varun Srivastava
 */

require_once ('config/db.php');

class UserProfile extends Db {

    var $db;

    function __construct() {
        $this->db = $this->dbconnection();
    }

    public function getUserByEmail($email) {
        if (!empty($email)) {
            $sql = "select * FROM author where email_address='" . $email . "'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (count($results) > 0) {
                return $results[0];
            }
        }
    }

    public function getUserByCustId($custid) {

        $sql = "select * FROM author where custid='" . $custid . "'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);       
        return $results;        
    }

    public function getUserAddByCustId($custid) {

        $sql = "SELECT * FROM `master_payment` WHERE `author_id` IN(SELECT id FROM author WHERE custid='$custid') AND first_name IS NOT NULL AND last_name IS NOT NULL AND email_address IS NOT NULL AND country IS NOT NULL AND state IS NOT NULL AND address IS NOT NULL AND zip IS NOT NULL AND city IS NOT NULL ORDER BY id DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    }

    public function getUserByCustidOrder($custid, $orderid) {

        $sql = "select * FROM master_payment where author_id IN (select id from author where custid='$custid') and order_id='$orderid'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (count($results) > 0) {
            return $results[0];
        }
    }

    

    public function get_countries($custid, $masterpay='') {
        if (($custid != '') && ($masterpay =='1')) {
            $user = $this->getUserByCustId($custid);
        } else if(($custid != '') && ($masterpay =='0')) {
            $user = $this->getUserAddByCustId($custid);
        } else{
            $user = $this->getUserByCustId($custid);            
        }
        $sql = "select * FROM oc_country order by name asc";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $str = '';
        $str.='<option value="">Select Country</option>';
        $select = '';
        foreach ($results as $val) {
            if(($custid != '') && ($masterpay =='1')) {
                if ($user['country'] == $val['country_id']) {                   
                    $select = 'selected="selected"';
                }
            } else if(($custid != '') && ($masterpay =='0')) {
                if ($user['country'] == $val['name']) {                    
                    $select = 'selected="selected"';
                }
            } else{                
                if ($user['country'] == $val['country_id']) {                   
                    $select = 'selected="selected"';
                }
            }
           $str.='<option value="' . $val['country_id'] . '" ' . $select . '>' . $val['name'] . '</option>';
           
            $select = '';
        }

        echo $str;
    }
    
    public function get_states($data, $custid, $masterpay='') {
       
        if (($custid != '') && ($masterpay == '1')) {
            $user = $this->getUserByCustId($custid);
            $country_id = $data['conuntry_id'];
            $sql = "select * FROM oc_zone  where country_id=$country_id order by name asc";          
        } else if(($custid != '') && ($masterpay == '0')) {
            $user = $this->getUserAddByCustId($custid);
            $country_name = $data['conuntry_id'];
            $sql = "select * FROM oc_zone  where country_id IN(select country_id FROM oc_country where name='$country_name') order by name asc";            
        } else{           
            $user = $this->getUserByCustId($custid);           
            $country_id = $data['conuntry_id'];
            $sql = "select * FROM oc_zone  where country_id=$country_id order by name asc";           
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $str = '';
        $str.='<option value="">Select State</option>';
        $select = '';
        foreach ($results as $val) {
            if(($custid != '') && ($masterpay == '1')) {
                if ($user['state'] == $val['zone_id']) {
                    $select = 'selected="selected"';
                }
            } else if(($custid != '') && ($masterpay == '0')) {              
                if ($user['state'] == $val['name']) {
                    $select = 'selected="selected"';
                }
            } else{               
                if ($user['state'] == $val['zone_id']) {
                    $select = 'selected="selected"';
                }
            }
            $str.='<option value="' . $val['zone_id'] . '" ' . $select . '>' . $val['name'] . '</option>';
            $select = '';
        }
        
        echo $str;
    }

    public function schdule_notification($data) {
        $app_id = $data['app_id'];
        $title = $data['title'];
        $msg = $data['desc'];
        $date = $data['date'];
        $current_id = $data['current_id'];
        $date = date('Y-m-d', strtotime($date));
        $is_delivered = 0;
        if ($current_id > 0) {
            $id = $current_id;
            $sql = "UPDATE schedule_push_notification SET
			  title = :title,
			  message = :message,
			  schedule_date = :schedule_date,
			  is_delivered = :is_delivered
            WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':title', $title, PDO::PARAM_STR);
            $stmt->bindParam(':message', $msg, PDO::PARAM_STR);
            $stmt->bindParam(':schedule_date', $date, PDO::PARAM_STR);
            $stmt->bindParam(':is_delivered', $is_delivered, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "fails";
            }
        } else {
            $sql = "INSERT INTO schedule_push_notification (app_id,title,message,is_delivered,schedule_date)VALUES('" . $app_id . "','" . $title . "','" . $msg . " ','" . $is_delivered . "','" . $date . "');";
            $stmt = $this->db->prepare($sql);
            if ($stmt->execute()) {
                echo "success";
            } else {
                echo "fails";
            }
        }
    }

    public function check_schdule_notification($app_id, $date) {
        $sql = "select id FROM schedule_push_notification where app_id='" . $app_id . "' and schedule_date='" . $date . "'  order by id desc limit 0,1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($results['id'] > 0) {
            return $results['id'];
        } else {
            return 0;
        }
    }

    public function get_all_apps($date) {
        $sql = "select * FROM schedule_push_notification where schedule_date='" . $date . "'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function update_schdule_notification($app_id, $date) {
        $is_delivered = 1;
        $sql = "UPDATE schedule_push_notification SET  is_delivered = '" . $is_delivered . "'   WHERE app_id = '" . $app_id . "' and schedule_date='" . $date . "'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "fails";
        }
    }

    public function delete_notification($data) {
        $id = $data['notification_id'];
        $sql = "Delete from  schedule_push_notification
            WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "fails";
        }
    }

}
