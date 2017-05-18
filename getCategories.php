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
    $cat='';
    $dbCon = getConnection();
    //  try {
    $mainCategoryQuery = "SELECT id,name FROM category WHERE parent_id=0";
    $mainCategoryQueryRun = $dbCon->query($mainCategoryQuery);
    $mainCategoryResult = $mainCategoryQueryRun->fetchAll(PDO::FETCH_ASSOC);
    $rowCount = $mainCategoryQueryRun->rowCount(PDO::FETCH_NUM);
    $categoryResult = array();
    for ($i = 0; $i < $rowCount; $i++) {

        $categories = getAllCat($mainCategoryResult[$i]['id']);



        if ($categories != null) {
            $cat[] = array("name" => $mainCategoryResult[$i]['name'], "id" => $mainCategoryResult[$i]['id'], "categories" => $categories);
        }
    }


  
    $basejson = json_encode($cat);
    echo $basejson;
//    } catch (Exception $e) {
//        $response = array("result" => 'error', "msg" => 'something went wrong');
//        $Basearray = array("response" => $response);
//        $basejson = json_encode($Basearray);
//        echo $basejson;
//    }
}

function getSubcategories($id) {
    $dbCon = getConnection();
    $categoryQuery = "SELECT id,NAME as category_name FROM category WHERE parent_id ='" . $id . "'";
    $categoryQueryRun = $dbCon->query($categoryQuery);
    $categoryResult = $categoryQueryRun->fetchAll(PDO::FETCH_ASSOC);
    return $categoryResult;
}

function getAllCat($id) {
    $alldataStore1 = '';
    $subcategories='';
    $resultCat = getSubcategories($id);
    $countCat = count($resultCat);
    if ($countCat > 0) {
        for ($j = 0; $j < $countCat; $j++) {

            $subcatId = $resultCat[$j]['id'];

            $alldataStore = $alldataStore1;
            if (count($alldataStore1) > 0) {
                $subcategories1=getAllCat($resultCat[$j]['id']);
                if(!empty($subcategories1) && $subcategories1 != null)
                {
                 $subcategories=$subcategories1;
                }
           
                $alldataStore1[] = array("id" => $resultCat[$j]['id'], "name" => $resultCat[$j]['category_name'], "categories" => $subcategories);
                $alldataStore[] = getAllCat($subcatId);
            }
            //  $alldataStore[]= getAllCat($subcatId);
        }
        return $alldataStore;
    }
}

$app->run();
