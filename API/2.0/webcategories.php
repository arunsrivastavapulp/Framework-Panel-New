<?php
require 'Slim/Slim.php';

require_once('common_functions.php');
require_once('includes/main_functions.php');

\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
$app = new Slim();

$app->post('/webcategory', 'webcategory'); // Using Post HTTP Method and process catalogLaunch function
$app->run();

/* 
 * results catalog launch data to api
 * Added By Arun Srivastava on 25/6/15
 */
class Fwcore extends MainFunctions
{ 
	function getParent($cat_id)
	{
		$usedCats    = $cat_id;
		$cquery2     = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id 
						FROM oc_category c 
						LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
						WHERE c.category_id='".$cat_id."'";
		$catdata2    = $this->queryRun($cquery2, 'select');
		
		if(!empty($catdata2))
		{
			if($catdata2['parent_id'] > 0)
			{
				$finaldata = $this->getParent($catdata2['parent_id']);
				
				$usedCats = $usedCats.','.$finaldata['childs'];
		
				return array('cats' => $finaldata['cats'], 'childs' => $usedCats);
			}
			else
			{
				$finaldata = $catdata2;
		
				return array('cats' => $finaldata, 'childs' => $usedCats);
			}
		}
	}
function webcategory()
{
     $app_idString = $_POST['app_id'];
   die;
     $appQueryData   = "select * from app_data where app_id='" . $app_idString . "'";
     
					$app_screenData = $this->query_run($appQueryData, 'select');
                                        print_r($app_screenData);
			
					if($app_screenData != '')
					{	
						$app_id        = $app_screenData['id'];
                                        }
                                        else
                                        {
                                            die;
                                        }

	$cquery1     = "SELECT DISTINCT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id 
										FROM oc_app_id ai 
										JOIN oc_product p  ON ai.product_id=p.product_id
										LEFT JOIN oc_product_to_category pc ON p.product_id=pc.product_id 
										LEFT JOIN oc_category c ON pc.category_id=c.category_id 
										LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
										WHERE ai.app_id='".$app_id."' AND p.status = '1'";
						$catdata1    = $this->queryRun($cquery1, 'select_all');
						
						$topparent = array();
						$childcats = array();
						if(!empty($catdata1))
						{
							foreach($catdata1 as $cats)
							{
								$topcats     = getParent($cats['itemid']);								
								$topparent[] = $topcats['cats'];
								$childcats[] = $topcats['childs'];
							}
						}
                                                $topparent = array_unique($topparent, SORT_REGULAR);
						
						$newtopparent = array();
						if(!empty($topparent))
						{
							foreach($topparent as $tempdaddd)
							{
								if(!empty($tempdaddd))
								{
									$newtopparent[] = $tempdaddd;
								}
							}
							$topparent = $newtopparent;
						}
						print_r($topparent);
}
}