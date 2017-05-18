<?php 
require_once('config/db.php');
$db = new DB();
$mysqli = $db->dbconnection();
$app_id = $_REQUEST['id'];
$startdate = $_REQUEST['startdate1'];
$enddate = $_REQUEST['enddate1'];
$explodedStartDate = explode('-',$startdate);
$explodedEndDate = explode('-',$enddate);
$start_date = $explodedStartDate[2].'-'.$explodedStartDate[1].'-'.$explodedStartDate[0].' 00:00:00';
$end_date = $explodedEndDate[2].'-'.$explodedEndDate[1].'-'.$explodedEndDate[0].' 23:59:59';
$app_query = "select download_date,last_login,platform,latest_version,email_address,phone from user_appdata where app_id='".$app_id."' and download_date >= '".$start_date."' and download_date <= '".$end_date."'"; 
$appQueryRun = $mysqli->query($app_query);


require_once("Classes/PHPExcel.php");
$objPHPExcel = new PHPExcel(); 

$objPHPExcel->setActiveSheetIndex(0); 

$rowCount = 2; 

$objPHPExcel->getActiveSheet()->SetCellValue('A1', "NO");
$objPHPExcel->getActiveSheet()->SetCellValue('B1', "Download Date");
$objPHPExcel->getActiveSheet()->SetCellValue('C1', "Last Login");
$objPHPExcel->getActiveSheet()->SetCellValue('D1', "Platform");
$objPHPExcel->getActiveSheet()->SetCellValue('E1', "Version");
$objPHPExcel->getActiveSheet()->SetCellValue('F1', "Email Address");
$objPHPExcel->getActiveSheet()->SetCellValue('G1', "Phone");

$no = 1;
while($rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC)){ 

		$platform_query = "select name from platform where id='".$rowFetch['platform']."' "; 
        $platformQueryRun = $mysqli->query($platform_query);
        $platfromRowFetch = $platformQueryRun->fetch(PDO::FETCH_ASSOC);

        if(empty($rowFetch['download_date']) || $rowFetch['download_date']==NULL || $rowFetch['download_date']==NULL){
        	$rowFetch['download_date']='N/A';
        }else{
        	$rowFetch['download_date']=$rowFetch['download_date'];
        }

 		if(empty($rowFetch['last_login']) || $rowFetch['last_login']==NULL || $rowFetch['last_login']=="0"){
        	$rowFetch['last_login']='N/A';
        }else{
        	$rowFetch['last_login']=$rowFetch['last_login'];
        }

         if(empty($platfromRowFetch['name']) || $platfromRowFetch['name']==NULL || $platfromRowFetch['name']=="0"){
        	$platfromRowFetch['name']='N/A';
        }else{
        	$platfromRowFetch['name']=$platfromRowFetch['name'];
        }

         if(empty($rowFetch['latest_version']) || $rowFetch['latest_version']==NULL || $rowFetch['latest_version']=="0"){
        	$rowFetch['latest_version']='N/A';
        }else{
        	$rowFetch['latest_version']=$rowFetch['latest_version'];
        }

         if(empty($rowFetch['email_address']) || $rowFetch['email_address']==NULL || $rowFetch['email_address']=='0'){
        	$rowFetch['email_address']='N/A';
        }else{
        	$rowFetch['email_address']=$rowFetch['email_address'];
        }

         if(empty($rowFetch['phone']) || $rowFetch['phone']==NULL || $rowFetch['phone']=='0'){
        	$rowFetch['phone']='N/A';
        }else{
        	$rowFetch['phone']=$rowFetch['phone'];
        }

	    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $no); 
	    $objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $rowFetch['download_date']);
	    $objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $rowFetch['last_login']); 
	    $objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $platfromRowFetch['name']);
	    $objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $rowFetch['latest_version']); 
	    $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $rowFetch['email_address']);
	    $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $rowFetch['phone']); 
	     
	    $rowCount++; 
	    $no++;
} 

header('Content-Type: application/vnd.ms-excel'); 
header('Content-Disposition: attachment;filename=app-details-'.$app_id.'.xls'); 
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->save('php://output');