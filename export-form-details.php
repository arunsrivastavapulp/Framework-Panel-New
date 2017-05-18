<?php 
require_once('config/db.php');
$db = new DB();
$mysqli = $db->dbconnection();
$app_id = $_REQUEST['id'];
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];
$formname = $_REQUEST['formname'];
$explodedStartDate = explode('-',$startdate);
$explodedEndDate = explode('-',$enddate);
$start_date = $explodedStartDate[2].'-'.$explodedStartDate[1].'-'.$explodedStartDate[0].' 00:00:00';
$end_date = $explodedEndDate[2].'-'.$explodedEndDate[1].'-'.$explodedEndDate[0].' 23:59:59';
$app_query = "select form_data from formfieldvalue where app_id='".$app_id."' and screen_id='".$formname."' and created >= '".$start_date."' and created <= '".$end_date."'"; 
$appQueryRun = $mysqli->query($app_query);

 
 require_once("Classes/PHPExcel.php");
$objPHPExcel = new PHPExcel(); 

$objPHPExcel->setActiveSheetIndex(0);  
 
$rowCount = 2; 
$index=0;
$no = 1;
$columnName = array();
while($rowFetch = $appQueryRun->fetch(PDO::FETCH_ASSOC)){
	$data = json_decode($rowFetch['form_data']);

	foreach($data->form_data as $key => $val){
		
		
		if(!in_array($key, $columnName, true)){
			$columnName[] = $key;
		} 
} 

}
$row = 1;
 $col = 0;
    foreach($columnName as $value) {
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $value);
        $col++;
    }
$row++;


$column_query = "select form_data from formfieldvalue where app_id='".$app_id."' and screen_id='".$formname."' and created >= '".$start_date."' and created <= '".$end_date."'"; 
$columnQueryRun = $mysqli->query($column_query);


$row2 = 2;
 
while($columnFetch = $columnQueryRun->fetch(PDO::FETCH_ASSOC)){
	
	$column = json_decode($columnFetch['form_data']);
    foreach($column->form_data as $key => $val2) {
		$pos = array_search($key,$columnName);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($pos, $row2, $val2);   
    }
$row2++;
}

 header('Content-Type: application/vnd.ms-excel'); 
header('Content-Disposition: attachment;filename=form-data-'.$app_id.'.xls'); 
header('Cache-Control: max-age=0'); 
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5'); 
$objWriter->save('php://output'); 