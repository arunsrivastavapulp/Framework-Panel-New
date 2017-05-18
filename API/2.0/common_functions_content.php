<?php

session_start();
require_once('includes/db.php');

class Fwcore {

    private function queryRun($query, $queryType) {
        $dbCon = content_db();
        $con = $dbCon->query($query);
        if ($queryType == 'insert') {
            $lastInsertId = $dbCon->lastInsertId();
            $dbCon = null;
            return $lastInsertId;
        } else if ($queryType == 'update') {
            $row = $con->execute();
            $dbCon = null;
            return $con;
        } else if ($queryType == 'select') {
            $row = $con->fetch(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'select_all') {
            $row = $con->fetchAll(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'delete') {
//            $row = $con->execute();            
            $dbCon = null;
            return $con;
        }
    }

    private function query_run($query, $queryType) {
        $dbCon = retail_db();
        $con = $dbCon->query($query);
        if ($queryType == 'insert') {
            $lastInsertId = $dbCon->lastInsertId();
            $dbCon = null;
            return $lastInsertId;
        } else if ($queryType == 'update') {
            $row = $con->execute();
            $dbCon = null;
            return $con;
        } else if ($queryType == 'select') {
            $row = $con->fetch(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'select_all') {
            $row = $con->fetchAll(PDO::FETCH_ASSOC);
            $dbCon = null;
            return $row;
        } else if ($queryType == 'delete') {
//            $row = $con->execute();            
            $dbCon = null;
            return $con;
        }
    }

    public function real_json_encode_new($data = '', $type, $errorTxt, $errorCode, $lastlogin = '', $authtoken = '') {

        header('Content-Type: text/html; charset=utf-8');
        if ($type == 'successData') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "success",
                                            "msg" => "",
                                            "last_login" => $lastlogin,
                                            "auth_token" => $authtoken
                                        ),
                                        "screen_data" => $data
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } elseif ($type == 'prodsuccessData') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', json_encode((
                            array(
                                "response" => array(
                                    "result" => "success",
                                    "msg" => "",
                                    "last_login" => $lastlogin,
                                    "auth_token" => $authtoken
                                ),
                                "screen_data" => $data
                            )
            )));
        } elseif ($type == 'success') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "success",
                                            "isSucess" => true,
                                            "code" => $errorCode,
                                            "msg" => $errorTxt
                                        ),
                                        "screen_data" => $data
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } elseif ($type == 'error') {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode((
                                    array(
                                        "response" => array(
                                            "result" => "fail",
                                            "isSucess" => false,
                                            "errorCode" => $errorCode,
                                            "errorMsg" => $errorTxt
                                        )
                                    )
                                    ), JSON_NUMERIC_CHECK)));
        } else {
            header("Content-Type: application/json");
            return preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', preg_replace('/\: *([0-9]+\.:?[0-9e+\-]*)/', ':"\\1"', json_encode($data, JSON_NUMERIC_CHECK)));
        }
    }

    public function real_json_encode($data = '', $type, $errorTxt, $errorCode) {

        if ($type == 'successData') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => true,
                            "status" => $errorCode,
                            "errorMsg" => $errorTxt,
                            "data" => $data
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } elseif ($type == 'success') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => false,
                            "errorCode" => $errorCode,
                            "errorMsg" => $errorTxt
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } elseif ($type == 'error') {
            header("Content-Type: application/json");
            return json_encode((
                    array(
                        "response" => array(
                            "isSucess" => false,
                            "errorCode" => $errorCode,
                            "errorMsg" => $errorTxt
                        )
                    )
                    ), JSON_NUMERIC_CHECK);
        } else {
            header("Content-Type: application/json");
            return json_encode(($data), JSON_NUMERIC_CHECK);
        }
    }

  

    public function productsUpdate($app_id, $compVal, $type, $compSave_id, $isCat, $prodid, $prodheading, $catheading, $catid) {
        if ($compVal == '') {
            $compVal = 0;
        }
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $checkProduct = "select * from retail_app_products where  app_id =:appid  and retail_app_component_id=:saveid";
        $con = $dbCon->prepare($checkProduct);
        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $con->bindParam(':saveid', $compSave_id, PDO::PARAM_INT);
        $con->execute();
        $RetailProdId = $con->fetch(PDO::FETCH_ASSOC);
        if (!empty($RetailProdId)) {
            $query = "update  retail_app_products set isActive=1,category_id=:compval,is_catalogue=:iscat,cat_id=:catid,cat_head=:cathead,prod_id=:prodid,prod_head=:prodhead where app_id=:appid and retail_app_component_id=:compsaveid";
            $updated = $dbCon->prepare($query);
            $updated->bindParam(':compval', $compVal, PDO::PARAM_INT);
            $updated->bindParam(':iscat', $isCat, PDO::PARAM_INT);
            $updated->bindParam(':catid', $catid, PDO::PARAM_INT);
            $updated->bindParam(':cathead', $catheading, PDO::PARAM_STR);
            $updated->bindParam(':prodid', $prodid, PDO::PARAM_INT);
            $updated->bindParam(':prodhead', $prodheading, PDO::PARAM_STR);
            $updated->bindParam(':appid', $app_id, PDO::PARAM_INT);
            $updated->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
            $updated->execute();
            $prodId = $RetailProdId['id'];
        } else {
            $prodUpdate = "insert into retail_app_products (`app_id`, `category_id`, `type`,`isActive`, `created_date`,`retail_app_component_id`,`is_catalogue`,`cat_id`,`cat_head`,`prod_id`,`prod_head`) values (:appid, :campval, :type, '1', now(),:compsaveid,:iscat,:catid,:catheading,:prodid,:prodheading )";
            $prodUpdateQry = $dbCon->prepare($prodUpdate);
            $prodUpdateQry->bindParam(':campval', $compVal, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':iscat', $isCat, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':type', $type, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':catid', $catid, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':catheading', $catheading, PDO::PARAM_STR);
            $prodUpdateQry->bindParam(':prodid', $prodid, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':prodheading', $prodheading, PDO::PARAM_STR);
            $prodUpdateQry->bindParam(':appid', $app_id, PDO::PARAM_INT);
            $prodUpdateQry->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
            $prodUpdateQry->execute();
            $prodId = $dbCon->lastInsertId();
        }

        $compRelRow = "select * from retail_app_component_rel where  retail_app_component_id =:compsaveid and retail_app_products_id=:prodid";
        $compRel = $dbCon->prepare($compRelRow);
        $compRel->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
        $compRel->bindParam(':prodid', $prodId, PDO::PARAM_INT);
        $compRel->execute();
        $RetailRelRow = $compRel->fetch(PDO::FETCH_ASSOC);
        if (!empty($RetailRelRow)) {
            $tableId = $RetailRelRow['id'];
            $query = "update  retail_app_component_rel set retail_app_component_id=:compsaveid,retail_app_products_id=:prodid where id=:tableid";
            $compRelUp = $dbCon->prepare($query);
            $compRelUp->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
            $compRelUp->bindParam(':prodid', $prodId, PDO::PARAM_INT);
            $compRelUp->bindParam(':tableid', $tableId, PDO::PARAM_INT);
            $compRelUp->execute();
        } else {
            $compRelUpdate = "insert into retail_app_component_rel (`retail_app_component_id`, `retail_app_products_id`, `created_date`) values (:compid,:prodid, now())";
            $compRelUpdateQ = $dbCon->prepare($compRelUpdate);
            $compRelUpdateQ->bindParam(':compid', $compSave_id, PDO::PARAM_INT);
            $compRelUpdateQ->bindParam(':prodid', $prodId, PDO::PARAM_INT);
            $compRelUpdateQ->execute();
            $compRelUpdateexe = $dbCon->lastInsertId();
        }
    }

    public function bannerUpdate($bannerdata, $app_id, $compType, $compRowId, $compSave_id, $compVal) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ($bannerdata != '' || !empty($bannerdata)) {
            $sqn = 1;
            foreach ($bannerdata as $banner) {

                $compbanner = "select * from app_banners where app_id=:appid and comp_id=:compid and retail_app_componentId=:compsaveid and seq=:seq";
                $con = $dbCon->prepare($compbanner);
                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $con->bindParam(':compid', $compType, PDO::PARAM_INT);
                $con->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
                $con->bindParam(':seq', $sqn, PDO::PARAM_INT);
                $con->execute();
                $Retailbanner = $con->fetch(PDO::FETCH_ASSOC);
                $prodheading = $catheading = $imageUrl = $itemdesc = $itemheading = $imageName = '';
                $isCat = $prodid = $catidbanner = 0;
                if (isset($banner->imagename)) {
                    $imageName = addslashes($banner->imagename);
                }
                if (isset($banner->imageurl)) {
                    $imageUrl = $banner->imageurl;
                }
                if (isset($banner->itemheading)) {
                    $itemheading = addslashes($banner->itemheading);
                }
                if (isset($banner->itemdesc)) {
                    $itemdesc = $banner->itemdesc;
                }
                if (isset($banner->itemcat->itemid)) {
                    $catidbanner = $banner->itemcat->itemid;
                }
                if (isset($banner->itemcat->itemheading)) {
                    $catheading = addslashes($banner->itemcat->itemheading);
                }
                if (isset($banner->itemprod->itemheading)) {
                    $prodheading = addslashes($banner->itemprod->itemheading);
                }
                if (isset($banner->itemprod->itemid)) {
                    $prodid = $banner->itemprod->itemid;
                }
                if ($prodid != 0) {
                    $isCat = 0;
                } elseif ($catidbanner != 0) {
                    $isCat = 1;
                }

                if (!empty($Retailbanner)) {
                    $BannerId = $Retailbanner['id'];
                    $query = "update app_banners set `app_id`=:appid, `comp_id`=:compid, `banner`=:imagename,`heading`=:heading, `subheading`=:itemdesc, `isActive`='1' , `created_date`= now(),retail_app_componentId=:compsaveid,seq=:sqn,banner_url=:bannerurl,`comp_val`=:compid,is_catalogue=:iscat,cat_id=:catidbanner,cat_head=:catheading,prod_id=:prodid,prod_head=:prodheading  where `id`=:id";
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->bindParam(':imagename', $imageName, PDO::PARAM_STR);
                    $con->bindParam(':heading', $itemheading, PDO::PARAM_STR);
                    $con->bindParam(':itemdesc', $itemdesc, PDO::PARAM_STR);
                    $con->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
                    $con->bindParam(':sqn', $sqn, PDO::PARAM_INT);
                    $con->bindParam(':bannerurl', $imageUrl, PDO::PARAM_STR);
                    $con->bindParam(':compid', $compType, PDO::PARAM_INT);
                    $con->bindParam(':catidbanner', $catidbanner, PDO::PARAM_INT);
                    $con->bindParam(':iscat', $isCat, PDO::PARAM_INT);
                    $con->bindParam(':catheading', $catheading, PDO::PARAM_STR);
                    $con->bindParam(':prodid', $prodid, PDO::PARAM_INT);
                    $con->bindParam(':prodheading', $prodheading, PDO::PARAM_STR);
                    $con->bindParam(':id', $BannerId, PDO::PARAM_STR);
                    $con->execute();
                } else {
                    $app_banners_com = "insert into app_banners (`app_id`, `comp_id`, `banner`,`heading`, `subheading`, `isActive` , `created_date`,retail_app_componentId,seq,banner_url,`comp_val`,`is_catalogue`,`cat_id`,`cat_head`,`prod_id`,`prod_head`) values (:appid, :compid, :imagename, :heading, :itemdesc, '1', now(),:compsaveid,:sqn,:bannerurl,:compval,:iscat,:catidbanner,:catheading,:prodid,:prodheading)";
                    $con = $dbCon->prepare($app_banners_com);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->bindParam(':compid', $compType, PDO::PARAM_INT);
                    $con->bindParam(':imagename', $imageName, PDO::PARAM_STR);
                    $con->bindParam(':heading', $itemheading, PDO::PARAM_STR);
                    $con->bindParam(':itemdesc', $itemdesc, PDO::PARAM_STR);
                    $con->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
                    $con->bindParam(':sqn', $sqn, PDO::PARAM_INT);
                    $con->bindParam(':bannerurl', $imageUrl, PDO::PARAM_STR);
                    $con->bindParam(':compval', $compVal, PDO::PARAM_INT);
                    $con->bindParam(':catidbanner', $catidbanner, PDO::PARAM_INT);
                    $con->bindParam(':iscat', $isCat, PDO::PARAM_INT);
                    $con->bindParam(':catheading', $catheading, PDO::PARAM_STR);
                    $con->bindParam(':prodid', $prodid, PDO::PARAM_INT);
                    $con->bindParam(':prodheading', $prodheading, PDO::PARAM_STR);
                    $con->execute();
                    $app_bannerExe = $dbCon->lastInsertId();
                }
                $sqn++;
            }
        }
    }

    public function specialProdUpdate($app_id, $compType, $compVal, $compSave_id, $title_id, $isCat, $prodid, $prodheading, $catheading, $catid) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $compSpecial = "select * from app_special_product where  app_id =:appid and comp_id=:compid and retail_app_componentId=:appcompid";
        $con = $dbCon->prepare($compSpecial);
        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $con->bindParam(':compid', $compType, PDO::PARAM_INT);
        $con->bindParam(':appcompid', $compSave_id, PDO::PARAM_INT);
        $con->execute();
        $RetailSpecial = $con->fetch(PDO::FETCH_ASSOC);
        if (!empty($RetailSpecial)) {
            $splId = $RetailSpecial['id'];
            $app_Specia = "update app_special_product set `app_id`=:appid, `comp_id`=:compid, `app_tag_id`=:titleid,`retail_app_componentId`=:compsaveid,`isActive`=1,is_catalogue=:iscat,cat_id=:catid,cat_head=:catheading,prod_id=:prodid,prod_head=:prodheading where `id`=:id";
            $con = $dbCon->prepare($app_Specia);
            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
            $con->bindParam(':titleid', $title_id, PDO::PARAM_INT);
            $con->bindParam(':compid', $compType, PDO::PARAM_INT);
            $con->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
            $con->bindParam(':iscat', $isCat, PDO::PARAM_INT);
            $con->bindParam(':catid', $catid, PDO::PARAM_INT);
            $con->bindParam(':catheading', $catheading, PDO::PARAM_STR);
            $con->bindParam(':prodid', $prodid, PDO::PARAM_INT);
            $con->bindParam(':prodheading', $prodheading, PDO::PARAM_STR);
            $con->bindParam(':id', $splId, PDO::PARAM_STR);
            $con->execute();
        } else {
            $app_Specials_com = "insert into app_special_product (`app_id`, `comp_id`, `app_tag_id`,`retail_app_componentId`,`isActive`,`is_catalogue`,`cat_id`,`cat_head`,`prod_id`,`prod_head`) values (:appid, :compid, :titleid,:compsaveid,'1',:iscat,:catid,:catheading,:prodid,:prodheading)";
            $con = $dbCon->prepare($app_Specials_com);
            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
            $con->bindParam(':compid', $compType, PDO::PARAM_INT);
            $con->bindParam(':titleid', $title_id, PDO::PARAM_INT);
            $con->bindParam(':compsaveid', $compSave_id, PDO::PARAM_INT);
            $con->bindParam(':iscat', $isCat, PDO::PARAM_INT);
            $con->bindParam(':catid', $catid, PDO::PARAM_INT);
            $con->bindParam(':catheading', $catheading, PDO::PARAM_STR);
            $con->bindParam(':prodid', $prodid, PDO::PARAM_INT);
            $con->bindParam(':prodheading', $prodheading, PDO::PARAM_STR);
            $con->execute();
            $Appexe = $dbCon->lastInsertId();
        }
    }

    public function setretailData($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $authorId = '';
        $custid = '';
		$catid   = $data['catid'];
		$themeid = $data['themeid'];
        try
        {
			if (isset($_SESSION['custid'])) {
				$custid = $_SESSION['custid'];
			}
			if ($custid != '') {
				$query = "select id from author where custid=:custid";
				$con = $dbCon->prepare($query);
				$con->bindParam(':custid', $custid, PDO::PARAM_INT);
				$con->execute();
				$stmtquery = $con->fetch(PDO::FETCH_ASSOC);
				$authorId = $stmtquery['id'];
				//$appname = $results[0]['summary'];
			}
			else{

			   $authorId=$data['author_id']; 
			}


			if ($authorId != '') {
				$platform       = 2;
				$appType        = 1;
				$launchScreenId = 1;
				$imageurl       = '';
				$imagename      = '';
				$review_rating  = 0;
				$model_number   = 0;
				if (isset($data['app_data']))
				{
					$dataJson = json_decode($data['app_data']);
					
					// app properties
					$appName = $appCat = $appTheme = $app_id = $title = '';
					if (isset($dataJson->screen_data->app_type)) {
						$appType = $dataJson->screen_data->app_type;
					}
					if (isset($dataJson->screen_data->logo_dtls->imagename)) {
						$imagename = addslashes($dataJson->screen_data->logo_dtls->imagename);
					}
					if (isset($dataJson->screen_data->logo_dtls->imageurl)) {
						$imageurl = $dataJson->screen_data->logo_dtls->imageurl;
					}
					if (isset($dataJson->screen_data->screen_properties->title)) {
						$appName = addslashes($dataJson->screen_data->screen_properties->title);
					}
					if (isset($dataJson->screen_data->screen_properties->cat_id)) {
						$appCat = $dataJson->screen_data->screen_properties->cat_id;
					}
					// cat id fix if category is not coming
					if($appCat == 0 || $appCat == '')
					{
						$appCat = $catid;
					}
					
					if (isset($dataJson->screen_data->screen_properties->theme_id)) {
						$appTheme = $dataJson->screen_data->screen_properties->theme_id;
					}
					// theme id fix if theme is not coming
					if($appTheme == 0 || $appTheme == '')
					{
						$appTheme = $themeid;
					}
					if (isset($dataJson->screen_data->screen_properties->app_id)) {
						$app_id = $dataJson->screen_data->screen_properties->app_id;
					}
					if (isset($dataJson->screen_data->review_rating) && $dataJson->screen_data->review_rating == true) {
						$review_rating = 1;
					}
					if (isset($dataJson->screen_data->model_number) && $dataJson->screen_data->model_number == true) {
						$model_number = 1;
					}
					if ($appName == '') {
						echo json_encode(array("msg_code" => '502', "msg" => 'Please choose app name '));
						die;
					}
					if ($app_id == '') {
						$checkname = "select * from app_data where summary=:appname and author_id=:authid and deleted!=1";
						$con = $dbCon->prepare($checkname);
						$con->bindParam(':appname', $appName, PDO::PARAM_STR);
						$con->bindParam(':authid', $authorId, PDO::PARAM_INT);
						$con->execute();
						$result_name = $con->fetch(PDO::FETCH_ASSOC);
						$checkresult = count($result_name);
						if (empty($result_name)) {
							$token = bin2hex(openssl_random_pseudo_bytes(18));
							$expiry = date('Y-m-d 00:00:00', strtotime('+30 days', time()));
							$sql = "INSERT INTO app_data(app_id,plan_expiry_date,summary,type_app,author_id,launch_screen_id,splash_screen_id,category,platform,theme,updated,app_version,created,html_updatetime) VALUES (:appid,:expiry,:appname,:apptype,:authid,:launchscreenid,'',:appcat,:platform,:themeid, NOW(),'1.0',NOW(),NOW())";
							$con = $dbCon->prepare($sql);
							$con->bindParam(':appid', $token, PDO::PARAM_STR);
							$con->bindParam(':expiry', $expiry, PDO::PARAM_STR);
							$con->bindParam(':appname', $appName, PDO::PARAM_STR);
							$con->bindParam(':apptype', $appType, PDO::PARAM_INT);
							$con->bindParam(':authid', $authorId, PDO::PARAM_INT);
							$con->bindParam(':launchscreenid', $launchScreenId, PDO::PARAM_INT);
							$con->bindParam(':appcat', $appCat, PDO::PARAM_INT);
							$con->bindParam(':platform', $platform, PDO::PARAM_INT);
							$con->bindParam(':themeid', $appTheme, PDO::PARAM_INT);
							$con->execute();
							$app_id = $dbCon->lastInsertId();

							if (isset($_SESSION['appid'])) {
								unset($_SESSION['appid']);
							}
						} else {
							$app_id = $result_name['id'];
						}
					} else {
						$checkname = "select * from app_data where summary=:appname and author_id=:authid and id !=:id and deleted!=1";
						$con = $dbCon->prepare($checkname);
						$con->bindParam(':appname', $appName, PDO::PARAM_STR);
						$con->bindParam(':authid', $authorId, PDO::PARAM_INT);
						$con->bindParam(':id', $app_id, PDO::PARAM_INT);
						$con->execute();
						$result_name = $con->fetch(PDO::FETCH_ASSOC);
						$checkresult = count($result_name);
						if (!empty($result_name)) {
							$sql = "UPDATE app_data SET summary = :appname,category=:appcat,platform = :platform,type_app=:apptype,updated =  NOW(),html_updatetime=now() WHERE id = :appid and deleted !=1";
							$con = $dbCon->prepare($sql);
							$con->bindParam(':appname', $appName, PDO::PARAM_STR);
							$con->bindParam(':appcat', $appCat, PDO::PARAM_INT);
							$con->bindParam(':platform', $platform, PDO::PARAM_INT);
							$con->bindParam(':apptype', $appType, PDO::PARAM_INT);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						} else {
							$sql = "UPDATE app_data SET platform = :platform,category=:appcat,type_app=:apptype,updated =  NOW(),html_updatetime=now() WHERE id = :appid and deleted !=1";
							$con = $dbCon->prepare($sql);
							$con->bindParam(':appcat', $appCat, PDO::PARAM_INT);
							$con->bindParam(':platform', $platform, PDO::PARAM_INT);
							$con->bindParam(':apptype', $appType, PDO::PARAM_INT);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						}
					}
					$_SESSION['appid'] = $app_id;

					$is_contactus = $is_order = $feedback = $tnc = $curr_id = 0;
					$is_contactusData = $orderconfirm_email = $package = $orderconfirm_no = $feedback_no = $feedback_email = $contactus_no = $contactus_email = $tnc_link = $discount_color = $text_color = $background_color = '';
					if (isset($dataJson->screen_data->defaultcurrency)) {
						$curr_id = $dataJson->screen_data->defaultcurrency;
					}
					if (isset($dataJson->screen_data->screen_properties->title)) {
						$title = addslashes($dataJson->screen_data->screen_properties->title);
					}
					if (isset($dataJson->screen_data->screen_properties->background_color)) {
						$background_color = $dataJson->screen_data->screen_properties->background_color;
					}

					if (isset($dataJson->screen_data->screen_properties->font_color)) {
						$text_color = $dataJson->screen_data->screen_properties->font_color;
					}
					if (isset($dataJson->screen_data->screen_properties->discount_color)) {
						$discount_color = $dataJson->screen_data->screen_properties->discount_color;
					}

					if (isset($dataJson->screen_data->tnc->tnc_email)) {
						$tnc_link = addslashes($dataJson->screen_data->tnc->tnc_email);
					}
					if (isset($dataJson->screen_data->tnc->is_tnc)) {
						$is_tnc = $dataJson->screen_data->tnc->is_tnc;
						if ($is_tnc = true) {
							$tnc = 1;
						}
					}

					if (isset($dataJson->screen_data->feedback_dtls->is_feedback)) {
						$is_feedback = $dataJson->screen_data->feedback_dtls->is_feedback;
						if ($is_feedback == true) {
							$feedback = 1;
						}
					}

					if (isset($dataJson->screen_data->feedback_dtls->feedback_email)) {
						$feedback_email = $dataJson->screen_data->feedback_dtls->feedback_email;
					}
					if (isset($dataJson->screen_data->feedback_dtls->feedback_no)) {
						$feedback_no = $dataJson->screen_data->feedback_dtls->feedback_no;
					}


					if (isset($dataJson->screen_data->contact_details->contact_email)) {
						$contactus_email = $dataJson->screen_data->contact_details->contact_email;
					}
					if (isset($dataJson->screen_data->contact_details->contact_phone)) {
						$contactus_no = $dataJson->screen_data->contact_details->contact_phone;
					}
					if (isset($dataJson->screen_data->contact_details->is_contactus)) {
						$is_contactusData = $dataJson->screen_data->contact_details->is_contactus;
						if ($is_contactusData == true) {
							$is_contactus = 1;
						}
					}
					if (isset($dataJson->screen_data->order_dtls->is_order)) {
						$is_orderStat = $dataJson->screen_data->order_dtls->is_order;
						if ($is_orderStat == true) {
							$is_order = 1;
						}
					}
					if (isset($dataJson->screen_data->order_dtls->package)) {
						$package = $dataJson->screen_data->order_dtls->package;
					}
					if (isset($dataJson->screen_data->order_dtls->orderconfirm_email)) {
						$orderconfirm_email = $dataJson->screen_data->order_dtls->orderconfirm_email;
					}
					if (isset($dataJson->screen_data->order_dtls->orderconfirm_no)) {
						$orderconfirm_no = $dataJson->screen_data->order_dtls->orderconfirm_no;
					}

					$checkAttr = "select * from app_catalogue_attr where app_id=:appid";
					$con = $dbCon->prepare($checkAttr);
					$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
					$con->execute();
					$resultAttr = $con->fetch(PDO::FETCH_ASSOC);

					$ca = 0;
					$cr_date = date('Y-m-d h:i:s');
					if ($resultAttr != '' || !empty($resultAttr)) {
						$appCatAttr = "update app_catalogue_attr set `title`=:title, `background_color`=:background_color, `text_color`=:text_color, `discount_color`=:discount_color, `is_feedback`=:feedback,`feedback_email`=:feedback_email,`feedback_no`=:feedback_no,`is_contactus`=:is_contactus, `contactus_email`=:contactus_email, `contactus_no`=:contactus_no,`is_tnc`=:tnc, `tnc_link`=:tnc_link,`is_order`=:is_order,`logo_link`=:imageurl,`package`=:package, `orderconfirm_email`=:orderconfirm_email,`orderconfirm_no`=:orderconfirm_no,`curr_id`=:curr_id,`is_review_rating`=:is_review_rating,`is_model_number`=:is_model_number where `app_id`=:appid";
						$con = $dbCon->prepare($appCatAttr);
						$con->bindParam(':title', $title, PDO::PARAM_STR);
						$con->bindParam(':background_color', $background_color, PDO::PARAM_STR);
						$con->bindParam(':text_color', $text_color, PDO::PARAM_STR);
						$con->bindParam(':discount_color', $discount_color, PDO::PARAM_STR);
						$con->bindParam(':feedback', $feedback, PDO::PARAM_INT);
						$con->bindParam(':feedback_email', $feedback_email, PDO::PARAM_STR);
						$con->bindParam(':feedback_no', $feedback_no, PDO::PARAM_STR);
						$con->bindParam(':is_contactus', $is_contactus, PDO::PARAM_INT);
						$con->bindParam(':contactus_email', $contactus_email, PDO::PARAM_STR);
						$con->bindParam(':contactus_no', $contactus_no, PDO::PARAM_STR);
						$con->bindParam(':tnc', $tnc, PDO::PARAM_STR);
						$con->bindParam(':tnc_link', $tnc_link, PDO::PARAM_STR);
						$con->bindParam(':is_order', $is_order, PDO::PARAM_STR);
						$con->bindParam(':imageurl', $imageurl, PDO::PARAM_STR);
						$con->bindParam(':package', $package, PDO::PARAM_STR);
						$con->bindParam(':orderconfirm_email', $orderconfirm_email, PDO::PARAM_STR);
						$con->bindParam(':orderconfirm_no', $orderconfirm_no, PDO::PARAM_STR);
						$con->bindParam(':curr_id', $curr_id, PDO::PARAM_INT);
						$con->bindParam(':is_review_rating', $review_rating, PDO::PARAM_INT);
						$con->bindParam(':is_model_number', $model_number, PDO::PARAM_INT);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
					} else {
						$appCatAttr = "insert into app_catalogue_attr (`app_id`, `created`, `catalogue_type`, `title`, `background_color`, `text_color`, `discount_color`,`is_feedback`, `feedback_email`,`feedback_no`,`is_contactus`, `contactus_email`, `contactus_no`,`is_tnc` ,`tnc_link`,`is_order`,`logo_link`,`package`,`orderconfirm_email` ,`orderconfirm_no`,`curr_id` ,`is_review_rating` ,`is_model_number`) values (:appid, :cr_date , '', :title, :background_color, :text_color, :discount_color,:feedback,:feedback_email,:feedback_no, :is_contactus,:contactus_email, :contactus_no,:tnc,:tnc_link,:is_order,:imageurl,:package,:orderconfirm_email,:orderconfirm_no,:curr_id, :is_review_rating, :is_model_number)";
						$con = $dbCon->prepare($appCatAttr);
						$con->bindParam(':title', $title, PDO::PARAM_STR);
						$con->bindParam(':cr_date', $cr_date, PDO::PARAM_STR);
						$con->bindParam(':background_color', $background_color, PDO::PARAM_STR);
						$con->bindParam(':text_color', $text_color, PDO::PARAM_STR);
						$con->bindParam(':discount_color', $discount_color, PDO::PARAM_STR);
						$con->bindParam(':feedback', $feedback, PDO::PARAM_INT);
						$con->bindParam(':feedback_email', $feedback_email, PDO::PARAM_STR);
						$con->bindParam(':feedback_no', $feedback_no, PDO::PARAM_STR);
						$con->bindParam(':is_contactus', $is_contactus, PDO::PARAM_INT);
						$con->bindParam(':contactus_email', $contactus_email, PDO::PARAM_STR);
						$con->bindParam(':contactus_no', $contactus_no, PDO::PARAM_STR);
						$con->bindParam(':tnc', $tnc, PDO::PARAM_STR);
						$con->bindParam(':tnc_link', $tnc_link, PDO::PARAM_STR);
						$con->bindParam(':is_order', $is_order, PDO::PARAM_STR);
						$con->bindParam(':imageurl', $imageurl, PDO::PARAM_STR);
						$con->bindParam(':package', $package, PDO::PARAM_STR);
						$con->bindParam(':orderconfirm_email', $orderconfirm_email, PDO::PARAM_STR);
						$con->bindParam(':orderconfirm_no', $orderconfirm_no, PDO::PARAM_STR);
						$con->bindParam(':curr_id', $curr_id, PDO::PARAM_INT);
						$con->bindParam(':is_review_rating', $review_rating, PDO::PARAM_INT);
						$con->bindParam(':is_model_number', $model_number, PDO::PARAM_INT);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
						$appCatAttrExe = $dbCon->lastInsertId();
					}

					if (!empty($dataJson->screen_data->comp_array)) {
						$checkRetailComp = "select * from retail_app_component where  app_id =:appid";
						$con = $dbCon->prepare($checkRetailComp);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
						$resultRetailComp = $con->fetch(PDO::FETCH_ASSOC);

						if (!empty($resultRetailComp)) {
							$query = "update  retail_app_component set isActive=0 where app_id=:appid";
							$con = $dbCon->prepare($query);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						}

						$checkRetailComp = "select * from retail_app_products where  app_id =:appid";
						$con = $dbCon->prepare($checkRetailComp);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
						$resultRetailComp = $con->fetch(PDO::FETCH_ASSOC);

						if (!empty($resultRetailComp)) {
							$queryProd = "update  retail_app_products set isActive=0 where app_id=:appid";
							$con = $dbCon->prepare($queryProd);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						}


						$checkRetailComp = "select * from app_banners where  app_id =:appid";
						$con = $dbCon->prepare($checkRetailComp);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
						$resultRetailComp = $con->fetch(PDO::FETCH_ASSOC);

						if (!empty($resultRetailComp)) {
							$queryBanner = "update  app_banners set isActive=0 where app_id=:appid";
							$con = $dbCon->prepare($queryBanner);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						}

						$checkRetailSpl = "select * from app_special_product where  app_id =:appid";
						$con = $dbCon->prepare($checkRetailSpl);
						$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
						$con->execute();
						$resultRetailSpl = $con->fetch(PDO::FETCH_ASSOC);
						if (!empty($resultRetailSpl)) {
							$querySpl = "update  app_special_product set isActive=0 where app_id=:appid";
							$con = $dbCon->prepare($querySpl);
							$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
							$con->execute();
						}

						$productType1 = array(101, 102, 103);
						$productType2 = array(109, 110);
						$bannersType = array(106, 107, 108, 111, 116, 117, 118);
						$specialType = array(104, 105, 112, 113, 114, 115);
						$seq = 1;
						foreach ($dataJson->screen_data->comp_array as $val) {


							$catheading = $prodheading = $titlename = $titleid = $compvalname = $title_id = $compRowId = $compId = $compType = $compVal = '';
							$isCat = $catid = $prodid = 0;
							if (isset($val->comp_type)) {
								$compType = $val->comp_type;
							}
							if (isset($val->comp_val)) {
								$compVal = $val->comp_val;
							}
							if (isset($val->comp_id)) {
								$compId = $val->comp_id;
							}

							if (isset($val->comp_row_id)) {
								$compRowId = $val->comp_row_id;
							}
							if ($compRowId == 'new') {
								$compRowId = 0;
							}
							if (isset($val->title_id)) {
								$title_id = $val->title_id;
							}
							if (isset($val->comp_val_name)) {
								$compvalname = addslashes($val->comp_val_name);
							}
							if (isset($val->title->name)) {
								$titlename = addslashes($val->title->name);
							}
							if (isset($val->title->id)) {
								$titleid = $val->title->id;
							}
							if (isset($val->itemcat->itemid)) {
								$catid = $val->itemcat->itemid;
							}
							if (isset($val->itemcat->itemheading)) {
								$catheading = addslashes($val->itemcat->itemheading);
							}
							if (isset($val->itemprod->itemheading)) {
								$prodheading = addslashes($val->itemprod->itemheading);
							}
							if (isset($val->itemprod->itemid)) {
								$prodid = $val->itemprod->itemid;
							}
							if ($prodid != 0) {
								$isCat = 0;
							} elseif ($catid != 0) {
								$isCat = 1;
							}

							if ($compType != '') {
								$compSave_id = '';
								$checkCompType = "select * from retail_app_component where  app_id =:appid and component_id=:comptype and id=:comprowid";
								$con = $dbCon->prepare($checkCompType);
								$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
								$con->bindParam(':comptype', $compType, PDO::PARAM_INT);
								$con->bindParam(':comprowid', $compRowId, PDO::PARAM_INT);
								$con->execute();
								$resultCompType = $con->fetch(PDO::FETCH_ASSOC);

								if (!empty($resultCompType)) {
									$query = "update  retail_app_component set isActive=1,`sort_order`=:seq,`comp_val_name`=:compvalname,title=:titlename,title_id=:titleid where app_id=:appid and id=:compRowId";
									$con = $dbCon->prepare($query);
									$con->bindParam(':seq', $seq, PDO::PARAM_INT);
									$con->bindParam(':compvalname', $compvalname, PDO::PARAM_STR);
									$con->bindParam(':titlename', $titlename, PDO::PARAM_STR);
									$con->bindParam(':titleid', $titleid, PDO::PARAM_INT);
									$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
									$con->bindParam(':compRowId', $compRowId, PDO::PARAM_INT);
									$con->execute();
									$compSave_id = $resultCompType['id'];
								} else {
									$compSave = "insert into retail_app_component (`app_id`, `component_id`, `sort_order`,`isActive`, `created_date`,`comp_val_name`,title,title_id) values (:appid, :compType,:seq, '1',:cr_date,:compvalname,:titlename,:titleid)";
									$con = $dbCon->prepare($compSave);
									$con->bindParam(':seq', $seq, PDO::PARAM_INT);
									$con->bindParam(':compType', $compType, PDO::PARAM_INT);
									$con->bindParam(':cr_date', $cr_date, PDO::PARAM_STR);
									$con->bindParam(':compvalname', $compvalname, PDO::PARAM_STR);
									$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
									$con->bindParam(':titlename', $titlename, PDO::PARAM_INT);
									$con->bindParam(':titleid', $titleid, PDO::PARAM_INT);
									$con->execute();
									$compSave_id = $dbCon->lastInsertId();
								}


								if (in_array($compType, $productType1)) {
									$type = 1;
									$this->productsUpdate($app_id, $compVal, $type, $compSave_id, $isCat, $prodid, $prodheading, $catheading, $catid);
								} elseif (in_array($compType, $productType2)) {
									$type = 2;
									$this->productsUpdate($app_id, $compVal, $type, $compSave_id, $isCat, $prodid, $prodheading, $catheading, $catid);
								} elseif (in_array($compType, $bannersType)) {

									$bannerdata = $val->elements->element_array;


									$this->bannerUpdate($bannerdata, $app_id, $compType, $compRowId, $compSave_id, $compVal);
								} elseif (in_array($compType, $specialType)) {
									$this->specialProdUpdate($app_id, $compType, $compVal, $compSave_id, $titleid, $isCat, $prodid, $prodheading, $catheading, $catid);
								}
							}    //compType loop ends here    

							$seq++;
						}
					}
				}
				echo json_encode(array("msg_code" => '200', "app_id" => $_SESSION['appid']));
			} else {
				echo json_encode(array("msg_code" => '501', "msg" => 'Please login '));
			}
        } catch (Exception $ex) {
			echo json_encode(array("msg_code" => '500',"msg"=>'oops!! Something went wrong'));
        }
    }

	public function getCatalogLaunchData($data)
	{   
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $authorId = '';
        $custid = '';

        if (isset($_SESSION['custid'])) {
            $custid = $_SESSION['custid'];
        }
        if ($custid != '') {
            $query = "select id from author where custid=:custid";
            $con = $dbCon->prepare($query);
            $con->bindParam(':custid', $custid, PDO::PARAM_INT);
            $con->execute();
            $stmtquery = $con->fetch(PDO::FETCH_ASSOC);
             $authorId = $stmtquery['id'];
            
            //$appname = $results[0]['summary'];
        }
        if ($authorId != '') {
            if ((isset($data['app_id']) && trim($data['app_id']) != '')) {
                $app_idString = $data['app_id'];
                $appQueryData = "select * from app_data where id=:appid";
                $con = $dbCon->prepare($appQueryData);
                $con->bindParam(':appid', $app_idString, PDO::PARAM_INT);
                $con->execute();
                $app_screenData = $con->fetch(PDO::FETCH_ASSOC);
                if ($app_screenData != '') {
                    $app_id = $app_screenData['id'];
                    $app_type_app = $app_screenData['type_app'];
                    $baseUrl = base_image_url();
                    $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id`,`is_feedback`,`feedback_email`,`feedback_no`,`is_order`,`package`,`orderconfirm_email`,`orderconfirm_no`,`text_color`,`discount_color`,`logo_link` FROM `app_catalogue_attr` WHERE `app_id`=:appid";
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->execute();
                    $launchdata = $con->fetch(PDO::FETCH_ASSOC);
                    $out = array();
                    if (!empty($launchdata)) {
                        $i = 0;
                        foreach ($launchdata as $key => $subarr) {
                            if ($key != 'title' && $key != 'background_color' && $key != 'app_tag_id') {
                                $out[$i][$key] = $subarr;
                                $i++;
                            }
                        }
                    }

                    $bannerdata = array();
                    $categorydata = array();
                    $showcasedata = array();

                    // ga tracking id
                    $ga_tracking = '';
                    $merchent_id = '';
                    $get_user_sql = "SELECT * FROM author WHERE id = :authid";
                    $con = $dbCon->prepare($get_user_sql);
                    $con->bindParam(':authid', $app_screenData['author_id'], PDO::PARAM_INT);
                    $con->execute();
                    $get_user = $con->fetch(PDO::FETCH_ASSOC);
                   
                    if (!empty($get_user)) {
                        $get_ocuser_sql = "SELECT v.merchant_id FROM oc_user u LEFT JOIN oc_vendors v ON v.user_id=u.user_id WHERE u.email = :email";
                        $con = $dbConRetail->prepare($get_ocuser_sql);
                        $con->bindParam(':email', $get_user['email_address'], PDO::PARAM_STR);
                        $con->execute();
                        $get_ocuser = $con->fetch(PDO::FETCH_ASSOC);
                   
                        if (!empty($get_ocuser) && $get_ocuser['merchant_id'] != '') {
                            $merchent_id = $get_ocuser['merchant_id'];
                        }
                    }

                    // admob data
                    $admob_id = '';
                    $get_mob_sql = "SELECT * FROM plans WHERE id = :planid";
                    $con = $dbCon->prepare($get_mob_sql);
                    $con->bindParam(':planid', $app_screenData['plan_id'], PDO::PARAM_INT);
                    $con->execute();
                    $get_admob = $con->fetch(PDO::FETCH_ASSOC);

                    $adData = array();
                    if (!empty($get_admob)) {
                        if (strtolower($get_admob['plan_name']) == 'basic') {
                            $tadData = array(
                                "app_id" => $app_idString,
                                "servername" => "admob",
                                "published_id" => 123,
                                "ad_serverid" => 1,
                                "unit_id" => "ca-app-pub-9311832623123794/2410122466"
                            );
                            $adData[] = $tadData;
                        } elseif (strtolower($get_admob['plan_name']) == 'advanced') {
                            $get_adserver_sql = "SELECT ad.published_id, ad.unit_id, aas.servername, ad.ad_serverid FROM app_adserver_details aad
											LEFT JOIN adserver_details ad ON aad.adserver_details_id = ad.id
											LEFT JOIN adserver aas ON ad.ad_serverid = aas.id
											WHERE aad.app_id = :appid";

                            $con = $dbCon->prepare($get_adserver_sql);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->execute();
                            $get_adserver = $con->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($get_adserver)) {
                                foreach ($get_adserver as $gaser) {
                                    $tadData = array(
                                        "app_id" => $app_idString,
                                        "servername" => $gaser['servername'],
                                        "published_id" => $gaser['published_id'],
                                        "ad_serverid" => $gaser['ad_serverid'],
                                        "unit_id" => $gaser['unit_id']
                                    );
                                    $adData[] = $tadData;
                                }
                            }
                        }
                    } else {
                        $tadData = array(
                            "app_id" => $app_idString,
                            "servername" => "admob",
                            "published_id" => 123,
                            "ad_serverid" => 1,
                            "unit_id" => "ca-app-pub-9311832623123794/2410122466"
                        );
                        $adData[] = $tadData;
                    }

                    // terms and conditions
                    $authorQuery00 = "select * from app_catalogue_attr where app_id=:appid";
                    $con = $dbCon->prepare($authorQuery00);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->execute();
                    $appctlgattr = $con->fetch(PDO::FETCH_ASSOC);


                    $getauthor = 0;
					$tnc = '';
                    if (!empty($appctlgattr)) {
                        if ($appctlgattr['is_tnc'] == 0 || $appctlgattr['is_tnc'] == '') {
                            $tnc = '';
                        } else {
                            $tnc = $appctlgattr['tnc_link'];
                        }
                    }
                    if ($tnc == '') {
                        $is_tnc = false;
                    } else {
                        $is_tnc = true;
                    }
                    // contact us details
                    $getauthor = 0;
                    if (!empty($appctlgattr)) {
                        if ($appctlgattr['is_contactus'] == 0) {
                            $contactarr = array(
                                'contact_email' => $appctlgattr['contactus_email'],
                                'contact_phone' => $appctlgattr['contactus_no'],
                                'is_contactus' => false);
                        } else {
                            $contactarr = array(
                                'contact_email' => $appctlgattr['contactus_email'],
                                'contact_phone' => $appctlgattr['contactus_no'],
                                'is_contactus' => true
                            );
                        }
                    } else {
                        $contactarr = array(
                            'contact_email' => $appctlgattr['contactus_email'],
                            'contact_phone' => $appctlgattr['contactus_no'],
                            'is_contactus' => false
                        );
                    }

                    // font & discount color
                    $fontcolor = "#7d7d7d";
                    $disccolor = "#FF0000";
                    if (!empty($appctlgattr)) {
                        if ($appctlgattr['text_color'] != '') {
                            $fontcolor = $appctlgattr['text_color'];
                        }

                        if ($appctlgattr['discount_color'] != '') {
                            $disccolor = $appctlgattr['discount_color'];
                        }
                    }

                    // currancy
                    $curr_id = 4;
                    if (!empty($appctlgattr)) {
                        if ($appctlgattr['curr_id'] != '') {
                            $curr_id = $appctlgattr['curr_id'];
                        }
                    }

                    // default currency
                    $defaultcurrquery = "SELECT * FROM oc_currency WHERE currency_id=:currid";
                    $con = $dbConRetail->prepare($defaultcurrquery);
                    $con->bindParam(':currid', $curr_id, PDO::PARAM_INT);
                    $con->execute();
                    $defaultcurrency = $con->fetch(PDO::FETCH_ASSOC);



                    $defaultcurrencyArr = array();
                    $defaultcurrencyArr['currency_id'] = $defaultcurrency['currency_id'];
                    $defaultcurrencyArr['title'] = $defaultcurrency['title'];
                    $defaultcurrencyArr['code'] = $defaultcurrency['code'];
                    $defaultcurrencyArr['symbol_left']  = $defaultcurrency['symbol_left'];
                    $defaultcurrencyArr['symbol_right'] = $defaultcurrency['symbol_right'];

                    if ($defaultcurrency['symbol_left'] != '') {
                        $defaultcurrencyArr['symbol'] = $defaultcurrency['symbol_left'];
                        $defaultcurrencyArr['is_left'] = 1;
                    } else {
                        $defaultcurrencyArr['symbol'] = $defaultcurrency['symbol_right'];
                        $defaultcurrencyArr['is_left'] = 0;
                    }
                    $defaultcurrencyArr['conversion_value'] = round($defaultcurrency['value'], 2);

                    if ($defaultcurrencyArr['code'] == 'GBP') {
                        $defaultcurrencyArr['symbol_code'] = 'e803';
                        $defaultcurrencyArr['symbol_code_discount'] = 'e803';
                    } elseif ($defaultcurrencyArr['code'] == 'USD' || $defaultcurrencyArr['code'] == 'SGD') {
                        $defaultcurrencyArr['symbol_code'] = 'e800';
                        $defaultcurrencyArr['symbol_code_discount'] = 'e800';
                    } elseif ($defaultcurrencyArr['code'] == 'EUR') {
                        $defaultcurrencyArr['symbol_code'] = 'e801';
                        $defaultcurrencyArr['symbol_code_discount'] = 'e801';
                    } elseif ($defaultcurrencyArr['code'] == 'INR') {
                        $defaultcurrencyArr['symbol_code'] = 'e802';
                        $defaultcurrencyArr['symbol_code_discount'] = 'e802';
                    }

                    // theme layout
                    $themelayoutsql = "SELECT * FROM retail_app_component WHERE app_id=:appid AND isActive = 1 ORDER BY sort_order";
                    $con = $dbCon->prepare($themelayoutsql);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->execute();
                    $themelayout = $con->fetchAll(PDO::FETCH_ASSOC);
                  
                    $comp_array = array();
                    $compVal = '';
                    if (!empty($themelayout))
					{
                        foreach ($themelayout as $layout)
						{
                            if ($layout['component_id'] == 101 || $layout['component_id'] == 102 || $layout['component_id'] == 103) // for all categories
							{ 
                                $cquery = "SELECT rap.* FROM retail_app_products rap 
											LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
											LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
											WHERE rap.app_id = :appid AND rap.isActive=1 
											AND rap.type = 1
											AND rac.component_id = :compid AND rap.retail_app_component_id = :rcompid";
                                $con = $dbCon->prepare($cquery);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                                $con->bindParam(':rcompid', $layout['id'], PDO::PARAM_INT);
                                $con->execute();
                                $catlist = $con->fetch(PDO::FETCH_ASSOC);

                                $finalcats = array();
                                if(!empty($catlist))
								{
                                    if(isset($catlist['cat_id']) && $compVal = $catlist['cat_id'] != '')
									{
                                        $compVal = $catlist['cat_id'];
                                    }

									$catdata = $this->getCurrentCatChild($app_id, $catlist['cat_id'], 4);

                                    $producttag_id = '';
                                    if (!empty($catdata))
									{
                                        foreach ($catdata as $tcdata)
										{
											$tcdata['itemheading'] = html_entity_decode($tcdata['itemheading']);
											$tcdata['itemdesc']    = html_entity_decode($tcdata['itemdesc']);
                                            
											// checking if current category is having child category or not
                                            $cqueryp = "SELECT count(*) as totalcount FROM oc_category WHERE parent_id = :id";
                                            $con = $dbConRetail->prepare($cqueryp);
                                            $con->bindParam(':id', $tcdata['itemid'], PDO::PARAM_INT);
                                            $con->execute();
                                            $ischild = $con->fetch(PDO::FETCH_ASSOC);
                                            
											if (!empty($ischild) && $ischild['totalcount'] > 0)
											{
                                                $tcdata['is_child_category'] = 1;
                                            }
											else
											{
                                                $tcdata['is_child_category'] = 0;
                                            }

                                            foreach ($tcdata as $kk => $perct)
											{
                                                if ($kk == 'imageurl') 
												{
                                                    $author_id = $app_screenData['author_id'];
                                                    $authorsql = "SELECT email_address FROM author WHERE id = :id";
                                                    $con = $dbCon->prepare($authorsql);
                                                    $con->bindParam(':id', $author_id, PDO::PARAM_INT);
                                                    $con->execute();
                                                    $authordata = $con->fetch(PDO::FETCH_ASSOC);

                                                    $userquery = "SELECT user_id FROM oc_user WHERE email =:email";
                                                    $con = $dbConRetail->prepare($userquery);
                                                    $con->bindParam(':email', $authordata['email_address'], PDO::PARAM_STR);
                                                    $con->execute();
                                                    $ocuserdata = $con->fetch(PDO::FETCH_ASSOC);

                                                    $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = :appid AND category_id = :catid AND vendor_id = :vendorid";
                                                    $con = $dbConRetail->prepare($vcimgquery);
                                                    $con->bindParam(':appid', $app_id, PDO::PARAM_STR);
                                                    $con->bindParam(':catid', $tcdata['itemid'], PDO::PARAM_INT);
                                                    $con->bindParam(':vendorid', $ocuserdata['user_id'], PDO::PARAM_INT);
                                                    $con->execute();
                                                    $catimgdata = $con->fetch(PDO::FETCH_ASSOC);
                                                    
													if (!empty($catimgdata))
													{
														if($catimgdata['image'] != '')
														{
															if(@getimagesize($catimgdata['image']))
															{
																$img_url = $catimgdata['image'];
															}
															else
															{
																$img_url = base_image_url() . 'data/' . $authordata['email_address'] . '/' . $app_id . '/' . $catimgdata['image'];
															}
														}
														else
														{
															$img_url = baseUrlWeb().'images-new/theme.png';
														}
                                                        $tcdata[$kk] = $img_url;
                                                        list($width, $height, $type, $attr) = getimagesize($img_url);

                                                        $tcdata['image_height'] = $height;
                                                        $tcdata['image_width'] = $width;
                                                    }
													else
													{
														$img_url     = baseUrlWeb().'images-new/theme.png';
                                                        $tcdata[$kk] = $img_url;
                                                        list($width, $height, $type, $attr) = getimagesize($img_url);

                                                        $tcdata['image_height'] = $height;
                                                        $tcdata['image_width'] = $width;
                                                    }
                                                }
												else
												{
                                                    $tcdata[$kk] = $perct;
                                                }
                                            }
                                            $finalcats[] = $tcdata;
                                        }
                                    }
                                }

								// setting component properties
                                if ($layout['component_id'] == 101)
								{
                                    $span = 1;
                                    $scroll_type = 1;
                                    $view_all = 0;
                                }
								elseif ($layout['component_id'] == 102)
								{
                                    $span = 1;
                                    $scroll_type = 1;
                                    $view_all = 0;
                                } 
								elseif ($layout['component_id'] == 103) 
								{
                                    $span = 2;
                                    $scroll_type = 0;
                                    $view_all = 1;
                                }

                                $comp_array[] = array(
                                    "title" => array("name" => $layout['title'], "id" => $layout['title_id']),
                                    "comp_id" => $layout['sort_order'],
                                    "comp_type" => $layout['component_id'],
                                    "comp_row_id" => $layout['id'],
                                    "comp_properties" => array(
                                      "span" => $span,
                                      "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                      "view_all" => $view_all
                                    ),
                                    "comp_val" => $compVal,
                                    "elements" => array(
                                        "element_count" => count($finalcats),
                                        "element_array" => $finalcats
                                    ),
                                    "comp_val_name" => $layout['comp_val_name'],
                                    "itemcat" => array("itemid" => $catlist['cat_id'], "itemheading" => $catlist['cat_head'], "parent_id" => "", "sort_order" => ""),
                                    "itemprod" => array("itemheading" => $catlist['prod_head'], "imageurl" => "", "itemid" => $catlist['prod_id'], "actualprice" => "", "price" => "", "addedToCart" => 0, "addedToWishlist" => 0, "discount" => "")
                                );
                            }
                            elseif ($layout['component_id'] == 104 || $layout['component_id'] == 105 || $layout['component_id'] == 112 || $layout['component_id'] == 113 || $layout['component_id'] == 114 || $layout['component_id'] == 115) // for showcase products
							{
                                $baseUrl     = base_image_url();
                                $itemHeading = $prodHeading = $prodId = $itemId = '';
                                
								$query = "SELECT * FROM `app_special_product` WHERE `app_id`=:appid AND `comp_id`=:compid AND retail_app_componentId = :retailcompid";
                                $con = $dbCon->prepare($query);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                                $con->bindParam(':retailcompid', $layout['id'], PDO::PARAM_INT);
                                $con->execute();
                                $launchdata = $con->fetch(PDO::FETCH_ASSOC);
								
                                // product tagging
                                $producttag = '';
                                if (!empty($launchdata))
								{
                                    $itemId      = $launchdata['cat_id'];
                                    $itemHeading = $launchdata['cat_head'];
                                    $prodHeading = $launchdata['prod_head'];
                                    $prodId      = $launchdata['prod_id'];
                                    if ($launchdata['app_tag_id'])
									{
                                        $compVal = $launchdata['app_tag_id'];
                                    }
									else
									{
                                        $compVal = '';
                                    }

                                    $querytag = "SELECT * FROM oc_retail_app_tag WHERE id=:id";
                                    $con = $dbConRetail->prepare($querytag);
                                    $con->bindParam(':id', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                    $con->execute();
                                    $apptag = $con->fetch(PDO::FETCH_ASSOC);

									if (!empty($apptag))
									{
                                        $producttag = $apptag['tag_name'];
                                    }

									// showcase products
                                    if ($launchdata['app_tag_id'] == 6) // discounted products
									{   
                                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice, ops.price AS special_price, ops.discount
													FROM oc_app_id ai 
													JOIN oc_product p ON ai.product_id=p.product_id
													LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
													LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
													LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
													LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
													WHERE ai.app_id=:appid AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";
                                        $con = $dbConRetail->prepare($cquery);
                                        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                        $con->execute();
                                        $showcase = $con->fetchAll(PDO::FETCH_ASSOC);
                                    }
									else // other than discounted products
									{
                                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice, ops.price AS special_price, ops.discount
													FROM oc_app_id ai 
													JOIN oc_product p ON ai.product_id=p.product_id
													LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
													LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
													LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
													LEFT JOIN oc_product_specs opps ON p.product_id=opps.product_id
													LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
													WHERE ai.app_id=:appid AND opps.app_tag_id = :tagid AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";

                                        $con = $dbConRetail->prepare($cquery);
                                        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                        $con->bindParam(':tagid', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                        $con->execute();
                                        $tshowcase = $con->fetchAll(PDO::FETCH_ASSOC);

                                        $showcase = $tshowcase;
                                    }

                                    $showcasedata = array();
                                    if (!empty($showcase))
									{
                                        foreach ($showcase as $caseshow)
										{
                                            foreach ($caseshow as $kkk => $val)
											{
                                                if ($kkk == 'imageurl')
												{
													if($val != '')
													{
														if(@getimagesize($val))
														{
															$image_url = $val;
														}
														else
														{
															$image_url = $baseUrl . $val;
														}
														$caseshow[$kkk] = $image_url;
													}
													else
													{
														$img_url = baseUrlWeb().'images-new/theme.png';
														$caseshow[$kkk] = $image_url;
													}

													if ($layout['component_id'] == 104)
													{
														$height = 980;
														$width = 640;
													}
													elseif ($layout['component_id'] == 105 || $layout['component_id'] == 112 || $layout['component_id'] == 113 || $layout['component_id'] == 114 || $layout['component_id'] == 115)
													{
														$height = 1080;
														$width = 1080;
													}
													$caseshow['image_height'] = $height;
													$caseshow['image_width']  = $width;
                                                }
                                            }
											
											$caseshow['special_price'] = $caseshow['special_price'] != '' ? $caseshow['special_price'] : $caseshow['actualprice'];
											$caseshow['discount']      = $caseshow['discount'] > 0 ? $caseshow['discount']."% off" : "";
											$caseshow['symbol_left']   = $defaultcurrencyArr['symbol_left'];
											$caseshow['symbol_right']  = $defaultcurrencyArr['symbol_right'];
                                            $showcasedata[] = $caseshow;
                                        }
                                    }
                                }

                                // setting component properties
                                if ($layout['component_id'] == 104)
								{
                                    $span = 1;
                                    $scroll_type = 1;
                                    $view_all = 0;
                                }
								elseif ($layout['component_id'] == 105 || $layout['component_id'] == 112 || $layout['component_id'] == 113 || $layout['component_id'] == 114 || $layout['component_id'] == 115)
								{
                                    $span = 3;
                                    $scroll_type = 0;
                                    $view_all = 1;
                                }

                                $comp_array[] = array(
                                    "title" => array("name" => $layout['title'], "id" => $layout['title_id']),
                                    "comp_id" => $layout['sort_order'],
                                    "comp_type" => $layout['component_id'],
                                    "comp_row_id" => $layout['id'],
                                    "comp_properties" => array(
                                      "span" => $span,
                                      "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                      "view_all" => $view_all
                                    ),
                                    "comp_val" => $compVal,
                                    "elements" => array(
                                        "element_count" => count($showcasedata),
                                        "element_array" => $showcasedata
                                    ),
                                    "comp_val_name" => $layout['comp_val_name'],
                                    "itemcat" => array("itemid" => $itemId, "itemheading" => $itemHeading, "parent_id" => "", "sort_order" => ""),
                                    "itemprod" => array("itemheading" => $prodHeading, "imageurl" => "", "itemid" => $prodId, "actualprice" => "", "price" => "", "addedToCart" => 0, "addedToWishlist" => 0, "discount" => "")
                                );
                            } 
                            elseif ($layout['component_id'] == 106 || $layout['component_id'] == 107 || $layout['component_id'] == 108 || $layout['component_id'] == 111 ||  $layout['component_id'] == 116 ||  $layout['component_id'] == 117)// for all banners & single components
							{ 
                                $query = "SELECT * FROM app_banners WHERE `app_id`=:appid AND comp_id = :compid and retail_app_componentId=:layoutid and isActive=1";
                                $con = $dbCon->prepare($query);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                                $con->bindParam(':layoutid', $layout['id'], PDO::PARAM_INT);
                                $con->execute();
                                $launchdata = $con->fetchAll(PDO::FETCH_ASSOC);

                                $actualprice  = '';
								$specialprice = '';
								$discount     = '';
								$symbol_left  = $defaultcurrencyArr['symbol_left'];
								$symbol_right = $defaultcurrencyArr['symbol_right'];
								$bannerdata   = array();
                                if (!empty($out)) {
                                    foreach ($launchdata as $tempdata)
									{
										if($layout['component_id'] == 116)
										{
											$prod_id = $tempdata['prod_id'];
											$clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image, p.image_rect, p.price AS actualprice,ops.price AS special_price
														FROM oc_app_id ai 
														JOIN oc_product p ON ai.product_id=p.product_id
														LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
														LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
														LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
														LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
														WHERE ai.app_id='".$app_id."' AND p.product_id='".$prod_id."' AND p.status = 1";
											$layproducts = $this->query_run($clquery, 'select');
											
											if(!empty($layproducts))
											{
												if($layproducts['image_rect'] != '')
												{
													$image_url = $layproducts['image_rect'];
												}
												else
												{
													$image_url = $layproducts['image'];
												}
												
												if(!@getimagesize($image_url))
												{
													$image_url = base_image_url().$image_url;
												}
											
												$actualprice  = $layproducts['actualprice'];
												$specialprice = isset($layproducts['special_price']) !='' ? $layproducts['special_price'] : $actualprice;		
											}
											else
											{
												$image_url = '';
												$actualprice  = '';
												$specialprice = '';		
											}
											
											list($width, $height) = @getimagesize($image_url);
										}
										elseif($layout['component_id'] == 117)
										{
											$basesql = "SELECT c.category_id AS itemid, vc.image, vc.rect_image, cd.name AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
														FROM oc_category c 
														LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
														LEFT JOIN vendor_category_image vc ON vc.category_id=c.category_id 
														WHERE vc.category_id='".$tempdata['cat_id']."' AND vc.app_id='".$app_id."'";
												
											$catdata = $this->query_run($basesql, 'select');
											
											if(!empty($catdata))
											{
												if($catdata['rect_image'] != '')
												{
													$image_url = $catdata['rect_image'];
												}
												else
												{
													$image_url = $catdata['image'];
												}
												
												if(!@getimagesize($image_url))
												{
													$image_url = base_image_url().$image_url;
												}
												
												$tempdata['heading'] = $catdata['itemheading'];
											}
											else
											{
												$image_url = '';		
											}
											
											list($width, $height) = @getimagesize($image_url);
										}
										else
										{
											$image_url = $tempdata['banner_url'];
											list($width, $height) = @getimagesize($image_url);
										}
										
										if ($layout['component_id'] == 111 || $layout['component_id'] == 116 || $layout['component_id'] == 117)
										{
											$height = 800;
											$width = 1280;
										}
										
										
										

                                        $tempCdata = array(
                                            "itemheading" => html_entity_decode($tempdata['heading']),
                                            "itemdesc" => $tempdata['subheading'],
                                            "imageurl" => $image_url,
                                            "imagename" => $tempdata['banner'],
                                            "itemid" => $tempdata['id'],
                                            "image_height" => $height,
                                            "image_width" => $width,
                                            "itemcat" => array("itemid" => $tempdata['cat_id'], "itemheading" => $tempdata['cat_head'], "parent_id" => "", "sort_order" => ""),
                                            "itemprod" => array("itemheading" => $tempdata['prod_head'], "imageurl" => $image_url, "itemid" => $tempdata['prod_id'], "actualprice" => $actualprice, "price" => $specialprice, "addedToCart" => 0, "addedToWishlist" => 0, "discount" => "", "symbol_left" => $symbol_left, "symbol_right" => $symbol_right)
                                        );
                                        $bannerdata[] = $tempCdata;
                                    }
                                }

                                // setting component properties
                                if ($layout['component_id'] == 106) {
                                    $span = 1;
                                    $scroll_type = 0;
                                    $view_all = 0;
                                } elseif ($layout['component_id'] == 107) {
                                    $span = 2;
                                    $scroll_type = 0;
                                    $view_all = 0;
                                } elseif ($layout['component_id'] == 108) {
                                    $span = 2;
                                    $scroll_type = 0;
                                    $view_all = 0;
                                } elseif ($layout['component_id'] == 111) {
                                    $span = 1;
                                    $scroll_type = 0;
                                    $view_all = 0;
                                }
								elseif ($layout['component_id'] == 117) {
                                    $span = 1;
                                    $scroll_type = 0;
                                    $view_all = 0;
                                }

                                $comp_array[] = array(
                                    "title" => array("name" => $layout['title'], "id" => $layout['title_id']),
                                    "comp_id" => $layout['sort_order'],
                                    "comp_type" => $layout['component_id'],
                                    "comp_row_id" => $layout['id'],
                                    "comp_properties" => array(
										"span" => $span,
										"scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
										"view_all" => $view_all
                                    ),
                                    "elements" => array(
                                        "element_count" => count($bannerdata),
                                        "element_array" => $bannerdata
                                    ),
                                    "comp_val_name" => $layout['comp_val_name']
                                );
                            } 
							elseif ($layout['component_id'] == 109 || $layout['component_id'] == 110) // for all products
							{ 
                                $apquery = "SELECT DISTINCT rap.* FROM retail_app_products rap 
											LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
											LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
											WHERE rap.app_id = :appid AND rap.isActive=1 
											AND rap.type = 2
											AND rac.component_id = :compid AND rap.isActive=1";

                                $con = $dbCon->prepare($apquery);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                                $con->execute();
                                $app_products = $con->fetch(PDO::FETCH_ASSOC);

								$prodId = $prodHeading = $itemHeading = $catId = $prodstr = '';
                                if (!empty($app_products))
								{
									// component limit
									if($layout['component_id'] == 109)
									{
										$limit = 3;
									}
									elseif($layout['component_id'] == 110)
									{
										$limit = 6;
									}
									
                                    $catId       = $app_products['cat_id'];
                                    $itemHeading = $app_products['cat_head'];

									$prodstr = $app_products['cat_id'];

                                    /* $clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND opc.category_id IN(:prodstr) AND p.status = 1 ORDER BY p.date_modified";
                                    $con = $dbConRetail->prepare($clquery);
                                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                    $con->bindParam(':prodstr', $prodstr, PDO::PARAM_STR);
                                    $con->execute();
                                    $layproductsCount = $con->fetchAll(PDO::FETCH_ASSOC); */
									
									$clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND opc.category_id IN(:prodstr) AND p.status = 1 ORDER BY p.date_modified LIMIT 0, ".$limit;
                                    $con = $dbConRetail->prepare($clquery);
                                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                    $con->bindParam(':prodstr', $prodstr, PDO::PARAM_STR);
                                    $con->execute();
                                    $layproducts = $con->fetchAll(PDO::FETCH_ASSOC);


                                    $finallayprod = array();
                                    foreach ($layproducts as $prod)
									{
                                        $proood = array();
                                        foreach ($prod as $key => $perpod)
										{
                                            if ($key == 'imageurl')
											{
												if($prod[$key] != '')
												{
													if(@getimagesize($prod[$key]))
													{
														$image_url = $prod[$key];
													}
													else
													{
														$image_url = base_image_url() . $prod[$key];
													}
												}
												else
												{
													$img_url = baseUrlWeb().'images-new/theme.png';
												}
                                                $proood[$key] = $image_url;
                                                list($width, $height) = getimagesize($image_url);

                                                /* if ($layout['component_id'] == 110) {
                                                    $height = $width = 1080;
                                                } */
                                                $proood['image_height'] = $height;
                                                $proood['image_width'] = $width;
                                            }
											else
											{
                                                $proood[$key] = $prod[$key];
                                            }
                                        }

                                        $proood['symbol_left']  = $defaultcurrencyArr['symbol_left'];
										$proood['symbol_right'] = $defaultcurrencyArr['symbol_right'];
										$finallayprod[] = $proood;
                                    }

                                    // setting component properties
                                    if ($layout['component_id'] == 109) {
                                        $span = 1;
                                        $scroll_type = 1;
                                        $view_all = 0;
                                    } elseif ($layout['component_id'] == 110) {
                                        $span = 3;
                                        $scroll_type = 0;
                                        $view_all = 1;
                                    }

                                    $comp_array[] = array(
                                        "title" => array("name" => $layout['title'], "id" => $layout['title_id']),
                                        "comp_id" => $layout['sort_order'],
                                        "comp_type" => $layout['component_id'],
                                        "comp_row_id" => $layout['id'],
                                        /* "comp_properties" => array(
                                          "span" => $span,
                                          "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                          "view_all" => $view_all
                                          ), */
                                        "comp_val" => $prodstr,
                                        "elements" => array(
                                            "element_count" => count($finallayprod),
                                            "element_array" => $finallayprod
                                        ),
                                        "comp_val_name" => $layout['comp_val_name'],
                                        "itemcat" => array("itemid" => $catId, "itemheading" => $itemHeading, "parent_id" => "", "sort_order" => ""),
                                        "itemprod" => array("itemheading" => $prodHeading, "imageurl" => "", "itemid" => $prodId, "actualprice" => "", "price" => "", "addedToCart" => 0, "addedToWishlist" => 0, "discount" => "")
                                    );
                                }
                            } 
							elseif($layout['component_id'] == 118) // for snapdeal component
							{ 
                                $query = "SELECT * FROM app_banners WHERE `app_id`=:appid AND comp_id = :compid and retail_app_componentId=:layoutid and isActive=1";
                                $con = $dbCon->prepare($query);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                                $con->bindParam(':layoutid', $layout['id'], PDO::PARAM_INT);
                                $con->execute();
                                $launchdata = $con->fetch(PDO::FETCH_ASSOC);

                                $finalcats = array();
                                if (!empty($launchdata))
								{
									$catdata = $this->getCurrentCatChild($app_id, $launchdata['cat_id'], 4);

                                    if (!empty($catdata))
									{
                                        foreach ($catdata as $tcdata)
										{
											$tcdata['itemheading'] = html_entity_decode($tcdata['itemheading']);
											$tcdata['itemdesc']    = html_entity_decode($tcdata['itemdesc']);
                                            
											// checking if current category is having child category or not
                                            $cqueryp = "SELECT count(*) as totalcount FROM oc_category WHERE parent_id = :id";
                                            $con = $dbConRetail->prepare($cqueryp);
                                            $con->bindParam(':id', $tcdata['itemid'], PDO::PARAM_INT);
                                            $con->execute();
                                            $ischild = $con->fetch(PDO::FETCH_ASSOC);
                                            
											if (!empty($ischild) && $ischild['totalcount'] > 0)
											{
                                                $tcdata['is_child_category'] = 1;
                                            }
											else
											{
                                                $tcdata['is_child_category'] = 0;
                                            }

                                            foreach ($tcdata as $kk => $perct)
											{
                                                if ($kk == 'imageurl') 
												{
                                                    $author_id = $app_screenData['author_id'];
                                                    $authorsql = "SELECT email_address FROM author WHERE id = :id";
                                                    $con = $dbCon->prepare($authorsql);
                                                    $con->bindParam(':id', $author_id, PDO::PARAM_INT);
                                                    $con->execute();
                                                    $authordata = $con->fetch(PDO::FETCH_ASSOC);

                                                    $userquery = "SELECT user_id FROM oc_user WHERE email =:email";
                                                    $con = $dbConRetail->prepare($userquery);
                                                    $con->bindParam(':email', $authordata['email_address'], PDO::PARAM_STR);
                                                    $con->execute();
                                                    $ocuserdata = $con->fetch(PDO::FETCH_ASSOC);

                                                    $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = :appid AND category_id = :catid AND vendor_id = :vendorid";
                                                    $con = $dbConRetail->prepare($vcimgquery);
                                                    $con->bindParam(':appid', $app_id, PDO::PARAM_STR);
                                                    $con->bindParam(':catid', $tcdata['itemid'], PDO::PARAM_INT);
                                                    $con->bindParam(':vendorid', $ocuserdata['user_id'], PDO::PARAM_INT);
                                                    $con->execute();
                                                    $catimgdata = $con->fetch(PDO::FETCH_ASSOC);
                                                    
													if (!empty($catimgdata))
													{
														if($catimgdata['image'] != '')
														{
															if(@getimagesize($catimgdata['image']))
															{
																$img_url = $catimgdata['image'];
															}
															else
															{
																$img_url = base_image_url() . 'data/' . $authordata['email_address'] . '/' . $app_id . '/' . $catimgdata['image'];
															}
														}
														else
														{
															$img_url = baseUrlWeb().'images-new/theme.png';
														}
                                                        $tcdata[$kk] = $img_url;
                                                        list($width, $height, $type, $attr) = getimagesize($img_url);

                                                        $tcdata['image_height'] = $height;
                                                        $tcdata['image_width'] = $width;
                                                    }
													else
													{
														$img_url     = baseUrlWeb().'images-new/theme.png';
                                                        $tcdata[$kk] = $img_url;
                                                        list($width, $height, $type, $attr) = getimagesize($img_url);

                                                        $tcdata['image_height'] = $height;
                                                        $tcdata['image_width'] = $width;
                                                    }
                                                }
												else
												{
                                                    $tcdata[$kk] = $perct;
                                                }
                                            }
                                            $finalcats[] = $tcdata;
                                        }
                                    }

									// for $bannerdata
									$bannerdata = array();
									$tempdata   = $launchdata;
									$basesql    = "SELECT c.category_id AS itemid, vc.image, vc.rect_image, cd.name AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
													FROM oc_category c 
													LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
													LEFT JOIN vendor_category_image vc ON vc.category_id=c.category_id 
													WHERE vc.category_id='".$tempdata['cat_id']."' AND vc.app_id='".$app_id."'";
										
									$singlecatdata = $this->query_run($basesql, 'select');
									
									if(!empty($singlecatdata))
									{
										if($singlecatdata['rect_image'] != '')
										{
											$image_url = $singlecatdata['rect_image'];
										}
										else
										{
											$image_url = $singlecatdata['image'];
										}
										
										if(!@getimagesize($image_url))
										{
											$image_url = base_image_url().$image_url;
										}
										
										$tempdata['heading'] = $singlecatdata['itemheading'];
									}
									else
									{
										$image_url = '';		
									}
											
									list($width, $height) = @getimagesize($image_url);

									$tempCdata = array(
										"itemheading" => $tempdata['heading'],
										"itemdesc" => $tempdata['subheading'],
										"imageurl" => $image_url,
										"imagename" => $tempdata['banner'],
										"itemid" => $tempdata['id'],
										"image_height" => $height,
										"image_width" => $width,
										"itemcat" => array("itemid" => $tempdata['cat_id'], "itemheading" => $tempdata['cat_head'], "parent_id" => "", "sort_order" => ""),
										"itemprod" => array("itemheading" => $tempdata['prod_head'], "imageurl" => $image_url, "itemid" => $tempdata['prod_id'], "actualprice" => $actualprice, "price" => $specialprice, "addedToCart" => 0, "addedToWishlist" => 0, "discount" => "", "symbol_left" => $symbol_left, "symbol_right" => $symbol_right)
									);
									$bannerdata[] = $tempCdata;
                                }

                                // setting component properties
                                $span = 1;
								$scroll_type = 0;
								$view_all = 1;

                                $comp_array[] = array(
                                    "title" => array("name" => $layout['title'], "id" => $layout['title_id']),
                                    "comp_id" => $layout['sort_order'],
                                    "comp_type" => $layout['component_id'],
                                    "comp_row_id" => $layout['id'],
                                    "comp_properties" => array(
										"span" => $span,
										"scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
										"view_all" => $view_all
                                    ),
                                    "elements" => array(
                                        "element_count" => count($bannerdata),
                                        "element_array" => $bannerdata
                                    ),
                                    "comp_val_name" => $layout['comp_val_name'],
									"snap_sim_data" => array(
                                        "element_count" => count($finalcats),
                                        "element_array" => $finalcats
                                    )
                                );
                            }
                        }
                    }

                    $baseUrl = base_image_url();

                    $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id`,`is_feedback`,`feedback_email`,`feedback_no`,`is_order`,`package`,`orderconfirm_email`,`orderconfirm_no`,`text_color`,`discount_color`,`logo_link`, `is_review_rating`, `is_model_number` FROM `app_catalogue_attr` WHERE `app_id`=:appid";
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                    $con->execute();
                    $launchdata = $con->fetch(PDO::FETCH_ASSOC);

                    $is_feedback = $feedback_email = $feedback_no = $is_order = $package = $orderconfirm_email = $orderconfirm_no = '';
                    $is_feedback1 = $launchdata['is_feedback'];
                    if ($is_feedback1 == 1) {
                        $is_feedback = true;
                    } else {
                        $is_feedback = false;
                    }
                    $feedback_email = $launchdata['feedback_email'];
                    $feedback_no = $launchdata['feedback_no'];
                    $is_order1 = $launchdata['is_order'];
                    if ($is_order1 == 1) {
                        $is_order = true;
                    } else {
                        $is_order = false;
                    }
                    $package = $launchdata['package'];
                    $orderconfirm_email = $launchdata['orderconfirm_email'];
                    $orderconfirm_no = $launchdata['orderconfirm_no'];

					if($launchdata['is_review_rating'] == 0)
					{
						$is_review_rating = false;
					}
					else
					{
						$is_review_rating = true;
					}
                    
					
					if($launchdata['is_model_number'] == 0)
					{
						$is_model_number = false;
					}
					else
					{
						$is_model_number  = true;
					}

                    $data = array(
                        /* "screen_id" => 1,
                          "parent_id" => 0,
                          "screen_type" => 1,
                          "tag" => 1,
                          "dirtyflag" => 0,
                          "ga_tracking" => $ga_tracking,
                          "merchent_id" => $merchent_id, */
                        "defaultcurrency" => $defaultcurrencyArr['currency_id'],
                        "app_type" => $app_type_app,
                        //"server_time" => date('Y-m-d H:i:s'),
                        "screen_properties" => array(
                            "title" => $launchdata['title'],
                            "popup_flag" => 0,
                            "background_color" => $launchdata['background_color'],
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png",
                            "font_color" => $launchdata['text_color'],
                            "discount_color" => $launchdata['discount_color'],
                            "app_id" => $app_id
                        ),
                        "review_rating" => $is_review_rating,
                        "model_number" => $is_model_number,
                        "tnc" => array("is_tnc" => $is_tnc, "tnc_email" => $tnc),
                        "contact_details" => $contactarr,
                        "logo_dtls" => array("imageurl" => $launchdata['logo_link']),
                        "adData" => $adData,
                        //"comp_count" => count($themelayout),
                        "comp_array" => $comp_array,
                        "feedback_dtls" => array("is_feedback" => $is_feedback, "feedback_email" => $feedback_email, "feedback_no" => $feedback_no),
                        "order_dtls" => array("is_order" => $is_order, "package" => $package, "orderconfirm_email" => $orderconfirm_email, "orderconfirm_no" => $orderconfirm_no)
                    );

                    $json = $this->real_json_encode_new($data, 'successData', '', 200, '', '');
                    echo $json;
                } else {
                    $json = $this->real_json_encode_new('', 'error', 'Auth token mismatch', 405); 
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode_new('', 'error', 'Parameter empty', 405);
                echo $json;
            }
        } else {
            echo json_encode(array("msg_code" => '501', "msg" => 'Please login '));
        }
    }

    public function registerUser($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if (!empty($data['custid'])) {

            $fname = xss_clean($data['fname']);
            $lname = xss_clean($data['lname']);
            $phone = $data['phone'];
            $country = $data['country'];
            $state = xss_clean($data['state']);
            $city = $data['city'];
            $address = xss_clean($data['address']);
            $zip = $data['zip'];
            $alternet_email = xss_clean($data['alternet_email']);
            $email = xss_clean($data['email']);
            $fax = $data['fax'];
            $mobile = $data['mobile'];
            $website = xss_clean($data['website']);
            $company = xss_clean($data['company']);
            $username = xss_clean($data['username']);
            $custid = $data['custid'];
            $mid = $data['mid'];
            $verification_code = $data['verification_code'];
            $curl = xss_clean($data['url']);
            if ($username == '') {
                if ($email != '') {
                    $query = "SELECT email_address from author WHERE email_address=:email";
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':email', $email, PDO::PARAM_INT);
                    $con->execute();
                    $emildata = $con->fetch(PDO::FETCH_ASSOC);


                    if (count($emildata) > 0 && $emildata != '') {
                        echo 'Email Address Already Exist!';
                    } else {
                        $this->author_changelog($data);
                        $is_confirm = '0';
                        $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
                        $basicUrl = $curl;
                        $chtmlcontent = file_get_contents('../edm/Registration.php');
                        $cname = ucwords($fname);
                        $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                        $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                        $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $verification_code, $chtmlcontent);
                        $cto = $email;
                        $cformemail = 'noreply@instappy.com';
                        $key = 'f894535ddf80bb745fc15e47e42a595e';
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                            CURLOPT_POST => 1,
                            CURLOPT_POSTFIELDS => array(
                                'api_key' => $key,
                                'subject' => $csubject,
                                'fromname' => 'Instappy',
                                'from' => $cformemail,
                                'content' => $chtmlcontent,
                                'recipients' => $cto
                            )
                        ));
                        $customerhead = curl_exec($curl);

                        curl_close($curl);
                        $query = "UPDATE author set first_name=:fname,last_name=:lname,phone_no=:phone,country=:country,state=:state,city=:city,street=:address,pincode=:zip,alternate_email=:alternet_email,email_address=:email,fax=:fax,mobile=:mobile,website=:website, organisation_name=:company, mid=:mid WHERE custid=:custid";
                        $con = $dbCon->prepare($query);
                        $con->bindParam(':fname', $fname, PDO::PARAM_STR);
                        $con->bindParam(':lname', $lname, PDO::PARAM_STR);
                        $con->bindParam(':phone', $phone, PDO::PARAM_STR);
                        $con->bindParam(':country', $country, PDO::PARAM_STR);
                        $con->bindParam(':state', $state, PDO::PARAM_STR);
                        $con->bindParam(':city', $city, PDO::PARAM_STR);
                        $con->bindParam(':address', $address, PDO::PARAM_STR);
                        $con->bindParam(':zip', $zip, PDO::PARAM_STR);
                        $con->bindParam(':alternet_email', $alternet_email, PDO::PARAM_STR);
                        $con->bindParam(':email', $email, PDO::PARAM_STR);
                      
                        $con->bindParam(':fax', $fax, PDO::PARAM_STR);
                        $con->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                        $con->bindParam(':website', $website, PDO::PARAM_STR);
                        $con->bindParam(':company', $company, PDO::PARAM_STR);
                        $con->bindParam(':mid', $mid, PDO::PARAM_INT);
                        $con->bindParam(':custid', $custid, PDO::PARAM_INT);
                        $con->execute();

                        echo 'Saved successfully!';
                    }
                }
            } elseif ($username != $email) {
                $query = "SELECT email_address from author WHERE email_address=:email and  email_address IS NOT NULL AND email_address<>''";
                $con = $dbCon->prepare($query);
                $con->bindParam(':email', $email, PDO::PARAM_STR);

                $con->execute();
                $emil_data = $con->fetch(PDO::FETCH_ASSOC);
                if (!empty($emil_data['email_address'])) {
                    echo 'Email Address Already Exist!';
                } else {
                    $this->author_changelog($data);
                    $is_confirm = '0';
                    $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
                    $basicUrl = $curl;
                    $chtmlcontent = file_get_contents('../edm/Registration.php');
                    $cname = ucwords($fname);
                    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                    $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $verification_code, $chtmlcontent);
                    $cto = $email;
                    $cformemail = 'noreply@instappy.com';
                    $key = 'f894535ddf80bb745fc15e47e42a595e';
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            'api_key' => $key,
                            'subject' => $csubject,
                            'fromname' => 'Instappy',
                            'from' => $cformemail,
                            'content' => $chtmlcontent,
                            'recipients' => $cto
                        )
                    ));
                    $customerhead = curl_exec($curl);

                    curl_close($curl);


                    $query = "UPDATE author set first_name=:fname,last_name=:lname,phone_no=:phone,country=:country,state=:state,city=:city,street=:address,pincode=:zip,alternate_email=:alternet_email,email_address=:email,fax=:fax,mobile=:mobile,website=:website, organisation_name=:company, mid=:mid WHERE custid=:custid";

                    $con = $dbCon->prepare($query);
                    $con->bindParam(':fname', $fname, PDO::PARAM_STR);
                    $con->bindParam(':lname', $lname, PDO::PARAM_STR);
                    $con->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $con->bindParam(':country', $country, PDO::PARAM_STR);
                    $con->bindParam(':state', $state, PDO::PARAM_STR);
                    $con->bindParam(':city', $city, PDO::PARAM_STR);
                    $con->bindParam(':address', $address, PDO::PARAM_STR);
                    $con->bindParam(':zip', $zip, PDO::PARAM_STR);
                    $con->bindParam(':alternet_email', $alternet_email, PDO::PARAM_STR);
                    $con->bindParam(':email', $email, PDO::PARAM_STR);
                 
                    $con->bindParam(':fax', $fax, PDO::PARAM_STR);
                    $con->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                    $con->bindParam(':website', $website, PDO::PARAM_STR);
                    $con->bindParam(':company', $company, PDO::PARAM_STR);
                    $con->bindParam(':mid', $mid, PDO::PARAM_INT);
                    $con->bindParam(':custid', $custid, PDO::PARAM_INT);
                    $con->execute();

                    if ($customerhead) {
                        echo 'Saved successfully!';
                    }
                }
            } else {
                $query = "UPDATE author set first_name=:fname,last_name=:lname,phone_no=:phone,country=:country,state=:state,city=:city,street=:address,pincode=:zip,alternate_email=:alternet_email,email_address=:email,fax=:fax,mobile=:mobile,website=:website, organisation_name=:company, mid=:mid WHERE custid=:custid";
                $con = $dbCon->prepare($query);
                $con->bindParam(':fname', $fname, PDO::PARAM_STR);
                $con->bindParam(':lname', $lname, PDO::PARAM_STR);
                $con->bindParam(':phone', $phone, PDO::PARAM_STR);
                $con->bindParam(':country', $country, PDO::PARAM_STR);
                $con->bindParam(':state', $state, PDO::PARAM_STR);
                $con->bindParam(':city', $city, PDO::PARAM_STR);
                $con->bindParam(':address', $address, PDO::PARAM_STR);
                $con->bindParam(':zip', $zip, PDO::PARAM_STR);
                $con->bindParam(':alternet_email', $alternet_email, PDO::PARAM_STR);
                $con->bindParam(':email', $email, PDO::PARAM_STR);
             
                $con->bindParam(':fax', $fax, PDO::PARAM_STR);
                $con->bindParam(':mobile', $mobile, PDO::PARAM_STR);
                $con->bindParam(':website', $website, PDO::PARAM_STR);
                $con->bindParam(':company', $company, PDO::PARAM_STR);
                $con->bindParam(':mid', $mid, PDO::PARAM_INT);
                $con->bindParam(':custid', $custid, PDO::PARAM_INT);
                $con->execute();
                echo 'Saved successfully!';
            }
        } else {
            echo 'error';
        }
    }

    public function vendorrequest($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $mainurl = 'http://182.74.47.179/SQLServer.aspx?Process=Pulp&Campaign=Pulp&PhoneNo=' . $data['mobile'] . '&SetCallBack=1&IsFilewise=1&First_name=' . $data['fname'] . ' ' . $data['lname'] . '&Email=' . $data['email'] . '&Organisation=' . $data['company'] . '&Organisation_Size=0&Industry=&App_type=&CustomerRemark=&Source=register';

        $url = str_replace(' ', '-', $mainurl);
        $curl2 = curl_init();
        curl_setopt_array($curl2, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $url
        ));
        $head2 = curl_exec($curl2);
        curl_close($curl2);

