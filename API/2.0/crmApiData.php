

<?php
require_once('../config/db.php');
$db = new DB();
$dbCon = $db->dbconnection();
		 

		
        $query = "SELECT ad.id, ad.created, ad.updated, ad.summary, ad.type_app, ad.type_app, ad.category, ad.created, ad.plan_expiry_date,ar.custid FROM app_data ad left join author ar on ad.author_id=ar.id WHERE ( ad.updated > DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) ) or ad.created > DATE( DATE_SUB( NOW() , INTERVAL 1 DAY ) )) and ad.deleted = 0";
        $queryData = $dbCon->query($query);
        $resultData = $queryData->fetch(PDO::FETCH_ASSOC);
		$calRow = $queryData->rowCount();

	if($calRow!=0){	
			 for($i=0; $i<$calRow; $i++){
				 $resultData = $queryData->fetch(PDO::FETCH_ASSOC);	
				 $apiUrl = 'http://182.74.47.179/universus/pulp_app_api.php?app_id='.$resultData['id'].'&app_name='.$resultData['summary'].'&app_type='.$resultData['type_app'].'&app_category='.$resultData['category'].'&app_status='.$resultData['category'].'&created_datetime='.$resultData['created'].'&plan_expiry_date='.$resultData['plan_expiry_date'].'&customerid='.$resultData['custid'].'';
				 $url = str_replace(' ', '-', $apiUrl);
				 $curl = curl_init();
				 curl_setopt_array($curl, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $url
				 ));
				 $head = curl_exec($curl);
				 curl_close($curl); 
			 }
				echo 'Data send successfully!';
						 
		}else{
				echo 'Data not available';
		}   

