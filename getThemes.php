<?php

/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'db.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();
$app->post('/index', 'index');

function index() {
    $dbCon = getConnection();


    /* try
      { */

  //  $themeQuery = "select th.id as themeId,th.name as name,th.is_featured,th.image_url as image ,th.image_url2 as image_new ,th.images2x as image2x,ct.category_id,ctg.parent_id,ctg.name as category_name FROM themes th join category_theme_rel ct on th.id=ct.theme_id left join category ctg on ct.category_id=ctg.id order by th.id ASC";
    $themeQuery = "select
 th.id as themeId,th.name as name,th.is_featured,th.image_url as image ,th.image_url2 as image_new ,th.images2x as image2x,ct.category_id,ctg.parent_id,ctg.name as category_name ,
 coalesce(ctg2.name,ctg1.name,ctg.name) as parent_category,coalesce(ctg2.id,ctg1.id,ctg.id) as parent_id
 FROM 
 themes th 
 join category_theme_rel ct on th.id=ct.theme_id 
 join category ctg on ct.category_id=ctg.id 
  left join category ctg1 on ctg.parent_id=ctg1.id
 left join category ctg2 on ctg1.parent_id=ctg2.id
 where ctg.parent_id<>''
 and  ct.category_id<>464
 order by th.id ASC";
    $themeQueryRun = $dbCon->query($themeQuery);
    $themeResult = $themeQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $rowCount = $themeQueryRun->rowCount(PDO::FETCH_NUM);
    $themeId = 0;
    $themeResult1 = '';
    
    foreach ($themeResult as $value) {
      
      
            

        if ($themeId == 0 || $themeId == $value['themeId']) {
            $themeId = $value['themeId'];
            $parent_cat[] = array("id" => $value['category_id'], "name" => $value['category_name']);
            $uniqueArray = array("id" => $value['themeId'], "name" => $value['name'], "image" => $value['image'],"image_new" => $value['image_new'], "image2x" => $value['image2x'],"featured" => $value['is_featured'], "parent_cat" => $parent_cat,"top_cat"=>array("id"=>$value['parent_id'],"name"=>$value['parent_category']));
        } else {
            if ($uniqueArray == '') {
                $uniqueArray = array("id" => $value['themeId'], "name" => $value['name'], "image" => $value['image'], "image_new" => $value['image_new'],"image2x" => $value['image2x'],"featured" => $value['is_featured'], "parent_cat" => $parent_cat,"top_cat"=>array("id"=>$value['parent_id'],"name"=>$value['parent_category']));
            }
            else
            {
              $parent_cat='';
            }

            $themeResult1[] = $uniqueArray;
            $uniqueArray = '';  
             $themeId = $value['themeId'];
            $parent_cat[] = array("id" => $value['category_id'], "name" => $value['category_name']);
            $uniqueArray = array("id" => $value['themeId'], "name" => $value['name'], "image" => $value['image'],"image_new" => $value['image_new'], "image2x" => $value['image2x'],"featured" => $value['is_featured'], "parent_cat" => $parent_cat,"top_cat"=>array("id"=>$value['parent_id'],"name"=>$value['parent_category']));
        }
    }


    $response = array("theme_data" => $themeResult1);
    $Basearray = array("response" => $response);
    $basejson = json_encode($Basearray);
    echo $basejson;
    /* }
      catch (Exception $e) {
      $response = array("result" => 'error', "msg" => 'something went wrong');
      $Basearray = array("response" => $response);
      $basejson = json_encode($Basearray);
      echo $basejson;
      } */
}

$app->run();
