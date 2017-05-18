<?php

require_once('includes/main_functions.php');

class Fwcore extends MainFunctions {

    function unique_multidim_array($array, $key) {
        $temp_array = array();
        $i = 0;
        $key_array = array();
        foreach ($array as $val) {
            if (!in_array($val[$key], $key_array)) {
                $key_array[$i] = $val[$key];
                $temp_array[] = $val;
            }
            $i++;
        }
        return $temp_array;
    }

    public function getScreenDetailData($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['category_id']) && $data['category_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $screen_id = $data['category_id'];
            $offset = $data['offset'];
            $app_idString = $data['app_id'];
            $authResult = $this->authCheck($authToken);
            if (!empty($authResult)) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');
                $app_id = $app_screenData['id'];
                $this->lastLogin_new($authToken, $device_id, $app_id);
                $lastlogindate = $this->lastLoginUpdateToken($authToken);
                $baseUrl = baseUrl();
                $query = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc FROM oc_category c LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id WHERE c.category_id='" . $screen_id . "'";
                $homedata = $this->queryRun($query, 'select');
                if (!empty($homedata)) {
                    $launchdata = $this->getCurrentCatChild($app_id, $screen_id);
                    $launchdata = $this->unique_multidim_array($launchdata, 'itemid');

                    $pageStatus = 'eof';
                    $bannerdata = array();
                    if (!empty($launchdata)) {
                        foreach ($launchdata as $tempdata) {
                            $category_id = $tempdata['itemid'];
                            $cqueryp = "SELECT count(*) as totalcount FROM oc_category c
										LEFT JOIN oc_product_to_category opc ON c.category_id = opc.category_id
										LEFT JOIN oc_app_id ai ON opc.product_id = ai.product_id WHERE c.parent_id = " . $category_id . " AND ai.app_id = " . $app_id;
                            $ischild = $this->queryRun($cqueryp, 'select');
                            if (!empty($ischild) && $ischild['totalcount'] > 0) {
                                $is_child_category = 1;
                            } else {
                                $is_child_category = 0;
                            }
                            $img_url = '';
                            foreach ($tempdata as $kk => $perct) {
                                if ($kk == 'imageurl') {
                                    $author_id = $app_screenData['author_id'];
                                    $authorsql = "SELECT email_address FROM author WHERE id = '" . $author_id . "'";
                                    $authordata = $this->query_run($authorsql, 'select');

                                    $userquery = "SELECT user_id FROM oc_user WHERE email = '" . $authordata['email_address'] . "'";
                                    $ocuserdata = $this->queryRun($userquery, 'select');

                                    $vcimgquery = "SELECT image FROM vendor_category_image WHERE app_id = '" . $app_id . "' AND category_id = '" . $tcdata['itemid'] . "' AND vendor_id = '" . $ocuserdata['user_id'] . "'";

                                    $catimgdata = $this->queryRun($vcimgquery, 'select');

                                    if (!empty($catimgdata)) {
                                        if (@getimagesize($catimgdata['image'])) {
                                            $img_url = $catimgdata['image'];
                                        } else {
                                            $img_url = baseUrl() . 'data/' . strtolower($authordata['email_address']) . '/' . $app_id . '/' . $catimgdata['image'];
                                        }

                                        $tcdata[$kk] = $img_url;
                                        list($width, $height, $type, $attr) = @getimagesize($img_url);

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

                            $tempCdata = array(
                                "itemheading" => $tempdata['itemheading'] != '' ? htmlspecialchars_decode(ucfirst($tempdata['itemheading'])) : '',
                                "imageurl" => $img_url,
                                "itemid" => $tempdata['itemid'],
                                "is_child_category" => $is_child_category
                            );

                            $bannerdata[] = $tempCdata;
                        }
                    }

                    $data = array(
                        "screen_id" => 2,
                        "parent_id" => 1,
                        "screen_type" => 2,
                        "tag" => 1,
                        "dirtyflag" => 0,
                        "pagination" => $pageStatus,
                        "server_time" => date('Y-m-d H:i:s'),
                        "screen_properties" => array(
                            "title" => ucfirst($homedata['itemheading']),
                            "popup_flag" => 0,
                            "background_color" => "",
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                        ),
                        "comp_count" => 1,
                        "comp_array" => array(
                            array(
                                "type" => "category",
                                "comp_id" => 21,
                                "elements" => array(
                                    "preference_count" => count($bannerdata),
                                    "preference_array" => $bannerdata
                                )
                            )
                        )
                    );

                    $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function viewProductsList($data) {
       
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['category_id']) && $data['category_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
          
           
             $authToken = $data['auth_token'];
            $category_id = $data['category_id'];
            $app_idString = $data['app_id'];
            $offset = $data['offset'];
            $authResult = $this->authCheck($authToken);
            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $totalViewCount = $this->updateCategoryViewCount($category_id);

                $baseUrl = baseUrl();
                $query = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc FROM oc_category c LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id WHERE c.category_id='" . $category_id . "'";
                $homedata = $this->queryRun($query, 'select');

                if (!empty($homedata)) {
                    $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, COALESCE(p.price, '') AS actualprice, COALESCE(ops.price, '') AS special_price, ops.discount FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_app_id ai ON ai.product_id=p.product_id WHERE opc.category_id='" . $category_id . "' AND ai.app_id='" . $app_id . "' AND p.status = 1 LIMIT $offset, 15";
                    $launchdata = $this->queryRun($cquery, 'select_all');

                    $maths = count($launchdata) % 15;
                    if ($maths == 0) {
                        $pageStatus = 'cof';
                    } else {
                        $pageStatus = 'eof';
                    }

                    $productlist = array();
                    if (!empty($launchdata)) {
                        foreach ($launchdata as $tempdata) {
                            $actualprice = $tempdata['actualprice'] ? $tempdata['actualprice'] : 0;
                            $specialprice = $tempdata['special_price'] ? $tempdata['special_price'] : 0;
                            $discount = $tempdata['discount'];

                            if (@getimagesize($tempdata['imageurl'])) {
                                $prod_img = $tempdata['imageurl'];
                            } else {
                                $prod_img = $baseUrl . $tempdata['imageurl'];
                            }
                            $tempCdata = array(
                                "itemheading" => $tempdata['itemheading'],
                                "imageurl" => $tempdata['imageurl'] != '' ? $prod_img : "",
                                "itemid" => $tempdata['itemid'],
                                "actualprice" => $actualprice,
                                "price" => $specialprice,
                                "addedToCart" => 0,
                                "addedToWishlist" => 0,
                                "discount" => $discount > 0 ? $discount . '%' : ''
                            );

                            $productlist[] = $tempCdata;
                        }
                    }

                    $data = array(
                        "screen_id" => 3,
                        "parent_id" => 2,
                        "screen_type" => 3,
                        "tag" => 1,
                        "dirtyflag" => 0,
                        "server_time" => date('Y-m-d H:i:s'),
                        "pagination" => $pageStatus,
                        "screen_properties" => array(
                            "title" => $homedata['itemheading'],
                            "popup_flag" => 0,
                            "background_color" => "",
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                        ),
                        "comp_array" => array(
                            array(
                                "type" => "products",
                                "comp_id" => 31,
                                "elements" => array(
                                    "products_array" => $productlist
                                )
                            )
                        )
                    );

                    $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function viewProductDetail($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['product_id']) && $data['product_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $product_id = $data['product_id'];
            $app_idString = $data['app_id'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                if ($app_idString != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    $app_id = $app_screenData['id'];
                } else {
                    $app_id = '';
                }

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $totalViewCount = $this->updateProductViewCount($product_id, $app_id);

                //$app_id = $result_screenData['id'];

                $baseUrl = baseUrl();

                $app_basics_query = "SELECT `is_review_rating`, `is_model_number` FROM `app_catalogue_attr` WHERE `app_id`='" . $app_id . "'";
                $app_basics_data = $this->query_run($app_basics_query, 'select');


                //$query    = "SELECT DISTINCT p.product_id as itemid, pc.name as itemheading, p.image, p.image_rect, COALESCE(p.price, '') as actualprice, pc.description, COALESCE(ops.price, '') AS special_price, p.length_class_id, p.weight_class_id, ops.discount, p.model FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id WHERE p.product_id='".$product_id."'";
                $query = "SELECT DISTINCT p.product_id AS itemid, pc.name AS itemheading, p.image, p.image_rect, COALESCE(p.price, '') AS actualprice, pc.description, COALESCE(ops.price, '') AS special_price, p.length_class_id, p.weight_class_id, ops.discount, p.model, opsp.weight_class FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id = pc.product_id LEFT JOIN oc_product_special ops ON p.product_id = ops.product_id LEFT JOIN oc_product_to_category opc ON p.product_id = opc.product_id left join oc_product_specs opsp on p.product_id = opsp.product_id WHERE p.product_id = '" . $product_id . "'";
                $homedata = $this->queryRun($query, 'select');

                if (!empty($homedata)) {
                    $actualprice = $homedata['actualprice'] ? $homedata['actualprice'] : 0;
                    $specialprice = $homedata['special_price'] ? $homedata['special_price'] : 0;
                    $discount = $homedata['discount'] > 0 ? $homedata['discount'] . '%' : 0;

                    $model = isset($homedata['model']) != '' ? $homedata['model'] : '';


                    $cquery = "SELECT image, image_rect FROM oc_product_image WHERE product_id='" . $product_id . "'";
                    $imagedata = $this->queryRun($cquery, 'select_all');


                    $imagelist = array();
                    if (@getimagesize($homedata['image'])) {
                        $sq_img_url = $homedata['image'];
                    } else {
                        $sq_img_url = $baseUrl . $homedata['image'];
                    }
                    $sqimage = $homedata['image'] != '' ? $sq_img_url : "";

                    if (@getimagesize($homedata['image_rect'])) {
                        $rect_img_url = $homedata['image_rect'];
                    } else {
                        $rect_img_url = $baseUrl . $homedata['image_rect'];
                    }
                    $rectimage = $homedata['image_rect'] != '' ? $rect_img_url : $sqimage;

                    if ($rectimage != '') {
                        list($width, $height, $type, $attr) = @getimagesize($rectimage);
                    } else {
                        $height = '';
                        $width = '';
                    }

                    $tempCdata = array(
                        "imageurl" => $rectimage,
                        "itemname" => $homedata['itemheading'],
                        "image_height" => '800',
                        "image_width" => $width
                    );

                    $imagelist[] = $tempCdata;
                    if (!empty($imagedata)) {
                        foreach ($imagedata as $tempdata) {
                            if (($tempdata['image'] != '' && $tempdata['image'] != 'no_image.jpg') || ($tempdata['image_rect'] != '' && $tempdata['image_rect'] != 'no_image.jpg')) {
                                if (@getimagesize($tempdata['image'])) {
                                    $sq_img_url = $tempdata['image'];
                                } else {
                                    if ($tempdata['image'] != 'no_image.jpg') {
                                        $sq_img_url = $baseUrl . $tempdata['image'];
                                    } else {
                                        $sq_img_url = '';
                                    }
                                }
                                $sqimage = $tempdata['image'] != '' ? $sq_img_url : "";

                                if (@getimagesize($tempdata['image_rect'])) {
                                    $rect_img_url = $tempdata['image_rect'];
                                } else {
                                    if ($tempdata['image_rect'] != 'no_image.jpg') {
                                        $rect_img_url = $baseUrl . $tempdata['image_rect'];
                                    } else {
                                        $rect_img_url = '';
                                    }
                                }
                                $rectimage = $tempdata['image_rect'] != '' ? $rect_img_url : $sqimage;

                                if ($rectimage != '') {
                                    list($width, $height, $type, $attr) = @getimagesize($rectimage);
                                } else {
                                    $height = '';
                                    $width = '';
                                }

                                if ($rectimage != '') {
                                    $tempCdata = array(
                                        "imageurl" => $rectimage,
                                        "itemname" => $homedata['itemheading'],
                                        "image_height" => '800',
                                        "image_width" => $width
                                    );

                                    $imagelist[] = $tempCdata;
                                }
                            }
                        }
                    }

                    $pquery = "SELECT * FROM oc_product_specs WHERE product_id='" . $product_id . "'";
                    $propdata = $this->queryRun($pquery, 'select');

                    $pquery1 = "SELECT * FROM oc_length_class_description WHERE length_class_id='" . $homedata['length_class_id'] . "'";
                    $propdata1 = $this->queryRun($pquery1, 'select');

                    if (!empty($propdata1)) {
                        $length_class = ' (' . $propdata1['title'] . ')';
                    } else {
                        $length_class = '';
                    }

                    /* $pquery12   = "SELECT * FROM oc_weight_class_description WHERE weight_class_id='".$homedata['weight_class_id']."'";
                      $propdata12 = $this->queryRun($pquery12, 'select');

                      if(!empty($propdata12))
                      {
                      $weight_class = ' ('.$propdata12['title'].')';
                      }
                      else
                      {
                      $weight_class = '';
                      } */

                    $pquery12 = "SELECT unit FROM oc_weight_class_description WHERE weight_class_id='" . $homedata['weight_class'] . "'";
                    $propdata12 = $this->queryRun($pquery12, 'select');

                    if (!empty($propdata12)) {
                        $weight_class = ' ' . $propdata12['unit'];
                    } else {
                        $weight_class = '';
                    }



                    $pdquery11 = "SELECT * FROM oc_product_spec_type WHERE product_id='" . $product_id . "'";
                    $prodataq11 = $this->queryRun($pdquery11, 'select');

                    $properties = array();

                    // color
                    if ($propdata['color'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['color'];
                        } else {
                            $property_type = 2;
                        }
                        $properties[] = array(
                            "property_title" => 'Color',
                            "property_type" => $property_type,
                            "values" => $propdata['color']
                        );
                    }

                    // size
                    if ($propdata['size'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['size'];
                        } else {
                            $property_type = 2;
                        }

                        $sizes = $propdata['size'];
                        if ($propdata['size_other'] != '') {
                            $sizes .= ',' . $propdata['size_other'];
                        }
                        $properties[] = array(
                            "property_title" => 'Size',
                            "property_type" => $property_type,
                            "values" => $sizes
                        );
                    }

                    // weight
                    if ($propdata['weight'] > 0) {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['weight'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Weight',
                            "property_type" => $property_type,
                            "values" => $propdata['weight'] . $weight_class
                        );
                    }

                    // dimension
                    $dimensionstr = '';
                    if ($propdata['length'] > 0) {
                        $dimensionstr .= $propdata['length'];
                    }

                    if ($propdata['width'] > 0) {
                        if ($dimensionstr != '') {
                            $dimensionstr .= ' x ' . $propdata['width'];
                        } else {
                            $dimensionstr .= $propdata['width'];
                        }
                    }

                    if ($propdata['height'] > 0) {
                        if ($dimensionstr != '') {
                            $dimensionstr .= ' x ' . $propdata['height'];
                        } else {
                            $dimensionstr .= $propdata['height'];
                        }
                    }

                    if ($dimensionstr != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['length'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Dimension',
                            "property_type" => $property_type,
                            "values" => $dimensionstr . $length_class
                        );
                    }

                    // dimension other
                    if ($propdata['dimension_other'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['dimension_other'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Dimension Others',
                            "property_type" => 2,
                            "values" => $propdata['dimension_other']
                        );
                    }

                    // special specs
                    if ($propdata['special_specs'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['special_specs'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Special Specification',
                            "property_type" => 2,
                            "values" => $propdata['special_specs']
                        );
                    }

                    // material
                    if ($propdata['material'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['material'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Material',
                            "property_type" => $property_type,
                            "values" => $propdata['material']
                        );
                    }

                    // warranty
                    if ($propdata['warranty'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['warranty'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Warranty',
                            "property_type" => $property_type,
                            "values" => $propdata['warranty']
                        );
                    }

                    // exchange_offer
                    if ($propdata['exchange_offer'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['exchange_offer'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Exchange Offer',
                            "property_type" => $property_type,
                            "values" => $propdata['exchange_offer']
                        );
                    }

                    // best_for
                    if ($propdata['best_for'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['best_for'];
                        } else {
                            $property_type = 2;
                        }

                        $best_for = $propdata['best_for'];
                        if ($propdata['best_for'] != '') {
                            $best_for .= ',' . $propdata['best_for_other'];
                        }
                        $properties[] = array(
                            "property_title" => 'Best For',
                            "property_type" => $property_type,
                            "values" => rtrim($best_for, ',')
                        );
                    }

                    // brand
                    if ($propdata['brand'] != '') {
                        if (!empty($prodataq11)) {
                            $property_type = $prodataq11['brand'];
                        } else {
                            $property_type = 2;
                        }

                        $properties[] = array(
                            "property_title" => 'Brand',
                            "property_type" => $property_type,
                            "values" => $propdata['brand'] 
                        );
                    }

                    // share data
                    $share_text = '';
                    $share_link='';
                     $share_text = $propdata['share_text'];
                     if(!empty($propdata['share_link'])){ 
                      $share_link = $propdata['share_link'].'?'.$prodataq11['product_id'];
                     }
             /*       if ($app_id == '7066') {
                        $share_text = 'Shared Via StoreToBuy';
                        $share_link = 'http://www.storetobuy.com/';
                    } else {
                        if ($propdata['share_text'] != '') {
                            $share_text = $propdata['share_text'];
                        }
                        // share link
                        $share_link = '';
                        if ($propdata['share_link'] != '') {
                            $share_link = $propdata['share_link'];
                        }
                    }

                    if ($app_id == '6027') {
                        $share_link = 'https://wildearth.in/';
                    } */

                    /* $finalAvg  = "SELECT AVG(rating) AS avg_rating,
                      COUNT(rating) AS rating_count,
                      SUM(IF (reviews IS NOT NULL,1,0)) AS review_count
                      FROM review_rating
                      WHERE product_id='".$product_id."' AND app_id = '".$app_id."' AND is_deleted = 0"; */

                    $finalAvg = "SELECT AVG(rating) AS avg_rating,
								  COUNT(rating) AS rating_count,
								  SUM(IF (reviews IS NOT NULL,1,0)) AS review_count
								  FROM review_rating
								  WHERE product_id='" . $product_id . "' AND is_deleted = 0";
                    $finalAvgR = $this->queryRun($finalAvg, 'select');

                    $average_rating = $finalAvgR['avg_rating'] != '' ? $finalAvgR['avg_rating'] : '';
                    $rating_count = $finalAvgR['rating_count'] != '' ? $finalAvgR['rating_count'] : '';
                    $review_count = $finalAvgR['review_count'] != '' ? $finalAvgR['review_count'] : '';

                   
                      // similar products
                      $s_query    = "SELECT  DISTINCT pc.product_id AS itemid,
                      pd.NAME AS itemheading,
                      p.image, p.image_rect,
                      COALESCE(p.price, '') AS actualprice,
                      pd.description,
                      COALESCE(ops.price, '') AS special_price,
                      p.length_class_id, p.weight_class_id,
                      ops.discount, p.model
                      FROM oc_product_to_category pc
                      INNER JOIN oc_product_description pd ON pc.product_id=pd.product_id
                      INNER JOIN oc_app_id oa ON oa.product_id=pd.product_id
                      INNER JOIN oc_product op ON pc.product_id=op.product_id
                      INNER JOIN oc_product_to_category pc1 ON pc1.category_id=pc.category_id
                      INNER JOIN oc_category_description cd ON pc1.category_id=cd.category_id
                      INNER JOIN oc_product p ON p.product_id=pd.product_id
                      LEFT OUTER JOIN oc_product_special ops ON ops.product_id=pd.product_id
                      WHERE op.STATUS=1
                      AND pc1.product_id ='".$product_id."'
                      AND oa.app_id='".$app_id."'
                      HAVING pc.product_id <>'".$product_id."' limit 10";
                      $similarprod = $this->queryRun($s_query, 'select_all');
 

                      $similar_data = array();
                      if(!empty($similarprod))
                      {
                      foreach($similarprod as $stempdata)
                      {
                      if(@getimagesize($stempdata['image']))
                      {
                      $sq_img_url = $stempdata['image'];
                      }
                      else
                      {
                      $sq_img_url = $baseUrl.$stempdata['image'];
                      }
                     $sqimage   = $sq_img_url;

                      if($sqimage != '')
                      {
                      list($width, $height, $type, $attr) = @getimagesize($sqimage);
                      }
                      else
                      {
                      $height = 800;
                      $width  = 800;
                      }


                      $ctempCdata = array(
                      "itemid"        => $stempdata['itemid'],
                      "itemheading"   => $stempdata['itemheading'],
                      "imageurl"      => $sqimage,
                      "image_height"  => $height,
                      "image_width"   => $width,
                      "actualprice"   => $stempdata['actualprice'],
                      "special_price" => $stempdata['special_price'],
                      "is_category"   => 0
                      );

                      $similar_data[] = $ctempCdata;
                      }
                      }

                      // recently viewed
                      $r_query    = "SELECT DISTINCT pvc.product_id AS itemid,
                      pd.NAME AS itemheading,
                      p.image, p.image_rect,
                      COALESCE(p.price, '') AS actualprice,
                      pd.description,
                      COALESCE(ops.price, '') AS special_price,
                      p.length_class_id, p.weight_class_id,
                      ops.discount, p.model
                      FROM product_view_count pvc INNER JOIN oc_product_description pd
                      ON pvc.product_id=pd.product_id
                      INNER JOIN oc_product p ON p.product_id=pd.product_id
                      LEFT OUTER JOIN oc_product_special ops ON ops.product_id=pd.product_id
                      WHERE pvc.app_id='".$app_id."'
                      AND pvc.product_id <>'".$product_id."' limit 10";
                      $recentlyviewed = $this->queryRun($r_query, 'select_all');

                      $recently_viewed = array();
                      if(!empty($recentlyviewed))
                      {
                      foreach($recentlyviewed as $rviewed)
                      {
                      if(@getimagesize($rviewed['image']))
                      {
                      $sq_img_url = $rviewed['image'];
                      }
                      else
                      {
                      $sq_img_url = $baseUrl.$rviewed['image'];
                      }
                      $sqimage   = $sq_img_url;

                      if($sqimage != '')
                      {
                      list($width, $height, $type, $attr) = @getimagesize($sqimage);
                      }
                      else
                      {
                      $height = 800;
                      $width  = 800;
                      }

                      $ctempCdata = array(
                      "itemid"        => $rviewed['itemid'],
                      "itemheading"   => $rviewed['itemheading'],
                      "imageurl"      => $sqimage,
                      "image_height"  => $height,
                      "image_width"   => $width,
                      "actualprice"   => $rviewed['actualprice'],
                      "special_price" => $rviewed['special_price'],
                      "is_category"   => 0
                      );

                      $recently_viewed[] = $ctempCdata;
                      }
                      }
                    

                    $data = array(
                        "screen_id" => 4,
                        "parent_id" => 3,
                        "screen_type" => 4,
                        "tag" => 1,
                        "dirtyflag" => 0,
                        "server_time" => date('Y-m-d H:i:s'),
                        "screen_properties" => array(
                            "title" => $homedata['itemheading'],
                            "popup_flag" => 0,
                            "background_color" => "",
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                        ),
                        "elements" => array(
                            "prod_title" => $homedata['itemheading'],
                            "images" => $imagelist,
                            "itemid" => $homedata['itemid'],
                            "actualprice" => $actualprice,
                            "price" => $specialprice,
                            "discount" => $discount,
                            "leftLabel" => "",
                            "rightLabel" => "",
                            "sharedata" => array(
                                "share_text" => $share_text,
                                "share_link" => $share_link
                            ),
                            "description" => array(
                                "heading" => "Product Details",
                                "subheading" => htmlspecialchars_decode($homedata['description']),
                                "model" => $model
                            ),
                            "properties" => $properties,
                            "reviews" => array(
                                "show_rating" => $app_basics_data['is_review_rating'],
                                "average_rating" => $average_rating,
                                "rating_count" => $rating_count,
                                "review_count" => $review_count
                            ),
                         "special_products" => array(
                          array(
                          "comp_type" => "109",
                          "title" => "Similar Products",
                          "elements" => array(
                          "element_count" => count($similar_data),
                          "element_array" => $similar_data
                          )
                          ),
                          array(
                          "comp_type" => "109",
                          "title" => "Recently Viewed",
                          "elements" => array(
                          "element_count" => count($recently_viewed),
                          "element_array" => $recently_viewed
                          )
                          )
                          ) 
                        )
                    );

                    $json = $this->real_json_encode($data, 'prodsuccessData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'No product found', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function viewProductReviews($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['product_id']) && $data['product_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $product_id = $data['product_id'];
            $app_idString = $data['app_id'];
            $offset = $data['offset'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                //$app_id = $result_screenData['id'];

                $baseUrl = baseUrl();
                $query = "SELECT DISTINCT p.product_id as itemid, pc.name as itemheading, p.image, p.image_rect
							FROM oc_product p 
							LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
							LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
							WHERE p.product_id='" . $product_id . "'";
                $homedata = $this->queryRun($query, 'select');


                if (!empty($homedata)) {
                    $imagelist = array();

                    if (@getimagesize($homedata['image'])) {
                        $sq_img_url = $homedata['image'];
                    } else {
                        $sq_img_url = $baseUrl . $homedata['image'];
                    }
                    $sqimage = $homedata['image'] != '' ? $sq_img_url : "";

                    if (@getimagesize($homedata['image_rect'])) {
                        $rect_img_url = $homedata['image_rect'];
                    } else {
                        $rect_img_url = $baseUrl . $homedata['image_rect'];
                    }
                    $rectimage = $homedata['image_rect'] != '' ? $rect_img_url : $sqimage;

                    if ($rectimage != '') {
                        list($width, $height, $type, $attr) = @getimagesize($rectimage);
                    } else {
                        $height = '';
                        $width = '';
                    }

                    $tempCdata = array(
                        "imageurl" => $rectimage,
                        "itemname" => $homedata['itemheading'],
                        "image_height" => $height,
                        "image_width" => $width
                    );

                    $imagelist[] = $tempCdata;


                    $cquery = "SELECT * FROM review_rating WHERE product_id='" . $product_id . "' AND app_id = '" . $app_id . "' AND is_deleted = 0 AND review_title!='' ORDER BY id DESC LIMIT $offset, 10";
                    $totalReviews = $this->queryRun($cquery, 'select_all');

                    $finalAvg = "SELECT COALESCE(AVG(rating),'0') AS avg_rating,
								  COUNT(rating) AS rating_count,
								  COALESCE(SUM(IF (reviews IS NOT NULL,1,0)),'0') AS review_count
								  FROM review_rating
								  WHERE product_id='" . $product_id . "' AND app_id = '" . $app_id . "' AND is_deleted = 0";
                    $finalAvgR = $this->queryRun($finalAvg, 'select');

                    $finalIndAvg = "SELECT rm.rating as base_rating,
									COUNT(rr.rating) AS rating,
									(SELECT COUNT(*) FROM review_rating) AS Total,
									ROUND((COUNT(rr.rating)/(SELECT COUNT(*) FROM review_rating)*100), 2) AS Avg
									FROM rating_master rm 
									LEFT OUTER JOIN
									(SELECT rating
									FROM review_rating
									WHERE app_id = '" . $app_id . "' AND product_id ='" . $product_id . "' AND is_deleted = 0) AS rr
									ON rm.rating=rr.rating
									GROUP BY rm.rating";
                    $finalIndAvgR = $this->queryRun($finalIndAvg, 'select_all');

                    $average_rating = $finalAvgR['avg_rating'];
                    $rating_count = $finalAvgR['rating_count'];
                    $review_count = $finalAvgR['review_count'];
                    $onestar = $finalIndAvgR[0]['rating'];
                    $twostar = $finalIndAvgR[1]['rating'];
                    $threestar = $finalIndAvgR[2]['rating'];
                    $fourstar = $finalIndAvgR[3]['rating'];
                    $fivestar = $finalIndAvgR[4]['rating'];

                    if (!empty($totalReviews)) {
                        $maths = count($totalReviews) % 10;
                        if ($maths == 0) {
                            $pageStatus = 'cof';
                        } else {
                            $pageStatus = 'eof';
                        }

                        $total_reviews = array();

                        foreach ($totalReviews as $reviewdata) {
                            $customerquery = "SELECT firstname, lastname,email FROM oc_customer WHERE customer_id='" . $reviewdata['customer_id'] . "'";
                            $customer__data = $this->queryRun($customerquery, 'select');

                            $fullname = '';
                            if (!empty($customer__data)) {
                                $fullname = $customer__data['firstname'];
                                if ($customer__data['lastname'] != '') {
                                    $fullname = $fullname . ' ' . $customer__data['lastname'];
                                }
                            }
                            if ($fullname == '') {
                                $fullname = $customer__data['email'];
                            }
                            if ($reviewdata['reviews'] == null) {
                                $review = '';
                            } else {
                                $review = $reviewdata['reviews'];
                            }
                            $single_rating = array(
                                "rating" => htmlentities($reviewdata['rating']),
                                "title" => htmlentities(ucwords($reviewdata['review_title'])),
                                "user_name" => htmlentities($fullname),
                                "updated" => date('d M, Y', strtotime($reviewdata['created_date'])),
                                "description" => htmlentities($review)
                            );
                            $total_reviews[] = $single_rating;
                        }
                    } else {
                        $total_reviews = array();
                        $pageStatus = 'eof';
                    }

                    $data = array(
                        "screen_id" => 4,
                        "parent_id" => 3,
                        "screen_type" => 4,
                        "tag" => 1,
                        "dirtyflag" => 0,
                        "server_time" => date('Y-m-d H:i:s'),
                        "screen_properties" => array(
                            "title" => $homedata['itemheading'],
                            "popup_flag" => 0,
                            "background_color" => "",
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                        ),
                        "elements" => array(
                            "prod_title" => htmlentities($homedata['itemheading']),
                            "itemid" => $homedata['itemid'],
                            "pagination" => $pageStatus,
                            "rating_and_reviews" => array(
                                "average_rating" => $average_rating,
                                "rating_count" => $rating_count,
                                "review_count" => $review_count,
                                "onestar" => $onestar,
                                "twostar" => $twostar,
                                "threestar" => $threestar,
                                "fourstar" => $fourstar,
                                "fivestar" => $fivestar
                            ),
                            "reviews_array" => $total_reviews
                        )
                    );
                    //	print_r($data);
                    $json = $this->real_json_encode($data, 'prodsuccessData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function saveProductReview($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['product_id']) && $data['product_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '') && (isset($data['rating']) && $data['rating'] != '')) {
            $error = 1;
            if ((isset($data['review_title']) && $data['review_title'] != '') && (isset($data['review']) && $data['review'] != '')) {
                $error = 0;
            } elseif ((isset($data['review_title']) && $data['review_title'] != '') && (isset($data['review']) && $data['review'] == '')) {
                $error = 0;
            } elseif ((isset($data['review_title']) && $data['review_title'] == '') && (isset($data['review']) && $data['review'] == '')) {
                $error = 0;
            }

            if ($error == 0) {
                $authToken = $data['auth_token'];
                $product_id = $data['product_id'];
                $app_idString = $data['app_id'];
                $email = $data['email'];
                $rating = htmlentities($data['rating']);
                $review_title = htmlentities($data['review_title']);
                $review = htmlentities($data['review']);
                $rating_id = htmlentities($data['rating_id']);

                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    $app_id = $app_screenData['id'];

                    $this->lastLogin_new($authToken, $device_id, $app_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $baseUrl = baseUrl();
                    $query = "SELECT DISTINCT p.product_id as itemid, pc.name as itemheading, p.image, p.image_rect, COALESCE(p.price, '') as actualprice, pc.description, COALESCE(ops.price, '') AS special_price, p.length_class_id, p.weight_class_id, ops.discount, p.model FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id WHERE p.product_id='" . $product_id . "'";
                    $homedata = $this->queryRun($query, 'select');

                    if (!empty($homedata)) {
                        $customerquery = "SELECT user_id, customer_id, firstname, email FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_id . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        if (!empty($customer__data)) {
                            $cquery = "SELECT * FROM review_rating WHERE product_id='" . $product_id . "' AND customer_id = '" . $customer__data['customer_id'] . "' AND app_id = '" . $app_id . "'";
                            $reviewdata = $this->queryRun($cquery, 'select');

                            if (empty($reviewdata)) {
                                $curr_date = date('Y-m-d H:i:s');
                                if ($review_title != '') {
                                    $rquery = "INSERT INTO review_rating (app_id, customer_id, product_id, rating, review_title, reviews, ip_address) 
												  VALUES('" . $app_id . "', '" . $customer__data['customer_id'] . "', '" . $product_id . "', '" . $rating . "', '" . $review_title . "', '" . $review . "', '" . $_SERVER["REMOTE_ADDR"] . "')";
                                } else {
                                    $rquery = "INSERT INTO review_rating (app_id, customer_id, product_id, rating, ip_address) 
												  VALUES('" . $app_id . "', '" . $customer__data['customer_id'] . "', '" . $product_id . "', '" . $rating . "', '" . $_SERVER["REMOTE_ADDR"] . "')";
                                }
                                $review_id = $this->queryRun($rquery, 'insert');


                                $data = array('order_status' => 'Your review has been placed successfully.', 'review_id' => $review_id);
                                $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                                echo $json;
                            } else {
                                $json = $this->real_json_encode('', 'error', 'You have already submitted your reviews', 405);
                                echo $json;
                            }
                        } else {
                            $json = $this->real_json_encode('', 'error', 'No user exists for this app', 405);
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Please check your credentials', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function registerVendor() {
        // Registry
        $registry = new Registry();

        // Loader
        $loader = new Loader($registry);
        $registry->set('this', $loader);

        // Config
        $config = new Config();
        $registry->set('config', $config);

        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        // request
        $request = new Request();
        $registry->set('request', $request);

        if ($request->server['REQUEST_METHOD'] == 'POST') {

            if (!array_key_exists('bank_name', $request->post)) {
                $request->post['bank_name'] = '';
            }
            if (!array_key_exists('iban', $request->post)) {
                $request->post['iban'] = '';
            }
            if (!array_key_exists('swift_bic', $request->post)) {
                $request->post['swift_bic'] = '';
            }
            if (!array_key_exists('tax_id', $request->post)) {
                $request->post['tax_id'] = '';
            }
            if (!array_key_exists('bank_address', $request->post)) {
                $request->post['bank_address'] = '';
            }
            if (!array_key_exists('fax', $request->post)) {
                $request->post['fax'] = '';
            }
            if (!array_key_exists('company_id', $request->post)) {
                $request->post['company_id'] = '';
            }
            if (!array_key_exists('address_2', $request->post)) {
                $request->post['address_2'] = '';
            }
            if (!array_key_exists('store_url', $request->post)) {
                $request->post['store_url'] = '';
            }
            if (!array_key_exists('store_description', $request->post)) {
                $request->post['store_description'] = '';
            }
            if (!array_key_exists('agree', $request->post)) {
                $request->post['agree'] = 1;
            }
            $request->post['singup_plan'] = 'lEsEo1G7GgEI-KcH5EuH1-4arIr8taV2ouwBurKjWww,:lEsEo1G7GgEI-KcH5EuH1-4arIr8taV2ouwBurKjWww,:lEsEo1G7GgEI-KcH5EuH1-4arIr8taV2ouwBurKjWww,:RwHlUeZrfq4JVKAiuDQ0S4eUa_q0NedSpjmEnWqVu9k,';
            $country_info = $this->getCountry($request->post['country_id']);
            if ($this->getUsernameBySignUp($request->post['username'])) {
                $json = $this->real_json_encode('', 'error', 'Warning: Username is already registered!', 405);
                echo $json;
            } elseif ($this->getEmailBySignUp($request->post['email'])) {
                $json = $this->real_json_encode('', 'error', 'Warning: E-Mail Address is already registered!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['firstname']) < 1) || (utf8_strlen($request->post['firstname']) > 32)) {
                $json = $this->real_json_encode('', 'error', 'First Name must be between 1 and 32 characters!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['lastname']) < 1) || (utf8_strlen($request->post['lastname']) > 32)) {
                $json = $this->real_json_encode('', 'error', 'Last Name must be between 1 and 32 characters!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $request->post['email'])) {
                $json = $this->real_json_encode('', 'error', 'E-Mail Address does not appear to be valid!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['paypal']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $request->post['paypal'])) {
                $json = $this->real_json_encode('', 'error', 'Paypal Address does not appear to be valid!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['telephone']) < 3) || (utf8_strlen($request->post['telephone']) > 32)) {
                $json = $this->real_json_encode('', 'error', 'Telephone must be between 3 and 32 characters!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['address_1']) < 3) || (utf8_strlen($request->post['address_1']) > 128)) {
                $json = $this->real_json_encode('', 'error', 'Address 1 must be between 3 and 128 characters!', 405);
                echo $json;
            } elseif ((utf8_strlen($request->post['city']) < 2) || (utf8_strlen($request->post['city']) > 128)) {
                $json = $this->real_json_encode('', 'error', 'City must be between 2 and 128 characters!', 405);
                echo $json;
            } elseif ($country_info && $country_info['postcode_required'] && (utf8_strlen($request->post['postcode']) < 2) || (utf8_strlen($request->post['postcode']) > 10)) {
                $json = $this->real_json_encode('', 'error', 'Postcode must be between 2 and 10 characters!', 405);
                echo $json;
            } elseif ($request->post['country_id'] == '') {
                $json = $this->real_json_encode('', 'error', 'Please select a country!', 405);
                echo $json;
            } elseif ($request->post['zone_id'] == '') {
                $json = $this->real_json_encode('', 'error', 'Please select a region / state!', 405);
                echo $json;
            } elseif ($request->post['confirm'] != $request->post['password']) {
                $json = $this->real_json_encode('', 'error', 'Password confirmation does not match password!', 405);
                echo $json;
            } else {

                //if ($config->get('signup_auto_approval')) {
                if (!file_exists(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $request->post['username']))) {
                    mkdir(rtrim(DIR_IMAGE . 'data/', '/') . '/' . str_replace('../', '', $request->post['username']), 0777);
                }
                //}				
                $db->query("INSERT INTO `" . DB_PREFIX . "user` SET username = '" . $db->escape($request->post['username']) . "', password = '" . $db->escape(md5($request->post['password'])) . "', firstname = '" . $db->escape($request->post['firstname']) . "', lastname = '" . $db->escape($request->post['lastname']) . "', email = '" . $db->escape($request->post['email']) . "', user_group_id = '50', date_added = NOW()");
                $user_id = $db->getLastId();
                $singup_plan = explode(':', $request->post['singup_plan']);
                $db->query("INSERT INTO " . DB_PREFIX . "vendors SET user_id = '" . (int) $user_id . "', vendor_name = '" . $db->escape($request->post['company']) . "', company = '" . $db->escape($request->post['company']) . "', firstname = '" . $db->escape($request->post['firstname']) . "', lastname = '" . $db->escape($request->post['lastname']) . "', telephone = '" . $db->escape($request->post['telephone']) . "', commission_id = '1', product_limit_id = '1', fax = '" . $db->escape($request->post['fax']) . "', email = '" . $db->escape($request->post['email']) . "', bank_name = '" . $db->escape($request->post['bank_name']) . "', iban = '" . $db->escape($request->post['iban']) . "', swift_bic = '" . $db->escape($request->post['swift_bic']) . "', tax_id = '" . $db->escape($request->post['tax_id']) . "', bank_address = '" . $db->escape($request->post['bank_address']) . "', company_id = '" . $db->escape($request->post['company_id']) . "', paypal_email = '" . $db->escape($request->post['paypal']) . "', vendor_description = '" . $db->escape($request->post['store_description']) . "', address_1 = '" . $db->escape($request->post['address_1']) . "', address_2 = '" . $db->escape($request->post['address_2']) . "', city = '" . $db->escape($request->post['city']) . "', postcode = '" . $db->escape($request->post['postcode']) . "', country_id = '" . (int) $request->post['country_id'] . "', zone_id = '" . (int) $request->post['zone_id'] . "', store_url = '" . $db->escape($request->post['store_url']) . "', sort_order = '0'");
                $vendor_id = $db->getLastId();
                $query = $db->query("SELECT category_id FROM `" . DB_PREFIX . "category`");
                $categories = array();
                foreach ($query->rows as $row) {
                    array_push($categories, $row['category_id']);
                }
                $db->query("UPDATE " . DB_PREFIX . "user SET status = '1', folder = '" . $db->escape($request->post['username']) . "', vendor_permission = '" . (int) $vendor_id . "', cat_permission = '" . serialize($categories) . "', store_permission = '" . serialize($config->get('signup_store')) . "' WHERE user_id = '" . (int) $user_id . "'");
                $json = $this->real_json_encode('', 'success', 'User Registered Successfully!', 200);
                echo $json;
            }
        }
    }

    public function loginVendor() {
        $registry = new Registry();

        $loader = new Loader($registry);
        $registry->set('load', $loader);

        $config = new Config();
        $registry->set('config', $config);

        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        // request
        $request = new Request();
        $registry->set('request', $request);

        $user = new User($registry);
        if ($user->login($request->post['username'], $request->post['password'])) {
            if ($user->isLogged()) {
                //echo 'User was logged in successfully.';
                $token = md5(mt_rand());
                $user->session->data['token'] = $token;
                //print_r($user);
                echo 'success';
//                                print_r ($user->session->data);
            } else {
                echo 'fails';
            }
        } else {
            echo 'invalid';
        }
    }

    public function registerCustomer() {
        // Registry
        $registry = new Registry();
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);
        $request = new Request();
        if ((isset($request->post['app_id']) && $request->post['app_id'] != '') && (isset($request->post['name']) && $request->post['name'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['password']) && $request->post['password'] != '') && (isset($request->post['phone']) && $request->post['phone'] != '') && (isset($request->post['gender']) && $request->post['gender'] != '') && (isset($request->post['uuid']) && $request->post['uuid'] != '') && (isset($request->post['newsletter']) && $request->post['newsletter'] != '') && (isset($request->post['app_type']) && $request->post['app_type'] != '') && (isset($request->post['login_type']) && $request->post['login_type'] != '') && (isset($request->post['app_version']) && $request->post['app_version'] != '') && (isset($request->post['api_version']) && $request->post['api_version'] != '') && (isset($request->post['platform']) && $request->post['platform'] != '') && (isset($request->post['auth_token']) && $request->post['auth_token'] != '')) {
            $platform = $request->post['platform'];
            $app_version = $request->post['app_version'];
            $api_version = $request->post['api_version'];
            $authToken = $request->post['auth_token'];
            $device_id = $request->post['device_id'];
            $app_id = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';
            $email = isset($request->post['email']) != '' ? $request->post['email'] : '';
            $pwd = isset($request->post['password']) != '' ? $request->post['password'] : '';
            $sendmail = 0;
            $jumpToAppID = '';
            $authResult = $this->authCheck($authToken, $device_id);
            if ($authResult > 0) {
                $appQueryData = "select * from app_data where app_id='" . $app_id . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');
                // user
                $customer = new Customer($registry);
                $registry->set('request', $request);
                if ($request->server['REQUEST_METHOD'] == 'POST' && !empty($app_screenData)) {
                    $first_word = $request->post['name'];
                    $last_word = '';
                    if (strpos($request->post['name'], ' ') !== false) {
                        $last_space = strrpos($request->post['name'], ' ');
                        $last_word = substr($request->post['name'], $last_space);
                        $first_word = substr($request->post['name'], 0, $last_space);
                    }
                    $request->post['firstname'] = $first_word;
                    $request->post['lastname'] = $last_word;
                    $request->post['fax'] = '';
                    $request->post['company'] = '';
                    $request->post['company_id'] = '';
                    $request->post['tax_id'] = '';
                    $request->post['address_2'] = '';
                    $request->post['telephone'] = $request->post['phone'];
                    $request->post['address_1'] = '123';
                    $request->post['city'] = 'abc';
                    $request->post['postcode'] = '123456';
                    $request->post['country_id'] = '99';
                    $request->post['zone_id'] = '100';
                    $request->post['confirm'] = $request->post['password'];
                    $request->post['gender'] = $request->post['gender'];

                    $country_info = $this->getCountry($request->post['country_id']);
                    if ((utf8_strlen($request->post['firstname']) < 1) || (utf8_strlen($request->post['firstname']) > 32)) {
                        $json = $this->real_json_encode('', 'error', 'First Name must be between 1 and 32 characters!', 405);
                        echo $json;
                    } elseif ((utf8_strlen($request->post['email']) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $request->post['email'])) {
                        $json = $this->real_json_encode('', 'error', 'E-Mail Address does not appear to be valid!', 405);
                        echo $json;
                    } elseif ($this->getTotalCustomersByEmail($request->post['email'], $app_screenData['id']) && $request->post['login_type'] == 1) {
                        $json = $this->real_json_encode('', 'error', 'Warning: E-Mail Address is already registered!', 405);
                        echo $json;
                    } elseif ((utf8_strlen($request->post['telephone']) < 3) || (utf8_strlen($request->post['telephone']) > 32)) {
                        $json = $this->real_json_encode('', 'error', 'Telephone must be between 3 and 32 characters!', 405);
                        echo $json;
                    } elseif ((utf8_strlen($request->post['address_1']) < 3) || (utf8_strlen($request->post['address_1']) > 128)) {
                        $json = $this->real_json_encode('', 'error', 'Address 1 must be between 3 and 128 characters!', 405);
                        echo $json;
                    } elseif ((utf8_strlen($request->post['city']) < 2) || (utf8_strlen($request->post['city']) > 128)) {
                        $json = $this->real_json_encode('', 'error', 'City must be between 2 and 128 characters!', 405);
                        echo $json;
                    } elseif ($country_info['postcode_required'] && (utf8_strlen($request->post['postcode']) < 2) || (utf8_strlen($request->post['postcode']) > 10)) {
                        $json = $this->real_json_encode('', 'error', 'Postcode must be between 2 and 10 characters!', 405);
                        echo $json;
                    } elseif ($request->post['country_id'] == '') {
                        $json = $this->real_json_encode('', 'error', 'Please select a country!', 405);
                        echo $json;
                    } elseif (!isset($request->post['zone_id']) || $request->post['zone_id'] == '') {
                        $json = $this->real_json_encode('', 'error', 'Please select a region / state!', 405);
                        echo $json;
                    } elseif ((utf8_strlen($request->post['password']) < 4) || (utf8_strlen($request->post['password']) > 20)) {
                        $json = $this->real_json_encode('', 'error', 'Password must be between 4 and 20 characters!', 405);
                        echo $json;
                    } elseif ($request->post['confirm'] != $request->post['password']) {
                        $json = $this->real_json_encode('', 'error', 'Password confirmation does not match password!', 405);
                        echo $json;
                    } else {

                        $request->post['address_1'] = '';
                        $request->post['city'] = '';
                        $request->post['postcode'] = '';
                        $request->post['country_id'] = '';
                        $request->post['zone_id'] = '';
                        $request->post['user_id'] = $authResult['user_id'];
                        $request->post['app_id'] = $app_screenData['id'];

                        if ($app_screenData['jump_to'] != 0 && $app_screenData['type_app'] == 1) {
                            if ($app_screenData['jump_to_app_id'] != 0) {

                                $jumpToAppID = $app_screenData['jump_to_app_id'];
                                $customerloginRetail = $this->insideCustomerLogin($request->post['email'], '', $jumpToAppID);
                                $sendmail = 0;
                                if (empty($customerloginRetail)) {

                                    if ($request->post['login_type'] == 0) {
                                        if ($request->post['telephone'] == '12345678' || $request->post['telephone'] == '1234568') {
                                            $request->post['telephone'] = '';
                                        }
                                        $customer_idRetail = $this->addCustomer($request->post, $request->post['login_type'], '', $app_screenData, $sendmail, $jumpToAppID);
                                        $responseRetail = $this->signup($app_id, $device_id, $request->post['firstname'], $request->post['lastname'], $platform, $api_version, $email, $pwd, $api_version, $sendmail, $jumpToAppID, $request->post['phone']);
                                    } else {
                                        $this->addNewCustomer($request->post, $request->post['login_type'], $api_version, $app_screenData, $sendmail, $jumpToAppID);

                                        $responseRetail = $this->signup($app_id, $device_id, $request->post['firstname'], $request->post['lastname'], $platform, $api_version, $email, $pwd, $api_version, $sendmail, $jumpToAppID, $request->post['phone']);
                                    }
                                }
                            }
                        }

                        $customerdata = $this->getCustomerByAppEmail($request->post['email'], $app_screenData['id']);

                        if (empty($customerdata)) {
                            if ($request->post['login_type'] == 0) { // for facebook
                                // telephone fix if login from facebook and user didnt set phone no.
                                if ($request->post['telephone'] == '12345678' || $request->post['telephone'] == '1234568') {
                                    $request->post['telephone'] = '';
                                }
                                $customer_id = $this->addCustomer($request->post, $request->post['login_type'], '', $app_screenData);

                                $response = $this->signup($app_id, $device_id, $request->post['firstname'], $request->post['lastname'], $platform, $api_version, $email, $pwd, $api_version, $sendmail, $jumpToAppID, $request->post['phone']);

                                $customer->session->data['customer_id'] = $customer_id;
                                $server = (object) array('server' => $_SERVER);
                                $customer->request = $server;

                                $customerlogin = $this->insideCustomerLogin($request->post['email'], '', $app_screenData['id']);

                                if (!empty($customerlogin)) {
                                    //if($customer->isLogged())
                                    //{
                                    $customer_id = $customerlogin['customer_id'];

                                    $cartdata = $this->getCartData($customerlogin['customer_id'], $app_screenData['id']);

                                    $cartdatacount = 0;
                                    if (!empty($cartdata)) {
                                        $cartdatacount = count($cartdata['cartdata']);
                                    }
                                    //echo 'User was logged in successfully.';
                                    $token = md5(mt_rand());
                                    $customer->session->data['token'] = $token;
                                    //print_r($user);
                                    $data = array(
                                        "email" => $customerlogin['email'],
                                        "cartcount" => $cartdatacount
                                    );

                                    $authToken = bin2hex(openssl_random_pseudo_bytes(16));

                                    // telephone fix if login from facebook and user didnt set phone no.
                                    if ($request->post['telephone'] == '12345678') {
                                        $rtelephone = '';
                                    } else {
                                        $rtelephone = $request->post['telephone'];
                                    }

                                    $query1 = "UPDATE oc_customer SET auth_token = '" . $authToken . "', telephone = '" . $rtelephone . "' WHERE customer_id='" . $customer_id . "'";
                                    $result2 = $this->queryRun($query1, 'update');

                                    $device_id = $authResult['device_id'];
                                    $this->lastLogin_new($authToken, $device_id, $app_screenData['id']);
                                    $lastlogindate = $this->lastLoginUpdateToken($authToken, $request->post['email']);

                                    $json = $this->real_json_encode($data, 'successData', 'Customer Registered Successfully!', 200, $lastlogindate, $authToken);
                                    echo $json;
                                } else {
                                    $json = $this->real_json_encode('', 'error', 'User not found or username or password do not match.', 405);
                                    echo $json;
                                }
                            } else {
                                // creating new user at different api version
                                $this->addNewCustomer($request->post, $request->post['login_type'], $api_version, $app_screenData);

                                $response = $this->signup($app_id, $device_id, $request->post['firstname'], $request->post['lastname'], $platform, $api_version, $email, $pwd, $api_version, $sendmail, $jumpToAppID, $request->post['phone']);


                                $lastlogindate = date('Y-m-d H:i:s');

                                $json = $this->real_json_encode(array('email' => $request->post['email']), 'success', 'Customer Registered Successfully! Please verify your email.', 200);
                                echo $json;
                            }
                        } else {
                            if ($request->post['login_type'] == 0) {
                                //$customer->session->data['customer_id'] = $customer_id;

                                $server = (object) array('server' => $_SERVER);
                                $customer->request = $server;

                                $customerlogin = $this->insideCustomerLogin($request->post['email'], '', $app_screenData['id']);

                                if (!empty($customerlogin)) {
                                    /* if($customer->isLogged())
                                      { */
                                    $customer_id = $customerlogin['customer_id'];

                                    $cartdata = $this->getCartData($customerlogin['customer_id'], $app_screenData['id']);

                                    $cartdatacount = 0;
                                    if (!empty($cartdata)) {
                                        $cartdatacount = count($cartdata['cartdata']);
                                    }
                                    //echo 'User was logged in successfully.';
                                    $token = md5(mt_rand());
                                    $customer->session->data['token'] = $token;
                                    //print_r($user);
                                    $data = array(
                                        "email" => $customerlogin['email'],
                                        "name" => $customerlogin['firstname'],
                                        "cartcount" => $cartdatacount
                                    );

                                    $authToken = bin2hex(openssl_random_pseudo_bytes(16));

                                    $query1 = "UPDATE oc_customer SET auth_token = '" . $authToken . "' WHERE customer_id='" . $customer_id . "'";
                                    $result2 = $this->queryRun($query1, 'update');

                                    $device_id = $authResult['device_id'];
                                    $this->lastLogin_new($authToken, $device_id, $app_screenData['id']);
                                    $lastlogindate = $this->lastLoginUpdateToken($authToken, $request->post['email']);

                                    $json = $this->real_json_encode($data, 'successData', 'Customer Registered Successfully!', 200, $lastlogindate, $authToken);
                                    echo $json;
                                    /* }
                                      else
                                      {
                                      $json = $this->real_json_encode('', 'error', 'Problem occurs in login vendor', 405);
                                      echo $json;
                                      } */
                                } else {
                                    $json = $this->real_json_encode('', 'error', 'User not found or username or password do not match.', 405);
                                    echo $json;
                                }
                            } else {
                                $json = $this->real_json_encode('', 'error', 'Email Already Exists.', 405);
                                echo $json;
                            }
                        }
                    }
                }
                //}
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * resend activation code for registered user
     * Added by Arun Srivastava on 2/12/15
     */

    public function resendActivationCode() {
        // request
        $request = new Request();

        if ((isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['app_id']) && $request->post['app_id'] != '')) {
            $authToken = $request->post['auth_token'];
            $app_idString = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                if ($app_idString != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    $app_id = $app_screenData['id'];
                } else {
                    $app_id = '';
                }

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);


                $digits = 4;
                $auto_otp = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

                $email = $request->post['email'];

                $registry = new Registry();
                // Database
                $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
                $registry->set('db', $db);
                $db->query("UPDATE " . DB_PREFIX . "customer SET otp = '" . $auto_otp . "', otp_date = NOW() WHERE LOWER(email) = '" . $db->escape(utf8_strtolower($email)) . "' AND app_id='" . $app_id . "'");

                $customerquery = "SELECT firstname, customer_id, user_id, email, otp, otp_date FROM oc_customer WHERE email='" . $request->post['email'] . "' AND app_id='" . $app_id . "'";
                $customer__data = $this->queryRun($customerquery, 'select');

                $storename = ucfirst($app_screenData['summary']);
                $validemail = $this->get_email_checkpointer(1, $app_id);

                if (!empty($validemail) && $validemail['email'] != '') {
                    $sendData = array();
                    $sendData['storename'] = $storename;
                    $sendData['from'] = $validemail['email'];
                    $sendData['to'] = $email;
                    $sendData['username'] = ucfirst($customer__data['firstname']);
                    $sendData['otp'] = $auto_otp;
                    $this->sendEDM(1, $sendData);
                }

                $lastlogindate = date('Y-m-d H:i:s');

                $json = $this->real_json_encode(array('email' => $email), 'success', 'Please check your email for new activation code.', 200);
                echo $json;
            }
        }
    }

    /*
     * verify registered user
     * Added by Arun Srivastava on 2/12/15
     */

    public function verifyRegisteredUser() {
        // request
        $request = new Request();

        if ((isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['otp']) && $request->post['otp'] != '') && (isset($request->post['app_id']) && $request->post['app_id'] != '')) {
            $authToken = $request->post['auth_token'];
            $app_idString = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                if ($app_idString != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    $app_id = $app_screenData['id'];
                    $appname = $app_screenData['summary'];
                } else {
                    $app_id = '';
                    $appname = '';
                }

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                // Registry
                $registry = new Registry();

                $config = new Config();
                $registry->set('config', $config);
                if ($request->server['REQUEST_METHOD'] == 'POST') {
                    $email = $request->post['email'];
                    $otp = $request->post['otp'];

                    $customerquery = "SELECT firstname, customer_id, user_id, email, otp, otp_date FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_id . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    if ($appname == '') {
                        $store_name = $this->getConfig('config_name');
                    } else {
                        $store_name = $appname;
                    }

                    $otp_time_after = date("Y-m-d H:i:s", strtotime('+1 day', strtotime($customer__data['otp_date'])));
                    $current_time = date("Y-m-d H:i:s");

                    if ($customer__data['otp'] != $otp) {
                        $json = $this->real_json_encode('', 'error', 'Authentication code mismatch', 405);
                        echo $json;
                    } elseif ($otp_time_after < $current_time) {
                        $json = $this->real_json_encode('', 'error', 'Authentication code expired. Please resend authentication code', 405);
                        echo $json;
                    } else {
                        $this->updateUserStatus($customer__data['email']);

                        $validemail = $this->get_email_checkpointer(2, $app_id);

                        if (!empty($validemail) && $validemail['email'] != '') {
                            $sendData = array();
                            $sendData['storename'] = $store_name;
                            $sendData['from'] = $validemail['email'];
                            $sendData['to'] = $customer__data['email'];
                            $sendData['username'] = ucfirst($customer__data['firstname']);
                            $this->sendEDM(2, $sendData);
                        }

                        $data = array(
                            "email" => $customer__data['email'],
                            "name" => ucfirst($customer__data['firstname'])
                        );
                        $json = $this->real_json_encode($data, 'successData', 'congratulation! Your account is verified', 200, $lastlogindate, $authToken);
                        echo $json;
                    }
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function loginCustomer() {
        // request
        $request = new Request();
        if ((isset($request->post['app_id']) && $request->post['app_id'] != '') && (isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['password']) && $request->post['password'] != '')) {
            $authToken = $request->post['auth_token'];
            $app_id_str = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';
            $authResult = $this->authCheck($authToken);

            $email = $request->post['email'];
            $pwd = $request->post['password'];

            if ($authResult > 0) {
                $appQueryData = "select * from app_data where app_id='" . $app_id_str . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');
                $app_id = $app_screenData['id'];
                $jump_to_app_id = '';
                $app_idRetail = $app_screenData['id'];
                if ($app_screenData['jump_to'] == 1 && $app_screenData['jump_to_app_id'] != '') {
                    if ($app_screenData['jump_to_app_id'] != 0) {
                        $app_idRetail = $app_screenData['jump_to_app_id'];
                    }
                }

                $count1 = $this->loginCheck($app_id, $email, $pwd);
                $count=count($count1);
                if ($count > 0) {
                    // Registry
                    $registry = new Registry();

                    // Database
                    $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
                    $registry->set('db', $db);

                    // user
                    $customer = new Customer($registry);

                    $customer_info = $this->getUserByEmail($request->post['email'], $app_id);

                    if (!empty($customer_info) && $customer_info['is_verified'] != 1) {
                        $json = $this->real_json_encode('', 'error', 'Warning: Your account requires approval before you can login.', 405);
                        echo $json;
                    } else {
                        $server = (object) array('server' => $_SERVER);
                        $customer->request = $server;
                        //$customer->session->data['customer_id'] = $_SERVER;
                        //$customerlogin = $customer->login($request->post['email'], $request->post['password']);
                        $customerlogin = $this->insideCustomerLogin($request->post['email'], $request->post['password'], $app_id);
                        if (empty($customerlogin)) {
                            $json = $this->real_json_encode('', 'error', 'Warning: No match for E-Mail Address and/or Password.', 405);
                            echo $json;
                        } else {
                            $authToken1 = bin2hex(openssl_random_pseudo_bytes(16));
                            $device_id = $authResult['device_id'];
                            $lastlogindate = $this->lastLogin_new($authToken1, $device_id, $app_id);

                            $lastlogindate = $this->lastLoginUpdateToken($authToken1, $request->post['email']);

                            $customername  = $count1['firstname'].' '.$count1['lastname'];
                           // $customername = $customerlogin['firstname'] . ' ' . $customerlogin['lastname'];

                            $cartdata = $this->getCartData($customerlogin['customer_id'], $app_idRetail);

                            $cartdatacount = 0;
                            if (!empty($cartdata)) {
                                $cartdatacount = count($cartdata['cartdata']);
                            }
                            //print_r($customer);
                            $data = array(
                                "email" => $customerlogin['email'],
                                "name" => $customername,
                                "cartcount" => $cartdatacount
                            );
                            $json = $this->real_json_encode($data, 'successData', 'Customer Login Successfully!', 200, $lastlogindate, $authToken1);
                            echo $json;
                        }
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Please check your login details', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * forgot customer
     * Added by Arun Srivastava on 2/12/15
     */

    public function forgotCustomer() {
        // request
        $request = new Request();

        if ((isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['app_id']) && $request->post['app_id'] != '')) {
            $api_version = isset($request->post['api_version']) != '' ? $request->post['api_version'] : '1.0';
            $authToken = $request->post['auth_token'];
            $device_id = isset($request->post['device_id']) != '' ? $request->post['device_id'] : '';
            $app_id = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';

            $authResult = $this->authCheck($authToken, $device_id);

            if ($authResult > 0) {
                if ($app_id != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_id . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');
                      $appsid=$app_screenData['id'];
                } else {
                    $appQueryData = "select * from app_data where id='" . $authResult['app_id'] . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');
                     $appsid=$authResult['app_id'];
                }

                $authToken = $authResult['auth_token'];
                if ($device_id == '') {
                    $device_id = $authResult['device_id'];
                }

                $this->lastLogin_new($authToken, $device_id, $appsid);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                // Registry
                $registry = new Registry();

                $config = new Config();
                $registry->set('config', $config);
                if ($request->server['REQUEST_METHOD'] == 'POST') {

                    if (!isset($request->post['email'])) {
                        $json = $this->real_json_encode('', 'error', 'Warning: The E-Mail Address was not found in our records, please try again!', 405);
                        echo $json;
                    } elseif (!$this->getTotalCustomersByEmail($request->post['email'], $app_screenData['id'])) {
                        $json = $this->real_json_encode('', 'error', 'Warning: The E-Mail Address was not found in our records, please try again!', 405);
                        echo $json;
                    } else {
                        $password = $this->forgotUpdateCustomer($request->post, $api_version, $app_screenData);

                        $customerquery = "SELECT firstname, customer_id, user_id, email, otp, otp_date FROM oc_customer WHERE email='" . $request->post['email'] . "' AND app_id='" . $app_screenData['id'] . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        $store_name = ucfirst($app_screenData['summary']);
                        $validemail = $this->get_email_checkpointer(5, $app_screenData['id']);

                        /*   if (!empty($validemail) && $validemail['email'] != '') {
                          $sendData = array();
                          $sendData['storename'] = $store_name;
                          $sendData['from'] = $validemail['email'];
                          $sendData['to'] = $customer__data['email'];
                          $sendData['password'] = $password;
                          $sendData['username'] = ucfirst($customer__data['firstname']);
                          $this->sendEDM(5, $sendData);
                          } */

                        $data = array(
                            "password" => ''
                        );

                        if ($api_version < '1.0.1') {
                            $json = $this->real_json_encode($data, 'successData', 'Customer Password Changed!', 200, $lastlogindate, $authToken);
                        } else {
                            $json = $this->real_json_encode($data, 'successData', 'OPT sent to email', 200, $lastlogindate, $authToken);
                        }
                        echo $json;
                    }
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * update forgoted password
     * Added by Arun Srivastava on 2/12/15
     */

    public function updateForgotPassword() {
    
        // request
        $request = new Request();

        if ((isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['otp']) && $request->post['otp'] != '') && (isset($request->post['newpassword']) && $request->post['newpassword'] != '') && (isset($request->post['app_id']) && $request->post['app_id'] != '')) {
            $authToken = $request->post['auth_token'];
            $app_id = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                if ($app_id != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_id . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');
                } else {
                    $appQueryData = "select * from app_data where id='" . $authResult['app_id'] . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');
                }

                // Registry
                $registry = new Registry();

                $config = new Config();
                $registry->set('config', $config);
                if ($request->server['REQUEST_METHOD'] == 'POST') {
                    $email = $request->post['email'];
                    $otp = $request->post['otp'];

                    $customerquery = "SELECT firstname, customer_id, user_id, email, otp, otp_date FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');
                    $store_name = ucfirst($app_screenData['summary']);
                    $password = $request->post['newpassword'];

                    $otp_time_after = date("Y-m-d H:i:s", strtotime('+1 day', strtotime($customer__data['otp_date'])));
                    $current_time = date("Y-m-d H:i:s");

                    if (empty($customer__data)) {
                        $json = $this->real_json_encode('', 'error', 'This email is not registered with us', 405);
                        echo $json;
                    } else if ($customer__data['otp'] != $otp) {
                        $json = $this->real_json_encode('', 'error', 'Authentication code mismatch', 406);
                        echo $json;
                    } elseif ($otp_time_after < $current_time) {
                        $json = $this->real_json_encode('', 'error', 'Authentication code expired. Please resend authentication code', 407);
                        echo $json;
                    } else {
              
                        $this->editPassword($customer__data['email'], $password, $app_screenData['id']);
                        $validemail = $this->get_email_checkpointer(7, $app_screenData['id']);
                        if (!empty($validemail) && $validemail['email'] != '') {
                            $sendData = array();
                            $sendData['storename'] = $store_name;
                            $sendData['from'] = $validemail['email'];
                            $sendData['to'] = $customer__data['email'];
                            $sendData['password'] = $password;
                            $sendData['username'] = ucfirst($customer__data['firstname']);
                            $this->sendEDM(7, $sendData);
                        }

                        /*
                          $subject = sprintf('%s - New Password', $store_name);

                          $message  = sprintf('A new password was requested from %s.', $store_name) . "<br /><br />";
                          $message .= 'Your new password is:' .$password. "<br /><br />";

                          $to        = $customer__data['email'];
                          $formemail = 'noreply@instappy.com';
                          $key       = 'f894535ddf80bb745fc15e47e42a595e';
                          //$url       = 'https://api.falconide.com/falconapi/web.send.rest?api_key='.$key.'&subject='.rawurlencode($subject).'&fromname='.rawurlencode("Forgot Password").'&from='.$formemail.'&content='.rawurlencode($message).'&recipients='.$to;
                          //$mail_resp = file_get_contents($url);
                          $curl = curl_init();
                          curl_setopt_array($curl, array(
                          CURLOPT_RETURNTRANSFER => 1,
                          CURLOPT_URL => 'https://api.falconide.com/falconapi/web.send.rest',
                          CURLOPT_POST => 1,
                          CURLOPT_POSTFIELDS => array(
                          'api_key' => $key,
                          'subject' => $subject,
                          'fromname' => $store_name != '' ? $store_name : 'Instappy',
                          'from' => $formemail,
                          'content' => $message,
                          'recipients' => $to
                          )
                          ));
                          $customerhead = curl_exec($curl);

                          curl_close($curl);
                         */

                        $data = array(
                            "password" => $password
                        );
                        $json = $this->real_json_encode($data, 'successData', 'Customer Password Changed!', 200, $lastlogindate, $authToken);
                        echo $json;
                    }
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * get product listing
     * added by Arun Srivastava on 13/7/15
     */

    public function getCustomerProfile($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['email']) && $data['email'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $email = $data['email'];
            $app_idString = $data['app_id'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                if ($app_idString != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    $app_id = $app_screenData['id'];
                } else {
                    $app_id = '';
                }

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $profiledata = $this->getprofiledata($email, $app_id);

                $profiledata['isaddressadded'] = 0;

                $AddQuery = "SELECT ca.* FROM customer_address ca LEFT JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $profiledata['customer_id'] . "'";
                $customerAdd = $this->queryRun($AddQuery, 'select');
                if (!empty($customerAdd)) {
                    $profiledata['isaddressadded'] = 1;
                }

                unset($profiledata['customer_id']);
                $json = $this->real_json_encode(json_decode(json_encode(array('profile' => $profiledata)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function updateCustomerProfile($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['email']) && $data['email'] != '') && (isset($data['telephone']) && $data['telephone'] != '') && (isset($data['gender']) && $data['gender'] != '') && (isset($data['firstname']) && $data['firstname'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $email = $data['email'];
            $app_idString = $data['app_id'];

            $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
            $app_screenData = $this->query_run($appQueryData, 'select');

            if (isset($data['old_email']) && $data['old_email'] != '') {
                $lastlogindate = date('Y-m-d H:i:s');
                $customerquery = "SELECT user_id FROM oc_customer WHERE email='" . $data['old_email'] . "' AND app_id = '" . $app_screenData['id'] . "'";
                $customer__data = $this->queryRun($customerquery, 'select');

                $returndata = $this->updateCustomer($data, $customer__data['user_id'], $app_screenData['id']);
                if ($returndata) {
                    $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Profile updated successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                } else {
                    $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                }
                echo $json;
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $this->lastLogin_new($authToken, $device_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $customerquery = "SELECT user_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $returndata = $this->updateCustomer($data, $customer__data['user_id'], $app_screenData['id']);
                    if ($returndata) {
                        $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Profile updated successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                    } else {
                        $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                    }
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * add to cart
     * Added By Arun Srivastava 3/8/15
     */
    /*
      public function addToCart($data)
      {
      if(isset($data['auth_token']) && isset($data['cart_data']))
      {
      $authToken = $data['auth_token'];
      $cart_data = json_decode($data['cart_data']);

      $authResult   = $this->authCheck($authToken);

      if($authResult > 0)
      {
      $authToken = $authResult['auth_token'];
      $device_id = $authResult['device_id'];

      $this->lastLogin_new($authToken, $device_id);

      $lastlogindate  = $this->lastLoginUpdateToken($authToken);

      $customerquery  = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='".$authToken."'";
      $customer__data = $this->queryRun($customerquery, 'select');

      if(!empty($cart_data))
      {
      $user_id = $customer__data['user_id'];
      $customer_id = $customer__data['customer_id'];
      $query   = "SELECT customer_cart FROM oc_customer_cart WHERE customer_id='".$customer_id."' AND is_active='1' AND is_deleted='0'";
      $result  = $this->queryRun($query, 'select');

      $finalcart = array();

      $currProd               = array();
      $currProd['product_id'] = $cart_data->product_id;
      $currProd['qty']        = isset($cart_data->qty) ? $cart_data->qty : 1;
      $currProd['size']       = isset($cart_data->size) ? $cart_data->size : '';
      $currProd['color']      = isset($cart_data->color) ? $cart_data->color : '';
      if(!empty($result) && $result['customer_cart'] != '')
      {
      $unserializecart = unserialize($result['customer_cart']);


      $tempcart = array();
      $counter = 0;
      if(!empty($unserializecart))
      {
      foreach($unserializecart as $currcartval)
      {
      $mycrt = $currcartval;

      if(($mycrt['product_id'] == $currProd['product_id']) && ($mycrt['size'] == $currProd['size']) && ($mycrt['color'] == $currProd['color']))
      {
      $mycrt['qty'] = $mycrt['qty']+$currProd['qty'];

      $counter++;
      }

      $tempcart[] = $mycrt;
      }
      }

      if($counter == 0)
      {
      $tempcart[] = $currProd;
      }

      $finalcart = $tempcart;
      }
      else
      {
      $finalcart = array($currProd);
      }
      $supercart   = serialize($finalcart);

      $returndata = $this->addtocustomercart($supercart, $customer_id);
      if($returndata)
      {
      $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Cart updated successfully.')), FALSE), 'successData','',200, $lastlogindate, $authToken);
      }
      else
      {
      $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
      }
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
     */

    /*
     * add to cart
     * Added By Arun Srivastava 24/9/15
     */

    public function addToCart($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['cart_data']) && $data['cart_data'] != '')) {
            $authToken = $data['auth_token'];
            $cart_data = json_decode($data['cart_data']);
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    if (!empty($cart_data)) {
                        $user_id = $customer__data['user_id'];
                        $customer_id = $customer__data['customer_id'];
                        $query00 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active='1'";
                        $result00 = $this->queryRun($query00, 'select');

                        if (!empty($result00)) {
                            $cartmainid = $result00['id'];
                        } else {
                            $query1 = "INSERT INTO oc_customer_cart_main VALUES('', '" . $customer_id . "', '" . $app_screenData['id'] . "', '1')";
                            $result1 = $this->queryRun($query1, 'insert');

                            $cartmainid = $result1;
                        }


                        $query = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $cartmainid . "' AND customer_id='" . $customer_id . "' AND is_active='1' AND is_deleted='0'";
                        $result = $this->queryRun($query, 'select_all');

                        $finalcart = array();

                        $currProd = array();
                        $currProd['product_id'] = $cart_data->product_id;
                        $currProd['qty'] = (isset($cart_data->qty) && $cart_data->qty > 1) ? $cart_data->qty : 1;
                        $currProd['size'] = isset($cart_data->size) ? $cart_data->size : '';
                        $currProd['color'] = isset($cart_data->color) ? $cart_data->color : '';
                        $currProd['material'] = isset($cart_data->material) ? $cart_data->material : '';

                        $final_seq_id = 0;
                        if (!empty($result)) {
                            $counter = 0;
                            foreach ($result as $singlecartdata) {
                                $unserializecart = unserialize($singlecartdata['customer_cart']);

                                if (!empty($unserializecart)) {
                                    $mycrt = $unserializecart;

                                    if (($mycrt['product_id'] == $currProd['product_id']) && ($mycrt['size'] == $currProd['size']) && ($mycrt['color'] == $currProd['color'])) {
                                        $mycrt['qty'] = $mycrt['qty'] + $currProd['qty'];

                                        $currcustcart = serialize($mycrt);

                                        $queryup = "UPDATE oc_customer_cart_data SET customer_cart = '" . $currcustcart . "' WHERE id = '" . $singlecartdata['id'] . "'";
                                        $resultup = $this->queryRun($queryup, 'update');

                                        $final_seq_id = $singlecartdata['id'];

                                        $counter++;
                                    }
                                }
                            }

                            if ($counter == 0) {
                                $currcustcart = serialize($currProd);

                                $queryup = "INSERT INTO oc_customer_cart_data VALUES('', '" . $cartmainid . "', '" . $customer_id . "', '" . $currcustcart . "', '1', '0')";
                                $resultup = $this->queryRun($queryup, 'insert');
                                $final_seq_id = $resultup;
                            }

                            $json = $this->real_json_encode(json_decode(json_encode(array('cart_sequence_id' => $final_seq_id, 'status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        } else {
                            $currcustcart = serialize($currProd);

                            $queryup = "INSERT INTO oc_customer_cart_data VALUES('', '" . $cartmainid . "', '" . $customer_id . "', '" . $currcustcart . "', '1', '0')";
                            $resultup = $this->queryRun($queryup, 'insert');
                            $final_seq_id = $resultup;

                            $json = $this->real_json_encode(json_decode(json_encode(array('cart_sequence_id' => $final_seq_id, 'status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        }
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No customer found', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        if (!empty($cart_data)) {
                            $user_id = $customer__data['user_id'];
                            $customer_id = $customer__data['customer_id'];
                            $query00 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active='1'";
                            $result00 = $this->queryRun($query00, 'select');

                            if (!empty($result00)) {
                                $cartmainid = $result00['id'];
                            } else {
                                $query1 = "INSERT INTO oc_customer_cart_main VALUES('', '" . $customer_id . "', '" . $app_screenData['id'] . "', '1')";
                                $result1 = $this->queryRun($query1, 'insert');

                                $cartmainid = $result1;
                            }


                            $query = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $cartmainid . "' AND customer_id='" . $customer_id . "' AND is_active='1' AND is_deleted='0'";
                            $result = $this->queryRun($query, 'select_all');

                            $finalcart = array();

                            $currProd = array();
                            $currProd['product_id'] = $cart_data->product_id;
                            $currProd['qty'] = (isset($cart_data->qty) && $cart_data->qty > 1) ? $cart_data->qty : 1;
                            $currProd['size'] = isset($cart_data->size) ? $cart_data->size : '';
                            $currProd['color'] = isset($cart_data->color) ? $cart_data->color : '';
                            $currProd['material'] = isset($cart_data->material) ? $cart_data->material : '';
                            if (!empty($result)) {
                                $counter = 0;
                                foreach ($result as $singlecartdata) {
                                    $unserializecart = unserialize($singlecartdata['customer_cart']);

                                    if (!empty($unserializecart)) {
                                        $mycrt = $unserializecart;

                                        if (($mycrt['product_id'] == $currProd['product_id']) && ($mycrt['size'] == $currProd['size']) && ($mycrt['color'] == $currProd['color'])) {
                                            $mycrt['qty'] = $mycrt['qty'] + $currProd['qty'];

                                            $currcustcart = serialize($mycrt);

                                            $queryup = "UPDATE oc_customer_cart_data SET customer_cart = '" . $currcustcart . "' WHERE id = '" . $singlecartdata['id'] . "'";
                                            $resultup = $this->queryRun($queryup, 'update');

                                            $counter++;
                                        }
                                    }
                                }

                                if ($counter == 0) {
                                    $currcustcart = serialize($currProd);

                                    $queryup = "INSERT INTO oc_customer_cart_data VALUES('', '" . $cartmainid . "', '" . $customer_id . "', '" . $currcustcart . "', '1', '0')";
                                    $resultup = $this->queryRun($queryup, 'insert');
                                }

                                $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            } else {
                                $currcustcart = serialize($currProd);

                                $queryup = "INSERT INTO oc_customer_cart_data VALUES('', '" . $cartmainid . "', '" . $customer_id . "', '" . $currcustcart . "', '1', '0')";
                                $resultup = $this->queryRun($queryup, 'insert');

                                $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            }
                            echo $json;
                        } else {
                            $json = $this->real_json_encode('', 'error', 'No customer found', 405);
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Please Check Your Credentials', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * view cart
     * Added By Arun Srivastava 5/8/15
     */

    public function viewCart($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $cartdata = $this->getCartData($customer__data['customer_id'], $app_screenData['id']);

					if($app_screenData['id'] == '7066')
					{
						$service_tax = '';
						$vat = '';
					}
					else
					{
						$service_tax = 14.5;
						$vat = 4;
					}
                    $finalarr = json_decode(json_encode(
                                    array(
                                        "screen_id" => 7,
                                        "parent_id" => 0,
                                        "screen_type" => 5,
                                        "tag" => 1,
                                        "dirtyflag" => 0,
                                        "server_time" => date('Y-m-d H:i:s'),
                                        "screen_properties" => array(
                                            "title" => 'My Cart',
                                            "popup_flag" => 0,
                                            "background_color" => "",
                                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                                        ),
                                        "taxes" => array(
                                            "service_tax" => $service_tax,
                                            "vat" => $vat
                                        ),
                                        'cart' => $cartdata['cartdata']
                                    )
                            ), FALSE);

                    $json = $this->real_json_encode($finalarr, 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);
                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        $cartdata = $this->getCartData($customer__data['customer_id'], $app_screenData['id']);

                        $finalarr = json_decode(json_encode(
                                        array(
                                            "screen_id" => 7,
                                            "parent_id" => 0,
                                            "screen_type" => 5,
                                            "tag" => 1,
                                            "dirtyflag" => 0,
                                            "server_time" => date('Y-m-d H:i:s'),
                                            "screen_properties" => array(
                                                "title" => 'My Cart',
                                                "popup_flag" => 0,
                                                "background_color" => "",
                                                "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                                            ),
                                            'cart' => $cartdata['cartdata']
                                        )
                                ), FALSE);

                        $json = $this->real_json_encode($finalarr, 'successData', '', 200, $lastlogindate, $authToken);
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * my orders
     * Added By Arun Srivastava 4/8/15
     */

    public function getMyOrders($data, $val = 0) {

        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    $baseUrl = baseUrl();

                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    //$customerquery  = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='".$authToken."'";
                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $returndata = $this->getmyorderdata($customer__data['customer_id'], $app_screenData['id']);
                    if (!empty($returndata)) {
                        $finalhtml = array();
                        foreach ($returndata as $perorder) {
                            $orderproduct = $this->getOrderProducts($perorder['order_id']);

                            $perorderarr = array();
                            $perorderarr['order_id'] = $perorder['order_id'];
                            $perorderarr['order_date'] = $perorder['date_added'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($perorder['date_added'])) : '';
                            $perorderarr['products'] = array();

                            if (!empty($orderproduct)) {
                                foreach ($orderproduct as $peroprod) {
                                    $myperoprod = array();
                                    $myperoprod['price'] = $peroprod['total'];
                                    $myperoprod['quantity'] = $peroprod['quantity'];
                                    $myperoprod['name'] = $peroprod['name'];
                                    $myperoprod['product_id'] = $peroprod['product_id'];
                                    $myperoprod['status'] = $peroprod['orderstatus'] != '' ? $peroprod['orderstatus'] : $perorder['orderstatus'];

                                    if (@getimagesize($peroprod['productdefaultimage'])) {
                                        $myperoprod['image'] = $peroprod['productdefaultimage'];
                                    } else {
                                        $myperoprod['image'] = $baseUrl . $peroprod['productdefaultimage'];
                                    }

                                    $perorderarr['products'][] = $myperoprod;
                                }
                            }

                            $finalhtml[] = json_decode(json_encode($perorderarr), FALSE);
                        }

                        if ($val == 1) {
                            return $finalhtml;
                            die;
                        }
                        $finalarr = json_decode(json_encode(
                                        array(
                                            "screen_id" => 6,
                                            "parent_id" => 0,
                                            "screen_type" => 5,
                                            "tag" => 1,
                                            "dirtyflag" => 0,
                                            "server_time" => date('Y-m-d H:i:s'),
                                            "screen_properties" => array(
                                                "title" => 'My Orders',
                                                "popup_flag" => 0,
                                                "background_color" => "",
                                                "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                                            ),
                                            'orders' => $finalhtml
                                        )
                                ), FALSE);
                        if ($finalarr) {
                            $json = $this->real_json_encode(json_decode(json_encode($finalarr), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        } else {
                            $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                        }
                        echo $json;
                    } elseif ($val == 0) {
                        $json = $this->real_json_encode('', 'error', 'No orders Yet.', 405);
                        echo $json;
                    }
                } elseif ($val == 0) {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];
                        $baseUrl = baseUrl();

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        $returndata = $this->getmyorderdata($customer__data['customer_id'], $app_screenData['id']);
                        //print_r($returndata);

                        if (!empty($returndata)) {
                            $finalhtml = array();
                            foreach ($returndata as $perorder) {
                                $orderproduct = $this->getOrderProducts($perorder['order_id']);

                                $perorderarr = array();
                                $perorderarr['order_id'] = $perorder['order_id'];
                                $perorderarr['order_date'] = $perorder['date_added'] != '0000-00-00 00:00:00' ? date('d/m/Y', strtotime($perorder['date_added'])) : '';
                                $perorderarr['products'] = array();

                                if (!empty($orderproduct)) {
                                    foreach ($orderproduct as $peroprod) {
                                        $myperoprod = array();
                                        $myperoprod['price'] = $peroprod['total'];
                                        $myperoprod['quantity'] = $peroprod['quantity'];
                                        $myperoprod['name'] = $peroprod['name'];
                                        $myperoprod['product_id'] = $peroprod['product_id'];
                                        $myperoprod['status'] = $peroprod['orderstatus'] != '' ? $peroprod['orderstatus'] : $perorder['orderstatus'];
                                        if (@getimagesize($peroprod['productdefaultimage'])) {
                                            $myperoprod['image'] = $peroprod['productdefaultimage'];
                                        } else {
                                            $myperoprod['image'] = $baseUrl . $peroprod['productdefaultimage'];
                                        }

                                        $perorderarr['products'][] = $myperoprod;
                                    }
                                }

                                $finalhtml[] = json_decode(json_encode($perorderarr), FALSE);
                            }

                            $finalarr = json_decode(json_encode(
                                            array(
                                                "screen_id" => 6,
                                                "parent_id" => 0,
                                                "screen_type" => 5,
                                                "tag" => 1,
                                                "dirtyflag" => 0,
                                                "server_time" => date('Y-m-d H:i:s'),
                                                "screen_properties" => array(
                                                    "title" => 'My Orders',
                                                    "popup_flag" => 0,
                                                    "background_color" => "",
                                                    "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                                                ),
                                                'orders' => $finalhtml
                                            )
                                    ), FALSE);
                            if ($finalarr) {
                                $json = $this->real_json_encode(json_decode(json_encode($finalarr), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            } else {
                                $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                            }
                            echo $json;
                        } elseif ($val == 0) {
                            $json = $this->real_json_encode('', 'error', 'No orders Yet.', 405);
                            echo $json;
                        }
                    } elseif ($val == 0) {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } elseif ($val == 0) {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } elseif ($val == 0) {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * supporting function for cart data
     * Added by Arun Srivastava
     */

    function getCartData($customer_id, $app_id) {
        $query00 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer_id . "' AND app_id = '" . $app_id . "' AND is_active = '1'";
        $result00 = $this->queryRun($query00, 'select');

        $cart_data = array();
        if (!empty($result00)) {
            $query = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $result00['id'] . "' AND customer_id='" . $customer_id . "' AND is_active = '1' AND is_deleted = '0'";
            $result = $this->queryRun($query, 'select_all');

            if (!empty($result)) {
                foreach ($result as $singlecartdata) {
                    $cartprod = unserialize($singlecartdata['customer_cart']);

                    if (!empty($cartprod)) {
                        $product_id = $cartprod['product_id'];
                        $baseUrl = baseUrl();
                        $query = "SELECT DISTINCT p.product_id as itemid, pc.name as itemheading, p.image as imageurl, COALESCE(p.price, '') as actualprice, pc.description, COALESCE(ops.price, '') AS special_price, p.length_class_id, p.weight_class_id, ops.discount, p.quantity, opc.category_id AS cur_cat_id FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id WHERE p.product_id='" . $product_id . "'";
                        $homedata = $this->queryRun($query, 'select');

                        if (!empty($homedata)) {
                            $actualprice = $homedata['actualprice'] ? $homedata['actualprice'] : 0;
                            $specialprice = $homedata['special_price'] ? $homedata['special_price'] : 0;

                            $cquery = "SELECT image FROM oc_product_image WHERE product_id='" . $product_id . "'";
                            $imagedata = $this->queryRun($cquery, 'select_all');

                            $imagelist = '';
                            if ($homedata['imageurl'] != '') {
                                if (@getimagesize($homedata['imageurl'])) {
                                    $imagelist = $homedata['imageurl'];
                                } else {
                                    $imagelist = $baseUrl . $homedata['imageurl'];
                                }
                            } else {
                                if (!empty($imagedata)) {
                                    if (@getimagesize($homedata['imageurl'])) {
                                        $imagelist = $imagedata[0]['image'] != '' ? $imagedata[0]['image'] : "";
                                    } else {
                                        $imagelist = $imagedata[0]['image'] != '' ? $baseUrl . $imagedata[0]['image'] : "";
                                    }
                                }
                            }

                            $pquery = "SELECT * FROM oc_product_specs WHERE product_id='" . $product_id . "'";
                            $propdata = $this->queryRun($pquery, 'select');



                            // dimension
                            $dimensionstr = '';
                            if ($propdata['length'] != '') {
                                $dimensionstr .= $propdata['length'];
                            }

                            if ($propdata['width'] != '') {
                                if ($dimensionstr != '') {
                                    $dimensionstr .= ' x ' . $propdata['width'];
                                } else {
                                    $dimensionstr .= $propdata['width'];
                                }
                            }

                            if ($propdata['height'] != '') {
                                if ($dimensionstr != '') {
                                    $dimensionstr .= ' x ' . $propdata['height'];
                                } else {
                                    $dimensionstr .= $propdata['height'];
                                }
                            }

                            //max_quantity
                            $max_quantity = $homedata['quantity'] > 0 ? $homedata['quantity'] : 0;

                            $exclude_array = array(2004, 2005, 2006, 2007, 2008, 2009, 2010, 2011, 2012, 2013, 2014);


                            if (!in_array($homedata['cur_cat_id'], $exclude_array)) {
                                $is_special = 1;
                                $special_product_img = array();

                                $image_sql = "SELECT * FROM oc_product_custom_images WHERE cart_sequence_id='" . $singlecartdata['id'] . "' AND product_id='" . $product_id . "'";
                                $custom_imgs = $this->queryRun($image_sql, 'select_all');

                                if (!empty($custom_imgs)) {
                                    foreach ($custom_imgs as $temp_img) {
                                        $mytemp_img = array();
                                        $mytemp_img['customised_image_id'] = $temp_img['id'];

                                        if (@getimagesize($temp_img['customised_image_url'])) {
                                            $mytemp_img['customised_image_url'] = $temp_img['customised_image_url'];
                                        } else {
                                            $mytemp_img['customised_image_url'] = base_url() . $temp_img['customised_image_url'];
                                        }

                                        $special_product_img[] = $mytemp_img;
                                    }
                                }
                            } else {
                                $is_special = 0;
                                $special_product_img = array();
                            }

                            $data = array(
                                "cart_sequence_id" => $singlecartdata['id'],
                                "image" => $imagelist,
                                "itemid" => $homedata['itemid'],
                                "actualprice" => $actualprice,
                                "price" => $specialprice,
                                "title" => filter_var(stripslashes(html_entity_decode($homedata['itemheading'])), FILTER_SANITIZE_STRING),
                                "dimension" => $dimensionstr,
                                "qty" => $cartprod['qty'],
                                "max_quantity" => $max_quantity,
                                "size" => $cartprod['size'],
                                "color" => $cartprod['color'],
                                "is_special" => $is_special,
                                "special_product_img" => $special_product_img
                            );

                            $cart_data[] = $data;
                        }
                    }
                }

                return array('cartdata' => $cart_data);
            } else {
                return array('cartdata' => $cart_data);
            }
        } else {
            return array('cartdata' => $cart_data);
        }
    }

    function getmyorderdata($user_id, $app_id) {
        $query = "SELECT o.*, os.name as orderstatus  FROM oc_order o LEFT JOIN oc_customer c ON c.customer_id=o.customer_id LEFT JOIN oc_order_status os ON os.order_status_id=o.order_status_id WHERE c.customer_id='" . $user_id . "' AND o.app_id = '" . $app_id . "' AND o.order_status_id != 1";
        $result = $this->queryRun($query, 'select_all');
        return $result;
    }

    function getOrderProducts($orderid) {
        $query1 = "SELECT op.*, os.NAME AS orderstatus, p.image AS productdefaultimage FROM oc_order_product op
					INNER JOIN oc_order_history oh ON oh.order_id=op.order_id
	LEFT JOIN oc_order_status os ON os.order_status_id=oh.order_status_id
					LEFT JOIN oc_product p ON p.product_id=op.product_id WHERE op.order_id='" . $orderid . "' order by oh.order_history_id desc limit 1";
        $result = $this->queryRun($query1, 'select_all');
        if(empty($result))
        {
        
        $query = "SELECT op.*, os.name as orderstatus, p.image as productdefaultimage FROM oc_order_product op
					LEFT JOIN oc_order_status os ON os.order_status_id=op.order_status_id
					LEFT JOIN oc_product p ON p.product_id=op.product_id
					WHERE op.order_id='" . $orderid . "'";
        $result = $this->queryRun($query, 'select_all');
        }
        return $result;
    }

    public function updateCustomer($data, $customer_id, $app_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        // request
        $request = new Request();
        $registry->set('request', $request);

        $getcustomerdetail = $this->getCustomerByUserId($customer_id);

        if (!empty($getcustomerdetail)) {
            $db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $db->escape($data['firstname']) . "',lastname = '" . $db->escape($data['lastname']) . "', email = '" . $db->escape($data['email']) . "', telephone = '" . $db->escape($data['telephone']) . "', gender = '" . $db->escape($data['gender']) . "', ip = '" . $db->escape($request->server['REMOTE_ADDR']) . "' WHERE user_id = '" . $customer_id . "'");

            //$db->query("UPDATE " . DB_PREFIX . "address SET firstname = '" . $db->escape($data['firstname']) . "', lastname = '" . $db->escape($data['lastname']) . "', address_1 = '" . $db->escape($data['address_1']) . "', city = '" . $db->escape($data['city']) . "', postcode = '" . $db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "' WHERE customer_id = '" . (int)$customer_id . "'");

            return $customer_id;
        } else {
            $db->query("INSERT INTO " . DB_PREFIX . "customer (firstname,lastname, email, telephone, gender, ip, user_id, app_id) VALUES('" . $db->escape($data['firstname']) . "',firstname = '" . $db->escape($data['firstname']) . "', '" . $db->escape($data['email']) . "', '" . $db->escape($data['telephone']) . "', '" . $db->escape($data['gender']) . "', '" . $db->escape($request->server['REMOTE_ADDR']) . "', '" . $customer_id . "', '" . $app_id . "')");
        }
    }

    /*
     * supporting function for adding to cart
     * Added by Arun Srivastava
     */

    public function addtocustomercart($data, $customer_id) {
        $registry = new Registry();
        // Database
        $db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $registry->set('db', $db);

        $cartQuery = "SELECT * FROM " . DB_PREFIX . "customer_cart WHERE customer_id = '" . $customer_id . "' AND is_active = '1' AND is_deleted = '0' ORDER BY id DESC LIMIT 0,1";
        $cartdata = $this->queryRun($cartQuery, 'select');

        if (!empty($cartdata)) {
            if ($db->query("UPDATE " . DB_PREFIX . "customer_cart SET customer_cart = '" . $data . "' WHERE id = '" . $cartdata['id'] . "'")) {
                return 1;
            } else {
                return '';
            }
        } else {
            $query = "INSERT INTO " . DB_PREFIX . "customer_cart VALUES('', '" . $customer_id . "', '" . $data . "', 1, 0)";
            $result = $this->queryRun($query, 'insert');

            if ($result) {
                return 1;
            } else {
                return '';
            }
        }
    }

    /*
     * supporting function for updating customer address
     * Added by Arun Srivastava
     */

    public function updateCustomerAddress($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['firstname']) && $data['firstname'] != '') && (isset($data['address_1']) && $data['address_1'] != '') && (isset($data['address_2']) && $data['address_2'] != '') && (isset($data['city']) && $data['city'] != '') && (isset($data['postcode']) && $data['postcode'] != '') && (isset($data['country']) && $data['country'] != '') && (isset($data['state']) && $data['state'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];

            if ($data['email'] != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');


                $lastlogindate = date('Y-m-d H:i:s');
                $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $data['email'] . "' AND app_id='" . $app_screenData['id'] . "'";
                $customer__data = $this->queryRun($customerquery, 'select');

                $query = "SELECT ca.* FROM customer_address ca
							LEFT JOIN oc_customer c ON c.customer_id=ca.user_id
							WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                $result = $this->queryRun($query, 'select');

                $query1 = "SELECT * FROM oc_customer WHERE customer_id='" . $customer__data['customer_id'] . "' ORDER BY customer_id DESC LIMIT 0,1";
                $result1 = $this->queryRun($query1, 'select');

                if (!empty($result)) {
                    $query = "UPDATE customer_address SET firstname = '" . $data['firstname'] . "', address_1 = '" . $data['address_1'] . "', address_2 = '" . $data['address_2'] . "', city = '" . $data['city'] . "', postcode = '" . $data['postcode'] . "', country = '" . $data['country'] . "', state = '" . $data['state'] . "' WHERE user_id='" . $result1['customer_id'] . "'";
                    $result = $this->queryRun($query, 'update');

                    $json = $this->real_json_encode(json_decode(json_encode(array('address_status' => 'Address updated successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                } else {
                    $query = "INSERT INTO customer_address (firstname, address_1, address_2, city, postcode, country, state, user_id) VALUES('" . $data['firstname'] . "', '" . $data['address_1'] . "', '" . $data['address_2'] . "', '" . $data['city'] . "', '" . $data['postcode'] . "', '" . $data['country'] . "', '" . $data['state'] . "', '" . $result1['customer_id'] . "')";
                    $result = $this->queryRun($query, 'insert');

                    $json = $this->real_json_encode(json_decode(json_encode(array('address_status' => 'Address inserted successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                }
                echo $json;
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $this->lastLogin_new($authToken, $device_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "' AND app_id='" . $data['app_id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $query = "SELECT ca.* FROM customer_address ca
								LEFT JOIN oc_customer c ON c.customer_id=ca.user_id
								WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                    $result = $this->queryRun($query, 'select');

                    $query1 = "SELECT * FROM oc_customer WHERE customer_id='" . $customer__data['customer_id'] . "' ORDER BY customer_id DESC LIMIT 0,1";
                    $result1 = $this->queryRun($query1, 'select');

                    if (!empty($result)) {
                        $query = "UPDATE customer_address SET firstname = '" . $data['firstname'] . "', address_1 = '" . $data['address_1'] . "', address_2 = '" . $data['address_2'] . "', city = '" . $data['city'] . "', postcode = '" . $data['postcode'] . "', country = '" . $data['country'] . "', state = '" . $data['state'] . "' WHERE user_id='" . $result1['customer_id'] . "'";
                        $result = $this->queryRun($query, 'update');

                        $json = $this->real_json_encode(json_decode(json_encode(array('address_status' => 'Address updated successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                    } else {
                        $query = "INSERT INTO customer_address (firstname, address_1, address_2, city, postcode, country, state, user_id) VALUES('" . $data['firstname'] . "', '" . $data['address_1'] . "', '" . $data['address_2'] . "', '" . $data['city'] . "', '" . $data['postcode'] . "', '" . $data['country'] . "', '" . $data['state'] . "', '" . $result1['customer_id'] . "')";
                        $result = $this->queryRun($query, 'insert');

                        $json = $this->real_json_encode(json_decode(json_encode(array('address_status' => 'Address inserted successfully')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                    }
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * get customer address
     * Added by Arun Srivastava
     */

    public function getCustomerAddress($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['email']) && $data['email'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];

            if ($data['email'] != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $lastlogindate = date('Y-m-d H:i:s');
                $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $data['email'] . "' AND app_id='" . $app_screenData['id'] . "'";
                $customer__data = $this->queryRun($customerquery, 'select');

                $query = "SELECT ca.* FROM customer_address ca LEFT JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                $result = $this->queryRun($query, 'select');

                if (!empty($result)) {
                    $address = array();
                    $address['firstname'] = $result['firstname'];
                    $address['address_1'] = $result['address_1'];
                    $address['address_2'] = $result['address_2'];
                    $address['city'] = $result['city'];
                    $address['postcode'] = $result['postcode'];
                    $address['country'] = $result['country'];
                    $address['state'] = $result['state'];

                    $json = $this->real_json_encode(json_decode(json_encode(array('address' => $address)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                } else {
                    $json = $this->real_json_encode('', 'error', 'No address exists.', 405);
                }
                echo $json;
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $this->lastLogin_new($authToken, $device_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $query = "SELECT ca.* FROM customer_address ca LEFT JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                    $result = $this->queryRun($query, 'select');

                    if (!empty($result)) {
                        $address = array();
                        $address['firstname'] = $result['firstname'];
                        $address['address_1'] = $result['address_1'];
                        $address['address_2'] = $result['address_2'];
                        $address['city'] = $result['city'];
                        $address['postcode'] = $result['postcode'];
                        $address['country'] = $result['country'];
                        $address['state'] = $result['state'];

                        $json = $this->real_json_encode(json_decode(json_encode(array('address' => $address)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No address exists.', 405);
                    }
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * for placing order
     * Added by Arun Srivastava
     */

    public function placeOrder($data) {
        if ($data['api_version'] == '1.0') {
            if ((isset($data['auth_token']) && $data['auth_token'] != '')) {
                $authToken = $data['auth_token'];
                //$app_idString = $data['app_id'];

                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    //$appQueryData   = "select * from app_data where app_id='" . $app_idString . "'";
                    //$app_screenData = $this->query_run($appQueryData, 'select');
                    //if($app_screenData != '')
                    //{
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $this->lastLogin_new($authToken, $device_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $customerquery = "SELECT user_id, customer_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    $cartquery1 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer__data['customer_id'] . "' AND is_active='1'";
                    $mycartdata1 = $this->queryRun($cartquery1, 'select');

                    if (!empty($mycartdata1)) {
                        $cartquery = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $mycartdata1['id'] . "' AND customer_id='" . $customer__data['customer_id'] . "' AND is_active='1' AND is_deleted='0'";
                        $mycartdata = $this->queryRun($cartquery, 'select_all');

                        if (!empty($mycartdata)) {
                            // get customer address
                            $query = "SELECT ca.*, c.email, c.telephone FROM customer_address ca JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                            $result = $this->queryRun($query, 'select');


                            $payment_name = ''; 
                            $order_status = 1;
                            $payment_type = '';
                            $order_total = 0;
                            foreach ($mycartdata as $ordermaindata) {
                                $od = unserialize($ordermaindata['customer_cart']);

                                // select from product table
                                $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                $proddetail = $this->queryRun($query0, 'select');

                                // select from product special table
                                $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                $proddisc = $this->queryRun($query11111, 'select');

                                if (!empty($proddisc)) {
                                    $prodprice = $proddisc['price'];
                                } else {
                                    $prodprice = $proddetail['price'];
                                }

                                $total = $prodprice * $od['qty'];
                                $order_total = $order_total + $total;
                            }

                            // insert into order table
                            $query = "INSERT INTO oc_order (
											customer_id,
											customer_group_id,
											firstname,
											email,
											telephone,
											payment_firstname,
											payment_address_1,
											payment_address_2,
											payment_city,
											payment_postcode,
											payment_country,
											payment_zone,
											payment_method,
											payment_code,
											shipping_firstname,
											shipping_address_1,
											shipping_address_2,
											shipping_city,
											shipping_postcode,
											shipping_country,
											shipping_zone,
											shipping_method,
											shipping_code,
											total,
											order_status_id,
											currency_code,
											date_added,
											date_modified
											) 
											VALUES(
											'" . $customer__data['customer_id'] . "',
											1,
											'" . $result['firstname'] . "',
											'" . $result['email'] . "',
											'" . $result['telephone'] . "',
											'" . $result['firstname'] . "',
											'" . $result['address_1'] . "',
											'" . $result['address_2'] . "',
											'" . $result['city'] . "',
											'" . $result['postcode'] . "',
											'" . $result['country'] . "',
											'" . $result['state'] . "',
											'" . $payment_name . "',
											'" . $payment_type . "',
											'" . $result['firstname'] . "',
											'" . $result['address_1'] . "',
											'" . $result['address_2'] . "',
											'" . $result['city'] . "',
											'" . $result['postcode'] . "',
											'" . $result['country'] . "',
											'" . $result['state'] . "',
											'Free Shipping',
											'free.free',
											'" . $order_total . "',
											'" . $order_status . "',
											'INR',
											'" . date('Y-m-d h:i:s', time()) . "',
											'" . date('Y-m-d h:i:s', time()) . "'
											)";
                            $orderid = $this->queryRun($query, 'insert');

                            foreach ($mycartdata as $ordermaindata) {
                                $od = unserialize($ordermaindata['customer_cart']);

                                // seelct from product table
                                $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                $proddetail = $this->queryRun($query0, 'select');

                                // select from product special table
                                $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                $proddisc = $this->queryRun($query11111, 'select');

                                if (!empty($proddisc)) {
                                    $prodprice = $proddisc['price'];
                                } else {
                                    $prodprice = $proddetail['price'];
                                }

                                $total = $prodprice * $od['qty'];

                                // insert into order table
                                $query = "INSERT INTO oc_order_product (order_id, product_id, name, model, quantity, price, total, order_status_id)
											VALUES('" . $orderid . "', '" . $od['product_id'] . "', '" . addslashes(htmlspecialchars($proddetail['name'])) . "', '" . addslashes(htmlspecialchars($proddetail['model'])) . "', '" . $od['qty'] . "', '" . $proddetail['price'] . "', '" . $total . "', 2)";
                                $curroprod = $this->queryRun($query, 'insert');

                                // add size to order option table
                                $query1 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
											VALUES('" . $orderid . "', '" . $curroprod . "', 'Size', '" . $od['size'] . "')";
                                $orderopt = $this->queryRun($query1, 'insert');

                                // add color to order option table
                                $query2 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
											VALUES('" . $orderid . "', '" . $curroprod . "', 'Color', '" . $od['color'] . "')";
                                $orderopt1 = $this->queryRun($query2, 'insert');
                            }
                        }

                        //$query5 = "UPDATE oc_customer_cart SET is_deleted = '1', is_active='0' WHERE id='".$$mycartdata['id']."'";
                        //$proddetail = $this->queryRun($query5, 'update');

                        $json = $this->real_json_encode(json_decode(json_encode(array('order_status' => 'Your order has been placed successfully.', 'order_id' => $orderid)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                        echo $json;
                    }
                    /*
                      }
                      else
                      {
                      $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                      echo $json;
                      }
                     */
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                echo $json;
            }
        } elseif ($data['api_version'] >= '1.0.1') {
            if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '')) {
                $authToken = $data['auth_token'];
                $app_idString = $data['app_id'];
                $email = $data['email'];
                $device_id = $data['device_id'];

                if ($email != '') {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $appCatlagueQuery = "SELECT * FROM app_catalogue_attr WHERE app_id = '" . $app_screenData['id'] . "'";
                        $appCatlagueData = $this->query_run($appCatlagueQuery, 'select');

                         
   $defaultcurrquery = "SELECT * FROM oc_currency WHERE currency_id='" . $appCatlagueData['curr_id'] . "'";
                $defaultcurrency = $this->queryRun($defaultcurrquery, 'select');
              $currency_code=  $defaultcurrency['code'];
                        /*   $currency_code = 'INR';
                     if (!empty($appCatlagueData)) {
                            if ($appCatlagueData['curr_id'] == 1) {
                                $currency_code = 'GBP';
                            } elseif ($appCatlagueData['curr_id'] == 2) {
                                $currency_code = 'USD';
                            } elseif ($appCatlagueData['curr_id'] == 3) {
                                $currency_code = 'EUR';
                            } elseif ($appCatlagueData['curr_id'] == 4) {
                                $currency_code = 'INR';
                            }
                        }  */
                        //$authToken = $authResult['auth_token'];
                        //$device_id = $authResult['device_id'];
                        //$this->lastLogin_new($authToken, $device_id);
                        //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                        $lastlogindate = date('Y-m-d H:i:s');

                        $customerquery = "SELECT firstname, email, user_id, customer_id FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        $cartquery1 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer__data['customer_id'] . "' AND app_id='" . $app_screenData['id'] . "' AND is_active='1'";
                        $mycartdata1 = $this->queryRun($cartquery1, 'select');

                        if (!empty($mycartdata1)) {
                            $cartquery = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $mycartdata1['id'] . "' AND customer_id='" . $customer__data['customer_id'] . "' AND is_active='1' AND is_deleted='0'";
                            $mycartdata = $this->queryRun($cartquery, 'select_all');

                            if (!empty($mycartdata)) {
                                // get customer address
                                $query = "SELECT ca.*, c.email, c.telephone FROM customer_address ca JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                                $result = $this->queryRun($query, 'select');


                                $payment_name = '';
                                $order_status = 1;
                                $payment_type = '';
                                $order_total = 0;
                                foreach ($mycartdata as $ordermaindata) {
                                    $od = unserialize($ordermaindata['customer_cart']);

                                    // select from product table
                                    $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                    $proddetail = $this->queryRun($query0, 'select');

                                    // select from product special table
                                    $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                    $proddisc = $this->queryRun($query11111, 'select');

                                    if (!empty($proddisc)) {
                                        $prodprice = $proddisc['price'];
                                    } else {
                                        $prodprice = $proddetail['price'];
                                    }

                                    $total = $prodprice * $od['qty'];
                                    $order_total = $order_total + $total;
                                }

                                // insert into order table
                                $query = "INSERT INTO oc_order (
											customer_id,
											customer_group_id,
											firstname,
											email,
											telephone,
											payment_firstname,
											payment_address_1,
											payment_address_2,
											payment_city,
											payment_postcode,
											payment_country,
											payment_zone,
											payment_method,
											payment_code,
											shipping_firstname,
											shipping_address_1,
											shipping_address_2,
											shipping_city,
											shipping_postcode,
											shipping_country,
											shipping_zone,
											shipping_method,
											shipping_code,
											total,
											order_status_id,
											currency_code,
											date_added,
											date_modified,
											app_id
											) 
											VALUES(
											'" . $customer__data['customer_id'] . "',
											1,
											'" . $result['firstname'] . "',
											'" . $result['email'] . "',
											'" . $result['telephone'] . "',
											'" . $result['firstname'] . "',
											'" . $result['address_1'] . "',
											'" . $result['address_2'] . "',
											'" . $result['city'] . "',
											'" . $result['postcode'] . "',
											'" . $result['country'] . "',
											'" . $result['state'] . "',
											'" . $payment_name . "',
											'" . $payment_type . "',
											'" . $result['firstname'] . "',
											'" . $result['address_1'] . "',
											'" . $result['address_2'] . "',
											'" . $result['city'] . "',
											'" . $result['postcode'] . "',
											'" . $result['country'] . "',
											'" . $result['state'] . "',
											'Free Shipping',
											'free.free',
											'" . $order_total . "',
											'" . $order_status . "',
											'" . $currency_code . "',
											'" . date('Y-m-d h:i:s', time()) . "',
											'" . date('Y-m-d h:i:s', time()) . "',
											'" . $app_screenData['id'] . "'
											)";
                                $orderid = $this->queryRun($query, 'insert');

                                foreach ($mycartdata as $ordermaindata) {
                                    $od = unserialize($ordermaindata['customer_cart']);

                                    // seelct from product table
                                    $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                    $proddetail = $this->queryRun($query0, 'select');

                                    // select from product special table
                                    $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                    $proddisc = $this->queryRun($query11111, 'select');

                                    if (!empty($proddisc)) {
                                        $prodprice = $proddisc['price'];
                                    } else {
                                        $prodprice = $proddetail['price'];
                                    }

                                    $total = $prodprice * $od['qty'];

                                    // insert into order table
                                    $query = "INSERT INTO oc_order_product (order_id, product_id, name, model, quantity, price, total, order_status_id)
											VALUES('" . $orderid . "', '" . $od['product_id'] . "', '" . addslashes(htmlspecialchars($proddetail['name'])) . "', '" . addslashes(htmlspecialchars($proddetail['model'])) . "', '" . $od['qty'] . "', '" . $proddetail['price'] . "', '" . $total . "', 2)";
                                    $curroprod = $this->queryRun($query, 'insert');

                                    // add size to order option table
                                    $query1 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
											VALUES('" . $orderid . "', '" . $curroprod . "', 'Size', '" . $od['size'] . "')";
                                    $orderopt = $this->queryRun($query1, 'insert');

                                    // add color to order option table
                                    $query2 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
											VALUES('" . $orderid . "', '" . $curroprod . "', 'Color', '" . $od['color'] . "')";
                                    $orderopt1 = $this->queryRun($query2, 'insert');
                                }
                                /* 	
                                  $validemail  = $this->get_email_checkpointer(6, $app_screenData['id']);

                                  if(!empty($validemail) && $validemail['email'] != '')
                                  {
                                  $sendData              = array();
                                  $store_name            = ucfirst($app_screenData['summary']);
                                  $sendData['storename'] = $store_name;
                                  $sendData['from']      = $validemail['email'];
                                  $sendData['to']        = $customer__data['email'];
                                  $sendData['username']  = ucfirst($customer__data['firstname']);
                                  $this->sendEDM(6, $sendData);
                                  }
                                 */
                            }

                            //$query5 = "UPDATE oc_customer_cart SET is_deleted = '1', is_active='0' WHERE id='".$$mycartdata['id']."'";
                            //$proddetail = $this->queryRun($query5, 'update');

                            $json = $this->real_json_encode(json_decode(json_encode(array('order_status' => 'Your order has been placed successfully.', 'order_id' => $orderid)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            echo $json;
                        } else {
                            $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $authResult = $this->authCheck($authToken);

                    if ($authResult > 0) {
                        $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                        $app_screenData = $this->query_run($appQueryData, 'select');

                        if ($app_screenData != '') {
                            $appCatlagueQuery = "SELECT * FROM app_catalogue_attr WHERE app_id = '" . $app_screenData['id'] . "'";
                            $appCatlagueData = $this->query_run($appCatlagueQuery, 'select');

                            $currency_code = 'INR';
                            if (!empty($appCatlagueData)) {
                                if ($appCatlagueData['curr_id'] == 1) {
                                    $currency_code = 'GBP';
                                } elseif ($appCatlagueData['curr_id'] == 2) {
                                    $currency_code = 'USD';
                                } elseif ($appCatlagueData['curr_id'] == 3) {
                                    $currency_code = 'EUR';
                                } elseif ($appCatlagueData['curr_id'] == 4) {
                                    $currency_code = 'INR';
                                }
                            }
                            $authToken = $authResult['auth_token'];
                            $device_id = $authResult['device_id'];

                            $this->lastLogin_new($authToken, $device_id);

                            $lastlogindate = $this->lastLoginUpdateToken($authToken);

                            $customerquery = "SELECT user_id, customer_id FROM oc_customer WHERE auth_token='" . $authToken . "'";
                            $customer__data = $this->queryRun($customerquery, 'select');

                            $cartquery1 = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer__data['customer_id'] . "' AND app_id='" . $app_screenData['id'] . "' AND is_active='1'";
                            $mycartdata1 = $this->queryRun($cartquery1, 'select');

                            if (!empty($mycartdata1)) {
                                $cartquery = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $mycartdata1['id'] . "' AND customer_id='" . $customer__data['customer_id'] . "' AND is_active='1' AND is_deleted='0'";
                                $mycartdata = $this->queryRun($cartquery, 'select_all');

                                if (!empty($mycartdata)) {
                                    // get customer address
                                    $query = "SELECT ca.*, c.email, c.telephone FROM customer_address ca JOIN oc_customer c ON c.customer_id=ca.user_id WHERE c.customer_id='" . $customer__data['customer_id'] . "'";
                                    $result = $this->queryRun($query, 'select');


                                    $payment_name = '';
                                    $order_status = 1;
                                    $payment_type = '';
                                    $order_total = 0;
                                    foreach ($mycartdata as $ordermaindata) {
                                        $od = unserialize($ordermaindata['customer_cart']);

                                        // select from product table
                                        $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                        $proddetail = $this->queryRun($query0, 'select');

                                        // select from product special table
                                        $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                        $proddisc = $this->queryRun($query11111, 'select');

                                        if (!empty($proddisc)) {
                                            $prodprice = $proddisc['price'];
                                        } else {
                                            $prodprice = $proddetail['price'];
                                        }

                                        $total = $prodprice * $od['qty'];
                                        $order_total = $order_total + $total;
                                    }

                                    // insert into order table
                                    $query = "INSERT INTO oc_order (
												customer_id,
												customer_group_id,
												firstname,
												email,
												telephone,
												payment_firstname,
												payment_address_1,
												payment_address_2,
												payment_city,
												payment_postcode,
												payment_country,
												payment_zone,
												payment_method,
												payment_code,
												shipping_firstname,
												shipping_address_1,
												shipping_address_2,
												shipping_city,
												shipping_postcode,
												shipping_country,
												shipping_zone,
												shipping_method,
												shipping_code,
												total,
												order_status_id,
												currency_code,
												date_added,
												date_modified,
												app_id
												) 
												VALUES(
												'" . $customer__data['customer_id'] . "',
												1,
												'" . $result['firstname'] . "',
												'" . $result['email'] . "',
												'" . $result['telephone'] . "',
												'" . $result['firstname'] . "',
												'" . $result['address_1'] . "',
												'" . $result['address_2'] . "',
												'" . $result['city'] . "',
												'" . $result['postcode'] . "',
												'" . $result['country'] . "',
												'" . $result['state'] . "',
												'" . $payment_name . "',
												'" . $payment_type . "',
												'" . $result['firstname'] . "',
												'" . $result['address_1'] . "',
												'" . $result['address_2'] . "',
												'" . $result['city'] . "',
												'" . $result['postcode'] . "',
												'" . $result['country'] . "',
												'" . $result['state'] . "',
												'Free Shipping',
												'free.free',
												'" . $order_total . "',
												'" . $order_status . "',
												'" . $currency_code . "',
												'" . date('Y-m-d h:i:s', time()) . "',
												'" . date('Y-m-d h:i:s', time()) . "',
												'" . $app_screenData['id'] . "'
												)";
                                    $orderid = $this->queryRun($query, 'insert');

                                    foreach ($mycartdata as $ordermaindata) {
                                        $od = unserialize($ordermaindata['customer_cart']);

                                        // seelct from product table
                                        $query0 = "SELECT * FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                        $proddetail = $this->queryRun($query0, 'select');

                                        // select from product special table
                                        $query11111 = "SELECT * FROM oc_product_special ps WHERE ps.product_id = '" . $od['product_id'] . "'";
                                        $proddisc = $this->queryRun($query11111, 'select');

                                        if (!empty($proddisc)) {
                                            $prodprice = $proddisc['price'];
                                        } else {
                                            $prodprice = $proddetail['price'];
                                        }

                                        $total = $prodprice * $od['qty'];

                                        // insert into order table
                                        $query = "INSERT INTO oc_order_product (order_id, product_id, name, model, quantity, price, total, order_status_id)
												VALUES('" . $orderid . "', '" . $od['product_id'] . "', '" . addslashes(htmlspecialchars($proddetail['name'])) . "', '" . addslashes(htmlspecialchars($proddetail['model'])) . "', '" . $od['qty'] . "', '" . $proddetail['price'] . "', '" . $total . "', 2)";
                                        $curroprod = $this->queryRun($query, 'insert');

                                        // add size to order option table
                                        $query1 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
												VALUES('" . $orderid . "', '" . $curroprod . "', 'Size', '" . $od['size'] . "')";
                                        $orderopt = $this->queryRun($query1, 'insert');

                                        // add color to order option table
                                        $query2 = "INSERT INTO oc_order_option (order_id, order_product_id, name, value)
												VALUES('" . $orderid . "', '" . $curroprod . "', 'Color', '" . $od['color'] . "')";
                                        $orderopt1 = $this->queryRun($query2, 'insert');
                                    }
                                }

                                //$query5 = "UPDATE oc_customer_cart SET is_deleted = '1', is_active='0' WHERE id='".$$mycartdata['id']."'";
                                //$proddetail = $this->queryRun($query5, 'update');

                                $json = $this->real_json_encode(json_decode(json_encode(array('order_status' => 'Your order has been placed successfully.', 'order_id' => $orderid)), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                                echo $json;
                            } else {
                                $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                                echo $json;
                            }
                        } else {
                            $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                        echo $json;
                    }
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * delete cart
     * Added By Arun Srivastava 20/8/15
     */

    public function deleteCart($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['cart_sequence_id']) && $data['cart_sequence_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '')) {
            $authToken = $data['auth_token'];
            $cart_sequence_id = $data['cart_sequence_id'];
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if (!empty($app_screenData)) {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email . "' AND app_id = '" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    if (!empty($customer__data)) {
                        $user_id = $customer__data['customer_id'];
                        $query = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $user_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active = '1'";
                        $result = $this->queryRun($query, 'select');

                        if (!empty($result)) {
                            $query1 = "UPDATE oc_customer_cart_data SET is_active = '0', is_deleted = '1' WHERE id = '" . $cart_sequence_id . "' AND cart_main_id='" . $result['id'] . "' AND customer_id='" . $user_id . "'";
                            $result1 = $this->queryRun($query1, 'update');

                            $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Product deleted from cart.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        } else {
                            $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                        }
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No user available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "' AND app_id = '" . $app_screenData['id'] . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');


                        if (!empty($customer__data)) {
                            $user_id = $customer__data['customer_id'];
                            $query = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $user_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active = '1'";
                            $result = $this->queryRun($query, 'select');

                            if (!empty($result)) {
                                $query1 = "UPDATE oc_customer_cart_data SET is_active = '0', is_deleted = '1' WHERE id = '" . $cart_sequence_id . "' AND cart_main_id='" . $result['id'] . "' AND customer_id='" . $user_id . "'";
                                $result1 = $this->queryRun($query1, 'update');

                                $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Product deleted from cart.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            } else {
                                $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                            }
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * update cart quantity
     * Added By Arun Srivastava 28/9/15
     */

    public function updateCartQuantity($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['cart_sequence_id']) && $data['cart_sequence_id'] != '') && (isset($data['quantity']) && $data['quantity'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '')) {
            $authToken = $data['auth_token'];
            $cart_sequence_id = $data['cart_sequence_id'];
            $quantity = $data['quantity'];
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE email='" . $email . "' AND app_id = '" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');


                    if (!empty($customer__data)) {
                        $user_id = $customer__data['customer_id'];
                        $query = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $user_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active = '1'";
                        $result = $this->queryRun($query, 'select');

                        if (!empty($result)) {
                            $query11 = "SELECT * FROM oc_customer_cart_data WHERE id = '" . $cart_sequence_id . "' AND cart_main_id='" . $result['id'] . "'";
                            $result11 = $this->queryRun($query11, 'select');

                            if (!empty($result11)) {
                                $cartdata = unserialize($result11['customer_cart']);

                                $cartdata['qty'] = $quantity;
                                $cartseq = serialize($cartdata);

                                $query1 = "UPDATE oc_customer_cart_data SET customer_cart = '" . $cartseq . "' WHERE id = '" . $cart_sequence_id . "'";
                                $result1 = $this->queryRun($query1, 'update');

                                $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                            } else {
                                $json = $this->real_json_encode('', 'error', 'No product available', 405);
                            }
                        } else {
                            $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                        }
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT customer_id, user_id FROM oc_customer WHERE auth_token='" . $authToken . "' AND app_id = '" . $app_screenData['id'] . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');


                        if (!empty($customer__data)) {
                            $user_id = $customer__data['customer_id'];
                            $query = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $user_id . "' AND app_id = '" . $app_screenData['id'] . "' AND is_active = '1'";
                            $result = $this->queryRun($query, 'select');

                            if (!empty($result)) {
                                $query11 = "SELECT * FROM oc_customer_cart_data WHERE id = '" . $cart_sequence_id . "' AND cart_main_id='" . $result['id'] . "'";
                                $result11 = $this->queryRun($query11, 'select');

                                if (!empty($result11)) {
                                    $cartdata = unserialize($result11['customer_cart']);

                                    $cartdata['qty'] = $quantity;
                                    $cartseq = serialize($cartdata);

                                    $query1 = "UPDATE oc_customer_cart_data SET customer_cart = '" . $cartseq . "' WHERE id = '" . $cart_sequence_id . "'";
                                    $result1 = $this->queryRun($query1, 'update');

                                    $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Cart updated successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                                } else {
                                    $json = $this->real_json_encode('', 'error', 'No product available', 405);
                                }
                            } else {
                                $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                            }
                            echo $json;
                        }
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function orderConfirm($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['order_id']) && $data['order_id'] != '') && (isset($data['payment_type']) && $data['payment_type'] != '') && (isset($data['payment_gateway']) && $data['payment_gateway'] != '') && (isset($data['transaction_id']) && $data['transaction_id'] != '') && (isset($data['transaction_status']) && $data['transaction_status'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['email']) && $data['email'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $email = $data['email'];
            $device_id = $data['device_id'];

            if ($email != '') {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    //$authToken = $authResult['auth_token'];
                    //$device_id = $authResult['device_id'];
                    //$this->lastLogin_new($authToken, $device_id);
                    //$lastlogindate  = $this->lastLoginUpdateToken($authToken);
                    $lastlogindate = date('Y-m-d H:i:s');

                    $customerquery = "SELECT user_id, customer_id, firstname, email FROM oc_customer WHERE email='" . $email . "' AND app_id='" . $app_screenData['id'] . "'";
                    $customer__data = $this->queryRun($customerquery, 'select');

                    if ($data['payment_type'] == 0) {
                        $payment_name = 'Cash On Delivery';
                        $order_status = 2;
                        $payment_type = 'cod';
                    } else {
                        $payment_name = $data['payment_gateway'];
                        $order_status = 2;
                        $payment_type = 'prepaid';
                    }

                    $query4 = "UPDATE oc_order SET payment_method = '" . $payment_name . "', payment_code = '" . $payment_type . "', order_status_id = '" . $order_status . "' WHERE order_id='" . $data['order_id'] . "'";
                    $orderupdate = $this->queryRun($query4, 'update');

                    if ($data['payment_type'] != 0) {
                        $query2 = "SELECT * FROM oc_order_transaction WHERE order_transaction_id = '" . $data['transaction_id'] . "' AND order_id = '" . $data['order_id'] . "'";
                        $transactionext = $this->queryRun($query2, 'select');

                        if (empty($transactionext)) {
                            $query3 = "INSERT INTO oc_order_transaction VALUES('','" . $data['transaction_id'] . "', '" . $data['order_id'] . "', '" . $data['transaction_status'] . "')";
                            $transaction = $this->queryRun($query3, 'insert');
                        }
                    }

                    $cartquery = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer__data['customer_id'] . "' AND app_id='" . $app_screenData['id'] . "' AND is_active='1'";
                    $mycartdata = $this->queryRun($cartquery, 'select');

                    $query5 = "UPDATE oc_customer_cart_main SET is_active='0' WHERE id='" . $mycartdata['id'] . "'";
                    $proddetail = $this->queryRun($query5, 'update');


                    $mycartdata1 = $mycartdata;
                    if (!empty($mycartdata1)) {
                        $cartquery = "SELECT * FROM oc_customer_cart_data WHERE cart_main_id = '" . $mycartdata1['id'] . "' AND customer_id='" . $customer__data['customer_id'] . "' AND is_active='1' AND is_deleted='0'";
                        $mycartdata = $this->queryRun($cartquery, 'select_all');
                        if (!empty($mycartdata)) {
                            $orderdata = "Order Details<br><table border='1'><tr><th>Product Id</th><th>Product Name</th></tr>";
                            foreach ($mycartdata as $ordermaindata) {
                                $od = unserialize($ordermaindata['customer_cart']);

                                // select from product table
                                $query0 = "SELECT *,pd.name as proname FROM oc_product p LEFT JOIN oc_product_description pd ON p.product_id=pd.product_id WHERE p.product_id = '" . $od['product_id'] . "'";
                                $proddetail = $this->queryRun($query0, 'select');

                                if (isset($proddetail['subtract']) && $proddetail['subtract'] == 1) {
                                    // update quantity in product table
                                    $quantityLeft = $proddetail['quantity'] - $od['qty'];
                                    $queryUp = "UPDATE oc_product SET quantity = '" . $quantityLeft . "' WHERE product_id = '" . $od['product_id'] . "'";
                                    $produpdate = $this->queryRun($queryUp, 'update');
                                }
                                $detailslist = "<tr><td>" . $od['product_id'] . "</td><td>" . $proddetail['proname'] . "</td></tr>";
                                $orderdata = $orderdata . $detailslist;
                            }
                            $orderdata = $orderdata . "</table><br>";
                        }
                    }
                    $query6 = "UPDATE oc_customer_cart_data SET is_deleted = '1', is_active='0' WHERE cart_main_id='" . $mycartdata1['id'] . "'";
                    $proddetail6 = $this->queryRun($query6, 'update');


                    $storename = ucfirst($app_screenData['summary']);
                    $validemail = $this->get_email_checkpointer(6, $app_screenData['app_id']);

                    if (!empty($validemail) && $validemail['email'] != '') {
                        $sendData = array();
                        $sendData['storename'] = $storename;
                        $sendData['from'] = $validemail['email'];
                        $sendData['to'] = $customer__data['email'];
                        $sendData['username'] = ucfirst($customer__data['firstname']);
                        $sendData['date_time'] = 2;
                        $sendData['order_id'] = $data['order_id'];

                        if (!empty($mycartdata)) {
                            $sendData['orderdata'] = $orderdata;
                        }

                        $timeslotdeliveryqry = "SELECT * FROM oc_order_delivery WHERE order_id = '" . $data['order_id'] . "'";
                        $timeSlotDelivery = $this->queryRun($timeslotdeliveryqry, 'select');

                        $sendData['time_slot'] = 0;
                        $sendData['delivery_date'] = 0;
                        if (!empty($timeSlotDelivery)) {
                            if ($sendData['date_time'] == 1) {
                                $sendData['delivery_date'] = $timeSlotDelivery['delivery_date'];
                            }
                            $sendData['time_slot'] = $timeSlotDelivery['time_slot'];
                        }
                        $this->sendEDM(6, $sendData);
                    }

                    $json = $this->real_json_encode(json_decode(json_encode(array('order_status' => 'Your order has been placed successfully.', 'order_id' => $data['order_id'])), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $authResult = $this->authCheck($authToken);

                if ($authResult > 0) {
                    $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                    $app_screenData = $this->query_run($appQueryData, 'select');

                    if ($app_screenData != '') {
                        $authToken = $authResult['auth_token'];
                        $device_id = $authResult['device_id'];

                        $this->lastLogin_new($authToken, $device_id);

                        $lastlogindate = $this->lastLoginUpdateToken($authToken);

                        $customerquery = "SELECT user_id, customer_id FROM oc_customer WHERE auth_token='" . $authToken . "' AND app_id='" . $app_screenData['id'] . "'";
                        $customer__data = $this->queryRun($customerquery, 'select');

                        if ($data['payment_type'] == 0) {
                            $payment_name = 'Cash On Delivery';
                            $order_status = 2;
                            $payment_type = 'cod';
                        } else {
                            $payment_name = $data['payment_gateway'];
                            $order_status = 2;
                            $payment_type = 'prepaid';
                        }

                        $query4 = "UPDATE oc_order SET payment_method = '" . $payment_name . "', payment_code = '" . $payment_type . "', order_status_id = '" . $order_status . "' WHERE order_id='" . $data['order_id'] . "'";
                        $orderupdate = $this->queryRun($query4, 'update');

                        if ($data['payment_type'] != 0) {
                            $query2 = "SELECT * FROM oc_order_transaction WHERE order_transaction_id = '" . $data['transaction_id'] . "' AND order_id = '" . $data['order_id'] . "'";
                            $transactionext = $this->queryRun($query2, 'select');

                            if (empty($transactionext)) {
                                $query3 = "INSERT INTO oc_order_transaction VALUES('','" . $data['transaction_id'] . "', '" . $data['order_id'] . "', '" . $data['transaction_status'] . "')";
                                $transaction = $this->queryRun($query3, 'insert');
                            }
                        }

                        $cartquery = "SELECT * FROM oc_customer_cart_main WHERE customer_id='" . $customer__data['customer_id'] . "' AND app_id='" . $app_screenData['id'] . "' AND is_active='1'";
                        $mycartdata = $this->queryRun($cartquery, 'select');

                        $query5 = "UPDATE oc_customer_cart_main SET is_active='0' WHERE id='" . $mycartdata['id'] . "'";
                        $proddetail = $this->queryRun($query5, 'update');

                        $query6 = "UPDATE oc_customer_cart_data SET is_deleted = '1', is_active='0' WHERE cart_main_id='" . $mycartdata['id'] . "'";
                        $proddetail6 = $this->queryRun($query6, 'update');

                        $json = $this->real_json_encode(json_decode(json_encode(array('order_status' => 'Your order has been placed successfully.')), FALSE), 'successData', '', 200, $lastlogindate, $authToken);
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                    echo $json;
                }
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    function add_suggest_catagory($data) {
        if ($data['type_select'] == 'Child') {
            $query = "INSERT INTO oc_suggest_category (cust_id, parent,child) VALUES('" . $data['custid'] . "', '" . $data['parent'] . "','" . $data['sugest_cat'] . "')";
        } else {
            $query = "INSERT INTO oc_suggest_category (cust_id, parent,child) VALUES('" . $data['custid'] . "', '" . $data['sugest_cat'] . "','')";
        }
        $id = $this->queryRun($query, 'insert');
        echo $id;
    }

    /*
     * function for app wizard api
     * Added by Arun Srivastava on 2/9/15
     */

    public function wizardInit($data) {
        if ((isset($data['author_id']) && $data['author_id'] != '') && (isset($data['app_name']) && $data['app_name'] != '') && (isset($data['email_id']) && $data['email_id'] != '')) {
            $query1 = "SELECT ad.summary as appName, ad.app_id as appId, ad.type_app as appType FROM app_data ad LEFT JOIN author a ON a.id=ad.author_id WHERE a.custid='" . $data['author_id'] . "' AND LOWER(ad.summary)='" . $data['app_name'] . "' AND a.email_address='" . $data['email_id'] . "' AND ad.deleted = 0 LIMIT 0,1";
            $result1 = $this->query_run($query1, 'select');

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

    /*
     * get searched product listing
     * added by Arun Srivastava on 14/9/15
     */

    public function searchProductsList($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['keyword']) && $data['keyword'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $keyword = $data['keyword'];
            $offset = $data['offset'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $baseUrl = baseUrl();
                $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, COALESCE(p.price, '') AS actualprice, COALESCE(ops.price, '') AS special_price, ops.discount FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_app_id ai ON ai.product_id=p.product_id WHERE (pc.name LIKE '%" . $keyword . "%' OR pc.description LIKE '%" . $keyword . "%' OR concat(', ', pc.tag, ',') like concat(', %" . $keyword . "%,')) AND ai.app_id='" . $app_id . "' AND p.status = 1 LIMIT $offset, 15";
                $launchdata = $this->queryRun($cquery, 'select_all');

                $maths = count($launchdata) % 15;
                if ($maths == 0) {
                    $pageStatus = 'cof';
                } else {
                    $pageStatus = 'eof';
                }


                $productlist = array();
                if (!empty($launchdata)) {
                    foreach ($launchdata as $tempdata) {
                        $actualprice = $tempdata['actualprice'] ? $tempdata['actualprice'] : 0;
                        $specialprice = $tempdata['special_price'] ? $tempdata['special_price'] : 0;
                        $discountprice = $tempdata['discount'] ? $tempdata['discount'] . '%' : 0;

                        if (@getimagesize($tempdata['imageurl'])) {
                            $tempdata_imageurl = $tempdata['imageurl'];
                        } else {
                            $tempdata_imageurl = $baseUrl . $tempdata['imageurl'];
                        }

                        $tempCdata = array(
                            "itemheading" => $tempdata['itemheading'],
                            "imageurl" => $tempdata['imageurl'] != '' ? $tempdata_imageurl : "",
                            "itemid" => $tempdata['itemid'],
                            "actualprice" => $actualprice,
                            "price" => $specialprice,
                            "discount" => $discountprice,
                            "addedToCart" => 0,
                            "addedToWishlist" => 0
                        );

                        $productlist[] = $tempCdata;
                    }
                }

                $data = array(
                    "screen_id" => 3,
                    "parent_id" => 2,
                    "screen_type" => 3,
                    "tag" => 1,
                    "dirtyflag" => 0,
                    "server_time" => date('Y-m-d H:i:s'),
                    "pagination" => $pageStatus,
                    "screen_properties" => array(
                        "title" => 'Searched Product',
                        "popup_flag" => 0,
                        "background_color" => "",
                        "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                    ),
                    "comp_array" => array(
                        array(
                            "type" => "products",
                            "comp_id" => 31,
                            "elements" => array(
                                "products_array" => $productlist
                            )
                        )
                    )
                );

                $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * save push token
     * added by Arun Srivastava on 14/9/15
     */

    public function savePushToken($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['push_token']) && $data['push_token'] != '') && (isset($data['device_id']) && $data['device_id'] != '') && (isset($data['platform']) && $data['platform'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $push_token = $data['push_token'];
            $device_id = $data['device_id'];
            $platform = $data['platform'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                //$device_id = $authResult['device_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $app_query = "select u.id from users u left join user_appdata uad on u.id=uad.user_id where uad.auth_token='" . $authToken . "' and u.device_id='" . $device_id . "' and uad.app_id='" . $app_id . "'";
                $app_screenData = $this->query_run($app_query, 'select');

                if (!empty($app_screenData)) {
                    $sqlUserUpdate = "UPDATE user_appdata ua JOIN users u ON ua.user_id=u.id SET ua.push_token='" . $push_token . "', ua.platform='" . $platform . "' WHERE ua.app_id='" . $app_id . "' AND u.device_id='" . $device_id . "'";
                    $proddetail = $this->query_run($sqlUserUpdate, 'update');

                    $json = $this->real_json_encode(json_decode(json_encode(array('status' => 'Push token updated successfully.'))), 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'No such user exist with these credentials', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * get image for category
     * added by Arun Srivastava on 17/9/15
     */

    function getCategoryImgFromProd($subcatsArr, $category_id, $app_id) {
        $explode = explode(',', $subcatsArr);
        if (in_array($category_id, $explode)) {
            $arrsearch = array_search($category_id, $explode);

            if ($arrsearch > 1) {
                $category_id = $explode[$arrsearch - 1];
                $launchdata1 = $this->getCategoryImgFromProd($subcatsArr, $category_id, $app_id);
            } elseif ($arrsearch == 1) {
                $category_id = $explode[$arrsearch - 1];
                $launchdata1 = $this->getCategoryImgFromProd($subcatsArr, $category_id, $app_id);
            } else {
                $cquery1 = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price FROM oc_product p LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id LEFT JOIN oc_app_id ai ON ai.product_id=p.product_id WHERE opc.category_id='" . $category_id . "' AND ai.app_id='" . $app_id . "' AND p.status=1 LIMIT 0, 1";
                $launchdata1 = $this->queryRun($cquery1, 'select');
            }
        }

        return $launchdata1;
    }

    /*
     * send contact details to app author
     * added by Arun Srivastava on 17/9/15
     */

    public function contactUs($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['device_id']) && $data['device_id'] != '') && (isset($data['full_name']) && $data['full_name'] != '') && (isset($data['email']) && $data['email'] != '') && (isset($data['message']) && $data['message'] != '') && (isset($data['phone']))) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $device_id = $data['device_id'];
            $full_name = $data['full_name'];
            $email = $data['email'];
            $message = $data['message'];
            $phone = isset($data['phone']) ? $data['phone'] : '';

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                //$device_id = $authResult['device_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];
                $app_name = $app_screenData['summary'];

                $this->lastLogin_new($authToken, $device_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';

                $authorQuery00 = "select * from app_catalogue_attr where app_id='" . $app_id . "'";
                $appctlgattr = $this->query_run($authorQuery00, 'select');

                $getauthor = 0;
                if (!empty($appctlgattr)) {
                    if ($appctlgattr['is_contactus'] == 0) {
                        $authorQuery = "select * from author where id='" . $app_screenData['author_id'] . "'";
                        $authorData = $this->query_run($authorQuery, 'select');
                        $feedback_email = $authorData['email_address'];
                    } else {
                        $feedback_email = $appctlgattr['contactus_email'];
                    }
                } else {
                    /* If app catalouge attribute is not define , then feedback email sent to author */
                    $authorQuery = "select * from author where id='" . $app_screenData['author_id'] . "'";
                    $authorData = $this->query_run($authorQuery, 'select');
                    $feedback_email = $authorData['email_address'];
                }

                $csubject = $full_name . " contacted you for " . $app_name;
                $chtmlcontent = "Appname : " . $app_name . "<br /> Name: " . $full_name . "<br /> Email: " . $email . "<br />Message: " . $message . "<br />Phone: " . $phone;

                $cto = $feedback_email;

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

                $data = array(
                    'status' => "Thankyou for contacting us.We'll get back to you shortly!"
                );

                $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * send feedback details to app author
     * added by Arun Srivastava on 23/9/15
     */

    public function feedback($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['device_id']) && $data['device_id'] != '') && (isset($data['full_name']) && $data['full_name'] != '') && (isset($data['full_name']) && $data['title'] != '') && (isset($data['title']) && $data['message'] != '')) {
            $authToken = $data['auth_token'];
            $app_idString = $data['app_id'];
            $device_id = $data['device_id'];
            $full_name = htmlentities($data['full_name']);
            $title = htmlentities($data['title']);
            $message = htmlentities($data['message']);

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                //$device_id = $authResult['device_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];
                $app_name = $app_screenData['summary'];

                $this->lastLogin_new($authToken, $device_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $cformemail = 'noreply@instappy.com';
                $key = 'f894535ddf80bb745fc15e47e42a595e';
                if ($app_screenData != '') {
                    $app_id = $app_screenData['id'];
                    $app_name = $app_screenData['summary'];
                    $app_type = $app_screenData['type_app'];
                    if ($app_type == '1') {
                        $appQueryFeedback = "select * from screen_title_id where app_id='" . $app_id . "' and screen_type=16 and deleted=0";
                        $app_screenFeed = $this->query_run($appQueryFeedback, 'select');


                        $feedback_email = '';
                        if (!empty($app_screenFeed)) {

                            $feedback_email = $app_screenFeed['others'];
                        }

                        $contentAppId = $app_id;
                        $contentAppIdString = $app_idString;

                        if ($feedback_email == '') {
                            $authorQuery = "select * from author where id='" . $app_screenData['author_id'] . "'";
                            $authorData = $this->query_run($authorQuery, 'select');
                            $feedback_email = $authorData['email_address'];
                        }
                    } else {
                        $retailAppId = $app_id;
                        $retailAppType = $app_type;
                        $retailAppIdString = $app_idString;
                        $appReatldata = "select *  from app_catalogue_attr where app_id='" . $app_id . "'";
                        $appctlgattr = $this->query_run($appReatldata, 'select');
                        if ($appctlgattr['is_feedback'] == 0) {
                            $authorQuery = "select * from author where id='" . $app_screenData['author_id'] . "'";
                            $authorData = $this->query_run($authorQuery, 'select');
                            $feedback_email = $authorData['email_address'];
                        } else {
                            $feedback_email = $appctlgattr['feedback_email'];
                        }
                    }
                } else {
                    /* If app catalouge attribute is not define , then feedback email sent to author */
                    $json = $this->real_json_encode('', 'error', 'No such App found', 410);
                    echo $json;
                    die;
                }


                $csubject = $full_name . " sent you a feedback for " . $app_name;
                $chtmlcontent = "Title : " . $title . "<br /> Appname : " . $app_name . "<br /> Name: " . $full_name . "<br />Message: " . $message;

                $cto = $feedback_email;

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

                $data = array(
                    'status' => 'Your feedback has been sent successfully.'
                );

                $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    function getcurrency() {
        $currencyquery = "SELECT * FROM oc_currency WHERE status = 1";
        $currencydata = $this->queryRun($currencyquery, 'select_all');
        echo json_encode($currencydata);
    }

    /*
     * results retail component data to api
     * Added By Arun Srivastava on 21/3/16
     */

    function getComponents() {
        $query = "SELECT id, name FROM component_type WHERE app_type_id=3";
        $data = $this->query_run($query, 'select_all');

        $json = $this->real_json_encode($data, 'successData', '', 200, '', '');
        echo $json;
    }

    function bulkImageUpload($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['cart_sequence_id']) && $data['cart_sequence_id'] != '') && (isset($data['product_id']) && $data['product_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['image_urls']) && !empty($data['image_urls']))) {
            $authToken = $data['auth_token'];
            $cart_sequence_id = $data['cart_sequence_id'];
            $product_id = $data['product_id'];
            $app_idString = $data['app_id'];
            $image_urls = $data['image_urls'];
            $excludedIds = $data['excludedIds'];

            if ($excludedIds != '') {
                $excludedIds = rtrim($excludedIds, ',');
            }

            $authResult = $this->authCheck($authToken);

            $imageIds = array();

            if (!empty($authResult)) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];
                //$app_id    = $authResult['app_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);

                $baseUrl = baseUrl();

                $getimageQuery = "SELECT * FROM oc_product_custom_images WHERE product_id = '" . $product_id . "' AND cart_sequence_id = '" . $cart_sequence_id . "' AND app_id = '" . $app_id . "' AND order_id = 0";
                $image_data = $this->queryRun($getimageQuery, 'select_all');

                if (!empty($image_data)) {
                    if ($excludedIds != '') {
                        $deleteImageQuery = "DELETE FROM oc_product_custom_images WHERE product_id = '" . $product_id . "' AND cart_sequence_id = '" . $cart_sequence_id . "' AND app_id = '" . $app_id . "' AND order_id =0 AND id NOT IN(" . $excludedIds . ")";
                        $this->queryRun($deleteImageQuery, 'delete');
                    } else {
                        $deleteImageQuery = "DELETE FROM oc_product_custom_images WHERE product_id = '" . $product_id . "' AND cart_sequence_id = '" . $cart_sequence_id . "' AND app_id = '" . $app_id . "' AND order_id =0";
                        $this->queryRun($deleteImageQuery, 'delete');
                    }
                }

                if (!empty($image_urls)) {
                    foreach ($image_urls as $url) {
                        $imageQuery = "INSERT INTO oc_product_custom_images VALUES('', '" . $product_id . "', '" . $cart_sequence_id . "', '" . $app_id . "', '', '" . $url . "')";
                        $image_id = $this->queryRun($imageQuery, 'insert');

                        $image_url = baseUrl() . $url;
                        $imageIds[] = array('image_url' => $image_url, 'image_id' => $image_id);
                    }
                }

                $json = $this->real_json_encode($imageIds, 'successData', '', 200, $lastlogindate, $authToken);
                echo $json;
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    public function viewProductsTagList($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['tag_id']) && $data['tag_id'] != '') && (isset($data['app_id']) && $data['app_id'] != '')) {
            $authToken = $data['auth_token'];
            $tag_id = $data['tag_id'];
            $app_idString = $data['app_id'];


            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $authToken = $authResult['auth_token'];
                $device_id = $authResult['device_id'];
                //$app_id    = $authResult['app_id'];

                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                $app_id = $app_screenData['id'];

                $this->lastLogin_new($authToken, $device_id, $app_id);

                $lastlogindate = $this->lastLoginUpdateToken($authToken);



                $baseUrl = baseUrl();
//				$query    = "SELECT c.category_id AS itemid, c.image AS imageurl, cd.NAME AS itemheading, cd.description AS itemdesc FROM oc_category c LEFT JOIN oc_category_description cd ON c.category_id=cd.category_id WHERE c.category_id='".$category_id."'";
//				$homedata = $this->queryRun($query, 'select');

                if (!empty($tag_id)) {
                    $querytag = "SELECT * FROM oc_retail_app_tag WHERE id='" . $tag_id . "'";
                    $apptag = $this->queryRun($querytag, 'select');


                    if (!empty($apptag)) {
                        $producttag = $apptag['tag_name'];
                    }

                    if ($tag_id == 6) {

                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
                                                                    FROM oc_app_id ai 
                                                                    JOIN oc_product p ON ai.product_id=p.product_id
                                                                    LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
                                                                    LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
                                                                    LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
                                                                    LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
                                                                    WHERE ai.app_id='" . $app_id . "' AND ops.price != '' AND p.status = 1 ORDER BY p.date_modified";
                        $launchdata = $this->queryRun($cquery, 'select_all');
                    } else {

                        $cquery = "SELECT DISTINCT p.product_id AS itemid, pc.NAME AS itemheading, p.image AS imageurl, p.price AS actualprice,ops.price AS special_price
                                                                FROM oc_app_id ai 
                                                                JOIN oc_product p ON ai.product_id=p.product_id
                                                                LEFT JOIN oc_product_description pc ON p.product_id=pc.product_id
                                                                LEFT JOIN oc_product_to_category opc ON p.product_id=opc.product_id
                                                                LEFT JOIN oc_product_special ops ON p.product_id=ops.product_id
                                                                LEFT JOIN oc_vendor v ON opc.product_id=v.vproduct_id
                                                                WHERE ai.app_id='" . $app_id . "' AND p.status = 1 ORDER BY p.date_modified ";
                        $launchdata = $this->queryRun($cquery, 'select_all');
                    }

                    $maths = count($launchdata) % 15;
                    if ($maths == 0) {
                        $pageStatus = 'cof';
                    } else {
                        $pageStatus = 'eof';
                    }


                    $productlist = array();
                    if (!empty($launchdata)) {
                        foreach ($launchdata as $tempdata) {
                            $actualprice = $tempdata['actualprice'] ? $tempdata['actualprice'] : 0;
                            $specialprice = $tempdata['special_price'] ? $tempdata['special_price'] : 0;
                            //$discount     = $tempdata['discount'];

                            if (@getimagesize($tempdata['imageurl'])) {
                                $tempdata_imageurl = $tempdata['imageurl'];
                            } else {
                                $tempdata_imageurl = $baseUrl . $tempdata['imageurl'];
                            }

                            $tempCdata = array(
                                "itemheading" => $tempdata['itemheading'],
                                "imageurl" => $tempdata['imageurl'] != '' ? $tempdata_imageurl : "",
                                "itemid" => $tempdata['itemid'],
                                "actualprice" => $actualprice,
                                "price" => $specialprice,
                                "addedToCart" => 0,
                                "addedToWishlist" => 0,
                                    //"discount"    => $discount > 0 ? $discount.'%' : ''
                            );

                            $productlist[] = $tempCdata;
                        }
                    }

                    $data = array(
                        "screen_id" => 3,
                        "parent_id" => 2,
                        "screen_type" => 3,
                        "tag" => 1,
                        "dirtyflag" => 0,
                        "server_time" => date('Y-m-d H:i:s'),
                        "pagination" => $pageStatus,
                        "screen_properties" => array(
                            "title" => $producttag,
                            "popup_flag" => 0,
                            "background_color" => "",
                            "background_image_url" => "http://www.instappy.com/catalogue/ecommerce_catalog_api/images/retail_bg.png"
                        ),
                        "comp_array" => array(
                            array(
                                "type" => "products",
                                "comp_id" => 31,
                                "elements" => array(
                                    "products_array" => $productlist
                                )
                            )
                        )
                    );

                    $json = $this->real_json_encode($data, 'successData', '', 200, $lastlogindate, $authToken);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * coupon code
     * Added By Arun Srivastava 29/6/16
     */

    public function checkCouponCode($data) {
        if ((isset($data['auth_token']) && $data['auth_token'] != '') && (isset($data['app_id']) && $data['app_id'] != '') && (isset($data['coupon']) && $data['coupon'] != '')) {
            $authToken = $data['auth_token'];
            $coupon = $data['coupon'];
            $app_idString = $data['app_id'];

            $authResult = $this->authCheck($authToken);

            if ($authResult > 0) {
                $appQueryData = "select * from app_data where app_id='" . $app_idString . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');

                if ($app_screenData != '') {
                    $authToken = $authResult['auth_token'];
                    $device_id = $authResult['device_id'];

                    $this->lastLogin_new($authToken, $device_id);

                    $lastlogindate = $this->lastLoginUpdateToken($authToken);

                    $couponQuery = "select * from oc_coupon where code='" . $coupon . "'";
                    $couponData = $this->queryRun($couponQuery, 'select');

                    if (!empty($couponData)) {
                        if ($couponData['type'] == 'P') {
                            $is_percent = 1;
                        } else {
                            $is_percent = 0;
                        }
                        $finalarr = json_decode(json_encode(
                                        array(
                                            'coupon_data' => array(
                                                "coupon_code" => $couponData['code'],
                                                "is_percent" => $is_percent,
                                                "amount" => $couponData['discount']
                                            )
                                        )
                                ), FALSE);

                        $json = $this->real_json_encode($finalarr, 'successData', '', 200, $lastlogindate, $authToken);
                        echo $json;
                    } else {
                        $json = $this->real_json_encode('', 'error', 'Invalid Coupon Code', 405);
                        echo $json;
                    }
                } else {
                    $json = $this->real_json_encode('', 'error', 'No app id available', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

    /*
     * Logout customer
     * Added By Arun Srivastava
     */

    public function logoutCustomer() {
        // request
        $request = new Request();

        if ((isset($request->post['app_id']) && $request->post['app_id'] != '') && (isset($request->post['email']) && $request->post['email'] != '') && (isset($request->post['auth_token']) && $request->post['auth_token'] != '') && (isset($request->post['device_id']) && $request->post['device_id'] != '')) {
            $authToken = $request->post['auth_token'];
            $app_id_str = isset($request->post['app_id']) != '' ? $request->post['app_id'] : '';
            $device_id = isset($request->post['device_id']) != '' ? $request->post['device_id'] : '';
            $authResult = $this->authCheck($authToken, $device_id);

            $email = $request->post['email'];

            if ($authResult > 0) {
                $appQueryData = "select * from app_data where app_id='" . $app_id_str . "'";
                $app_screenData = $this->query_run($appQueryData, 'select');
                $app_id = $app_screenData['id'];

                $returndata = $this->logoutUser($device_id, $app_id);

                if ($returndata == 1) {
                    $json = $this->real_json_encode('', 'success', 'Customer Logout Successfully!', 200);
                    echo $json;
                } else {
                    $json = $this->real_json_encode('', 'error', 'Something went wrong', 405);
                    echo $json;
                }
            } else {
                $json = $this->real_json_encode('', 'error', 'Auth token mismatch', 405);
                echo $json;
            }
        } else {
            $json = $this->real_json_encode('', 'error', 'Parameter empty', 405);
            echo $json;
        }
    }

}