//            if($head2 == "Success"){
//                echo "sent to vendor";
//            } else{
//                echo "not sent to vendor";
//            }           
    }

    public function userAvatar() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if (!isset($_FILES['avatar']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
            echo 'Image file is Missing!';
        } else {
            $username = $_POST['username'];
            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../avatars/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['avatar']) || !is_uploaded_file($_FILES['avatar']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['avatar']['name']; //file name
                $image_size = $_FILES['avatar']['size']; //file size
                $image_temp = $_FILES['avatar']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }
                $dimg = imagecreatetruecolor(1, 1);
                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    /* by hemant gariya for transparent background 
                      Code start */





                    /* Code ends here  */


                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        $query = 'UPDATE author set avatar=:avatar WHERE email_address=:email';
                        $con = $dbCon->prepare($query);
                        $con->bindParam(':avatar', htmlentities($new_file_name), PDO::PARAM_STR);
                        $con->bindParam(':email', $_POST['username'], PDO::PARAM_STR);
                        $con->execute();

                        echo 'Image Uploaded Successfully';
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    #####  This function will proportionally resize image ##### 

    function normal_resize_image($source, $destination, $image_type, $max_size, $image_width, $image_height, $quality) {

        $dbCon = content_db();
        $dbConRetail = retail_db();

        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize
        //do not resize if image is smaller than max size
        if ($image_width <= $max_size && $image_height <= $max_size) {
            if ($this->save_image($source, $destination, $image_type, $quality)) {
                return true;
            }
        }

        //Construct a proportional size of new image
        $image_scale = min($max_size / $image_width, $max_size / $image_height);
        $new_width = ceil($image_scale * $image_width);
        $new_height = ceil($image_scale * $image_height);

        $new_canvas = imagecreatetruecolor($new_width, $new_height); //Create a new true color image

        switch ($image_type) {
            case 'image/png':
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($new_canvas, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($new_canvas, $background);

                // turning off alpha blending (to ensure alpha channel information 
                // is preserved, rather than removed (blending with the rest of the 
                // image in the form of black))
                imagealphablending($new_canvas, false);

                // turning on alpha channel information saving (to ensure the full range 
                // of transparency is preserved)
                imagesavealpha($new_canvas, true);

                break;
            case 'image/gif':
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($new_canvas, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($new_canvas, $background);
                break;
        }

        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality); //save resized image
        }

        return true;
    }

    ##### This function corps image to create exact square, no matter what its original size! ######

    function crop_image_square($source, $destination, $image_type, $square_size, $image_width, $image_height, $quality) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize

        if ($image_width > $image_height) {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size = $image_width - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }
        $new_canvas = imagecreatetruecolor($square_size, $square_size); //Create a new true color image
        switch ($image_type) {
            case 'image/png':
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($new_canvas, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($new_canvas, $background);

                // turning off alpha blending (to ensure alpha channel information 
                // is preserved, rather than removed (blending with the rest of the 
                // image in the form of black))
                imagealphablending($new_canvas, false);

                // turning on alpha channel information saving (to ensure the full range 
                // of transparency is preserved)
                imagesavealpha($new_canvas, true);

                break;
            case 'image/gif':
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($new_canvas, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($new_canvas, $background);

                break;
        }
        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $square_size, $square_size, $s_size, $s_size)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### This function corps image to create exact rectangle, no matter what its original size! ######

    function crop_image_rectangle($source, $destination, $image_type, $rect_size, $image_width, $image_height, $quality) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ($image_width <= 0 || $image_height <= 0) {
            return false;
        } //return false if nothing to resize

        if ($image_width > $image_height) {
            $y_offset = 0;
            $x_offset = ($image_width - $image_height) / 2;
            $s_size = $image_width - ($x_offset * 2);
        } else {
            $x_offset = 0;
            $y_offset = ($image_height - $image_width) / 2;
            $s_size = $image_height - ($y_offset * 2);
        }

        $new_canvas = imagecreatetruecolor($rect_size[0], $rect_size[1]); //Create a new true color image
        //Copy and resize part of an image with resampling
        if (imagecopyresampled($new_canvas, $source, 0, 0, $x_offset, $y_offset, $rect_size[0], $rect_size[1], $s_size, $s_size)) {
            $this->save_image($new_canvas, $destination, $image_type, $quality);
        }

        return true;
    }

    ##### Saves image resource to file ##### 

    function save_image($source, $destination, $image_type, $quality) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        switch (strtolower($image_type)) {//determine mime type
            case 'image/png':
                imagepng($source, $destination);
                return true; //save png file
                break;
            case 'image/gif':
                imagegif($source, $destination);
                return true; //save gif file
                break;
            case 'image/jpeg': case 'image/pjpeg':
                imagejpeg($source, $destination, $quality);
                return true; //save jpeg file
                break;
            default: return false;
        }
    }

    public function authenticationCheck($authkey) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $query = "SELECT id FROM `author` WHERE auth_token=:key";
        $con = $dbCon->prepare($query);
        $con->bindParam(':key', $authkey, PDO::PARAM_STR);
        $con->execute();
        $result = $con->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /*
     * check availability of data w.r.t app_id, author_id
     * Added By Arun Srivastava on 16/6/15
     */

    public function appDetailCheck($author_id, $app_id) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $query = "SELECT id FROM `app_data` WHERE author_id=:authid AND id=:appid";
        $con = $dbCon->prepare($query);
        $con->bindParam(':authid', $author_id, PDO::PARAM_STR);
        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
        $con->execute();
        $result = $con->fetch(PDO::FETCH_ASSOC);
        $returndata = count($result);
        return $returndata;
    }

    public function planExpiryDate($id) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $query = "SELECT validity_in_days FROM `plans` WHERE id=:id";
        $con = $dbCon->prepare($query);
        $con->bindParam(':id', $id, PDO::PARAM_INT);
        $con->execute();
        $result = $con->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public function auth_create($user_id) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $token = bin2hex(openssl_random_pseudo_bytes(16));
        $query = "UPDATE `author` SET `updated`=:servertime,`auth_token`=:token where `id`=:userid";
        $con = $dbCon->prepare($query);
        $con->bindParam(':servertime', $servertime, PDO::PARAM_STR);
        $con->bindParam(':token', $token, PDO::PARAM_STR);
        $con->bindParam(':userid', $user_id, PDO::PARAM_INT);
        $con->execute();


