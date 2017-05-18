<?php

require_once('common_functions_content.php');

class Retail {

    public function getCatalogLaunchData($data) {
        $fw = new Fwcore();
        $fw->getCatalogLaunchData($data);
        //print_r($data);
    }

    public function setretailData($data) {
        $fw = new Fwcore();
        $fw->setretailData($data);
    }

    public function gettemplateData($data) {
   $baseUrl = 'http://www.instappy.com/';
        $dbCon = content_db();
        $id = $data['theme_id'];
        if (($id >= 24) && ($id <= 50) && $id != 30 && is_numeric($id)) {
            $i = 0;
            $json = array();
            $adqr = array();
          $backgroundColor = $fontColor = $discountColor = '';          
            $app_theme_query = "SELECT id,theme_version,background_color,font_color,discount_color,theme_html,REPLACE(REPLACE(REPLACE(theme_html, '{', ''), '}', ''), '\"', '') AS themerow FROM themes WHERE id=:id";   
            $app_theme_queryExecution = $dbCon->prepare($app_theme_query);  
            $app_theme_queryExecution->bindParam("id", $id);
            $app_theme_queryExecution->execute();
            $app_theme_query_result = $app_theme_queryExecution->fetchAll(PDO::FETCH_ASSOC);  
            
        
           foreach ($app_theme_query_result as $adqrR) {
                $adqr[$i] = $adqrR;
                $i++;
                $backgroundColor = $adqrR['background_color'];
                $fontColor = $adqrR['font_color'];
                $discountColor = $adqrR['discount_color'];
                $themeVersion = $adqrR['discount_color'];
            }

            $ex = explode(',', $adqrR['themerow']);

            $nnav = str_replace("navigationbar:", " ", $ex[0]);
            $navigation = $nnav;


            $doc = new DOMDocument();
            @$doc->loadHTML($ex[2]);

            $tags = $doc->getElementsByTagName('img');
            $eArr = 0;
            foreach ($tags as $tag) {
                $elementArray[$eArr] = array("imageurl" => $tag->getAttribute('src'));
                $eArr++;
            }
            $element_count = count($elementArray);
            $element_array['elements'] = array();

            $element_array['elements'] = array("elements" => array(
                    "element_array" => $elementArray,
                    "element_count" => @$element_count
            ));


            if (!$app_theme_query_result) {
                $json['error'] = 'No Theme Found';


                echo json_encode($json);
                exit;
            }

            foreach ($app_theme_query_result as $adqrR) {
                $adqr[$i] = $adqrR;
                $i++;
            }
            //$json[]=$adqr;
            $json = array_merge($json, array('theme' => @$adqrR));
            
            if (($id >= 24) && ($id <= 50)) {
                $c = 0;
                $comp = array();
                $comp_query = "select id as comp_type, name as title from component_type where id in('111', '102', '109', '104') order by id asc";
                $comp_query_queryExecution = $dbCon->query($comp_query);
                $comp_query_query_result = $comp_query_queryExecution->fetchAll(PDO::FETCH_ASSOC);

                foreach ($comp_query_query_result as $compR) {
                    $comArray = array();
                    $comArray = array("title" => array("name" => $compR['title'], "id" => ''), "comp_type" => $compR['comp_type']);
                    @$sort_arr = array('comp_id' => '');
                    if ($compR['comp_type'] == 111) {
                        @$sort_arr = array('comp_id' => '1');
                    }
                    if ($compR['comp_type'] == 102) {
                        @$sort_arr = array('comp_id' => '2');
                    }
                    if ($compR['comp_type'] == 109) {
                        @$sort_arr = array('comp_id' => '3');
                    }
                    if ($compR['comp_type'] == 104) {
                        @$sort_arr = array('comp_id' => '4');
                    }
                    $comp[$c] = array_merge($comArray, @$sort_arr); //$compR+$sort_arr;
                    $c++;
                }
                $comp[3] = array_merge($comp[3], $element_array['elements']);
                $final_comp_array[0] = $comp[3];
                $final_comp_array[1] = $comp[0];
                $final_comp_array[2] = $comp[2];
                $final_comp_array[3] = $comp[1];
                // $json=array_merge( $json , array('comp_count'=>$c) );
                $json = array_merge($json, array('screen_data' => array('comp_count' => $c, 'comp_array' => $final_comp_array, 'navigation' => $navigation, "screen_properties" => array("theme_id" => "", "cat_id" => "", "background_color" => $backgroundColor, "font_color" => $fontColor, "discount_color" => $discountColor, "title" => ""))));

                // $json['comp_count']=$c;
                // $json['comp_array']=$comp;
            }
        } 
        elseif ($id > 50 && is_numeric($id)) {
            $backgroundColor = $fontColor = $discountColor = '';
            $themeVersion=0;
                     
            $app_theme_query = "SELECT id,theme_version,theme_html,background_color,font_color,discount_color,theme_html,image_urls,component_attached FROM themes WHERE id=:id";   
            $app_theme_queryExecution = $dbCon->prepare($app_theme_query);  
            $app_theme_queryExecution->bindParam("id", $id);
            $app_theme_queryExecution->execute();
            $app_theme_query_result = $app_theme_queryExecution->fetchAll(PDO::FETCH_ASSOC);  
           
            $adqrR = $app_theme_query_result[0];
            $json = array('theme' => $adqrR);
            $backgroundColor = $adqrR['background_color'];
            $fontColor = $adqrR['font_color'];
            $discountColor = $adqrR['discount_color'];
            $themeVersion = $adqrR['theme_version'];
            $eArr = 0;
            if($themeVersion == 1.1)
            {          
               $json= json_decode($adqrR['theme_html']);            
          
            }
            else
            { 
            $elementArray = array();
            $image_urls = json_decode($adqrR['image_urls']);
              $elementArray1=array();
                 if (!empty($image_urls)) {
                $arrayCount = count($image_urls);
                for ($h = 0; $h < $arrayCount; $h++) {
                    $img__url = $image_urls[$h];
                 $r=0;
                 $keyVal='';
                    foreach ($img__url as $key => $val) {
                        if($key == '101' || $key =='111')
                        {
                                $keyVal=$key;
                                $image_urls00 = explode(',', $img__url->{$key});
                                foreach ($image_urls00 as $imgurl) {
                              $elementArray[$eArr] = array("imageurl" => $baseUrl . $imgurl);
                          $eArr++;
                          }   
                        }
                    }
                }
           
            }
            $element_count = count($elementArray);
            $element_array['elements'] = array();
            $element_array['elements'] = array("elements" => array(
                    "element_array" => $elementArray,
                    "element_count" => @$element_count
            ));            
$eArr = 0;
			$elementArray1 = array();			
			if(!empty($image_urls))
			{			
                                if(isset($image_urls[1]))
                                {
                                   $img__url = $image_urls[1]; 
                                
				$image_urls01 = explode(',', $img__url->{108});
				foreach ($image_urls01 as $imgurl) 
				{
					$elementArray1[$eArr] = array("imageurl" => $baseUrl.$imgurl);
					$eArr++;
				}
                                }
			}
			$element_count1=count($elementArray1);
			$element_108=array("elements"=>array(
													  "element_array"=>$elementArray1,
													   "element_count"=>@$element_count1
											  ));
          
            $c = 0;
            $comp = array();
            $comp_query = "select id as comp_type, name as title from component_type where id in('111', '101', '108') order by id asc";
            $comp_query_queryExecution = $dbCon->query($comp_query);
            $comp_query_query_result = $comp_query_queryExecution->fetchAll(PDO::FETCH_ASSOC);

            foreach ($comp_query_query_result as $compR) {
                $comArray = array();
                $comArray = array("title" => array("name" => $compR['title'], "id" => ''), "comp_type" => $compR['comp_type']);
                @$sort_arr = array('comp_id' => '');
                if ($compR['comp_type'] == 111) {
                    @$sort_arr = array('comp_id' => '1');
                }
                if ($compR['comp_type'] == 101) {
                    @$sort_arr = array('comp_id' => '2');
                }
                if ($compR['comp_type'] == 108) {
                    @$sort_arr = array('comp_id' => '3');
                }
                $comp[$c] = array_merge($comArray, @$sort_arr); //$compR+$sort_arr;
                $c++;
            }

          	$final_comp_array[0] = array_merge($element_array['elements'], $comp[2]);
			$final_comp_array[1] = $comp[0];
			$final_comp_array[2] = array_merge($element_108, $comp[1]);
         


            $json = array_merge($json, array('screen_data' => array('comp_count' => $c, 'comp_array' => $final_comp_array, 'navigation' => '', "screen_properties" => array("theme_id" => "", "cat_id" => "", "background_color" => $backgroundColor, "font_color" => $fontColor, "discount_color" => $discountColor, "title" => ""))));
              }
            }
        elseif ($id == 30 && is_numeric($id)) {
            $app_theme_query = "SELECT id,background_color,font_color,discount_color,theme_html,image_urls,component_attached FROM themes WHERE id=:id";   
            $app_theme_queryExecution = $dbCon->prepare($app_theme_query);  
            $app_theme_queryExecution->bindParam("id", $id);
            $app_theme_queryExecution->execute();
            $app_theme_query_result = $app_theme_queryExecution->fetchAll(PDO::FETCH_ASSOC);
         
            $backgroundColor = '';
            $fontColor = '';
            $discountColor = '';
            $adqrR = $app_theme_query_result[0];
            $json = array('theme' => $adqrR);

            $eArr = 0;
            $elementArray = array();
            $image_urls = json_decode($adqrR['image_urls']);
            $backgroundColor = $adqrR['background_color'];
            $fontColor = $adqrR['font_color'];
            $discountColor = $adqrR['discount_color'];

            // for 101
            if (!empty($image_urls)) {
                $img_url = $image_urls[0];
                if (!empty($img_url->{101})) {
                    $image_urls0 = explode(',', $img_url->{101});
                    foreach ($image_urls0 as $imgurl) {
                        $elementArray[$eArr] = array("imageurl" => $baseUrl . $imgurl);
                        $eArr++;
                    }
                }
            }
            $element_count = count($elementArray);
            $element_array['elements'] = array();
            $element_array['elements'] = array("elements" => array(
                    "element_array" => $elementArray,
                    "element_count" => @$element_count
            ));

            // for 106
            $eArr = 0;
            $singlebanner = array();
            if (!empty($image_urls)) {
                $img_url = $image_urls[1];
                if (!empty($img_url->{106})) {
                    $image_urls1 = explode(',', $img_url->{106});
                    foreach ($image_urls1 as $imgurl) {
                        $singlebanner[$eArr] = array("imageurl" => $baseUrl . $imgurl);
                        $eArr++;
                    }
                }
            }
            $element_count = count($singlebanner);
            $single_banner_array1 = array("elements" => array(
                    "element_array" => $singlebanner,
                    "element_count" => @$element_count
            ));

            // for 106
            $eArr = 0;
            $singlebanner1 = array();
            if (!empty($image_urls)) {
                $img_url = $image_urls[2];
                if (!empty($img_url->{106})) {
                    $image_urls1 = explode(',', $img_url->{106});
                    foreach ($image_urls1 as $imgurl) {
                        $singlebanner1[$eArr] = array("imageurl" => $baseUrl . $imgurl);
                        $eArr++;
                    }
                }
            }
            $element_count = count($singlebanner1);
            $single_banner_array2 = array("elements" => array(
                    "element_array" => $singlebanner1,
                    "element_count" => @$element_count
            ));

            // for 106
            $eArr = 0;
            $singlebanner2 = array();
            if (!empty($image_urls)) {
                $img_url = $image_urls[3];
                if (!empty($img_url->{106})) {
                    $image_urls1 = explode(',', $img_url->{106});
                    foreach ($image_urls1 as $imgurl) {
                        $singlebanner2[$eArr] = array("imageurl" => $baseUrl . $imgurl);
                        $eArr++;
                    }
                }
            }
            $element_count = count($singlebanner2);
            $single_banner_array3 = array("elements" => array(
                    "element_array" => $singlebanner2,
                    "element_count" => @$element_count
            ));


            $c = 0;
            $comp = array();
            $component_attached = $adqrR['component_attached'];

            if (!empty($component_attached)) {
                $component_attached = explode(',', $component_attached);

                $iii = 1;
                foreach ($component_attached as $ca) {
                    $comp_query = "select id as comp_type, name as title from component_type where id = '" . $ca . "' order by id asc";
                    $comp_query_queryExecution = $dbCon->query($comp_query);
                    $comp_query_query_result = $comp_query_queryExecution->fetchAll(PDO::FETCH_ASSOC);

                    $compR = $comp_query_query_result[0];
                    $comArray = array();
                    $comArray = array("title" => array("name" => $compR['title'], "id" => ''), "comp_type" => $compR['comp_type']);
                    @$sort_arr = array('comp_id' => '');
                    if ($compR['comp_type'] == 111) {
                        @$sort_arr = array('comp_id' => $iii);
                    }
                    $comp[$c] = array_merge($comArray, @$sort_arr); //$compR+$sort_arr;
                    $c++;
                }
            }

            $final_comp_array[0] = array_merge($element_array['elements'], $comp[0]);
            $final_comp_array[1] = $comp[1];
            $final_comp_array[2] = array_merge($single_banner_array1, $comp[2]);
            $final_comp_array[3] = array_merge($single_banner_array2, $comp[3]);
            $final_comp_array[4] = array_merge($single_banner_array3, $comp[4]);
            $json = array_merge($json, array('screen_data' => array('comp_count' => $c, 'comp_array' => $final_comp_array, 'navigation' => '', "screen_properties" => array("theme_id" => "", "cat_id" => "", "background_color" => $backgroundColor, "font_color" => $fontColor, "discount_color" => $discountColor, "title" => ""))));
        }
        else  {
           $json=array("warning"=>'Dont mess with URL'); 
        }
        echo json_encode($json);
    }

   

}
