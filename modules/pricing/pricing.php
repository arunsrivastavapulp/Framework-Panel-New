<?php

require_once ('config/db.php');

class mypricing extends Db {

    var $db;

    public function __construct() {
        $this->db = $this->dbconnection();
    }

    public function pricing() {
        $realescape = "select * from plans where id=9";
        $getallcat = $this->db->prepare($realescape);
        $getallcat->execute();
        $getall = $getallcat->fetchAll(PDO::FETCH_ASSOC);
        return $getall;
    }

    public function geticonPrice() {
        $geticonprice = "SELECT id FROM payment_type where name='Icon'";
        $geticon = $this->db->prepare($geticonprice);
        $geticon->execute();
        $geticomP = $geticon->fetch(PDO::FETCH_ASSOC);
        return $geticomP['id'];
    }

    public function getsplashscreen() {
        $getsplashscreenprice = "SELECT * FROM payment_type where name='Splash Screen'";
        $getsplashscreen = $this->db->prepare($getsplashscreenprice);
        $getsplashscreen->execute();
        $getsplashscreenP = $getsplashscreen->fetch(PDO::FETCH_ASSOC);
        return $getsplashscreenP['id'];
    }

    public function geticonlink($app_id) {
        $geticondata = "SELECT * FROM app_data where id='$app_id'";
        $geticonlink = $this->db->prepare($geticondata);
        $geticonlink->execute();
        $geticonlinkAD = $geticonlink->fetch(PDO::FETCH_ASSOC);
        return $geticonlinkAD;
    }

    public function geticonCurrency($iconLink) {
        $geticonprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Icon') AS payment_type_id,id AS payment_type_value FROM default_icons WHERE image_40='$iconLink'";
        $geticon = $this->db->prepare($geticonprice);
        $geticon->execute();
        $geticomP = $geticon->fetch(PDO::FETCH_ASSOC);
        return $geticomP;
    }

    public function getsplashscreenCurrency($splashscreenid) {
        $getsplashscreenprice = "SELECT *,(SELECT id FROM payment_type WHERE NAME='Splash Screen') AS payment_type_id,id AS payment_type_value FROM splash_screen WHERE id='$splashscreenid'";
        $getsplashscreen = $this->db->prepare($getsplashscreenprice);
        $getsplashscreen->execute();
        $getsplashscreenP = $getsplashscreen->fetch(PDO::FETCH_ASSOC);
        return $getsplashscreenP;
    }

    public function carthavedata($custid) {
        $sql = "SELECT mp.id as masterpayment_id,mp.order_id as masterpayment_orderid,ap.* FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
WHERE ap.payment_done=0 AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";
        $getallappplandetails = $this->db->prepare($sql);
        $getallappplandetails->execute();
        $getallplan = $getallappplandetails->fetchAll(PDO::FETCH_ASSOC);
        return $getallplan;
    }

    public function getallappsplan($custid) {
        $sql = "SELECT mp.id as masterpayment_id,mp.order_id as masterpayment_orderid,ap.* FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
WHERE ap.payment_done=0 AND ap.plan_id IS NOT NULL  AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";
//        $sql = "SELECT * FROM author_payment WHERE master_payment_id IN (SELECT id FROM master_payment WHERE payment_done=0 AND author_id IN (SELECT id FROM author WHERE custid=$custid))";
        $getallappplandetails = $this->db->prepare($sql);
        $getallappplandetails->execute();
        $getallplan = $getallappplandetails->fetchAll(PDO::FETCH_ASSOC);
        return $getallplan;
    }

    public function getallappsIS($authorpaymentID) {
        $sql = "SELECT * FROM author_payment_details WHERE author_payment_id='$authorpaymentID'";
        $getallappISdetails = $this->db->prepare($sql);
        $getallappISdetails->execute();
        $getallIS = $getallappISdetails->fetchAll(PDO::FETCH_ASSOC);
        return $getallIS;
    }

    public function getplan($planid) {
        $sql = "SELECT * FROM plans where id='$planid' and deleted!='1'";
        $getplans = $this->db->prepare($sql);
        $getplans->execute();
        $getplan = $getplans->fetch(PDO::FETCH_ASSOC);
        return $getplan;
    }

    public function getallappsMpackages($custid) {
        $sql = "SELECT mp.id as masterpayment_id,mp.order_id as masterpayment_orderid,ap.*,apd.* FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
join author_payment_details apd on apd.author_payment_id=ap.id
WHERE ap.payment_done=0 AND apd.payment_type_id=3 AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";
//        $sql = "SELECT * FROM author_payment WHERE master_payment_id IN (SELECT id FROM master_payment WHERE payment_done=0 AND author_id IN (SELECT id FROM author WHERE custid=$custid))";
        $getallappMP = $this->db->prepare($sql);
        $getallappMP->execute();
        $getallMP = $getallappMP->fetchAll(PDO::FETCH_ASSOC);
        return $getallMP;
    }

    public function getallPaidapps($custid) {
        $sql = "SELECT a.id as app_id,a.summary AS AppName,a.author_id,a.published,CONCAT_WS(' ',b.first_name,b.last_name) as author_name FROM app_data a
join author b on a.author_id=b.id WHERE b.custid='$custid'";
        $getallPaidapp = $this->db->prepare($sql);
        $getallPaidapp->execute();
        $getallPaid = $getallPaidapp->fetchAll(PDO::FETCH_ASSOC);
        return $getallPaid;
    }

    public function getMPprice($mpid) {
        $sqlmp = "SELECT * FROM marketing_packages WHERE id='$mpid'";
        $appMP = $this->db->prepare($sqlmp);
        $appMP->execute();
        $appMPid = $appMP->fetch(PDO::FETCH_ASSOC);
        return $appMPid;
    }

    public function getalldistinctMPprice($custid) {
        $sql = "SELECT DISTINCT apd.payment_type_value,apd.amount FROM master_payment mp 
join author_payment ap on ap.master_payment_id=mp.id
join author_payment_details apd on apd.author_payment_id=ap.id
WHERE ap.payment_done=0 AND apd.payment_type_id=3 AND mp.author_id IN (SELECT id FROM author WHERE custid='$custid')";
        $getallPaidapp = $this->db->prepare($sql);
        $getallPaidapp->execute();
        $getallPaid = $getallPaidapp->fetchAll(PDO::FETCH_ASSOC);
        return $getallPaid;
    }

    public function getASOprice() {
        $sqlmp = "SELECT * FROM marketing_packages";
        $appMP = $this->db->prepare($sqlmp);
        $appMP->execute();
        $appMPid = $appMP->fetchAll(PDO::FETCH_ASSOC);
        return $appMPid;
    }

}

?>