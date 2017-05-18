<?php 
// from 1.5
require_once('includes/main_functions.php');
require_once('common_functions.php');

class AddressData extends MainFunctions
{
	/*
     * get catalog launch data
	 * added by Arun Srivastava on 25/6/15
	 */
	public function fetchMultipleAddress($data)
	{
        $cf = new Fwcore();
		if((isset($data['app_id']) && trim($data['app_id']) != '') && (isset($data['auth_token']) && trim($data['auth_token']) != ''))
		{
			$authToken    = $data['auth_token'];
			$app_idString = $data['app_id']; 
			$domain_id    = $data['domain_id']; 
			
			$authResult   = $this->authCheck($authToken);
			
			if($authResult > 0)
			{
				$authToken = $authResult['auth_token'];
				$device_id = $authResult['device_id'];
				
				$appQueryData   = "select * from app_data where app_id='" . $app_idString . "'";
				$app_screenData = $this->query_run($appQueryData, 'select');
				
				$app_id         = $app_screenData['id'];
				
				$this->lastLogin_new($authToken, $device_id, $app_id);
				
				$lastlogindate  = $this->lastLoginUpdateToken($authToken);
				
				if($domain_id != '')
				{
					$cquery = "SELECT da.id as address_id, da.address, d.domainname FROM domain_address da LEFT JOIN domains d ON d.id=da.domain_id WHERE da.domain_id='".$domain_id."' AND da.isActive='1' AND d.isActive = '1'";
				}
				else
				{
					$cquery = "SELECT da.id as address_id, da.address, d.domainname FROM domain_address da LEFT JOIN domains d ON d.id=da.domain_id WHERE da.isActive='1' AND d.isActive = '1'";
				}
				$addressdata = $this->queryRun($cquery, 'select_all');
				
				if(!empty($addressdata))
				{
					$data = array("address_array" => $addressdata);
					
					$json = $this->real_json_encode($data, 'successData','',200, $lastlogindate, $authToken);
					echo $json;
				}
				else
				{
					$json = $this->real_json_encode('', 'error', 'No Addresses found', 405);
					echo $json;
				}
			}
			else
			{
				$json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
				echo $json;
			}
		}
		else
		{
			$json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
			echo $json;
		}
    }
}