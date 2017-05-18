<?php 
require_once('common_functions_content.php');

class Retailsecond 
{ 
  
  public function getCatalogLaunchData($data)
	{
		 $fw = new Fwcore();
		$fw->getCatalogLaunchData($data);
		//print_r($data);
	}
  

	public function setretailData($data)
	{
		 $fw = new Fwcore();
		$fw->setretailData($data);
		
		
	}
	
	public function gettemplateData($data)
	{
		$dbCon = content_db();
		$id=$data['theme_id'];
		$i=0; 
		$json=array();
		$adqr=array();   
    $app_theme_query ="SELECT id,theme_html,REPLACE(REPLACE(REPLACE(theme_html, '{', ''), '}', ''), '\"', '') AS themerow FROM themes WHERE id=:id";
    $app_theme_queryExecution =$dbCon->prepare($app_theme_query);
     $app_theme_queryExecution->bindParam(':id',$id,PDO::PARAM_INT);             
                $app_theme_queryExecution->execute();
   $app_theme_query_result = $app_theme_queryExecution->fetchAll(PDO::FETCH_ASSOC);
    foreach($app_theme_query_result as $adqrR)
  {
	$adqr[$i]=$adqrR;
	$i++  ;
  }
   
   $ex=explode(',',$adqrR['themerow']);
  
   $nnav=str_replace("navigationbar:"," ",$ex[0]);
   $navigation=$nnav;
  
      $navigationArray=
      $doc = new DOMDocument();
      @$doc->loadHTML($ex[2]);

      $tags = $doc->getElementsByTagName('img');
     $eArr=0;
        foreach ($tags as $tag) {
       $elementArray[$eArr]= array("imageurl" => $tag->getAttribute('src'));
	   $eArr++;
        }
		$element_count=count($elementArray);
		$element_array['elements']=array();
		
		$element_array['elements']=array("elements"=>array(
		                                          "element_array"=>$elementArray,
		                                           "element_count"=>@$element_count
		                                  ));
   
   
   if(!$app_theme_query_result)
   {
	$json['error']='No Theme Found';
   
   
	   echo json_encode($json);
	   exit;
   }
	   
  foreach($app_theme_query_result as $adqrR)
  {
	$adqr[$i]=$adqrR;
	$i++  ;
  }
  //$json[]=$adqr;
  $json=array_merge( $json , array('theme'=>@$adqrR));
  if(($id >= 24)&&($id <= 50))
  {
  $c=0;
  $comp=array();
   $comp_query = "select id as comp_type, name as title from component_type where id in('111', '102', '109', '104') order by id asc";
    $comp_query_queryExecution = $dbCon->query($comp_query);
   $comp_query_query_result = $comp_query_queryExecution->fetchAll(PDO::FETCH_ASSOC);
   
     foreach($comp_query_query_result as $compR)
  {
	@$sort_arr=array('comp_id'=>'');
	if($compR['comp_type']==111)
	{
	 	@$sort_arr=array('comp_id'=>'1');
	}
	if($compR['comp_type']==102)
	{
	 	@$sort_arr=array('comp_id'=>'2');
	}
	if($compR['comp_type']==109)
	{
	 	@$sort_arr=array('comp_id'=>'3');
	}
	if($compR['comp_type']==104)
	{
	 	@$sort_arr=array('comp_id'=>'4');
	}
	$comp[$c]=array_merge( $compR , @$sort_arr );//$compR+$sort_arr;
	$c++  ;
  }
  $comp[3]=array_merge( $comp[3] , $element_array['elements']);
  $final_comp_array[0]=$comp[3];
   $final_comp_array[1]=$comp[0];
    $final_comp_array[2]=$comp[2];
	 $final_comp_array[3]=$comp[1];
 // $json=array_merge( $json , array('comp_count'=>$c) );
  $json=array_merge( $json ,array('screen_data'=> array('comp_count'=>$c,'comp_array'=>$final_comp_array,'navigation'=> $navigation)) );
 
   // $json['comp_count']=$c;
   // $json['comp_array']=$comp;
  }
   
	   echo json_encode($json);
	 //  $yummy = json_decode($json, true);
	 //  print_r($yummy);

		
	}
}
