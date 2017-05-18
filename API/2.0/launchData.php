<?php

require_once('includes/main_functions.php');
require_once('common_functions.php');

class LaunchData extends MainFunctions {

    public function getCatalogLaunchData($data) {
        $searchUrl = '/s3.amazon/';
        $navcatarray = array();
        $cf = new Fwcore();
        $screenopen = "-1";
        if ((isset($data['app_id']) && trim($data['app_id']) != '') && (isset($data['app_type']) && trim($data['app_type']) != '') && (isset($data['device_id']) && trim($data['device_id']) != '') && (isset($data['platform']) && trim($data['platform']) != '') && (isset($data['api_version']) && trim($data['api_version']) != '') && (isset($data['app_version']) && trim($data['app_version']) != '') && (isset($data['first_launch']) && trim($data['first_launch']) != '')) {
            $app_idString = $data['app_id'];
            $app_type = $data['app_type'];
            $device_id = $data['device_id'];
            $platform = $data['platform'];
            $api_version = $data['api_version'];
            $app_version = $data['app_version'];
            $first_launch = $data['first_launch'];

            $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $cf->query_run($appQueryData, 'select');

            if ($app_screenData != '') {
                $app_id = $app_screenData['id'];
                $baseUrl = baseUrl();
                $attrData = $this->getAppAttrData($app_id, $app_screenData);

                $bannerdata = $attrData['bannerData'];
                $themelayoutsql = "SELECT * FROM retail_app_component WHERE app_id='" . $app_id . "' AND isActive = 1 ORDER BY sort_order";
                $themelayout = $cf->query_run($themelayoutsql, 'select_all');
                $comp_array = array();
                if (!empty($themelayout)) {

                    $allcatData = $cf->getCatDataForApp($app_id);

                    foreach ($themelayout as $layout) {
                        $productType1 = array(101, 102, 103);
                        $productType2 = array(109, 110);
                        $bannersType = array(106, 107, 108, 111);
                        $bannersType1 = array(118);
                        $specialType = array(104, 105);
                        $specialType1 = array(112, 113, 114, 115);
                        $specialType2 = array(116);
                        $specialType3 = array(117);
                                                if (in_array($layout['component_id'], $productType1)) {
                            $cquery = "SELECT rap.category_id, rap.cat_id, rap.prod_id, rap.is_catalogue FROM retail_app_products rap 
									LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
									LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
									WHERE rap.app_id = '" . $app_id . "' AND rap.isActive=1 
									AND rap.type = 1
									AND rac.component_id = '" . $layout['component_id'] . "'";
                            $catlist = $this->query_run($cquery, 'select');
                            $cat_count = 0;
                            $finalcats = array();
                            if (!empty($catlist)) {
                                $catdata = $cf->getCurrentCatChildComp($allcatData, $catlist['cat_id'], 4);
                                $cat_count = count($catdata);
                                if (!empty($catdata)) {

                                    foreach ($catdata as $tcdata) {
                                        $cqueryp = "SELECT COUNT(*) AS totalcount 
															FROM oc_category c
															LEFT JOIN oc_product_to_category opc ON opc.category_id = c.category_id
															LEFT JOIN oc_app_id ai ON ai.product_id = opc.product_id
															WHERE c.parent_id = '" . $tcdata['itemid'] . "' AND ai.app_id = '" . $app_id . "'";
                                        $ischild = $this->queryRun($cqueryp, 'select');

                                        if (!empty($ischild) && $ischild['totalcount'] > 0) {
                                            $tcdata['is_child_category'] = "1";
                                            $tcdata['screen_type'] = "0";
                                            $tcdata['is_category'] = "1";

                                            $tcdata['is_navigation'] = "1";
                                        } else {
                                            $tcdata['is_child_category'] = "0";
                                            $tcdata['screen_type'] = "1";
                                            $tcdata['is_category'] = "1";
                                            $tcdata['is_navigation'] = "1";
                                        }
                                        if ($tcdata['is_category'] == "1") {
                                            if ($tcdata['is_child_category'] == "1") {
                                                $screenopen = "2";
                                            } else {
                                                $screenopen = "3";
                                            }
                                        } else {
                                            $screenopen = "4";
                                        }
                                        $navcatarray[] = array("name" => $tcdata['itemheading'], "icon" => $tcdata['icomoon_code'], "is_child" => "0", "level" => "0", "link_to_screenid" => "-1", "catalogue_component" => array("screen_type" => $screenopen, "category_id" => $tcdata['itemid'], "app_type" => $app_type, "catalogue_app_id" => $app_idString), "cat_array" => array());
                                        foreach ($tcdata as $kk => $perct) {
                                            if ($kk == 'imageurl') {
                                                $author_id = $app_screenData['author_id'];
                                                $authorsql = "SELECT email_address FROM author WHERE id = '" . $author_id . "'";
                                                $authordata = $this->query_run($authorsql, 'select');

                                                $userquery = "SELECT user_id FROM oc_user WHERE email = '" . $authordata['email_address'] . "'";
                                                $ocuserdata = $this->queryRun($userquery, 'select');

                                                $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = '" . $app_id . "' AND category_id = '" . $tcdata['itemid'] . "' AND vendor_id = '" . $ocuserdata['user_id'] . "'";

                                                $catimgdata = $this->queryRun($vcimgquery, 'select');

                                                if (!empty($catimgdata)) {

                                                    if (preg_match($searchUrl, $catimgdata['image'])) {
                                                        $img_url = $catimgdata['image'];
                                                    } else {
                                                        $img_url = baseUrl() . 'data/' . strtolower($authordata['email_address']) . '/' . $app_id . '/' . $catimgdata['image'];
                                                    }
                                                    $tcdata[$kk] = $img_url;
                                                    // list($width, $height, $type, $attr) = @getimagesize($img_url);
                                                    $height = '1080';
                                                    $width = '1080';
                                                    $tcdata['image_height'] = "$height";
                                                    $tcdata['image_width'] = "$width";
                                                } else {
                                                    $tcdata[$kk] = 'http://www.instappy.com/images-new/half-large.jpg';
                                                    //    list($width, $height, $type, $attr) = @getimagesize($tcdata[$kk]);

                                                    $tcdata['image_height'] = "1080";
                                                    $tcdata['image_width'] = "1080";
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
                                $span = "1";
                                $scroll_type = "1";
                                $view_all = "0";
                                $default_block = "3";
                            } elseif ($layout['component_id'] == 102) {
                                $span = "1";
                                $scroll_type = "1";
                                $view_all = "0";
                                $default_block = "3";
                            } elseif ($layout['component_id'] == 103) {
                                $span = "2";
                                $scroll_type = "0";
                                $view_all = "1";
                                if ($cat_count < 4) {
                                    $view_all = "0";
                                }
                                $default_block = "4";
                            }

                            $finalcats = $cf->unique_multidim_array($finalcats, 'itemid');

                            if (empty($finalcats)) {
                                for ($i = 0; $i < $default_block; $i++) {
                                    $blank_arr = array(
                                        "itemid" => "0",
                                        "imageurl" => 'http://www.instappy.com/images-new/half-large.jpg',
                                        "itemheading" => "",
                                        "itemdesc" => "",
                                        "parent_id" => "",
                                        "icomoon_code" => "",
                                        "is_child_category" => "0",
                                        "screen_type" => "1",
                                        "is_category" => "1",
                                        "is_navigation" => "0",
                                        "image_height" => "1080",
                                        "image_width" => "1080"
                                    );
                                    $finalcats[] = $blank_arr;
                                }
                            }

                            $finalcaatcount = count($finalcats);

                            $comp_array[] = array(
                                "title" => $layout['title'],
                                "comp_id" => $layout['id'],
                                "comp_type" => $layout['component_id'],
                                "parent_id" => $catlist['cat_id'],
                                "screen_type" => '0',
                                "is_category" => "1",
                                "comp_properties" => array(
                                    "span" => $span,
                                    "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                    "view_all" => $view_all
                                ),
                                "element_count" => "$finalcaatcount",
                                "element_array" => $finalcats
                            );
                        } 
						elseif (in_array($layout['component_id'], $specialType)) { // for showcase products
                            $baseUrl = baseUrl();
                            $query = "SELECT `app_tag_id` FROM `app_special_product` WHERE `app_id`='" . $app_id . "' AND `comp_id`='" . $layout['component_id'] . "' AND retail_app_componentId = '" . $layout['id'] . "' AND isActive=1";
                            $launchdata = $this->query_run($query, 'select');
                            // product tagging
                            $producttag = '';
                            if (!empty($launchdata)) {
                                $querytag = "SELECT * FROM oc_retail_app_tag WHERE id='" . $launchdata['app_tag_id'] . "'";
                                $apptag = $this->queryRun($querytag, 'select');
                                if (!empty($apptag)) {
                                    $producttag = $apptag['tag_name'];
                                }
                                if ($launchdata['app_tag_id'] == 6) {

                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
														FROM oc_app_id ai 
														JOIN oc_product p ON ai.product_id=p.product_id
														LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
														LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
                                                                                                                LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
														LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
														WHERE ai.app_id='" . $app_id . "' AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";
                                    $showcase = $this->queryRun($cquery, 'select_all');
                                } else {

                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
														FROM oc_app_id ai 
														JOIN oc_product p ON ai.product_id=p.product_id
														LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
														LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
                                                                                                                LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
														LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
														WHERE ai.app_id='" . $app_id . "' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";
                                    $tshowcase = $this->queryRun($cquery, 'select_all');

                                    $showcase = array();
                                    if (!empty($tshowcase)) {
                                        foreach ($tshowcase as $tttttp) {
                                            $ccquery = "SELECT * FROM oc_product_special WHERE product_id = '" . $tttttp['itemid'] . "'";
                                            $ttshowcase = $this->queryRun($ccquery, 'select');

                                            if (!empty($ttshowcase)) {

                                                $tttttp['special_price'] = $ttshowcase['price'];
                                                if ($tttttp['special_price'] == null)
                                                    $tttttp['special_price'] = "0";
                                                $tttttp['is_category'] = "0";
                                                $showcase[] = $tttttp;
                                            }
                                            else {
                                                $tttttp['is_category'] = "0";
                                                $showcase[] = $tttttp;
                                            }
                                        }
                                    }
                                }

                                $showcasedata = array();
                                $caseshow = array();
                                if (!empty($showcase)) {
                                    foreach ($showcase as $caseshow) {
                                        foreach ($caseshow as $kkk => $val) {
                                            if ($kkk == 'imageurl') {
                                                if (preg_match($searchUrl, $val)) {
                                                    $image_url = $val;
                                                } else {
                                                    $image_url = $baseUrl . $val;
                                                }

                                                $caseshow[$kkk] = $image_url;
                                                //list($width, $height) = getimagesize($image_url);

                                                if ($layout['component_id'] == 104) {
                                                    $height = "980";
                                                    $width = "640";
                                                } elseif ($layout['component_id'] == 105) {
                                                    $height = "1080";
                                                    $width = "1080";
                                                }
                                                $caseshow['image_height'] = $height != '' ? "$height" : '';
                                                $caseshow['image_width'] = $width != '' ? "$width" : '';
                                                $caseshow['is_category'] = "0";
                                            }
                                            if ($kkk == 'special_price') {
                                                if ($caseshow['special_price'] == null)
                                                    $caseshow['special_price'] = "0";
                                            }
                                        }
                                        $showcasedata[] = $caseshow;
                                    }
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 104) {
                                $span = "1";
                                $scroll_type = "1";
                                $view_all = "0";
                                $default_block = "3";
                            } elseif ($layout['component_id'] == 105) {
                                $span = "3";
                                $scroll_type = 0;
                                $view_all = "1";
                                if (count($showcase) < 6) {
                                    $view_all = "0";
                                }
                                $default_block = "6";
                            }

                            $showcasedatacount = count($showcasedata);
                            if ($showcasedatacount == 0) {
                                if ($showcasedatacount < $default_block) {
                                    $remaining = $default_block - $showcasedatacount;

                                    $imageurl = baseUrl() . 'half-white-products.jpg';
                                    //   list($width, $height) = @getimagesize($imageurl);
                                    $height = '1080';
                                    $width = '1080';
                                    $minusone = 0;

                                    for ($i = 0; $i < $remaining; $i++) {
                                        $tempdummyarray = array(
                                            "itemid" => $minusone,
                                            "itemheading" => "",
                                            "imageurl" => $imageurl,
                                            "image_height" => $height != '' ? "$height" : '',
                                            "image_width" => $width != '' ? "$width" : '',
                                            "actualprice" => 0,
                                            "special_price" => 0,
                                            "is_category" => "0"
                                        );
                                        $showcasedata[] = $tempdummyarray;
                                    }
                                }
                            }

                            $comp_array[] = array(
                                "title" => $layout['title'],
                                "comp_id" => $layout['id'],
                                "comp_type" => $layout['component_id'],
                                "screen_type" => "1",
                                "tag_id" => $launchdata['app_tag_id'],
                                "comp_properties" => array(
                                    "span" => "$span",
                                    "scroll_type" => "$scroll_type", // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                    "view_all" => "$view_all"
                                ),
                                "element_count" => count($showcasedata),
                                "element_array" => $showcasedata
                            );
                        } 
						elseif (in_array($layout['component_id'], $bannersType)) {
                            $tempCdata = array();
                            $query = "SELECT id, banner,banner_url, heading, subheading, is_catalogue, cat_id, prod_id FROM app_banners WHERE `app_id`='" . $app_id . "' AND comp_id = '" . $layout['component_id'] . "' AND isActive='1' AND retail_app_componentId='" . $layout['id'] . "'";
                            $launchdata = $this->query_run($query, 'select_all');

                            $bannerdata = array();
                            if (!empty($launchdata)) {
                                foreach ($launchdata as $tempdata) {
                                    if ($tempdata['is_catalogue'] == 1) {
                                        $is_category = "1";
                                        $itemid = $tempdata['cat_id'];
                                        $is_child_category1 = $this->getCurrentCatChildComp($allcatData, $itemid);
                                             if(!empty($is_child_category1))
                                        {
                                          $is_child_category='1';  
                                        }
                                        else
                                        {
                                            $is_child_category='0';
                                        }
                                    } else {
                                        $is_category = 0;
                                        $is_child_category = 0;
                                        $itemid = $tempdata['prod_id'];
                                    }
                                    //$image_url = baseImageUrl().$app_id.'/'.$tempdata['banner'];
                                    $image_url = $tempdata['banner_url'];
                                    if (preg_match('/http/', $image_url)) {
                                        $image_url = $image_url;
                                    } else {
                                        $image_url = base_url() . $image_url;
                                    }
                                    //  list($width, $height) = @getimagesize($image_url);
                                    if ($layout['component_id'] == 111) {
                                        $height = "800";
                                        $width = "1280";
                                    }
                                    else if($layout['component_id'] == 106)
                                    {
                                        $height = "200";
                                        $width = "800";
                                    }                                         
                                    else {
                                        $height = "600";
                                        $width = "600";
                                    }
                                    //  if($itemid !=0){ 
                                    $tempCdata = array(
                                        "itemheading" => $tempdata['heading'],
                                        "itemdesc" => $tempdata['subheading'],
                                        "imageurl" => $image_url,
                                        "itemid" => $itemid,
                                        "image_height" => $height != '' ? "$height" : '',
                                        "image_width" => $width != '' ? "$width" : '',
                                        "is_category" => $is_category,
                                        "is_child_category" => $is_child_category
                                    );

                                    $bannerdata[] = $tempCdata;
                                    //}
                                }
                            }
                            //print_r($bannerdata);
                            //die();
                            // setting component properties
                            if ($layout['component_id'] == 106) {
                                $span = "1";
                                $scroll_type = "0";
                                $view_all = "0";
                            } elseif ($layout['component_id'] == 107) {
                                $span = "2";
                                $scroll_type = "0";
                                $view_all = "0";
                            } elseif ($layout['component_id'] == 108) {
                                $span = "2";
                                $scroll_type = "0";
                                $view_all = "0";
                            } elseif ($layout['component_id'] == 111) {
                                $span = "1";
                                $scroll_type = "0";
                                $view_all = "0";
                            }

                            $comp_array[] = array(
                                "title" => $layout['title'],
                                "comp_id" => $layout['id'],
                                "comp_type" => $layout['component_id'],
                                "comp_properties" => array(
                                    "span" => $span,
                                    "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                    "view_all" => $view_all
                                ),
                                "element_count" => count($bannerdata),
                                "element_array" => $bannerdata
                            );
                        } 
						elseif (in_array($layout['component_id'], $productType2)) { // for all products
                            $apquery = "SELECT DISTINCT rap.cat_id FROM retail_app_products rap 
													LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
													LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
													WHERE rap.app_id = '" . $app_id . "' AND rap.isActive=1 
													AND rap.type = 2
													AND rac.component_id = '" . $layout['component_id'] . "' AND rap.isActive=1";
                            $app_products = $this->query_run($apquery, 'select');

                            if (!empty($app_products)) {
                                /*
                                  $prodstr = '';
                                  foreach($app_products as $prodid)
                                  {
                                  if($prodstr == '')
                                  {
                                  $prodstr = $prodid['category_id'];
                                  }
                                  else
                                  {
                                  $prodstr = $prodstr.', '.$prodid['category_id'];
                                  }
                                  }
                                 */

                                $prodstr = $app_products['cat_id'];
                                $clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
													FROM oc_app_id ai 
													JOIN oc_product p ON ai.product_id=p.product_id
													LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
													LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
                                                                                                        LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
													LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
													WHERE ai.app_id='" . $app_id . "' AND opc.category_id IN(" . $prodstr . ") AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 10";
                                $layproducts = $this->queryRun($clquery, 'select_all');

                                $finallayprod = array();
                                foreach ($layproducts as $prod) {
                                    $proood = array();
                                    foreach ($prod as $key => $perpod) {
                                        if ($key == 'imageurl') {
                                            if (preg_match($searchUrl, $prod[$key])) {
                                                $image_url = $prod[$key];
                                            } else {
                                                $image_url = baseUrl() . $prod[$key];
                                            }

                                            $proood[$key] = $image_url;
                                            //  list($width, $height) = @getimagesize($image_url);
                                            $height = '1080';
                                            $width = '1080';
                                            if ($layout['component_id'] == 110) {
                                                $height = $width = "1080";
                                            }
                                            $proood['image_height'] = $height != '' ? "$height" : '';
                                            $proood['image_width'] = $width != '' ? "$width" : '';
                                        } else {
                                            $proood[$key] = $prod[$key];
                                        }
                                        $proood['is_category'] = "0";
                                    }

                                    $finallayprod[] = $proood;
                                }

                                // setting component properties
                                if ($layout['component_id'] == 109) {
                                    $span = "1";
                                    $scroll_type = "1";
                                    $view_all = "0";
                                    $default_block = "3";
                                } elseif ($layout['component_id'] == 110) {
                                    $span = "3";
                                    $scroll_type = "0";
                                    $default_block = "6";
                                    $view_all = "1";
                                    $showcasedatacount = count($finallayprod);

                                    if ($showcasedatacount < $default_block) {
                                        $view_all = "0";
                                    }
                                }

                                $showcasedatacount = count($finallayprod);

                                if ($showcasedatacount == 0) {
                                    if ($showcasedatacount < $default_block) {
                                        $remaining = $default_block - $showcasedatacount;

                                        $imageurl = baseUrl() . 'half-white-products.jpg';
                                        // list($width, $height) = @getimagesize($imageurl);
                                        $height = '1080';
                                        $width = '1080';
                                        $minusone = 0;

                                        for ($i = 0; $i < $remaining; $i++) {
                                            $tempdummyarray = array(
                                                "itemid" => $minusone,
                                                "itemheading" => "",
                                                "imageurl" => $imageurl,
                                                "image_height" => $height != '' ? "$height" : '',
                                                "image_width" => $width != '' ? "$width" : '',
                                                "actualprice" => "0",
                                                "special_price" => "0",
                                                "is_category" => ""
                                            );
                                            $finallayprod[] = $tempdummyarray;
                                        }
                                    }
                                }

                                $comp_array[] = array(
                                    "title" => $layout['title'],
                                    "comp_id" => $layout['id'],
                                    "comp_type" => $layout['component_id'],
                                    "screen_type" => "1",
                                    "parent_id" => $prodstr,
                                    "comp_properties" => array(
                                        "span" => $span,
                                        "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                        "view_all" => $view_all
                                    ),
                                    "element_count" => count($finallayprod),
                                    "element_array" => $finallayprod
                                );
                            }
                        } 
						elseif (in_array($layout['component_id'], $specialType1)) { // for discounted products
                            $baseUrl = baseUrl();
                            $query = "SELECT `app_tag_id` FROM `app_special_product` WHERE `app_id`='" . $app_id . "' AND `comp_id`='" . $layout['component_id'] . "' AND retail_app_componentId = '" . $layout['id'] . "' AND isActive=1";
                            $launchdata = $this->query_run($query, 'select');

                            // product tagging
                            $producttag = '';
                            if (!empty($launchdata)) {
                                $querytag = "SELECT * FROM oc_retail_app_tag WHERE id='" . $launchdata['app_tag_id'] . "'";
                                $apptag = $this->queryRun($querytag, 'select');


                                if (!empty($apptag)) {
                                    $producttag = $apptag['tag_name'];
                                }

                                $showcase = array();
                                $showcasedata = array();
                                if ($launchdata['app_tag_id'] == 6) {
                                    // showcase products
                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
														FROM oc_app_id ai 
														JOIN oc_product p ON ai.product_id=p.product_id
														LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
														LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
														LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
														LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
														WHERE ai.app_id='" . $app_id . "' AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";
                                    $showcase = $this->queryRun($cquery, 'select_all');
                                } else {
                                    // showcase products
                                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
														FROM oc_app_id ai 
														JOIN oc_product p ON ai.product_id=p.product_id
														LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
														LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
														LEFT JOIN oc_product_specs opps ON p.product_id=opps.product_id
                                                                                                                LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
														LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
														WHERE ai.app_id='" . $app_id . "' AND opps.app_tag_id = '" . $launchdata['app_tag_id'] . "' AND p.status = 1 ORDER BY p.date_modified LIMIT 0, 6";
                                    $showcase = $this->queryRun($cquery, 'select_all');
                                }


                                if (!empty($showcase)) {
                                    foreach ($showcase as $caseshow) {
                                        foreach ($caseshow as $kkk => $val) {
                                            if ($kkk == 'imageurl') {
                                                if (preg_match($searchUrl, $val)) {
                                                    $image_url = $val;
                                                } else {
                                                    $image_url = $baseUrl . $val;
                                                }

                                                $caseshow[$kkk] = $image_url;
                                                // list($width, $height) = @getimagesize($image_url);
                                                $height = '1080';
                                                $width = '1080';
                                                $caseshow['image_height'] = $height != '' ? "$height" : '';
                                                $caseshow['image_width'] = $width != '' ? "$width" : '';
                                            }
                                            if ($kkk == 'special_price') {
                                                if ($caseshow['special_price'] == null)
                                                    $caseshow['special_price'] = "0";
                                            }
                                        }


                                        $caseshow['is_category'] = "0";
                                        $showcasedata[] = $caseshow;
                                    }
                                }
                            }

                            // setting component properties
                            if ($layout['component_id'] == 112) {
                                $span = "1";
                                $scroll_type = "1";
                                $view_all = "1";
                                if (count($showcase) < 6) {
                                    $view_all = "0";
                                }
                            } elseif ($layout['component_id'] == 113) {
                                $span = "3";
                                $scroll_type = "0";
                                $view_all = "1";
                                if (count($showcase) < 6) {
                                    $view_all = "0";
                                }
                            } elseif ($layout['component_id'] == 114) {
                                $span = "2";
                                $scroll_type = "0";
                                $view_all = "1";
                                if (count($showcase) < 4) {
                                    $view_all = "0";
                                }
                            } elseif ($layout['component_id'] == 115) {
                                $span = "2";
                                $scroll_type = "0";
                                $view_all = "1";
                                if (count($showcase) < 2) {
                                    $view_all = "0";
                                }
                            }

                            $comp_array[] = array(
                                "title" => $producttag,
                                "comp_id" => $layout['id'],
                                "comp_type" => $layout['component_id'],
                                "screen_type" => "1",
                                "tag_id" => $launchdata['app_tag_id'],
                                "comp_properties" => array(
                                    "span" => $span,
                                    "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                    "view_all" => $view_all
                                ),
                                "element_count" => count($showcasedata),
                                "element_array" => $showcasedata
                            );
                        } elseif (in_array($layout['component_id'], $specialType2)) { // for single product
                            $tempCdata = array();
                            $query = "SELECT id, banner,banner_url, heading, subheading, is_catalogue, cat_id, prod_id FROM app_banners WHERE `app_id`='" . $app_id . "' AND comp_id = '" . $layout['component_id'] . "' AND isActive='1' AND retail_app_componentId='" . $layout['id'] . "'";
                            $app_products = $this->query_run($query, 'select');

                            $span = "1";
                            $scroll_type = "0";
                            $view_all = "0";
                            $default_block = "1";

                            if (!empty($app_products)) {
                                $prod_id = $app_products['prod_id'];
                                $clquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image, p.image_rect, p.price AS actualprice,ops.price AS special_price
										FROM oc_app_id ai 
										JOIN oc_product p ON ai.product_id=p.product_id
										LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
										LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
										LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
										LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
										WHERE ai.app_id='" . $app_id . "' AND p.product_id='" . $prod_id . "' AND p.status = 1";

                                $layproducts = $this->queryRun($clquery, 'select');

                                if (!empty($layproducts)) {
                                    $prod = $layproducts;

                                    $finallayprod = array();
                                    $proood = array();
                                    foreach ($prod as $key => $perpod) {
                                        if ($key == 'image_rect') {
                                            if ($prod[$key] != '') {
                                                $imagedataurl = $prod[$key];
                                            } else {
                                                $imagedataurl = $prod['image'];
                                            }

                                            if (preg_match('/http/', $imagedataurl)) {
                                                $image_url = $imagedataurl;
                                            } else {
                                                $image_url = baseUrl() . $imagedataurl;
                                            }
                                            $proood['imageurl'] = $image_url;
                                            //  list($width, $height) = @getimagesize($image_url);
                                            $height = "800";
                                            $width = "1280";
                                            if ((isset($width) && $width != '') && (isset($height) && $height != '')) {
                                                $height = "800";
                                                $width = "1280";
                                            }

                                            $proood['image_height'] = $height != '' ? "$height" : '';
                                            $proood['image_width'] = $width != '' ? "$width" : '';
                                        } else {
                                            $proood[$key] = $prod[$key];
                                        }
                                        $proood['is_category'] = 0;
                                    }

                                    $pquery = "SELECT * FROM oc_product_specs WHERE product_id='" . $prod_id . "'";
                                    $propdata = $this->queryRun($pquery, 'select');

                                    if (!empty($propdata)) {
                                        $proood['is_specification'] = 1;
                                    } else {
                                        $proood['is_specification'] = 0;
                                    }
                                    $proood['addedToWishlist'] = '0';
                                    $finallayprod[] = $proood;
                                    /*
                                      $showcasedatacount = count($finallayprod);

                                      if($showcasedatacount < $default_block)
                                      {
                                      $remaining = $default_block - $showcasedatacount;

                                      $imageurl             = baseUrl().'half-white-products.jpg';
                                      list($width, $height) = @getimagesize($imageurl);
                                      $minusone = 0;

                                      for($i=0; $i < $remaining; $i++)
                                      {
                                      $tempdummyarray = array(
                                      "itemid" => $minusone,
                                      "itemheading" => "",
                                      "imageurl" => $imageurl,
                                      "image_height" => $height != '' ? $height : '',
                                      "image_width" => $width != '' ? $width : '',
                                      "actualprice" => 0,
                                      "special_price" => 0,
                                      "is_category" => ""
                                      );
                                      $finallayprod[] = $tempdummyarray;
                                      }
                                      }
                                     */
                                } else {
                                    $finallayprod = array();
                                    $imageurl = baseUrl() . 'product-img.jpg';
                                    list($width, $height) = @getimagesize($imageurl);
                                    $minusone = 0;

                                    $tempdummyarray = array(
                                        "itemid" => $minusone,
                                        "itemheading" => "",
                                        "imageurl" => $imageurl,
                                        "image_height" => $height != '' ? "$height" : '',
                                        "image_width" => $width != '' ? "$width" : '',
                                        "actualprice" => "0",
                                        "special_price" => "0",
                                        "addedToWishlist" => '0',
                                        "is_category" => ""
                                    );
                                    $finallayprod[] = $tempdummyarray;
                                }

                                $prodstr = $app_products['cat_id'];
                                $comp_array[] = array(
                                    "title" => $layout['title'],
                                    "comp_id" => $layout['id'],
                                    "comp_type" => $layout['component_id'],
                                    "screen_type" => "1",
                                    "parent_id" => $prodstr,
                                    "comp_properties" => array(
                                        "span" => $span,
                                        "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                        "view_all" => $view_all
                                    ),
                                    "element_count" => count($finallayprod),
                                    "element_array" => $finallayprod
                                );
                            }
                        } 
						elseif (in_array($layout['component_id'], $specialType3)) { // for single category
                            $tempCdata = array();
                            $query = "SELECT id, banner,banner_url, heading, subheading, is_catalogue, cat_id, prod_id FROM app_banners WHERE `app_id`='" . $app_id . "' AND comp_id = '" . $layout['component_id'] . "' AND isActive='1' AND retail_app_componentId='" . $layout['id'] . "'";
                            $launchdata = $this->query_run($query, 'select_all');

                            $bannerdata = array();
                            if (!empty($launchdata)) {
                                foreach ($launchdata as $tempdata) {
                                    if ($tempdata['is_catalogue'] == 1) {
                                        $is_category = "1";
                                        $itemid = $tempdata['cat_id'];
                                        $is_child_category1 = $this->getCurrentCatChildComp($allcatData, $itemid);
                                             if(!empty($is_child_category1))
                                        {
                                          $is_child_category='1';  
                                        }
                                        else
                                        {
                                            $is_child_category='0';
                                        }
                                    } else {
                                        $is_category = 0;
                                        $is_child_category = 0;
                                        $itemid = $tempdata['prod_id'];
                                    }

                                    $cqueryp = "SELECT cd.*, c.parent_id, c.image FROM oc_category c LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id WHERE c.category_id = " . $itemid;
                                    $categorydata = $this->queryRun($cqueryp, 'select');
                                    $author_id = $app_screenData['author_id'];
                                    $authorsql = "SELECT email_address FROM author WHERE id = '" . $author_id . "'";
                                    $authordata = $this->query_run($authorsql, 'select');

                                    $userquery = "SELECT user_id FROM oc_user WHERE email = '" . $authordata['email_address'] . "'";
                                    $ocuserdata = $this->queryRun($userquery, 'select');

                                    $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = '" . $app_id . "' AND category_id = '" . $itemid . "' AND vendor_id = '" . $ocuserdata['user_id'] . "'";
                                    $catimgdata = $this->queryRun($vcimgquery, 'select');


                                    if (!empty($catimgdata)) {



                                        //if(!file_exists($image_url))
                                        if (!@getimagesize($catimgdata['image'])) {
                                            $image_url = 'http://www.instappy.com/images-new/product-img.jpg';
                                        } else {
                                            if (@getimagesize($image_url)) {
                                                $image_url = $catimgdata['image'];
                                            } else {
                                                $image_url = baseUrl() . 'data/' . strtolower($authordata['email_address']) . '/' . $app_id . '/' . $catimgdata['image'];
                                            }
                                        }
                                    } else {

                                        $image_url = 'http://www.instappy.com/images-new/product-img.jpg';
                                    }

                                    //   list($width, $height) = @getimagesize($image_url);

                                    $height = "800";
                                    $width = "1280";

                                    $tempCdata = array(
                                        "itemheading" => $categorydata['name'],
                                        "itemdesc" => '',
                                        "imageurl" => $image_url,
                                        "itemid" => $itemid,
                                        "image_height" => $height != '' ? "$height" : '',
                                        "image_width" => $width != '' ? "$width" : '',
                                        "is_category" => $is_category,
                                        "is_child_category" => $is_child_category,
                                        "is_navigation" => "1",
                                        "parent_id" => isset($categorydata['parent_id']) != '' ? $categorydata['parent_id'] : '',
                                        "screen_type" => "1",
                                        "icomoon_code" => isset($categorydata['icomoon']) != '' ? $categorydata['icomoon'] : ''
                                    );

                                    if ($is_category == 1) {
                                        if ($is_child_category == 1) {
                                            $screenopen = "2";
                                        } else {
                                            $screenopen = "3";
                                        }
                                    } else {
                                        $screenopen = "4";
                                    }
                                    $navcatarray[] = array("name" => $categorydata['name'], "icon" => $categorydata['icomoon'], "is_child" => '0', "level" => "0", "link_to_screenid" => "-1", "catalogue_component" => array("screen_type" => $screenopen, "category_id" => $itemid, "app_type" => $app_type, "catalogue_app_id" => $app_idString), "cat_array" => array());
                                    $bannerdata[] = $tempCdata;
                                }
                            }


                            // setting component properties
                            $span = "1";
                            $scroll_type = "0";
                            $view_all = "0";
                            $default_block = "1";

                            $comp_array[] = array(
                                "title" => $layout['title'],
                                "comp_id" => $layout['id'],
                                "comp_type" => $layout['component_id'],
                                "comp_properties" => array(
                                    "span" => $span,
                                    "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                                    "view_all" => $view_all
                                ),
                                "element_count" => count($bannerdata),
                                "element_array" => $bannerdata
                            );
                        }
						elseif (in_array($layout['component_id'], $bannersType1)) {	
							$tempCdata=array();
							$query      = "SELECT id, banner,banner_url, heading, subheading, is_catalogue, cat_id, prod_id FROM app_banners WHERE `app_id`='".$app_id."' AND comp_id = '".$layout['component_id']."' AND isActive='1' AND retail_app_componentId='".$layout['id']."'";
							$catlist = $this->query_run($query, 'select');

							$finalcats = array();
							if(!empty($catlist))
							{									
								$catdata = $cf->getCurrentCatChild($app_id, $catlist['cat_id'],4);
								$cat_count = count($catdata);
								if(!empty($catdata))
								{
									foreach($catdata as $tcdata)
									{
										// checking if current category is having child category or not
										$cqueryp = "SELECT count(*) as totalcount FROM oc_category WHERE parent_id = ".$tcdata['itemid'];
										$ischild = $this->queryRun($cqueryp, 'select');

										if(!empty($ischild) && $ischild['totalcount'] > 0)
										{
											$tcdata['is_child_category'] = 1;
											$tcdata['screen_type'] = 0;
											$tcdata['is_category'] = 1;
											$tcdata['is_navigation'] = 1;
                                                                                         $screenopen = "2";
										}
										else
										{
											$tcdata['is_child_category'] = 0;
											$tcdata['screen_type'] = 1;
											$tcdata['is_category'] = 1;
											$tcdata['is_navigation'] = 1;
                                                                                         $screenopen = "3";
										}
										
										foreach($tcdata as $kk => $perct)
										{
											if($kk == 'imageurl')
											{
												$author_id  = $app_screenData['author_id'];
												$authorsql  = "SELECT email_address FROM author WHERE id = '".$author_id."'";
												$authordata = $this->query_run($authorsql, 'select');

												$userquery  = "SELECT user_id FROM oc_user WHERE email = '".$authordata['email_address']."'";
												$ocuserdata = $this->queryRun($userquery, 'select');

												 $vcimgquery = "SELECT image,short_description FROM vendor_category_image WHERE app_id = '".$app_id."' AND category_id = '".$tcdata['itemid']."' AND vendor_id = '".$ocuserdata['user_id']."'";

												$catimgdata = $this->queryRun($vcimgquery, 'select');

												if(!empty($catimgdata))
												{	
													if(@getimagesize($catimgdata['image']))
													{
														$img_url = $catimgdata['image'];
													}
													else
													{
														$img_url = baseUrl().'data/'.strtolower($authordata['email_address']).'/'.$app_id.'/'.$catimgdata['image'];
													}

													$tcdata[$kk] = $img_url;
													list($width, $height, $type, $attr) = @getimagesize($img_url);

													$tcdata['image_height'] = $height;
													$tcdata['image_width']  = $width;
												}
												else
												{
													$tcdata[$kk] = '';

													$tcdata['image_height'] = 800;
													$tcdata['image_width']  = 800;
												}
											}
											elseif($kk == 'itemdesc')
											{
												$author_id  = $app_screenData['author_id'];
												$authorsql  = "SELECT email_address FROM author WHERE id = '".$author_id."'";
												$authordata = $this->query_run($authorsql, 'select');

												$userquery  = "SELECT user_id FROM oc_user WHERE email = '".$authordata['email_address']."'";
												$ocuserdata = $this->queryRun($userquery, 'select');

												 $vcimgquery = "SELECT image,short_description FROM vendor_category_image WHERE app_id = '".$app_id."' AND category_id = '".$tcdata['itemid']."' AND vendor_id = '".$ocuserdata['user_id']."'";

												$catimgdata = $this->queryRun($vcimgquery, 'select');

												if(!empty($catimgdata))
												{	
													$tcdata['itemdesc'] = $catimgdata['short_description'] != "" ? $catimgdata['short_description'] : "";
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
							
							
							$finalcats = $cf->unique_multidim_array($finalcats, 'itemid');
                                                       
							
							$finalcaatcount = count($finalcats);
foreach($finalcats as $tcdata)
{
   $navcatarray[] = array("name" => $tcdata['itemheading'], "icon" => $tcdata['icomoon_code'], "is_child" => '0', "level" => "0", "link_to_screenid" => "-1", "catalogue_component" => array("screen_type" => $screenopen, "category_id" => $tcdata['itemid'], "app_type" => $app_type, "catalogue_app_id" => $app_idString), "cat_array" => array());  
}
							// setting component properties
							$span          = 1;
							$scroll_type   = 1;
							$default_block = 4;
							$view_all      = 1;
							/* if($finalcaatcount > 4)
							{
								$view_all = 1;
							} */
																							
							$comp_array[] = array(
											"title" => $layout['title'] !="" ? $layout['title'] : "Category",
											"comp_id" => $layout['id'],
											"comp_type" => $layout['component_id'],
													"parent_id"=>$catlist['cat_id'],
													"screen_type"=>'0',
											"is_category" => "1",                                                                                                                
											"comp_properties" => array(
													"span" => $span,
													"scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
													"view_all" => $view_all
											),
											/*"elements" => array(
													"element_count" => count($finalcats),
													"element_array" => $finalcats
											)*/
													"element_count" => count($finalcats),
													"element_array" => $finalcats
										); 
                              
                                
                                       
                                                        
						}
                    }
                }

                $time_array = array(
                    "select_date" => 0,
                    "select_time" => 0,
                    "start_time" => '08:00:00',
                    "end_time" => '20:00:00',
                    "time_interval" => '60',
                );


                $baseUrl = baseUrl();
                $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`='" . $app_id . "'";
                $launchdata = $this->query_run($query, 'select');

                $data = array(
                    "screen_id" => "1",
                    "parent_id" => "0",
                    "screen_type" => "1",
                    "tag" => "1",
                    "dirtyflag" => "0",
                    "merchent_id" => $attrData['merchent_id'],
                    "defaultcurrency" => $attrData['defaultcurrency'],
                    "server_time" => date('Y-m-d H:i:s'),
                    "reatil_app_properties" => array(
                        "title" => $launchdata['title'],
                        "popup_flag" => 0,
                        "background_color" => $launchdata['background_color'],
                        "background_image_url" => $baseUrl . "catalogue/ecommerce_catalog_api/images/retail_bg.jpg",
                        "font_color" => $attrData['font_color'],
                        "discount_color" => $attrData['discount_color']
                    ),
                    "comp_count" => count($themelayout),
                    "comp_array" => $comp_array
                );
                $navcatarray1=  array_unique($navcatarray, SORT_REGULAR);
                $finalarray = array("navigation" => $navcatarray1, "result" => $data);
                return $finalarray;
            } else {
                $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                return $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            return $json;
        }
    }

    function getAppBasicData($data) {

        if ((isset($data['app_id']) && trim($data['app_id']) != '')) {
            $app_idString = $data['app_id'];

            $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $this->query_run($appQueryData, 'select');

            if (!empty($app_screenData)) {
                $baseUrl = baseUrl();
                $app_id = $app_screenData['id'];
                $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`='" . $app_id . "'";
                $launchdata = $this->query_run($query, 'select');

                $out = array();
                $finalcats = array();
                if (!empty($launchdata)) {
                    $i = 0;
                    foreach ($launchdata as $key => $subarr) {
                        if ($key != 'title' && $key != 'background_color' && $key != 'app_tag_id') {
                            $out[$i][$key] = $subarr;
                            $i++;
                        }
                    }
                }



                $cquery1 = "SELECT  rap.cat_id AS cat_id ,rac.component_id AS component_id ,rap.category_id AS category_id,
                                                rap.isActive 
                                                FROM instappy_production.retail_app_products rap 
                                                LEFT JOIN instappy_production.retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
                                                LEFT JOIN instappy_production.retail_app_component rac ON racr.retail_app_component_id = rac.id
                                                WHERE rap.app_id = '" . $app_id . "' AND rap.isActive=1";

                $catdata1 = $this->query_run($cquery1, 'select');


                if (!empty($catdata1)) {
                    foreach ($catdata1 as $cats) {

                        $cat_id = $catdata1['cat_id'];


                        $subbb_cats = "SELECT * FROM (
							SELECT DISTINCT 
							(
							CASE WHEN opc.`category_id` IN ('" . $cat_id . "' ) THEN NULL 
							WHEN oc11.`parent_id` IN ('" . $cat_id . "' ) THEN opc.`category_id`
							WHEN oc12.`parent_id` IN ('" . $cat_id . "' ) THEN oc11.`parent_id`
							WHEN oc13.`parent_id` IN ('" . $cat_id . "' ) THEN oc12.`parent_id`
							END
							) AS 2nd_level

							FROM oc_product p 
							LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id 
							LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id 
							LEFT JOIN `oc_category_description` occ1 ON opc.`category_id`=occ1.`category_id`
							LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id 
							LEFT JOIN oc_app_id ai ON ai.product_id=p.product_id 
							LEFT JOIN oc_category oc11 ON oc11.`category_id`=opc.`category_id`
							LEFT JOIN `oc_category_description` occ2 ON oc11.`parent_id`=occ2.`category_id`
							LEFT JOIN oc_category oc12 ON oc12.`category_id`=oc11.`parent_id`
							LEFT JOIN `oc_category_description` occ3 ON oc12.`parent_id`=occ3.`category_id`

							LEFT JOIN oc_category oc13 ON oc13.`category_id`=oc12.`parent_id`
							LEFT JOIN `oc_category_description` occ4 ON oc13.`parent_id`=occ4.`category_id`

							WHERE 
							ai.app_id='" . $app_id . "' AND
							opc.`category_id`<>''
							AND p.STATUS = '1'
							) AS m
							LEFT JOIN `oc_category_description` yii ON m.`2nd_level`=yii.`category_id`";



                        $catdata2 = $this->queryRun($subbb_cats, 'select_all');


                        if (!empty($catdata2)) {

                            foreach ($catdata2 as $subcats) {

                                if (isset($subcats['category_id'])) {

                                    if ($subcats['category_id']) {
                                        $is_category = 1;
                                        $itemid = $subcats['category_id'];
                                        $getchildcats = $this->getCurrentCatChild($app_id, $itemid);
                                        if (!empty($getchildcats)) {
                                            $is_child_category = 1;
                                        } else {
                                            $is_child_category = 0;
                                        }
                                        $val['itemheading'] = $subcats['name'] != '' ? htmlspecialchars_decode($subcats['name']) : '';
                                        $val['itemid'] = $subcats['category_id'];
                                        $val['icomoon'] = $subcats['icomoon'];
                                        $val['is_category'] = $is_category;
                                        $val['is_child_category'] = $is_child_category;



                                        if (!$this->in_array_r($val, $finalcats)) {

                                            $finalcats[] = $val;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    //For loop ends
                }
                // font & discount color
                $authorQuery00 = "select * from app_catalogue_attr where app_id='" . $app_id . "'";
                $appctlgattr = $this->query_run($authorQuery00, 'select');

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

                $curr_id = 4;
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['curr_id'] != '') {
                        $curr_id = $appctlgattr['curr_id'];
                    }
                }

                // default currency
                $defaultcurrquery = "SELECT * FROM oc_currency WHERE currency_id='" . $curr_id . "'";
                $defaultcurrency = $this->queryRun($defaultcurrquery, 'select');

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
                } elseif ($defaultcurrencyArr['code'] == 'USD'  ) {
                    $defaultcurrencyArr['symbol_code'] = 'e800';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e800';
                } elseif ($defaultcurrencyArr['code'] == 'SGD') {
                    $defaultcurrencyArr['symbol_code'] = 'eac7';
                    $defaultcurrencyArr['symbol_code_discount'] = 'eac7';
                } elseif ($defaultcurrencyArr['code'] == 'EUR') {
                    $defaultcurrencyArr['symbol_code'] = 'e801';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e801';
                } elseif ($defaultcurrencyArr['code'] == 'INR') {
                    $defaultcurrencyArr['symbol_code'] = 'e802';
                    $defaultcurrencyArr['symbol_code_discount'] = 'e802';
                }

                $data = array(
                    "background_color" => $launchdata['background_color'],
                    "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png",
                    "font_color" => $fontcolor,
                    "discount_color" => $disccolor,
                    "main_category_array" => $finalcats,
                    "defaultcurrency" => $defaultcurrencyArr
                );

                $json = $this->real_json_encode($data, 'successData', '', 200, '', '');
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function getCategoryComponentDetails($app_id, $comp_id) {
        $cquery = "SELECT rap.category_id, rap.cat_id, rap.prod_id, rap.is_catalogue FROM retail_app_products rap 
                            LEFT JOIN retail_app_component_rel racr ON racr.retail_app_products_id = rap.id
                            LEFT JOIN retail_app_component rac ON racr.retail_app_component_id = rac.id
                            WHERE rap.app_id = '" . $app_id . "' AND rap.isActive=1 
                            AND rap.type = 1
                            AND rac.component_id = '" . $comp_id . "'";
        $catlist = $this->query_run($cquery, 'select');

        $finalcats = array();
        if (!empty($catlist)) {
            $catdata = $cf->getCurrentCatChild($app_id, $catlist['cat_id'], 4);
            $cat_count = count($catdata);
            if (!empty($catdata)) {
                foreach ($catdata as $tcdata) {
                    // checking if current category is having child category or not
                    $cqueryp = "SELECT count(*) as totalcount FROM oc_category WHERE parent_id = " . $tcdata['itemid'];
                    $ischild = $this->queryRun($cqueryp, 'select');

                    if (!empty($ischild) && $ischild['totalcount'] > 0) {
                        $tcdata['is_child_category'] = "1";
                        $tcdata['screen_type'] = "0";
                        $tcdata['is_category'] = "1";
                        $tcdata['is_navigation'] = "1";
                    } else {
                        $tcdata['is_child_category'] = "0";
                        $tcdata['screen_type'] = "1";
                        $tcdata['is_category'] = "1";
                        $tcdata['is_navigation'] = "1";
                    }

                    foreach ($tcdata as $kk => $perct) {
                        if ($kk == 'imageurl') {
                            $author_id = $app_screenData['author_id'];
                            $authorsql = "SELECT email_address FROM author WHERE id = '" . $author_id . "'";
                            $authordata = $this->query_run($authorsql, 'select');

                            $userquery = "SELECT user_id FROM oc_user WHERE email = '" . $authordata['email_address'] . "'";
                            $ocuserdata = $this->queryRun($userquery, 'select');

                            $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = '" . $app_id . "' AND category_id = '" . $tcdata['itemid'] . "' AND vendor_id = '" . $ocuserdata['user_id'] . "'";

                            $catimgdata = $this->queryRun($vcimgquery, 'select');

                            if (!empty($catimgdata)) {
                                if (preg_match($searchUrl, $catimgdata['image'])) {
                                    $image_url = $catimgdata['image'];
                                } else {
                                    $img_url = baseUrl() . 'data/' . strtolower($authordata['email_address']) . '/' . $app_id . '/' . $catimgdata['image'];
                                }



                                $tcdata[$kk] = $img_url;
                                list($width, $height, $type, $attr) = @getimagesize($img_url);

                                $tcdata['image_height'] = "$height";
                                $tcdata['image_width'] = "$width";
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
            $default_block = 3;
        } elseif ($layout['component_id'] == 102) {
            $span = 1;
            $scroll_type = 1;
            $view_all = 0;
            $default_block = 3;
        } elseif ($layout['component_id'] == 103) {
            $span = 2;
            $scroll_type = 0;
            $view_all = 1;
            if ($cat_count < 4) {
                $view_all = 0;
            }
            $default_block = 4;
        }

        $finalcats = $cf->unique_multidim_array($finalcats, 'itemid');

        $finalcaatcount = count($finalcats);

//									


        $comp_array[] = array(
            "title" => $layout['title'],
            "comp_id" => $layout['id'],
            "comp_type" => $layout['component_id'],
            "parent_id" => $catlist['cat_id'],
            "screen_type" => '0',
            "is_category" => "1",
            "comp_properties" => array(
                "span" => $span,
                "scroll_type" => $scroll_type, // 0 - no scroll, 1 - horrizontal, 2 - vertical
                "view_all" => $view_all
            ),
            "element_count" => count($finalcats),
            "element_array" => $finalcats
        );
    }

    public function getAppAttrData($app_id, $app_screenData) {

        $query = "SELECT `title`,`banner1`,`banner2`,`banner3`,`background_color`,`app_tag_id` FROM `app_catalogue_attr` WHERE `app_id`='" . $app_id . "'";
        $launchdata = $this->query_run($query, 'select');

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

        /*   if (!empty($out)) {
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
          } */

        // ga tracking id
        $ga_tracking = '';



        // merchent id
        $merchent_id = '';
        $get_user_sql = "SELECT * FROM author WHERE id = '" . $app_screenData['author_id'] . "'";
        $get_user = $this->query_run($get_user_sql, 'select');

        if (!empty($get_user)) {
            $get_ocuser_sql = "SELECT v.merchant_id FROM oc_user u LEFT JOIN oc_vendors v ON v.user_id=u.user_id WHERE u.email = '" . $get_user['email_address'] . "'";
            $get_ocuser = $this->queryRun($get_ocuser_sql, 'select');

            if (!empty($get_ocuser) && $get_ocuser['merchant_id'] != '') {
                $merchent_id = $get_ocuser['merchant_id'];
            }
        }
        // admob data
        $admob_id = '';


        $adData = array();

        // terms and conditions
        $authorQuery00 = "select * from app_catalogue_attr where app_id='" . $app_id . "'";
        $appctlgattr = $this->query_run($authorQuery00, 'select');

        $getauthor = 0;
        $tnc = 'http://www.instappy.com/terms-of-service.php';
        if (!empty($appctlgattr)) {
            if ($appctlgattr['is_tnc'] != 0 && $appctlgattr['tnc_link'] != null) {
                $tnc = $appctlgattr['tnc_link'];
            }
        }

        // contact us details
        $getauthor = 0;
        $contactarr = array(
            'contact_email' => '',
            'contact_phone' => ''
        );

        if (!empty($appctlgattr) && $appctlgattr['is_contactus'] != 0) {
            $contactarr = array(
                'contact_email' => $appctlgattr['contactus_email'],
                'contact_phone' => $appctlgattr['contactus_no']
            );
        }
        // feedback details
        $feedbackarr = array(
            'feedback_email' => '',
            'feedback_phone' => ''
        );
        if (!empty($appctlgattr) && $appctlgattr['is_feedback'] != 0) {

            $feedbackarr = array(
                'feedback_email' => $appctlgattr['feedback_email'],
                'feedback_phone' => $appctlgattr['feedback_no']
            );
        }
        // order details
        $orderarr = array(
            'order_email' => '',
            'order_phone' => '',
            'order_logo' => ''
        );
        if (!empty($appctlgattr) && $appctlgattr['is_order'] != 0) {
            $orderarr = array(
                'order_email' => $appctlgattr['orderconfirm_email'],
                'order_phone' => $appctlgattr['orderconfirm_no'],
                'order_logo' => $appctlgattr['logo_link']
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
        $curr_id = 4; //INR
        if (!empty($appctlgattr)) {
            if ($appctlgattr['curr_id'] != '') {
                $curr_id = $appctlgattr['curr_id'];
            }
        }

        // default currency
        $defaultcurrquery = "SELECT * FROM oc_currency WHERE currency_id='" . $curr_id . "'";
        $defaultcurrency = $this->queryRun($defaultcurrquery, 'select');

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
        } elseif ($defaultcurrencyArr['code'] == 'USD') {
            $defaultcurrencyArr['symbol_code'] = 'e800';
            $defaultcurrencyArr['symbol_code_discount'] = 'e800';
        } 
		elseif ($defaultcurrencyArr['code'] == 'SGD') {
                    $defaultcurrencyArr['symbol_code'] = 'eac7';
                    $defaultcurrencyArr['symbol_code_discount'] = 'eac7';
                }
		elseif ($defaultcurrencyArr['code'] == 'EUR') {
            $defaultcurrencyArr['symbol_code'] = 'e801';
            $defaultcurrencyArr['symbol_code_discount'] = 'e801';
        } elseif ($defaultcurrencyArr['code'] == 'INR') {
            $defaultcurrencyArr['symbol_code'] = 'e802';
            $defaultcurrencyArr['symbol_code_discount'] = 'e802';
        }

        $data = array(
            "bannerData" =>$bannerdata,
            //  "ga_tracking" => $ga_tracking,
            "merchent_id" => $merchent_id,
            "defaultcurrency" => $defaultcurrencyArr,
            "font_color" => $fontcolor,
            "discount_color" => $disccolor
                //  "tnc" => $tnc,
                //  "contact_details" => $contactarr,
                //  "feedback_details" => $feedbackarr,
                //  "order_details" => $orderarr,
                //  "adData" => $adData
        );

        return $data;
    }

}
