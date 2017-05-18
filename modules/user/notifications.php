<?php

/*
  Functionality : Notification
  Author : prem
 */

require_once ('config/db.php');

class Notification extends Db {

    var $db;

    function __construct() {
        $this->db = $this->dbconnection();
    }

    public function schdule_notification($data) {
        $app_id = $data['app_id'];
        $title = $data['title'];
        $msg = $data['desc'];
        $current_id = $data['current_id'];

        $time1 = $data['time1'];
        $time2 = $data['time2'];
        $time = $time1 . $time2;
        $date1 = $data['date'];

        $date = date('Y-m-d H:i:s', strtotime($date1 . $time));
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $date2 = date('Y-m-d H:i:s');
        }
        if ($date2 < $date) {
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
        } else{
            echo "backtime";
        }
    }

    public function check_schdule_notification($app_id, $date) {
        $sql = "select id FROM schedule_push_notification where app_id='" . $app_id . "' and schedule_date<'$date' AND schedule_date >=('$date'-INTERVAL 1 HOUR) order by id desc limit 0,1";
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
        $sql = "select * FROM schedule_push_notification where schedule_date<'$date' AND schedule_date >=('$date'-INTERVAL 1 HOUR) AND is_delivered=0";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    public function update_schdule_notification($app_id, $id) {
        $is_delivered = 1;
        $sql = "UPDATE schedule_push_notification SET  is_delivered = '" . $is_delivered . "'   WHERE app_id = '" . $app_id . "' and id='$id'";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "fails";
        }
    }

    public function delete_notification($data) {
        $id = $data['notification_id'];
        $sql = "delete from schedule_push_notification WHERE id = '" . $id . "' ";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "fails";
        }
    }

    public function ios_notification_ppk_file($data) {
        $app_id = $data['app_id'];
        if (!file_exists('panelimage/' . $app_id . '/ioscertificate')) {
            mkdir('panelimage/' . $app_id . '/ioscertificate', 0775, true);
        }
        $upload_path = 'panelimage/' . $app_id . '/ioscertificate/';
        $file_details = pathinfo($_FILES["ios_file"]["name"]);
        $file_type = $file_details['extension'];

        $file_size = $_FILES["ios_file"]["size"];

        if ($file_type != 'cer') {
            echo "invaild_file";
        } else if ($file_size > 102400) {
            echo "invaild_size";
        } else {
            $fileName = $_FILES["ios_file"]["name"];
            $fileTmpLoc = $_FILES["ios_file"]["tmp_name"];
            // Path and file name
            $pathAndName = $upload_path . $fileName;
            // Run the move_uploaded_file() function here
            $moveResult = move_uploaded_file($fileTmpLoc, $pathAndName);
            // Evaluate the value returned from the function if needed
            if ($moveResult == true) {
                $sql = "UPDATE app_data SET  ioscertificate = '" . $fileName . "'   WHERE id = '" . $app_id . "'";
                $stmt = $this->db->prepare($sql);
                if ($stmt->execute()) {
                    echo "success#" . $fileName;
                } else {
                    echo "fails";
                }
            } else {
                echo "not_uploaded";
            }
        }
    }

}