//get token
        $query1 = "Select auth_token from `author` where id =:userid";
        $con = $dbCon->prepare($query1);
        $con->bindParam(':userid', $user_id, PDO::PARAM_INT);
        $con->execute();
        $authtoken = $con->fetch(PDO::FETCH_ASSOC);

        return $authtoken['auth_token'];
    }

    public function email_check($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $emailC = $userdata['email_address'];
        $query = "Select id from author where email_address = :emailC";
        $con = $dbCon->prepare($query);
        $con->bindParam(':emailC', $emailC, PDO::PARAM_STR);
        $con->execute();
        $row = $con->fetch(PDO::FETCH_ASSOC);

        $chkEmail = 0;
        if (count($row['id']) > 0) {
            $chkEmail = 1;
        }
        return $chkEmail;
    }

    public function fb_check($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $fbid = $userdata['fbid'];
        $query = "Select id from author where fbid = :fbid";
        $con = $dbCon->prepare($query);
        $con->bindParam(':fbid', $fbid, PDO::PARAM_STR);
        $con->execute();
        $row = $con->fetch(PDO::FETCH_ASSOC);
        $fbid_check = 0;
        if (count($row['id']) > 0) {
            $fbid_check = 1;
        }
        return $fbid_check;
    }

    public function newUserRegister($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $email = htmlentities($userdata['email_address']);
        $author_product = $userdata['author_product'];
        $author_product_category = $userdata['author_product_category'];
        $first_name = htmlentities($userdata['first_name']);
        $last_name = htmlentities($userdata['last_name']);
        $company_name = htmlentities($userdata['company_name']);
        $mobile_number = htmlentities($userdata['mobile_number']);
        $source = $userdata['source'];
        $password = $userdata['password'];
        $mobile_country = $userdata['mobile_country'];
        $code = $userdata['verification_code'];
        $ip_address = $userdata['ip_address'];

        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `email_address`,`first_name`,`last_name`,`organisation_name`,`mobile`,`product_prefereed`,`theme_preferred`,`source`,`custid`, `password`,`verification_code`,`active`,`deleted`,`mobile_country_code`,`ip_address`) VALUES ('1',:newdate,:servertime,:email,:fname,:lname,:cname,:phone,:authprod,:prodcat,:source,UNIX_TIMESTAMP(),:password,:code,'1','0',:mcountry,:ip)";
        $con = $dbCon->prepare($query);
        $con->bindParam(':newdate', $NewDate, PDO::PARAM_STR);
        $con->bindParam(':servertime', $servertime, PDO::PARAM_STR);
        $con->bindParam(':email', $email, PDO::PARAM_STR);
        $con->bindParam(':fname', $first_name, PDO::PARAM_STR);
        $con->bindParam(':lname', $last_name, PDO::PARAM_STR);
        $con->bindParam(':cname', $company_name, PDO::PARAM_STR);
        $con->bindParam(':phone', $mobile_number, PDO::PARAM_STR);
        $con->bindParam(':authprod', $author_product, PDO::PARAM_STR);
        $con->bindParam(':prodcat', $author_product_category, PDO::PARAM_STR);
        $con->bindParam(':source', $source, PDO::PARAM_STR);
        $con->bindParam(':password', $password, PDO::PARAM_STR);
        $con->bindParam(':code', $code, PDO::PARAM_STR);
        $con->bindParam(':mcountry', $mobile_country, PDO::PARAM_STR);
        $con->bindParam(':ip', $ip_address, PDO::PARAM_STR);
        $con->execute();
        $user_id = $dbCon->lastInsertId();
        if ($user_id > 0) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function newuserregister_fb($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $fb_token = $userdata['fb_token'];
        $first_name = $userdata['first_name'];
        $last_name = $userdata['last_name'];
        $source = $userdata['source'];
        $avatar = $userdata['avatar'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        $ip_address = $userdata['ip'];

        $base_url = $userdata['base_url'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `first_name`,`last_name`,`avatar`,`fb_token`,`fbid`,`email_address`,`custid`,`source`,`password`,`verification_code`,`is_confirm`,`active`,`deleted`,`ip_address`) VALUES ('1','$NewDate','$servertime','$first_name','$last_name','$avatar','$fb_token','$fbid','$email',UNIX_TIMESTAMP(),'$source','$password','$code','1','1','0','$ip_address')";
        $user_id = $this->queryRun($query, 'insert');

        if ($user_id > 0) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $query = "select * from author where id=$user_id";
                $userresults = $this->queryRun($query, 'select');
                if ($userresults['email_address'] != '') {
                    $lname = $userresults['last_name'];
                    $fname = $userresults['first_name'];
                    $username = $userresults['email_address'];
                    $custid = $userresults['custid'];
                    $basicUrl = $base_url;
                    $cto = $username;
                    $chtmlcontent = file_get_contents('../edm/Welcome-to-instappy.php');
                    $clastname = $lname != 'User' ? ' ' . $lname : $lname;
                    if (!empty($fname)) {
                        $cname = " " . $fname;
                    } else {
                        $cname = "";
                    }
                    $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
                    $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
                    $chtmlcontent = str_replace('{customer_id}', $custid, $chtmlcontent);

                    $csubject = 'Welcome to Instappy';
                    $cformemail = 'noreply@instappy.com';
                    $key = 'f894535ddf80bb745fc15e47e42a595e';
                    //$url = 'https://api.falconide.com/falconapi/web.send.rest?api_key=' . $key . '&subject=' . rawurlencode($csubject) . '&fromname=' . rawurlencode($csubject) . '&from=' . $cformemail . '&content=' . rawurlencode($chtmlcontent) . '&recipients=' . $cto;
                    //$customerhead = file_get_contents($url);
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_RETURNTRANSFER => 1,
                        CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                        CURLOPT_POST => 1,
                        CURLOPT_POSTFIELDS => array(
                            'api_key' => $key,
                            'subject' => $csubject,
                            'fromname' => 'Instappy',
                            'from' => $cformemail,
                            'content' => $chtmlcontent,
                            'recipients' => $cto
                        )
                    ));
                    $customerhead = curl_exec($curl);

                    curl_close($curl);
                }
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT, 'custid' => $userresults['custid'], 'email_address' => $userresults['email_address'], 'first_name' => $userresults['first_name']));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function updateuser_fb($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {   //check ip from share internet
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {   //to check ip is pass from proxy
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $fb_token = $userdata['fb_token'];
        $first_name = $userdata['first_name'];
        $last_name = $userdata['last_name'];
        $source = $userdata['source'];
        $avatar = $userdata['avatar'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        $ip_address = $userdata['ip'];

        $is_confirm = 1;
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "update  author set plan_id=1,plan_expiry_date='$NewDate',updated='$servertime',first_name='$first_name',last_name='$last_name',avatar='$avatar',fb_token='$fb_token',fbid='$fbid',active=1,deleted=0,ip_address='$ip_address' where fbid='$fbid' ";
        $user_id = $this->queryRun($query, 'update');

        if ($user_id) {
            $query = "select * from author where fbid='$fbid'";
            $userresults = $this->queryRun($query, 'select');
            $custid = $userresults['custid'];
            $data = array('register' => array('updatedOn' => $servertime, 'custid' => $custid, 'email_address' => $userresults['email_address'], 'first_name' => $userresults['first_name']));
            $json = $this->real_json_encode($data, 'successData', '', '200');
            echo $json;
        } else {
            $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
            echo $json;
        }
    }

    function newuserregister_gplus($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $gPlus_token = $userdata['gPlus_token'];
        $first_name = $userdata['first_name'];
        $avatar = $userdata['avatar'];
        $last_name = $userdata['last_name'];
        $fbid = $userdata['fbid'];
        $code = $userdata['verification_code'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));

        $query = "INSERT INTO `author`(`plan_id`, `plan_expiry_date`,`created`, `first_name`,`last_name`,`avatar`,`gPlus_token`,`fbid`,`email_address`, `password`,`verification_code`,`is_confirm`,`active`,`deleted`) VALUES ('1','$NewDate','$servertime','$first_name','$last_name','$avatar','$gPlus_token','$fbid','$email','$password','$code','1','1','0')";
        $user_id = $this->queryRun($query, 'insert');

        if ($user_id) {
            $authT = $this->auth_create($user_id);
            if ($authT != '') {
                $data = array('register' => array('updatedOn' => $servertime, 'authToken' => $authT));
                $json = $this->real_json_encode($data, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    function updateuser_gplus($userdata) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $email = $userdata['email_address'];
        $password = $userdata['password'];
        $gPlus_token = $userdata['gPlus_token'];
        $first_name = $userdata['first_name'];
        $avatar = $userdata['avatar'];
        $last_name = $userdata['last_name'];
        $fbid = $userdata['fbid'];
        if (function_exists('date_default_timezone_set')) {
            date_default_timezone_set('Asia/Calcutta');
            $servertime = date('Y-m-d H:i:s');
        }
        $planExpiry = $this->planExpiryDate(1);
        $planExpiryDate = $planExpiry['validity_in_days'];
        $NewDate = date('Y-m-d H:i:s', strtotime('+' . $planExpiryDate . ' days', strtotime($servertime)));
        $query = "update  author set plan_id=1,plan_expiry_date='$NewDate',updated='$servertime',first_name='$first_name',last_name='$last_name',avatar='$avatar',gPlus_token='$gPlus_token',fbid='$fbid',password='$password',active=1,deleted=0 where email_address='$email' ";
        $user_id = $this->queryRun($query, 'update');
        if ($user_id) {
            $data = array('register' => array('updatedOn' => $servertime));
            $json = $this->real_json_encode($data, 'successData', '', '200');
            echo $json;
        } else {
            $json = $this->real_json_encode('', 'error', 'Authentication Failed', '403');
            echo $json;
        }
    }

    public function resetPass() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ($_POST != '') {
            if (md5($_POST['old_pass']) == $this->getUserPass(htmlentities($_POST['username']))) {
                $query = 'UPDATE author set password=:password WHERE email_address=:email';
                $con = $dbCon->prepare($query);
                $con->bindParam(':password', md5($_POST['new_pass']), PDO::PARAM_STR);
                $con->bindParam(':email', $_POST['username'], PDO::PARAM_STR);
                $con->execute();

                $curl = curl_init();
                // Set some options - we are passing in a useragent too here
                curl_setopt_array($curl, array(
                    CURLOPT_RETURNTRANSFER => 1,
                    CURLOPT_URL => $_POST['catalogueUrl'] . 'catalogue/ecommerce_catalog_api/index.php/vendorforgot',
                    CURLOPT_USERAGENT => 'Codular Sample cURL Request',
                    CURLOPT_POST => 1,
                    CURLOPT_POSTFIELDS => array(
                        'email' => $_POST['username'],
                        'password' => $_POST['new_pass']
                    )
                ));
                // Send the request & save response to $resp
                $resp = curl_exec($curl);
                $results = json_decode($resp);
                echo 'updated Successfully!';
            } else {
                echo 'Old Password is not correct!';
            }
        } else {
            echo 'error';
        }
    }

    public function getUserPass($email) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $query = 'select password from author WHERE email_address=:email';
        $con = $dbCon->prepare($query);
        $con->bindParam(':email', $email, PDO::PARAM_STR);
        $con->execute();
        $result = $con->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }

    public function uploadAppIcons() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $appID = $_POST['appid'];

        if (!isset($_FILES['icons']) || !is_uploaded_file($_FILES['icons']['tmp_name'])) {
            echo 'Image file is Missing!';
        } else {
            if (!isset($_POST['appid']) && $_POST['appid'] != '') {
                die('Please select APP to upload icons!'); // output error when above checks fail.
            }

            $appID = $_POST['appid'];
            ############ Configuration ##############
            //$thumb_square_size 		= array(1024,512,192,144,114,100,96,72,60,57,50,48,36); //Thumbnails will be cropped to given array pixels
            $thumb_square_size = array(29, 36, 48, 50, 57, 60, 72, 76, 87, 96, 100, 114, 120, 144, 152, 180, 192, 512, 1024); //Thumbnails will be cropped to given array pixels
            $max_image_size = 1500; //Maximum image size (height and width)
            $thumb_prefix = "appicon_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $_POST['appid'] . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['icons']) || !is_uploaded_file($_FILES['icons']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['icons']['name']; //file name
                $image_size = $_FILES['icons']['size']; //file size
                $image_temp = $_FILES['icons']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size
                // check imageheight & imagewidth is > 1024
                if (($image_size_info[0] < 1024) || ($image_size_info[1] < 1024)) {
                    die('Icon image needs to be 1024x1024 pixels'); // output error when above checks fail.
                }

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;
                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;
                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        $delquery = "DELETE FROM `self_icons` WHERE app_id=:appid";
                        $con = $dbCon->prepare($delquery);
                        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);

                        $con->execute();

                        foreach ($thumb_square_size as $thumb_square_size) {
                            $new_file_name = $image_name_only . '_' . $thumb_square_size . 'X' . $thumb_square_size . '.' . $image_extension;
                            //folder path to save resized images and thumbnails
                            $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                            //call crop_image_square() function to create square thumbnails
                            if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                                die('Error Creating thumbnail');
                            }

                            /* We have succesfully resized and created thumbnail image
                              We can now output image to user's browser or store information in the database */
                            /* echo '<div align="center">';
                              echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                              echo '<br />';
                              echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                              echo '</div>'; */
                            //$baseurl = baseUrlContent();
                            $image_url = baseUrlWeb();
                            $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                            $newFileName = $image_name_only . '_' . $thumb_square_size . 'X' . $thumb_square_size;
                            $query = "INSERT INTO `self_icons` (`app_id`,`icon_name`,`icon_type`,`image_40`) VALUES (:appid,:name,'1',:img)";
                            $con = $dbCon->prepare($query);
                            $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                            $con->bindParam(':img', $imageurl, PDO::PARAM_STR);
                            $con->bindParam(':name', $newFileName, PDO::PARAM_STR);
                            $con->execute();
                            $imagedata = $dbCon->lastInsertId();

                            // custom icons conditions start
                              $query_auth_detail="SELECT payment_type_id ,payment_type_value,apd.is_custom FROM author_payment_details AS apd JOIN author_payment AS ap ON apd.author_payment_id=ap.id  WHERE ap.app_id=:appid";
                              //$auth_payment_details = $this->queryRun($query_auth_detail, 'select_all');
                              $con = $dbCon->prepare($query_auth_detail);
                              $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                              $con->execute();
                              $auth_payment_details=$con->fetchAll(PDO::FETCH_ASSOC);
                             // for app icons
                              $icon_update='1';
                              if(count($auth_payment_details)>0){
                                 if($auth_payment_details[0]['payment_type_id']==1){

                                    if($auth_payment_details[0]['is_custom']==2){
                                        $icon_update = '2'; 

                                        $custom_icon = 'UPDATE custom_icons SET self_icon_id=:self_icon_id  WHERE id=:id';
                                        $id=$auth_payment_details[0]['payment_type_value'];
                                        $con = $dbCon->prepare($custom_icon);
                                        $con->bindParam(':self_icon_id', $imagedata, PDO::PARAM_INT);
                                        $con->bindParam(':id', $id, PDO::PARAM_INT);
                                        $con->execute();


                                    }
                                    else if($auth_payment_details[0]['is_custom']==1){

                                        $icon_update = '1';
                                    }
                                    else{
                                     $icon_update = '1';

                                 }

                             }
                         }
                         else{
                            $icon_update = '1';

                        }

                    // custom icons conditions end


                        $query = 'UPDATE app_data SET app_image=:img,is_self_custom_icon=:is_self_custom_icon WHERE id=:appid';
                            $con = $dbCon->prepare($query);
                            $con->bindParam(':img', $imageurl, PDO::PARAM_STR);
                            $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                        $con->bindParam(':is_self_custom_icon', $icon_update, PDO::PARAM_INT);
                            $con->execute();

                            //echo 'Image Uploaded Successfully';
                        }
                        echo 'Icons Uploaded Successfully';
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    public function uploadAppScreens() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $appID = $_POST['appid'];

        if (!isset($_FILES['screens']) || !is_uploaded_file($_FILES['screens']['tmp_name'])) {
            echo 'Image File is Missing!';
        } else {
            if (!isset($_POST['appid']) && $_POST['appid'] != '') {
                die('Please select APP to upload screens!'); // output error when above checks fail.
            }

            $appID = $_POST['appid'];
            ############ Configuration ##############
            $thumb_rect_size = array(
                array(2208, 1242),
                array(1536, 2048),
                array(1600, 2560),
                array(1200, 1920),
                array(1334, 750),
                array(1136, 640),
                array(1024, 768),
                array(480, 320),
                array(768, 1280),
                array(600, 1024)
            ); //Thumbnails will be cropped to given array pixels
            $max_image_size = 2500; //Maximum image size (height and width)
            $thumb_prefix = "splashscreen_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $_POST['appid'] . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['screens']) || !is_uploaded_file($_FILES['screens']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['screens']['name']; //file name
                $image_size = $_FILES['screens']['size']; //file size
                $image_temp = $_FILES['screens']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size
                // check imageheight & imagewidth is > 1024
                if (($image_size_info[0] < 1080) || ($image_size_info[1] < 1920)) {
                    die('Image needs to be 1080*1920 pixels.'); // output error when above checks fail.
                }

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;
                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;
                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        $delquery = "DELETE FROM `splash_screen` WHERE app_id=:appid";
                        $con = $dbCon->prepare($delquery);
                        $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                        $con->execute();
                        foreach ($thumb_rect_size as $thumb_rect_size) {

                            $new_file_name = $image_name_only . '_' . $thumb_rect_size[0] . 'X' . $thumb_rect_size[1] . '.' . $image_extension;
                            //folder path to save resized images and thumbnails
                            $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                            //call crop_image_square() function to create square thumbnails
                            if (!$this->crop_image_rectangle($image_res, $thumb_save_folder, $image_type, $thumb_rect_size, $image_width, $image_height, $jpeg_quality)) {
                                die('Error Creating thumbnail');
                            }

                            //$baseurl = baseUrlContent();
                            $is_default_splash = '1';
                            $image_url = baseUrlWeb();
                            $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                            $newFileName = $image_name_only . '_' . $thumb_rect_size[0] . 'X' . $thumb_rect_size[1];
                            $query = "INSERT INTO `splash_screen` (`name`,`app_id`,`image_link`) VALUES (:name,:appid,:url)";
                            $con = $dbCon->prepare($query);
                            $con->bindParam(':name', $newFileName, PDO::PARAM_INT);
                            $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                            $con->bindParam(':url', $imageurl, PDO::PARAM_STR);
                            $con->execute();

                            $imagedata = $dbCon->lastInsertId();
                            $query_auth_detail="SELECT payment_type_id ,payment_type_value,apd.is_custom FROM author_payment_details AS apd JOIN author_payment AS ap ON apd.author_payment_id=ap.id  WHERE ap.app_id=:appid";
                            $con = $dbCon->prepare($query_auth_detail);
                            $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                            $con->execute();
                            $auth_payment_details=$con->fetchAll(PDO::FETCH_ASSOC);
                             //$auth_payment_details = $this->queryRun($query_auth_detail, 'select_all');
                            $splash_update_default=1;
                           	$splash_update_custom=1;
                            if(count($auth_payment_details)>0 && isset($auth_payment_details[1]['payment_type_id'])){
                        // for spash screen
                                if($auth_payment_details[1]['payment_type_id']==2){                                
                                    if($auth_payment_details[1]['is_custom']==2){
                                        $splash_update_default=2;
                          				 $splash_update_custom=2;
                                        $id= $auth_payment_details[1]['payment_type_value'];                            
                                        $custom_icon = 'UPDATE custom_splashscreen SET splash_screen_id=:splash_screen_id  WHERE id=:id';
                                        $con = $dbCon->prepare($custom_icon);
                                        $con->bindParam(':splash_screen_id', $imagedata, PDO::PARAM_INT);
                                        $con->bindParam(':id', $id, PDO::PARAM_INT);
                                        $con->execute();

                                    }
                                    else if($auth_payment_details[1]['is_custom']==1){
                                       $splash_update_default=1;
                      				  $splash_update_custom=1;


                                    }
                                    else{

                                     $splash_update_default=1;
                         			 $splash_update_custom=1;     
                                  }

                              }
                          }
                          else{
                           $splash_update_default=1;
                           $splash_update_custom=1;
                       }  
                       $query = 'UPDATE app_data SET splash_screen_id=:imgdata,is_default_splash=:is_default_splash,is_self_custom_splashscreen=:is_self_custom_splashscreen WHERE id=:appid';
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':imgdata', $imagedata, PDO::PARAM_INT);
                    $con->bindParam(':is_default_splash', $splash_update_default, PDO::PARAM_INT);
                    $con->bindParam(':is_self_custom_splashscreen', $splash_update_custom, PDO::PARAM_INT);
                    $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                    $con->execute();
                           
                            //echo 'Image Uploaded Successfully';
                        }
                        echo 'Screens Uploaded Successfully';
                    }

                    $thumb_rect_size1080 = array(1080, 1920);
                    $new_file_name = $image_name_only . '_' . $thumb_rect_size1080[0] . 'X' . $thumb_rect_size1080[1] . '.' . $image_extension;
                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $new_canvas = imagecreatetruecolor($thumb_rect_size1080[0], $thumb_rect_size1080[1]); //Create a new true color image
                    //Copy and resize part of an image with resampling
                    if (imagecopyresampled($new_canvas, $image_res, 0, 0, 0, 0, $thumb_rect_size1080[0], $thumb_rect_size1080[1], $thumb_rect_size1080[0], $thumb_rect_size1080[1])) {
                        $this->save_image($new_canvas, $thumb_save_folder, $image_type, $jpeg_quality);
                    }
                    //$baseurl = baseUrlContent();
                    $image_url = baseUrlWeb();
                    $imageurl = $image_url . 'panelimage/' . $appID . '/' . $thumb_prefix . $new_file_name;
                    $newFileName = $image_name_only . '_' . $thumb_rect_size1080[0] . 'X' . $thumb_rect_size1080[1];
                    $query = "INSERT INTO `splash_screen` (`name`,`app_id`,`image_link`) VALUES (:name,:appid,:imgurl)";
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':name', $newFileName, PDO::PARAM_STR);
                    $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                    $con->bindParam(':imgurl', $imageurl, PDO::PARAM_STR);
                    $con->execute();
                    $imagedata = $dbCon->lastInsertId();

                    $query_auth_detail="SELECT payment_type_id ,payment_type_value,apd.is_custom FROM author_payment_details AS apd JOIN author_payment AS ap ON apd.author_payment_id=ap.id  WHERE ap.app_id=:appid";
                    $con = $dbCon->prepare($query_auth_detail);
                    $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                    $con->execute();
                    $auth_payment_details=$con->fetchAll(PDO::FETCH_ASSOC);
                             //$auth_payment_details = $this->queryRun($query_auth_detail, 'select_all');
                    $splash_update_default=1;
                    $splash_update_custom=1;
                    if(count($auth_payment_details)>0 && isset($auth_payment_details[1]['payment_type_id'])){
                        // for spash screen
                        if($auth_payment_details[1]['payment_type_id']==2){                                
                            if($auth_payment_details[1]['is_custom']==2){
                                $splash_update_default=2;
                                $splash_update_custom=2;
                                $id= $auth_payment_details[1]['payment_type_value'];                            
                                $custom_icon = 'UPDATE custom_splashscreen SET splash_screen_id=:splash_screen_id  WHERE id=:id';
                                $con = $dbCon->prepare($custom_icon);
                                $con->bindParam(':splash_screen_id', $imagedata, PDO::PARAM_INT);
                                $con->bindParam(':id', $id, PDO::PARAM_INT);
                                $con->execute();

                            }
                            else if($auth_payment_details[1]['is_custom']==1){
                                $splash_update_default=1;
                                $splash_update_custom=1;


                            }
                            else{

                              $splash_update_default=1;
                              $splash_update_custom=1;     
                          }

                      }
                  }
                  else{
                   $splash_update_default='1';
                   $splash_update_custom='1';
               }  
               $query = 'UPDATE app_data SET splash_screen_id=:imgdata,is_default_splash=:is_default_splash,is_self_custom_splashscreen=:is_self_custom_splashscreen WHERE id=:appid';
                    $con = $dbCon->prepare($query);
                    $con->bindParam(':imgdata', $imagedata, PDO::PARAM_INT);
                    $con->bindParam(':is_default_splash', $splash_update_default, PDO::PARAM_INT);
                    $con->bindParam(':is_self_custom_splashscreen', $splash_update_custom, PDO::PARAM_INT);
                    $con->bindParam(':appid', $appID, PDO::PARAM_INT);
                    $con->execute();


                    imagedestroy($image_res); //freeup memory
                }
            }
        }
    }

    /*
     * Update Billing details of user
     * Added By Varun Srivastava on 12/8/15
     */

    public function updateUserBillingDetails() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if (isset($_POST) && $_POST['userid'] != '') {
            $query = 'SELECT * from author_payment where author_id="' . $_POST['userid'] . '" AND app_id="' . $_POST['app_id'] . '"';
            $user_trans = $this->queryRun($query, 'select');
            if (count($user_trans) > 0) {
                $query = 'UPDATE author_payment set author_ip=:uip, 
													transaction_id="", 
													total_amount=:tamount, 
													first_name=:fname, 
													last_name=:bname, 
													email_address=:email, 
													phone=:phone, 
													country=:cntry, 
													state=:billstate, 
													city=:bcity, 
													address=:baddress
													WHERE author_id=:uid
													AND app_id=:appid';
                $con = $dbCon->prepare($query);
                $con->bindParam(':uid', $_POST['userid'], PDO::PARAM_INT);
                $con->bindParam(':uip', $_POST['userip'], PDO::PARAM_STR);
                $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_INT);
                $con->bindParam(':tamount', $_POST['total_amount'], PDO::PARAM_STR);
                $con->bindParam(':fname', trim($_POST['billing_fname']), PDO::PARAM_STR);
                $con->bindParam(':bname', trim($_POST['billing_lname']), PDO::PARAM_STR);
                $con->bindParam(':email', trim($_POST['billing_email']), PDO::PARAM_STR);
                $con->bindParam(':phone', trim($_POST['billing_phone']), PDO::PARAM_STR);
                $con->bindParam(':cntry', $_POST['billing_country'], PDO::PARAM_STR);
                $con->bindParam(':billstate', $_POST['billing_state'], PDO::PARAM_STR);
                $con->bindParam(':bcity', trim($_POST['billing_city']), PDO::PARAM_STR);
                $con->bindParam(':baddress', trim($_POST['billing_address1']), PDO::PARAM_STR);
                $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_INT);
                $con->execute();
            } else {
                $query = 'INSERT INTO author_payment set (author_id, author_ip, app_id, transaction_id, total_amount, first_name, last_name, email_address, phone, country, state, city, address) 
						VALUES (:uid, :uip, :appid, "", :tamount,:fname, :bname, :email, :phone, :cntry, :billstate,:bcity, :baddress';
                $con = $dbCon->prepare($query);
                $con->bindParam(':uid', $_POST['userid'], PDO::PARAM_INT);
                $con->bindParam(':uip', $_POST['userip'], PDO::PARAM_STR);
                $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_INT);
                $con->bindParam(':tamount', $_POST['total_amount'], PDO::PARAM_STR);
                $con->bindParam(':fname', trim($_POST['billing_fname']), PDO::PARAM_STR);
                $con->bindParam(':bname', trim($_POST['billing_lname']), PDO::PARAM_STR);
                $con->bindParam(':email', trim($_POST['billing_email']), PDO::PARAM_STR);
                $con->bindParam(':phone', trim($_POST['billing_phone']), PDO::PARAM_STR);
                $con->bindParam(':cntry', $_POST['billing_country'], PDO::PARAM_STR);
                $con->bindParam(':billstate', $_POST['billing_state'], PDO::PARAM_STR);
                $con->bindParam(':bcity', trim($_POST['billing_city']), PDO::PARAM_STR);
                $con->bindParam(':baddress', trim($_POST['billing_address1']), PDO::PARAM_STR);
                $con->execute();
            }
        }
    }

    public function resend_email($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $custid = $data['custid'];
        $url = $data['url'];
        $name = $data['name'];
        $email = $data['email'];
        $uid = uniqid();
        $query = "update  author set verification_code=:uid,email_address=:email,is_confirm=0 where custid=:custid";
        $con = $dbCon->prepare($query);
        $con->bindParam(':uid', $uid, PDO::PARAM_INT);
        $con->bindParam(':email', $email, PDO::PARAM_STR);
        $con->bindParam(':custid', $custid, PDO::PARAM_INT);
        $con->execute();
        $user_id = $con->fetch(PDO::FETCH_ASSOC);

        if ($user_id) {

            $csubject = 'Thank you for registering with Instappy. Verify your e-mail';
            $basicUrl = $url;
            $chtmlcontent = file_get_contents('../edm/Registration.php');
            $cname = ucwords($name);
            $chtmlcontent = str_replace('{customer_name}', $cname, $chtmlcontent);
            $chtmlcontent = str_replace('{base_url}', $basicUrl, $chtmlcontent);
            $chtmlcontent = str_replace('{verify_link}', $basicUrl . 'signup-verification.php?verification=' . $uid, $chtmlcontent);
            $cto = $email;
            $cformemail = 'noreply@instappy.com';
            $key = 'f894535ddf80bb745fc15e47e42a595e';
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'api_key' => $key,
                    'subject' => $csubject,
                    'fromname' => 'Instappy',
                    'from' => $cformemail,
                    'content' => $chtmlcontent,
                    'recipients' => $cto
                )
            ));
            $customerhead = curl_exec($curl);
            curl_close($curl);
            if ($customerhead) {
                echo "success";
            }
        } else {
            echo "fails";
        }
    }

    function uploadAndroidPublish() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        //print_r($_POST);
        //print_r($_FILES); exit;
        /* for Phone screen */
        $appid = $_POST['app_id'];
        $phone_screen_images = '';




        $queryAndroid = "SELECT * from android_app_data where app_id=:appid LIMIT 0,1";
        $con = $dbCon->prepare($queryAndroid);
        $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_INT);
        $con->execute();
        $resultAndroid = $con->fetch(PDO::FETCH_ASSOC);

        if (empty($resultAndroid)) {
            $query = "INSERT INTO android_app_data (app_id,title,short_description,full_description,featured_banner_link,promo_video_link,applicationtype,googleplay_category,contentrating,phone_no,website_link,email_address,privacy_policy_link,androidpublisher_email,developerconsole_name) 
                            VALUES (:appid,:title,:sdesc,:description,:pvideo,:apptype,:appcat,:rating,:phone,:web,:email,:purl,:pemail,:appdevacc)";
            $con = $dbCon->prepare($query);
            $con->bindParam(':title', htmlentities($_POST['app_name']), PDO::PARAM_STR);
            $con->bindParam(':description', htmlentities($_POST['app_full_desc']), PDO::PARAM_STR);
            $con->bindParam(':sdesc', htmlentities($_POST['app_short_desc']), PDO::PARAM_STR);
            $con->bindParam(':pvideo', htmlentities($_POST['app_promo_url']), PDO::PARAM_STR);
            $con->bindParam(':purl', htmlentities($_POST['app_privacy_url']), PDO::PARAM_STR);
            $con->bindParam(':pemail', htmlentities($_POST['app_dev_acc_email']), PDO::PARAM_STR);
            $con->bindParam(':web', htmlentities($_POST['app_website']), PDO::PARAM_STR);
            $con->bindParam(':appdevacc', htmlentities($_POST['app_dev_acc_name']), PDO::PARAM_STR);
            $con->bindParam(':apptype', $_POST['app_type'], PDO::PARAM_INT);
            $con->bindParam(':appcat', $_POST['app_category'], PDO::PARAM_STR);
            $con->bindParam(':rating', $_POST['app_contant_rating'], PDO::PARAM_STR);
            $con->bindParam(':phone', $_POST['app_phone'], PDO::PARAM_STR);
            $con->bindParam(':email', htmlentities($_POST['app_email']), PDO::PARAM_STR);
            $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_STR);

            if ($con->execute()) {

                echo "success";
            } else {
                echo "fails";
            }
        } else {
            $query = "update  android_app_data set title=:title,short_description=:sdesc,full_description=:description,promo_video_link=:pvideo,applicationtype=:apptype,googleplay_category=:appcat,contentrating=:rating,phone_no=:phone,website_link=:web,email_address=:email,privacy_policy_link=:purl,androidpublisher_email=:pemail,developerconsole_name=:appdevacc where app_id=:appid";
            $con = $dbCon->prepare($query);
            $con->bindParam(':title', htmlentities($_POST['app_name']), PDO::PARAM_STR);
            $con->bindParam(':description', htmlentities($_POST['app_full_desc']), PDO::PARAM_STR);
            $con->bindParam(':sdesc', htmlentities($_POST['app_short_desc']), PDO::PARAM_STR);
            $con->bindParam(':pvideo', htmlentities($_POST['app_promo_url']), PDO::PARAM_STR);
            $con->bindParam(':purl', htmlentities($_POST['app_privacy_url']), PDO::PARAM_STR);
            $con->bindParam(':pemail', htmlentities($_POST['app_dev_acc_email']), PDO::PARAM_STR);
            $con->bindParam(':web', htmlentities($_POST['app_website']), PDO::PARAM_STR);
            $con->bindParam(':appdevacc', htmlentities($_POST['app_dev_acc_name']), PDO::PARAM_STR);
            $con->bindParam(':apptype', $_POST['app_type'], PDO::PARAM_INT);
            $con->bindParam(':appcat', $_POST['app_category'], PDO::PARAM_STR);
            $con->bindParam(':rating', $_POST['app_contant_rating'], PDO::PARAM_STR);
            $con->bindParam(':phone', $_POST['app_phone'], PDO::PARAM_STR);
            $con->bindParam(':email', htmlentities($_POST['app_email']), PDO::PARAM_STR);
            $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_STR);


            if ($con->execute()) {

                echo "success";
            } else {
                echo "fails";
            }
        }
    }

    function uploadIOSPublish() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $screen_image_1 = '';
        $screen_image_2 = '';
        $screen_image_3 = '';
        $screen_image_4 = '';
        $screen_image_5 = '';
        $appid = $_POST['app_id'];
        if (!isset($_FILES['upload_image_1']) || !is_uploaded_file($_FILES['upload_image_1']['tmp_name'])) {
            // echo 'Image file is Missing!';
        } else {
            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_1']) || !is_uploaded_file($_FILES['upload_image_1']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_1']['name']; //file name
                $image_size = $_FILES['upload_image_1']['size']; //file size
                $image_temp = $_FILES['upload_image_1']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        //$baseurl = baseUrlContent();
                        $image_url = baseUrlWeb();
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_1 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 2 starts */
        if (!isset($_FILES['upload_image_2']) || !is_uploaded_file($_FILES['upload_image_2']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_2']) || !is_uploaded_file($_FILES['upload_image_2']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_2']['name']; //file name
                $image_size = $_FILES['upload_image_2']['size']; //file size
                $image_temp = $_FILES['upload_image_2']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        //$baseurl = baseUrlContent();
                        $image_url = baseUrlWeb();
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_2 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 2 Ends */
        /* IMAGE 3 starts */
        if (!isset($_FILES['upload_image_3']) || !is_uploaded_file($_FILES['upload_image_3']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_3']) || !is_uploaded_file($_FILES['upload_image_3']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_3']['name']; //file name
                $image_size = $_FILES['upload_image_3']['size']; //file size
                $image_temp = $_FILES['upload_image_3']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        //$baseurl = baseUrlContent();
                        $image_url = baseUrlWeb();
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_3 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 3 Ends */
        /* IMAGE 4 starts */
        if (!isset($_FILES['upload_image_4']) || !is_uploaded_file($_FILES['upload_image_4']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_4']) || !is_uploaded_file($_FILES['upload_image_4']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }

                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_4']['name']; //file name
                $image_size = $_FILES['upload_image_4']['size']; //file size
                $image_temp = $_FILES['upload_image_4']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        //$baseurl = baseUrlContent();
                        $image_url = baseUrlWeb();
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_4 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 4 Ends */
        /* IMAGE 5 starts */
        if (!isset($_FILES['upload_image_5']) || !is_uploaded_file($_FILES['upload_image_5']['tmp_name'])) {
            //	echo 'Image file is Missing!';
        } else {

            ############ Configuration ##############
            $thumb_square_size = 200; //Thumbnails will be cropped to 200x200 pixels
            $max_image_size = 500; //Maximum image size (height and width)
            $thumb_prefix = "thumb_"; //Normal thumb Prefix
            $destination_folder = '../panelimage/' . $appid . '/'; //upload directory ends with / (slash)
            if (!is_dir($destination_folder)) {
                mkdir($destination_folder, 0755, true);
            }
            $jpeg_quality = 90; //jpeg quality
            ##########################################
            //continue only if $_POST is set and it is a Ajax request
            if (isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                // check $_FILES['ImageFile'] not empty
                if (!isset($_FILES['upload_image_5']) || !is_uploaded_file($_FILES['upload_image_5']['tmp_name'])) {
                    die('Image file is Missing!'); // output error when above checks fail.
                }
                //uploaded file info we need to proceed
                $image_name = $_FILES['upload_image_5']['name']; //file name
                $image_size = $_FILES['upload_image_5']['size']; //file size
                $image_temp = $_FILES['upload_image_5']['tmp_name']; //file temp

                $image_size_info = getimagesize($image_temp); //get image size

                if ($image_size_info) {
                    $image_width = $image_size_info[0]; //image width
                    $image_height = $image_size_info[1]; //image height
                    $image_type = $image_size_info['mime']; //image type
                } else {
                    die("Make sure image file is valid!");
                }

                //switch statement below checks allowed image type 
                //as well as creates new image from given file 
                switch ($image_type) {
                    case 'image/png':
                        $image_res = imagecreatefrompng($image_temp);
                        break;
                    case 'image/gif':
                        $image_res = imagecreatefromgif($image_temp);
                        break;
                    case 'image/jpeg': case 'image/pjpeg':
                        $image_res = imagecreatefromjpeg($image_temp);
                        break;
                    default:
                        $image_res = false;
                }

                if ($image_res) {
                    //Get file extension and name to construct new file name 
                    $image_info = pathinfo($image_name);
                    $image_extension = strtolower($image_info["extension"]); //image extension
                    $image_name_only2 = strtolower($image_info["filename"]); //file name only, no extension
                    $image_name_only = str_replace(" ", "_", $image_name_only2); //file name replace space
                    //create a random name for new image (Eg: fileName_293749.jpg) ;
                    $new_file_name = $image_name_only . '_' . rand(0, 9999999999) . '.' . $image_extension;

                    //folder path to save resized images and thumbnails
                    $thumb_save_folder = $destination_folder . $thumb_prefix . $new_file_name;
                    $image_save_folder = $destination_folder . $new_file_name;

                    //call normal_resize_image() function to proportionally resize image
                    if ($this->normal_resize_image($image_res, $image_save_folder, $image_type, $max_image_size, $image_width, $image_height, $jpeg_quality)) {
                        //call crop_image_square() function to create square thumbnails
                        if (!$this->crop_image_square($image_res, $thumb_save_folder, $image_type, $thumb_square_size, $image_width, $image_height, $jpeg_quality)) {
                            die('Error Creating thumbnail');
                        }

                        /* We have succesfully resized and created thumbnail image
                          We can now output image to user's browser or store information in the database */
                        /* echo '<div align="center">';
                          echo '<img src="uploads/'.$thumb_prefix . $new_file_name.'" alt="Thumbnail">';
                          echo '<br />';
                          echo '<img src="uploads/'. $new_file_name.'" alt="Resized Image">';
                          echo '</div>'; */
                        //$baseurl = baseUrlContent();
                        $image_url = baseUrlWeb();
                        $imageurl = $image_url . 'panelimage/' . $appid . '/' . $thumb_prefix . $new_file_name;
                        $screen_image_5 = $imageurl;
                    }

                    imagedestroy($image_res); //freeup memory
                }
            }
        }
        /* IMAGE 5 Ends */
        $queryAndroid = "SELECT * from ios_app_data where app_id=:appid LIMIT 0,1";
        $con = $dbCon->prepare($queryAndroid);
        $con->bindParam(':appid', $_POST['app_id'], PDO::PARAM_INT);
        $con->execute();
        $resultAndroid = $con->fetch(PDO::FETCH_ASSOC);



        if (empty($resultAndroid)) {
            $query = "INSERT INTO ios_app_data (app_id,title,full_description,keywords,support_url,marketing_url,privacy_policy_url,iphonesix_link,iphonesixplus_link,iphonefive_link,iphonefour_link,ios_category_one,ios_category_two,rating,copyright,publisher_first_name,publisher_last_name,publisher_phone,publisher_email) 
		VALUES (:appid,:title,:description,:keywords,:surl,:murl,:purl,:imglink,:slink,:silink,:sm4,:apptype,:appcat,'',:cright,:fname,:lname,:phone,:email)";
            $con = $dbCon->prepare($query);
            $con->bindParam(':title', htmlentities($_POST['app_name']), PDO::PARAM_STR);
            $con->bindParam(':description', htmlentities($_POST['app_full_desc']), PDO::PARAM_STR);
            $con->bindParam(':keywords', htmlentities($_POST['app_keywords']), PDO::PARAM_STR);
            $con->bindParam(':surl', htmlentities($_POST['support_url']), PDO::PARAM_STR);
            $con->bindParam(':murl', htmlentities($_POST['meeting_url']), PDO::PARAM_STR);
            $con->bindParam(':purl', htmlentities($_POST['privacy_url']), PDO::PARAM_STR);
            $con->bindParam(':imglink', htmlentities($screen_image_1), PDO::PARAM_STR);
            $con->bindParam(':slink', htmlentities($screen_image_2), PDO::PARAM_STR);
            $con->bindParam(':silink', htmlentities($screen_image_3), PDO::PARAM_STR);
            $con->bindParam(':sm4', htmlentities($screen_image_4), PDO::PARAM_STR);
            $con->bindParam(':apptype', $_POST['app_type'], PDO::PARAM_INT);
            $con->bindParam(':appcat', $_POST['app_category'], PDO::PARAM_STR);
            $con->bindParam(':cright', $_POST['copyright'], PDO::PARAM_STR);
            $con->bindParam(':fname', htmlentities($_POST['first_name']), PDO::PARAM_STR);
            $con->bindParam(':lname', htmlentities($_POST['last_name']), PDO::PARAM_STR);
            $con->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
            $con->bindParam(':email', htmlentities($_POST['email']), PDO::PARAM_STR);
            $con->bindParam(':appid', $appid, PDO::PARAM_STR);
            $con->execute();
            echo "Uploaded Successfully!";
        } else {
            $query = "update  ios_app_data set title=:title,full_description=:description,keywords=:keywords,support_url=:surl,marketing_url=:murl,privacy_policy_url=:purl,iphonesix_link=:imglink,iphonesixplus_link=:slink,iphonefive_link=:silink,iphonefour_link=:sm4,ios_category_one=:apptype,ios_category_two=:appcat,rating='',copyright=:cright,publisher_first_name=:fname,publisher_last_name=:lname,publisher_phone=:phone,publisher_email=:email where app_id=:appid";
            $con = $dbCon->prepare($query);
            $con->bindParam(':title', htmlentities($_POST['app_name']), PDO::PARAM_STR);
            $con->bindParam(':description', htmlentities($_POST['app_full_desc']), PDO::PARAM_STR);
            $con->bindParam(':keywords', htmlentities($_POST['app_keywords']), PDO::PARAM_STR);
            $con->bindParam(':surl', htmlentities($_POST['support_url']), PDO::PARAM_STR);
            $con->bindParam(':murl', htmlentities($_POST['meeting_url']), PDO::PARAM_STR);
            $con->bindParam(':purl', htmlentities($_POST['privacy_url']), PDO::PARAM_STR);
            $con->bindParam(':imglink', htmlentities($screen_image_1), PDO::PARAM_STR);
            $con->bindParam(':slink', htmlentities($screen_image_2), PDO::PARAM_STR);
            $con->bindParam(':silink', htmlentities($screen_image_3), PDO::PARAM_STR);
            $con->bindParam(':sm4', htmlentities($screen_image_4), PDO::PARAM_STR);
            $con->bindParam(':apptype', $_POST['app_type'], PDO::PARAM_INT);
            $con->bindParam(':appcat', $_POST['app_category'], PDO::PARAM_STR);
            $con->bindParam(':cright', $_POST['copyright'], PDO::PARAM_STR);
            $con->bindParam(':fname', htmlentities($_POST['first_name']), PDO::PARAM_STR);
            $con->bindParam(':lname', htmlentities($_POST['last_name']), PDO::PARAM_STR);
            $con->bindParam(':phone', $_POST['phone'], PDO::PARAM_STR);
            $con->bindParam(':email', htmlentities($_POST['email']), PDO::PARAM_STR);
            $con->bindParam(':appid', $appid, PDO::PARAM_STR);
            $con->execute();

            echo "Uploaded Successfully!";
        }
    }

    /*
     * function for app wizard api
     * Added by Arun Srivastava on 2/9/15
     */

    public function wizardInit($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ((isset($data['author_id']) && $data['author_id'] != '') && (isset($data['app_name']) && $data['app_name'] != '') && (isset($data['email_id']) && $data['email_id'] != '')) {
            $query1 = "SELECT ad.summary as appName, ad.app_id as appId, ad.type_app as appType FROM app_data ad LEFT JOIN author a ON a.id=ad.author_id WHERE a.custid=:custid AND LOWER(ad.summary)=:appname AND a.email_address=:email AND ad.deleted=0 LIMIT 0,1";
            $con = $dbCon->prepare($query1);
            $con->bindParam(':custid', $data['author_id'], PDO::PARAM_INT);
            $con->bindParam(':appname', $data['app_name'], PDO::PARAM_STR);
            $con->bindParam(':email', $data['email_id'], PDO::PARAM_STR);
            $con->execute();
            $result1 = $con->fetch(PDO::FETCH_ASSOC);


            if (!empty($result1)) {
                $dataR = array(
                    'app' => array(
                        'appName' => $result1['appName'],
                        'appId' => $result1['appId'],
                        'appType' => $result1['appType']
                    )
                );
                $json = $this->real_json_encode($dataR, 'successData', '', '200');
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'No data found', '405');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', '405');
            echo $json;
        }
    }

    public function author_changelog($data) {

        print_r($data);
    }

    /*
     * results retail component data to api
     * Added By Arun Srivastava on 21/3/16
     */

    function getComponents() {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        $query = "SELECT id, name FROM component_type WHERE app_type_id=3 order by sort_order ASC";
        $con = $dbCon->prepare($query);
        $con->execute();
        $data = $con->fetchAll(PDO::FETCH_ASSOC);
        $json = $this->real_json_encode($data, 'successData', '', 200, '', '');
        echo $json;
    }

    function retailAppData($data) {
        $dbCon = content_db();
        $dbConRetail = retail_db();
        if ((isset($data['app_id']) && trim($data['app_id']) != '')) {
            $app_idString = $data['app_id'];

            $appQueryData = "select * from app_data where app_id=:appid";
            $con = $dbCon->prepare($appQueryData);
            $con->bindParam(':appid', $app_idString, PDO::PARAM_STR);
            $con->execute();
            $app_screenData = $con->fetch(PDO::FETCH_ASSOC);


            if ($app_screenData != '') {
                $app_id = $app_screenData['id'];

                $baseUrl = baseUrlContent();
                $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`=:appid";
                $con = $dbCon->prepare($query);
                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $con->execute();
                $launchdata = $con->fetch(PDO::FETCH_ASSOC);


                $out = array();
                if (!empty($launchdata)) {
                    $i = 0;
                    foreach ($launchdata as $key => $subarr) {
                        if ($key != 'title' && $key != 'background_color' && $key != 'app_tag_id') {
                            $out[$i][$key] = $subarr;
                            $i++;
                        }
                    }
                }

                $bannerdata = array();
                $categorydata = array();
                $showcasedata = array();

                if (!empty($out)) {
                    $i = 1;
                    foreach ($out as $tempdata) {
                        $tempCdata = array(
                            "itemheading" => "",
                            "itemdesc" => "",
                            "imageurl" => $tempdata['banner' . $i],
                            "itemid" => ""
                        );
                        $i++;
                        $bannerdata[] = $tempCdata;
                    }
                }

                // ga tracking id
                $ga_tracking = '';

                //if($app_screenData['analytics_code'] != '')
                //{
                $gatrackingsql = "SELECT * FROM app_analytics_mapping WHERE app_id = :id";
                $con = $dbCon->prepare($gatrackingsql);
                $con->bindParam(':id', $app_screenData['id'], PDO::PARAM_INT);
                $con->execute();
                $gatracking = $con->fetch(PDO::FETCH_ASSOC);


                if (!empty($gatracking)) {
                    $ga_tracking = $gatracking['analytics_id'];
                }
                //}
                // merchent id
                $merchent_id = '';
                $get_user_sql = "SELECT * FROM author WHERE id = :id";
                $con = $dbCon->prepare($get_user_sql);
                $con->bindParam(':id', $app_screenData['author_id'], PDO::PARAM_INT);
                $con->execute();
                $get_user = $con->fetch(PDO::FETCH_ASSOC);

                if (!empty($get_user)) {
                    $get_ocuser_sql = "SELECT v.merchant_id FROM oc_user u LEFT JOIN oc_vendors v ON v.user_id=u.user_id WHERE u.email = :email";
                    $con = $dbConRetail->prepare($get_ocuser_sql);
                    $con->bindParam(':email', $get_user['email_address'], PDO::PARAM_STR);
                    $con->execute();
                    $get_ocuser = $con->fetch(PDO::FETCH_ASSOC);
                    if (!empty($get_ocuser) && $get_ocuser['merchant_id'] != '') {
                        $merchent_id = $get_ocuser['merchant_id'];
                    }
                }


                // terms and conditions
                $authorQuery00 = "select * from app_catalogue_attr where app_id=:appid";
                $con = $dbCon->prepare($authorQuery00);
                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $con->execute();
                $appctlgattr = $con->fetch(PDO::FETCH_ASSOC);


                $getauthor = 0;
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['is_tnc'] == 0) {
                        $tnc = 'http://www.instappy.com/terms-of-service.php';
                    } else {
                        $tnc = $appctlgattr['tnc_link'];
                    }
                } else {
                    $tnc = '';
                }

                // contact us details
                $getauthor = 0;
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['is_contactus'] == 0) {
                        $contactarr = array(
                            'contact_email' => '',
                            'contact_phone' => '',
                            'is_contactus' => false
                        );
                    } else {
                        $contactarr = array(
                            'contact_email' => $appctlgattr['contactus_email'],
                            'contact_phone' => $appctlgattr['contactus_no'],
                            'is_contactus' => true
                        );
                    }
                } else {
                    $contactarr = array(
                        'contact_email' => $appctlgattr['contactus_email'],
                        'contact_phone' => $appctlgattr['contactus_no'],
                        'is_contactus' => false
                    );
                }

                // font & discount color
                $fontcolor = "#7d7d7d";
                $disccolor = "#FF0000";
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['text_color'] != '') {
                        $fontcolor = $appctlgattr['text_color'];
                    }

                    if ($appctlgattr['discount_color'] != '') {
                        $disccolor = $appctlgattr['discount_color'];
                    }
                }

                // currancy
                $curr_id = 4;
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['curr_id'] != '') {
                        $curr_id = $appctlgattr['curr_id'];
                    }
                }

                // default currency
                $defaultcurrquery = "SELECT * FROM oc_currency WHERE currency_id=:currid";
                $con = $dbConRetail->prepare($defaultcurrquery);
                $con->bindParam(':currid', $curr_id, PDO::PARAM_INT);
                $con->execute();
                $defaultcurrency = $con->fetch(PDO::FETCH_ASSOC);


                $defaultcurrencyArr = array();
                $defaultcurrencyArr['title'] = $defaultcurrency['title'];
                $defaultcurrencyArr['code'] = $defaultcurrency['code'];

                if ($defaultcurrency['symbol_left'] != '') {
                    $defaultcurrencyArr['symbol'] = $defaultcurrency['symbol_left'];
                    $defaultcurrencyArr['is_left'] = 1;
                } else {
                    $defaultcurrencyArr['symbol'] = $defaultcurrency['symbol_right'];
                    $defaultcurrencyArr['is_left'] = 0;
                }
                $defaultcurrencyArr['conversion_value'] = round($defaultcurrency['value'], 2);

                if ($defaultcurrencyArr['code'] == 'GBP') {
                    $defaultcurrencyArr['symbol_code'] = 'e803';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e803';
                } elseif ($defaultcurrencyArr['code'] == 'USD' || $defaultcurrencyArr['code'] == 'SGD') {
                    $defaultcurrencyArr['symbol_code'] = 'e800';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e800';
                } elseif ($defaultcurrencyArr['code'] == 'EUR') {
                    $defaultcurrencyArr['symbol_code'] = 'e801';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e801';
                } elseif ($defaultcurrencyArr['code'] == 'INR') {
                    $defaultcurrencyArr['symbol_code'] = 'e802';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e802';
                }

                // theme layout
                $themelayoutsql = "SELECT * FROM retail_app_component WHERE app_id=:appid AND isActive = 1 ORDER BY sort_order";
                $con = $dbCon->prepare($themelayoutsql);
                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $con->execute();
                $themelayout = $con->fetchAll(PDO::FETCH_ASSOC);
                $comp_array = array();
                if (!empty($themelayout)) {
                    $compVal = '';
                    foreach ($themelayout as $layout) {
                        if ($layout['component_id'] == 101 || $layout['component_id'] == 102 || $layout['component_id'] == 103) { // for all categories
                            $cquery = "SELECT rap.category_id FROM retail_app_products rap 
											LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
											LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
											WHERE rap.app_id = :appid AND rap.isActive=1 
											AND rap.type = 1
											AND rac.component_id = :compid";
                            $con = $dbCon->prepare($cquery);
                            $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->execute();
                            $catlist = $con->fetch(PDO::FETCH_ASSOC);

                            $finalcats = array();
                            if (!empty($catlist)) {
                                $cquery = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon as icomoon_code
											FROM oc_category c 
											LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
											LEFT JOIN oc_product_to_category opc ON opc.category_id=cd.category_id 
											LEFT JOIN oc_app_id oai ON oai.product_id=opc.product_id 
											WHERE c.parent_id = :catid AND oai.app_id =:appid GROUP BY opc.category_id";
                                $con = $dbConRetail->prepare($cquery);
                                $con->bindParam(':catid', $catlist['category_id'], PDO::PARAM_INT);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->execute();
                                $catdata = $con->fetchAll(PDO::FETCH_ASSOC);


                                if (!empty($catdata)) {
                                    foreach ($catdata as $tcdata) {
                                        $compVal = $tcdata['parent_id'];
                                        // checking if current category is having child category or not
                                        $cqueryp = "SELECT count(*) as totalcount FROM oc_category WHERE parent_id =:parentid";
                                        $con = $dbConRetail->prepare($cqueryp);
                                        $con->bindParam(':parentid', $tcdata['itemid'], PDO::PARAM_INT);
                                        $con->execute();
                                        $ischild = $con->fetch(PDO::FETCH_ASSOC);


                                        if (!empty($ischild) && $ischild['totalcount'] > 0) {
                                            $tcdata['is_child_category'] = 1;
                                        } else {
                                            $tcdata['is_child_category'] = 0;
                                        }

                                        foreach ($tcdata as $kk => $perct) {
                                            if ($kk == 'imageurl') {
                                                $author_id = $app_screenData['author_id'];
                                                $authorsql = "SELECT email_address FROM author WHERE id = :id";
                                                $con = $dbCon->prepare($authorsql);
                                                $con->bindParam(':id', $author_id, PDO::PARAM_INT);
                                                $con->execute();
                                                $authordata = $con->fetch(PDO::FETCH_ASSOC);


                                                $userquery = "SELECT user_id FROM oc_user WHERE email = :email";
                                                $con = $dbConRetail->prepare($userquery);
                                                $con->bindParam(':email', $authordata['email_address'], PDO::PARAM_STR);
                                                $con->execute();
                                                $ocuserdata = $con->fetch(PDO::FETCH_ASSOC);

                                                $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = :appid AND category_id = :catid AND vendor_id = :vendorid";
                                                $con = $dbConRetail->prepare($vcimgquery);
                                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                                $con->bindParam(':catid', $tcdata['itemid'], PDO::PARAM_INT);
                                                $con->bindParam(':vendorid', $ocuserdata['user_id'], PDO::PARAM_INT);
                                                $con->execute();
                                                $catimgdata = $con->fetch(PDO::FETCH_ASSOC);


                                                if (!empty($catimgdata)) {
                                                    $img_url = base_image_url() . 'data/' . $authordata['email_address'] . '/' . $app_id . '/' . $catimgdata['image'];
                                                    $tcdata[$kk] = $img_url;
                                                    list($width, $height, $type, $attr) = getimagesize($img_url);

                                                    $tcdata['image_height'] = $height;
                                                    $tcdata['image_width'] = $width;
                                                } else {
                                                    $tcdata[$kk] = '';

                                                    $tcdata['image_height'] = '';
                                                    $tcdata['image_width'] = '';
                                                }
                                            } else {
                                                $tcdata[$kk] = $perct;
                                            }
                                        }
                                        $finalcats[] = $tcdata;
                                    }
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 101) {
                                $span = 1;
                                $scroll_type = 1;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 102) {
                                $span = 1;
                                $scroll_type = 1;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 103) {
                                $span = 2;
                                $scroll_type = 0;
                                $view_all = 1;
                            }

                            $comp_array[] = array(
                                "title" => "Categories",
                                "comp_id" => $layout['sort_order'],
                                "comp_type" => $layout['component_id'],
                                "comp_row_id" => $layout['id'],
                                /* "comp_properties" => array(
                                  "span" => $span,
                                  "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                  "view_all" => $view_all
                                  ), */
                                "comp_val" => $compVal,
                                "elements" => array(
                                    /* "element_count" => count($finalcats), */
                                    "element_array" => $finalcats
                                ),
                                "comp_val_name" => $layout['comp_val_name']
                            );
                        } elseif ($layout['component_id'] == 104 || $layout['component_id'] == 105) { // for showcase products
                            $baseUrl = base_image_url();
                            //$query      = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`='".$app_id."'";
                            $query = "SELECT * FROM `app_special_product` WHERE app_id`=:appid and `comp_id`=:compid";

                            $con = $dbCon->prepare($query);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                            $con->execute();
                            $launchdata = $con->fetch(PDO::FETCH_ASSOC);
                            // product tagging
                            $producttag = '';
                            if (!empty($launchdata)) {
                                $compVal = $launchdata['app_tag_id'];
                                if ($launchdata['app_tag_id'] > 0) {
                                    $querytag = "SELECT * FROM oc_retail_app_tag WHERE id=:tagid";
                                    $con = $dbConRetail->prepare($querytag);
                                    $con->bindParam(':tagid', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                    $con->execute();
                                    $apptag = $con->fetch(PDO::FETCH_ASSOC);

                                    if (!empty($apptag)) {
                                        $producttag = $apptag['tag_name'];
                                    }

                                    if ($launchdata['app_tag_id'] == 6) {
                                        // showcase products
                                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";
                                        $con = $dbConRetail->prepare($cquery);
                                        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                        $con->execute();
                                        $showcase = $con->fetchAll(PDO::FETCH_ASSOC);
                                    } else {
                                        // showcase products
                                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,p.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_specs opps ON p.product_id=opps.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND opps.app_tag_id = :tagid AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";

                                        $con = $dbConRetail->prepare($cquery);
                                        $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                        $con->bindParam(':tagid', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                        $con->execute();
                                        $tshowcase = $con->fetchAll(PDO::FETCH_ASSOC);



                                        $showcase = array();
                                        if (!empty($tshowcase)) {
                                            foreach ($tshowcase as $tttttp) {
                                                $ccquery = "SELECT * FROM oc_product_special WHERE product_id = :prodid";
                                                //      $ttshowcase = $this->query_run($ccquery, 'select');
                                                $con = $dbConRetail->prepare($ccquery);
                                                $con->bindParam(':prodid', $tttttp['itemid'], PDO::PARAM_INT);

                                                $con->execute();
                                                $ttshowcase = $con->fetch(PDO::FETCH_ASSOC);

                                                if (!empty($ttshowcase)) {
                                                    $tttttp['special_price'] = $ttshowcase['price'];

                                                    $showcase[] = $tttttp;
                                                } else {
                                                    $showcase[] = $tttttp;
                                                }
                                            }
                                        }
                                    }

                                    $showcasedata = array();
                                    if (!empty($showcase)) {
                                        foreach ($showcase as $caseshow) {
                                            foreach ($caseshow as $kkk => $val) {
                                                if ($kkk == 'imageurl') {
                                                    $image_url = $baseUrl . $val;
                                                    $caseshow[$kkk] = $image_url;
                                                    //list($width, $height) = getimagesize($image_url);

                                                    if ($layout['component_id'] == 104) {
                                                        $height = 980;
                                                        $width = 640;
                                                    } elseif ($layout['component_id'] == 105) {
                                                        $height = 1080;
                                                        $width = 1080;
                                                    }
                                                    $caseshow['image_height'] = $height;
                                                    $caseshow['image_width'] = $width;
                                                }
                                            }
                                            $showcasedata[] = $caseshow;
                                        }
                                    }
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 104) {
                                $span = 1;
                                $scroll_type = 1;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 105) {
                                $span = 3;
                                $scroll_type = 0;
                                $view_all = 1;
                            }

                            $comp_array[] = array(
                                "title" => $producttag,
                                "comp_id" => $layout['sort_order'],
                                "comp_type" => $layout['component_id'],
                                "comp_row_id" => $layout['id'],
                                /* "comp_properties" => array(
                                  "span" => $span,
                                  "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                  "view_all" => $view_all
                                  ), */
                                "comp_val" => $compVal,
                                "elements" => array(
                                    "element_count" => count($showcasedata),
                                    "element_array" => $showcasedata
                                ),
                                "comp_val_name" => $layout['comp_val_name']
                            );
                        } elseif ($layout['component_id'] == 106 || $layout['component_id'] == 107 || $layout['component_id'] == 108 || $layout['component_id'] == 111) { // for all banners
                            $query = "SELECT id, banner, heading, subheading FROM app_banners WHERE `app_id`=:appid AND comp_id = :compid";
                            $con = $dbCon->prepare($query);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                            $con->execute();
                            $launchdata = $con->fetchAll(PDO::FETCH_ASSOC);

                            $bannerdata = array();
                            if (!empty($out)) {
                                foreach ($launchdata as $tempdata) {
                                    $image_url = baseImageUrl() . $tempdata['banner'];
                                    list($width, $height) = getimagesize($image_url);

                                    if ($layout['component_id'] == 111) {
                                        $height = 800;
                                        $width = 1280;
                                    }

                                    $tempCdata = array(
                                        "itemheading" => $tempdata['heading'],
                                        "itemdesc" => $tempdata['subheading'],
                                        "imageurl" => $image_url,
                                        "itemid" => $tempdata['id'],
                                        "image_height" => $height,
                                        "image_width" => $width,
                                    );
                                    $bannerdata[] = $tempCdata;
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 106) {
                                $span = 1;
                                $scroll_type = 0;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 107) {
                                $span = 2;
                                $scroll_type = 0;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 108) {
                                $span = 2;
                                $scroll_type = 0;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 111) {
                                $span = 1;
                                $scroll_type = 0;
                                $view_all = 0;
                            }

                            $comp_array[] = array(
                                "title" => "Banners",
                                "comp_id" => $layout['sort_order'],
                                "comp_type" => $layout['component_id'],
                                "comp_row_id" => $layout['id'],
                                /* "comp_properties" => array(
                                  "span" => $span,
                                  "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                  "view_all" => $view_all
                                  ), */
                                "elements" => array(
                                    "element_count" => count($bannerdata),
                                    "element_array" => $bannerdata
                                ),
                                "comp_val_name" => $layout['comp_val_name']
                            );
                        } elseif ($layout['component_id'] == 109 || $layout['component_id'] == 110) { // for all products
                            $apquery = "SELECT DISTINCT rap.category_id FROM retail_app_products rap 
											LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
											LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
											WHERE rap.app_id = :appid AND rap.isActive=1 
											AND rap.type = 2
											AND rac.component_id = :compid";
                            $con = $dbCon->prepare($apquery);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->bindParam(':compid', $layout['component_id'], PDO::PARAM_INT);
                            $con->execute();
                            $app_products = $con->fetchAll(PDO::FETCH_ASSOC);

                            if (!empty($app_products)) {
                                $prodstr = '';
                                foreach ($app_products as $prodid) {
                                    if ($prodstr == '') {
                                        $prodstr = $prodid['category_id'];
                                    } else {
                                        $prodstr = $prodstr . ', ' . $prodid['category_id'];
                                    }
                                }
                                $clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,p.price AS special_price
											FROM oc_app_id ai 
											JOIN oc_product p ON ai.product_id=p.product_id
											LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
											LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
											LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
											WHERE ai.app_id=:appid AND opc.category_id IN(:prodstr) AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";
                                $con = $dbConRetail->prepare($clquery);
                                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                $con->bindParam(':prodstr', $prodstr, PDO::PARAM_STR);
                                $con->execute();
                                $layproducts = $con->fetchAll(PDO::FETCH_ASSOC);
                                $finallayprod = array();
                                foreach ($layproducts as $prod) {
                                    $proood = array();
                                    foreach ($prod as $key => $perpod) {
                                        if ($key == 'imageurl') {
                                            $image_url = base_image_url() . $prod[$key];
                                            $proood[$key] = $image_url;
                                            list($width, $height) = getimagesize($image_url);

                                            if ($layout['component_id'] == 110) {
                                                $height = $width = 1080;
                                            }
                                            $proood['image_height'] = $height;
                                            $proood['image_width'] = $width;
                                        } else {
                                            $proood[$key] = $prod[$key];
                                        }
                                    }

                                    $finallayprod[] = $proood;
                                }

                                // setting component properties
                                if ($layout['component_id'] == 109) {
                                    $span = 1;
                                    $scroll_type = 1;
                                    $view_all = 0;
                                } elseif ($layout['component_id'] == 110) {
                                    $span = 3;
                                    $scroll_type = 0;
                                    $view_all = 1;
                                }

                                $comp_array[] = array(
                                    "title" => "Products",
                                    "comp_id" => $layout['sort_order'],
                                    "comp_type" => $layout['component_id'],
                                    "comp_row_id" => $layout['id'],
                                    /* "comp_properties" => array(
                                      "span" => $span,
                                      "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                      "view_all" => $view_all
                                      ), */
                                    "elements" => array(
                                        "element_count" => count($finallayprod),
                                        "element_array" => $finallayprod
                                    ),
                                    "comp_val_name" => $layout['comp_val_name']
                                );
                            }
                        } elseif ($layout['component_id'] == 112 || $layout['component_id'] == 113) { // for discounted products
                            $baseUrl = base_image_url();
                            $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`=:appid";
                            $con = $dbCon->prepare($query);
                            $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                            $con->execute();
                            $launchdata = $con->fetch(PDO::FETCH_ASSOC);
                            $producttag = '';
                            if (!empty($launchdata)) {
                                $querytag = "SELECT * FROM oc_retail_app_tag WHERE id=:tagid";
                                $con = $dbConRetail->prepare($querytag);
                                $con->bindParam(':tagid', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                $con->execute();
                                $apptag = $con->fetch(PDO::FETCH_ASSOC);
                                if (!empty($apptag)) {
                                    $producttag = $apptag['tag_name'];
                                    $producttag_id = $apptag['id'];
                                }

                                if ($launchdata['app_tag_id'] == 6) {
                                    // showcase products
                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";
                                    $con = $dbConRetail->prepare($cquery);
                                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                    $con->execute();
                                    $showcase = $con->fetchAll(PDO::FETCH_ASSOC);
                                } else {
                                    // showcase products
                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,p.price AS special_price
												FROM oc_app_id ai 
												JOIN oc_product p ON ai.product_id=p.product_id
												LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
												LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
												LEFT JOIN oc_product_specs opps ON p.product_id=opps.product_id
												LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
												WHERE ai.app_id=:appid AND opps.app_tag_id = :tagid AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";
                                    $con = $dbConRetail->prepare($cquery);
                                    $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                                    $con->bindParam(':tagid', $launchdata['app_tag_id'], PDO::PARAM_INT);
                                    $con->execute();
                                    $tshowcase = $con->fetchAll(PDO::FETCH_ASSOC);


                                    $showcase = array();
                                    if (!empty($tshowcase)) {
                                        foreach ($showcase as $caseshow) {
                                            foreach ($caseshow as $kkk => $val) {
                                                if ($kkk == 'imageurl') {
                                                    $image_url = $baseUrl . $val;
                                                    $caseshow[$kkk] = $image_url;
                                                    list($width, $height) = getimagesize($image_url);

                                                    $caseshow['image_height'] = $height;
                                                    $caseshow['image_width'] = $width;
                                                }
                                            }
                                            $showcasedata[] = $caseshow;
                                        }
                                    }
                                }

                                if (!empty($showcase)) {
                                    foreach ($showcase as $kkk => $val) {
                                        if ($kkk == 'imageurl') {
                                            $image_url = $baseUrl . $val['imageurl'];
                                            $showcase[$kkk] = $image_url;
                                            list($width, $height) = getimagesize($image_url);

                                            $showcase['image_height'] = $height;
                                            $showcase['image_width'] = $width;
                                        }
                                        $showcasedata[] = $showcase;
                                    }
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 112) {
                                $span = 1;
                                $scroll_type = 1;
                                $view_all = 0;
                            } elseif ($layout['component_id'] == 113) {
                                $span = 2;
                                $scroll_type = 0;
                                $view_all = 1;
                            }

                            $comp_array[] = array(
                                "title" => $producttag,
                                "title_id" => $producttag_id,
                                "comp_id" => $layout['sort_order'],
                                "comp_type" => $layout['component_id'],
                                "comp_row_id" => $layout['id'],
                                /* "comp_properties" => array(
                                  "span" => $span,
                                  "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                  "view_all" => $view_all
                                  ), */
                                "elements" => array(
                                    "element_count" => count($showcasedata),
                                    "element_array" => $showcasedata
                                ),
                                "comp_val_name" => $layout['comp_val_name']
                            );
                        }
                    }
                }

                $baseUrl = baseUrlContent();
                $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`=:appid";
                $con = $dbCon->prepare($query);
                $con->bindParam(':appid', $app_id, PDO::PARAM_INT);
                $con->execute();
                $launchdata = $con->fetch(PDO::FETCH_ASSOC);
                $data = array(
                    "defaultcurrency" => $defaultcurrencyArr,
                    "screen_properties" => array(
                        "title" => $launchdata['title'],
                        "popup_flag" => 0,
                        "background_color" => $launchdata['background_color'],
                        "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png",
                        "font_color" => $fontcolor,
                        "discount_color" => $disccolor
                    ),
                    "tnc" => $tnc,
                    "contact_details" => $contactarr,
                    "comp_count" => count($themelayout),
                    "comp_array" => $comp_array
                );

                $json = $this->real_json_encode($data, 'successData', '', 200, '', $app_idString);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }
	
	/*
	 * function is to get child categories
	 * Added By Arun Srivastava on 22/9/16
	 */
	public function getCurrentCatChild($app_id, $cat_id, $limit='0')
	{
		$dbConRetail = retail_db();
		if($limit == 0)
		{
			$ProdLimit = '';  
		}
		else
		{
			$ProdLimit='limit '.$limit;   
		}
		$basesql = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
					FROM oc_app_id ai JOIN oc_product p ON ai.product_id=p.product_id 
					LEFT JOIN oc_product_to_category pc ON p.product_id=pc.product_id 
					LEFT JOIN oc_product_description pd ON (p.product_id = pd.product_id) 
					LEFT JOIN oc_category c ON pc.category_id=c.category_id
					LEFT JOIN oc_vendor vd ON (pd.product_id = vd.vproduct_id) 
					LEFT JOIN oc_vendors vds ON (vd.vendor = vds.vendor_id) 
					LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
					WHERE ai.app_id=:appid AND c.category_id<>'' AND ai.app_id<>'' AND pd.language_id = '1'  
					GROUP BY c.category_id ORDER BY c.parent_id ASC";
		$con = $dbConRetail->prepare($basesql);
		$con->bindParam(':appid', $app_id, PDO::PARAM_INT);
		$con->execute();
		$catdata = $con->fetchAll(PDO::FETCH_ASSOC);
		
		
		$catArr = array();
		if(!empty($catdata))
		{
			$singlecat=array();
			$lastParent='';
			foreach($catdata as $singlecat)
			{
				if(count($catArr) == $limit && $limit != 0)
				{  
					break;
				}
				if($singlecat['parent_id'] == $cat_id)
				{                     
					$catArr[] = $singlecat;
				}
				else 
				{
					$topImgCat=array();

					if($lastParent != $singlecat['parent_id'])
					{ 
						$topImgCat = $this->getAllTopLevelCat($singlecat['parent_id'], $cat_id);
					}
                    $lastParent = $singlecat['parent_id'];
                                        
					if(!empty($topImgCat))
					{
						//Dont know why this is happening/ PLs check @Hemant-Ritu
						if(!in_array($topImgCat, $catArr))
						{
							$catArr[] = $topImgCat;
						}
					}
				}         
			}
		}
		
		return $catArr;
	}
	
	/*
	 * function is to get top parent categories
	 * Added By Arun Srivastava on 22/9/16
	 */
	public function getAllTopLevelCat($cat_id, $needle)
	{ 
		$dbConRetail = retail_db();	
		$basesql = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc, c.parent_id, cd.icomoon AS icomoon_code
					FROM oc_category c 
					LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id 
					WHERE c.category_id=:catid";
		$con = $dbConRetail->prepare($basesql);
		$con->bindParam(':catid', $cat_id, PDO::PARAM_INT);
		$con->execute();
		$catdata = $con->fetch(PDO::FETCH_ASSOC);
		
		//$catdata = $this->queryRun($basesql, 'select');
                
		if(!empty($catdata))
		{
			if($catdata['parent_id'] == $needle)
			{  
				return $catdata;
			}
			else
			{
				if($catdata['parent_id'] != 0)
				{
					return $this->getAllTopLevelCat($catdata['parent_id'], $needle);
				}
			}
		}
	}

}
