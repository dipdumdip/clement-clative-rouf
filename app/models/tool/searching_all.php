<?php
namespace Models\Tool;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

// If it's going to need the database, then it's
// probably smart to require it before we start.

class Searching_All extends DatabaseObject {
    //SEarch for all related  Updates

  public static function search_AJAX_KEYWORD_Finder($key_qury, $filter, $per_page, $offset) {
      $database = new Database;
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
		$tottal_array = '';
  	// More Button
		$data='';
       $morequery= (!empty($per_page) || isset($offset)) ?		$morequery="LIMIT {$per_page} OFFSET {$offset}" : "";	
				
	$tottal_array_query_start = "SELECT *	FROM (";
		
		$union="	UNION ";
	$events="SELECT  EV.title AS title FROM appointment AS EV
					LEFT JOIN event_repeater EVR ON EVR.app_id_fk=EV.appointment_id
					WHERE  EV.title  LIKE '%{$key_qury}%'
					AND EVR.ev_start<='{$full_date}'
					AND EVR.ev_final>='{$full_date}'
					AND EV.ticketer>0
					GROUP BY title 
				";
	$companys="SELECT  A.companyname AS title FROM company A
					WHERE  A.companyname  LIKE '%{$key_qury}%'
					AND A.active>0
					GROUP BY title ";
					
	$sales=" SELECT  title AS title FROM products
					WHERE title LIKE '%{$key_qury}%'
					AND end_date > '{$time_date}' AND approve>0 AND done=1 
					GROUP BY title ";
	$peoples="SELECT P.`firstname_entry` AS title FROM personal AS P
						INNER JOIN authors U on U.auid=P.author_id_fk 
					WHERE P.firstname_entry LIKE '%{$key_qury}%'  AND U.role>1
					GROUP BY title ";
	
	$bookings="SELECT IF( bs.group_name!='' ,bs.group_name, bs.slot_name) AS title 
					FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
					WHERE  (  bs.group_name LIKE '%{$key_qury}%' OR  bs.slot_name  LIKE '%{$key_qury}%' )
					AND A.active>0  AND bs.approve>0 GROUP BY title ";
					
	$promotion ="SELECT  IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) AS title
						FROM  promotion AS pm
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$full_date}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
							AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
							AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
							AND B.ShowDate>=pm_t.start_date
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE (  pm.title  LIKE '%{$key_qury}%' OR   pm.details  LIKE '%{$key_qury}%'  ) AND 
						pm.approve>0 GROUP BY title ";

		
	
$tottal_array_query_end =" 	)derivedTable {$morequery} ";
			$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){

		$guery_final = $tottal_array_query_start.$sales.$union.$peoples.$union.$companys.$union.$events.$tottal_array_query_end;

	}else if (strpos($filter, 'all')=== false){
			$section_arr =array('sales', 'peoples', 'companys', 'events', 'bookings', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.$tottal_array_query_end;		
	}

		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		return !empty($totttal_array) ? ($totttal_array) : false;
 }
	
  public static function search_AJAX_FILL_KEY_Press($key_qury, $filter, $per_page, $offset, $soter, $lat, $lng) {
      $database = new Database;
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_day= date("Y-m-d 00:00:00", strtotime("+1 week"));
	$tottal_array = '';
  	// More Button
		$data='';
       $morequery="";
	if(!empty($soter) && $soter=='distance'  && $lat!=NULL  && $lng!=NULL ){
				$soterSelecter_people= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( P.home_lat ) ) 
									* cos( radians( P.home_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( P.home_lat ) ) ) ) AS sorter_order ," ;		
				$soterSelecter_company= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		
				$soterSelecter_bookings= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		
				$soterSelecter_promo= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.lat ) ) 
									* cos( radians( A.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_product= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( M.lat ) ) 
									* cos( radians( M.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( M.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_clouds= " 0 AS sorter_order ," ;		//<there is no location for clouds floating everywhere
				$soterSelecter_events= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( EV.lat ) ) 
									* cos( radians( EV.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( EV.lat ) ) ) ) AS sorter_order ," ;		
				
	}
    
	 
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
				
	$tottal_array_query_start = "SELECT *	FROM (";
		
	$sales=" SELECT M.product_id AS product_id, M.end_date AS created, {$soterSelecter_product} 
					CONCAT(  M.`photo`,'/[PRO]') AS photo, M.lat AS lat, M.lng AS lng,
					M.title AS title, M.offer_description AS description, M.offer_price AS offer_price,
					M.original_price AS original_price, M.end_date AS main_time
					FROM products M
					LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					WHERE MATCH (  M.title, M.offer_description ) AGAINST ( :key_qury    IN BOOLEAN MODE )
					AND M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1 
					GROUP BY product_id  ";
		$union="	UNION ";
	$peoples="SELECT U.authorname AS product_id,  U.created AS created,  {$soterSelecter_people}   CONCAT(  P.`photo`,'/[AU]') AS photo,
					P.home_lat AS lat,  P.home_lng AS lng, 
					IF( P.`firstname_entry`!='' ,CONCAT(U.`authorname`,' : ', P.`firstname_entry`, ' ', P.`surname_entry`), U.`authorname`) AS title,
					P.aboutme AS description, (SELECT count(*) FROM `impression_author` IMP_U 
								WHERE IMP_U.author=P.`author_id_fk`) AS offer_price,
						NULL AS original_price, U.last_login AS main_time
					FROM personal AS P
					LEFT JOIN `authors` AS U ON P.`author_id_fk` = U.auid 
					WHERE  MATCH (  P.firstname_entry, P.surname_entry , P.title_post ) AGAINST ( :key_qury    IN BOOLEAN MODE )
					    OR MATCH (U.authorname) AGAINST ( :key_qury    IN BOOLEAN MODE )
					";

	$bookings="SELECT CONCAT(A.companyname,'/booking/',bs.`slot_id`) AS product_id, ADDTIME('{$full_date}', bs.time_end) AS created,
					{$soterSelecter_bookings} IF( bs.uploads!='' , CONCAT( bs.uploads,'/[BKN]'), CONCAT( A.`company_logo`,'/[BKN]')) AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng, CONCAT( bs.group_name,' ', bs.slot_name, ' booking from ',
					 IF( A.companytitle!='' ,A.companytitle, A.companyname)) AS title,  A.aboutcompany AS description,  
					 IF( bspl.spcl_price IS NULL , bs.slot_price, bspl.spcl_price) AS offer_price, 
						NULL AS original_price, IF( A.booking_type='long', bs.`slot_id`,'') AS main_time
					FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN booking_slots_spcl bspl ON bspl.`slot_id_fk` = bs.`slot_id` AND DAYNAME( '{$full_date}' ) = bspl.spcl_weeek_day
					WHERE  MATCH (  bs.group_name, bs.slot_name , bs.slot_details, A.companyname,  A.companytitle  ) AGAINST ( :key_qury    IN BOOLEAN MODE )
					AND A.active>0  AND bs.approve>0
					GROUP BY product_id ";
					
	$promotion ="SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS product_id,
					ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)) AS created,
					{$soterSelecter_promo} CONCAT( pm.uploads,'/[PRM]') AS photo,
					 A.lat AS lat,  A.lng AS lng, IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) AS title,
						pm.details AS description,  
					 IF( pm_sp.spcl_percentage IS NULL , pm.percetage, pm_sp.spcl_percentage) AS offer_price, 
						NULL AS original_price, CONCAT(pm.pm_id,'/',A.location)  AS main_time
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
										AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
										AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
										AND B.ShowDate>=pm_t.start_date
										AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE MATCH (  pm.title, pm.details ) AGAINST ( :key_qury IN BOOLEAN MODE ) AND 
					pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
					GROUP BY title";

	
	$companys="SELECT A.companyname AS product_id,  A.created AS created,  {$soterSelecter_company} CONCAT( A.`company_logo`,'/[COM]') AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng,  IF( A.companytitle!='' ,CONCAT(A.companyname,' : ', A.companytitle), A.companyname) AS title, 
					 A.aboutcompany AS description, (SELECT count(*) FROM `impression_company` IMP_C 
					WHERE IMP_C.company_id=A.company_id  AND IMP_C.impression>1 ) AS offer_price, NULL AS original_price, W.view_count AS main_time
					FROM company A
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					LEFT JOIN website W ON A.company_id = W.company_id
					WHERE MATCH (  A.companyname,  A.companytitle ) AGAINST ( :key_qury IN BOOLEAN MODE )  AND pc.class_id_fk<>6
					AND A.active>0
					GROUP BY product_id ";
	
	$events="SELECT EV.appointment_id AS product_id,  EV.created AS created,  {$soterSelecter_events}   CONCAT(  EV.`title_img`,'/[EV]') AS photo,
					EV.lat AS lat,  EV.lng AS lng, EV.title AS title,  EV.basic_description AS description,  
					 EV.view_count AS offer_price, NULL AS original_price, EVR.ev_final AS main_time
					FROM appointment AS EV
					LEFT JOIN event_repeater EVR ON EVR.app_id_fk=EV.appointment_id
					WHERE  MATCH (  EV.title, EV.comment ) AGAINST ( :key_qury IN BOOLEAN MODE )
					AND EVR.ev_start<='{$full_date}'
					AND EVR.ev_final>='{$full_date}'
					AND EV.ticketer>0
				";
	
	
$tottal_array_query_end =" 	) derivedTable {$morequery} ";
			$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){

	$guery_final = $tottal_array_query_start.$sales.$union.$peoples.$union.$companys.$union.$events.$union.$promotion.$union.$bookings.$tottal_array_query_end;

	}else if (strpos($filter, 'all')=== false){
			$section_arr =array('sales', 'peoples', 'companys', 'events', 'bookings', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.'  '.$tottal_array_query_end;		
	}
	// echo $guery_final;
	
		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		return !empty($totttal_array) ? ($totttal_array) : false;
 }

  public static function search_for_key($key_qury, $filter, $per_page, $offset, $soter, $lat, $lng, $color_id, $div_id, $category, $category2) {
      $database = new Database;
		$tottal_array = '';
  	// More Button
		$data='';
       $morequery="";
       $colorFilter="";
       $soterSelecter_people="";
       $soterSelecter_company="";
       $soterSelecter_promo="";
       $soterSelecter_bookings="";
       $soterSelecter_product="";
       $soterSelecter_events="";
       $soterSelecter_clouds="";
				$soterFilter_promo= "" ;		
				$soterFilter_bookings= "" ;		
				$soterFilter_product= "" ;		
				$soterFilter_company= "" ;		
	$colorJointer_promo= "" ;		
	$colorJointer_bookings= "" ;		
	$colorJointer_product= "" ;		
	$colorJointer_company= "" ;		
	$colorJointer_events= "" ;		
	$colorJointer_clouds= "" ;		
	$colorJointer_peoples= "" ;		
	      $soterSelecter=""; $soterFilter=""; $soterOrder= " ORDER BY created DESC " ;	
	$soterFilter_people=""; 
	$soterJointer_promo= "" ;		
	$soterJointer_bookings= "" ;		
	$soterJointer_product= "" ;		
	$soterJointer_company= "" ;		
	$soterJointer_people= "" ;		
	$soterJointer_clouds= "" ;		
	$soterJointer_events= "" ;		
       $cat1Layer="";
       $cat2Layer="";
	   			$soterFilter_events= "" ;		
				$soterFilter_clouds= " " ;		
				$soterFilter_promo= "" ;		
				$soterFilter_bookings= "" ;		
				$soterFilter_product= "" ;		
				$soterFilter_company= " " ;		
				$soterFilter_people= " " ;		
				$soterFilter_people="";
			$colorFilter_companys= "" ;		
			$colorFilter_events=" ";
			$colorFilter_clouds=" ";
			$colorFilter_peoples=" ";
			$colorFilter_products=" ";
			$colorFilter_promo=" ";
			$colorFilter_bookings=" ";
  		$keyQuery_companys= "" ;		
		$keyQuery_events=" ";
		$keyQuery_products=" ";
		$keyQuery_promo=" ";
		$keyQuery_booking=" ";
		$keyQuery_people=" ";

     if(!empty($soter) && $soter=='L2H_price' ||$soter=='H2L_price' ){
				$soterSelecter_promo= " IF( pm_sp.spcl_percentage IS NULL , pm.percetage, pm_sp.spcl_percentage) AS sorter_order ," ;		
				$soterSelecter_bookings= " IF( bspl.spcl_price IS NULL , bs.slot_price, bspl.spcl_price) AS sorter_order ," ;		
				$soterSelecter_events= " 0 AS sorter_order ," ;		
				$soterSelecter_clouds= " 0 AS sorter_order ," ;		
				$soterSelecter_people= " 0 AS sorter_order ," ;		
				$soterSelecter_company= " 0 AS sorter_order ," ;		
				$soterSelecter_product= " M.offer_price AS sorter_order ," ;		
				$soterJointer_promo= "" ;		
				$soterJointer_bookings= "" ;		
				$soterJointer_product= "" ;		
				$soterJointer_company= " " ;		
				$soterJointer_people= "" ;		
				$soterJointer_clouds= "" ;		
				$soterJointer_events= "" ;		
			$soterOrder= ($soter=='L2H_price') ?  " ORDER BY sorter_order ASC " : " ORDER BY sorter_order DESC " ;		
				$soterFilter_promo= "" ;		
				$soterFilter_bookings= "" ;		
				$soterFilter_events= "" ;		
				$soterFilter_clouds= " " ;		
				$soterFilter_product= "" ;		
				$soterFilter_company= " " ;		
				$soterFilter_people="";
	}elseif(!empty($soter) && $soter=='distance'  && $lat!=NULL  && $lng!=NULL ){
				$soterSelecter_people= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( P.home_lat ) ) 
									* cos( radians( P.home_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( P.home_lat ) ) ) ) AS sorter_order ," ;		
				$soterSelecter_company= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		
				$soterSelecter_bookings= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_promo= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.lat ) ) 
									* cos( radians( A.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_product= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( M.lat ) ) 
									* cos( radians( M.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( M.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_clouds= " 0 AS sorter_order ," ;		//<there is no location for clouds floating everywhere
				$soterSelecter_events= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( EV.lat ) ) 
									* cos( radians( EV.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( EV.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterJointer_events= " " ;		
				$soterJointer_clouds= " " ;		
				$soterJointer_promo= " " ;		
				$soterJointer_bookings= " " ;		
				$soterJointer_product= " " ;		
				$soterJointer_company= " " ;		
				$soterJointer_people= "  " ;		
			$soterOrder= " ORDER BY sorter_order " ;		
				$soterFilter_promo= "" ;		
				$soterFilter_bookings= "" ;		
				$soterFilter_product= "" ;		
				$soterFilter_company= "" ;		
				$soterFilter_people=" ";
				$soterFilter_clouds= "" ;		
				$soterFilter_events=" ";
	}else if(!empty($soter) && $soter=='reviewrank' ){
				$soterSelecter_people= " U.follower_count AS sorter_order ," ;		
				$soterSelecter_company= " (sum(CRW.sum) div 280) AS sorter_order ," ;		
				$soterSelecter_bookings= " (sum(CRW.sum) div 280) AS sorter_order ," ;		
				$soterSelecter_product= " (sum(PRW.sum)div 280) AS sorter_order ," ;		
				$soterSelecter_clouds= "  CLD.view_count AS sorter_order ," ;		
				$soterSelecter_events= " EV.view_count AS sorter_order ," ;		
				$soterSelecter_promo= " A.view_count AS sorter_order ," ;		
				
				$soterJointer_product= " LEFT JOIN productlikes PRW ON PRW.product_id= M.product_id " ;		
				$soterJointer_company= " LEFT JOIN companylikes CRW ON CRW.company_id= A.company_id " ;		
				$soterJointer_promo= "" ;		
				$soterJointer_bookings= " LEFT JOIN companylikes CRW ON CRW.company_id= A.company_id " ;		
				$soterJointer_events= "" ;		
				$soterJointer_clouds= "" ;		
				$soterJointer_people= "" ;		
			$soterOrder= " ORDER BY sorter_order DESC " ;		
				$soterFilter_promo= "" ;		
				$soterFilter_bookings= "" ;		
				$soterFilter_product= "" ;		
				$soterFilter_company= "" ;		
				$soterFilter_events=" ";
				$soterFilter_clouds=" ";
				$soterFilter_people=" ";
		}
    
     if(!empty($color_id) && $color_id!='' && $color_id !=NULL ){
				$colorJointer_product= " LEFT JOIN classify_product_new pc2 ON pc2.product_id_fk= M.product_id  " ;		
				$colorJointer_company= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_bookings= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_clouds= " LEFT JOIN classify_cloud_new pc2 ON pc2.cloud_id_fk= CLD.cloud_id " ;		
				$colorJointer_events= "" ;		
				$colorJointer_promo= "" ;		

				$colorFilter_products=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_companys=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_bookings=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_clouds=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_promo=" ";
				$colorFilter_events=" ";
				$colorFilter_peoples=" ";
		}
    
     if(!empty($category) && $category!='1' && $div_id !=NULL ){
				$cat1Layer=" AND pc.class_id_fk=1  AND pc.value={$category} ";		}
	
	if($category2){		$cat2Layer=" AND pc.value_2={$category2} ";		}
    
  
	if(!empty($key_qury) && $key_qury!='' && $key_qury !=NULL ){
		$keyQuery_products=" AND MATCH (  M.title, M.offer_description ) AGAINST ( :key_qury    IN BOOLEAN MODE )  ";
		$keyQuery_people=" AND MATCH (  P.firstname_entry, P.surname_entry , P.title_post ) AGAINST ( :key_qury    IN BOOLEAN MODE )
									OR MATCH (U.authorname) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
		$keyQuery_booking= " AND  MATCH (  bs.group_name, bs.slot_name , bs.slot_details, A.companyname,  A.companytitle ) AGAINST ( :key_qury    IN BOOLEAN MODE )  " ;		
		$keyQuery_companys= " AND   MATCH (  A.companyname,  A.companytitle ) AGAINST ( :key_qury IN BOOLEAN MODE )  " ;		
		$keyQuery_events="  AND MATCH (  EV.title, EV.comment ) AGAINST ( :key_qury IN BOOLEAN MODE )";
		$keyQuery_promo=" AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
	}
			 
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
		 $time_date= date("Y-m-d H:i:s", time());
		 $full_date= date("Y-m-d 00:00:00", time());
		 $next_date= date("Y-m-d 00:00:00", strtotime("+1 days"));

	 $tottal_array_query_start = "SELECT *	FROM (";
		
	$sales=" SELECT M.product_id AS product_id, M.end_date AS created, {$soterSelecter_product} CONCAT(  M.`photo`,'/[PRO]') AS photo, M.lat AS lat, M.lng AS lng,
					M.title AS title, M.offer_description AS description, M.offer_price AS offer_price, M.original_price AS original_price, M.end_date AS main_time
					FROM products M
					LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
					{$soterJointer_product} {$colorJointer_product} 
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					WHERE M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1  {$keyQuery_products}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_product} {$colorFilter_products}
					GROUP BY product_id  ";
		$union="	UNION ";

	$bookings="SELECT CONCAT(A.companyname,'/booking/',bs.`slot_id`) AS product_id, ADDTIME('{$full_date}', bs.time_end) AS created,
					{$soterSelecter_bookings} IF( bs.uploads!='' , CONCAT( bs.uploads,'/[BKN]'), CONCAT( A.`company_logo`,'/[BKN]')) AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng, CONCAT( bs.group_name,' ', bs.slot_name, ' booking from ',
					 IF( A.companytitle!='' ,A.companytitle, A.companyname)) AS title,  A.aboutcompany AS description,  
					 IF( bspl.spcl_price IS NULL , bs.slot_price, bspl.spcl_price) AS offer_price, 
						NULL AS original_price,IF( A.booking_type='long', bs.`slot_id`,'') AS main_time
					FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					{$soterJointer_bookings}  {$colorJointer_bookings}
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN booking_slots_spcl bspl ON bspl.`slot_id_fk` = bs.`slot_id` AND DAYNAME( '{$full_date}' ) = bspl.spcl_weeek_day
					WHERE   A.active>0 AND bs.approve>0  {$keyQuery_booking}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_company} {$colorFilter_bookings}
					GROUP BY product_id ";
	
	
	$promotion ="SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS product_id,
					ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)) AS created,
					{$soterSelecter_promo} CONCAT( pm.uploads,'/[PRM]') AS photo,
					 A.lat AS lat,  A.lng AS lng, 
					 CONCAT(DAYNAME( B.ShowDate ),' ', IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) )AS title,
						pm.details AS description,  
					 IF( pm_sp.spcl_percentage IS NULL , pm.percetage, pm_sp.spcl_percentage) AS offer_price, 
						NULL AS original_price,  CONCAT(pm.pm_id,'/',A.location)  AS main_time
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_date}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
							AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
							AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
							AND B.ShowDate>=pm_t.start_date
							AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					{$soterJointer_promo} 
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
						{$keyQuery_promo}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_promo}
					GROUP BY main_time ";
				
		$peoples="SELECT U.authorname AS product_id,  U.created AS created,  {$soterSelecter_people}   CONCAT(  P.`photo`,'/[AU]') AS photo,
					P.home_lat AS lat,  P.home_lng AS lng, 
					IF( P.`firstname_entry`!='' ,CONCAT(U.`authorname`,' : ', P.`firstname_entry`, ' ', P.`surname_entry`), U.`authorname`) AS title,
					P.aboutme AS description,  NULL AS original_price, P.view_count AS main_time,
						(SELECT count(*) FROM `impression_author` IMP_U 
								WHERE IMP_U.author=P.`author_id_fk`) AS offer_price
					FROM personal AS P
					LEFT JOIN `authors` AS U ON P.`author_id_fk` = U.auid 
					{$soterJointer_people}
					WHERE  U.role>1  {$keyQuery_people}
					
					{$soterFilter_people} OR MATCH (U.authorname) AGAINST ( :key_qury    IN BOOLEAN MODE )
					";
	
	$companys="SELECT A.companyname AS product_id,  A.created AS created,  {$soterSelecter_company} CONCAT( A.`company_logo`,'/[COM]') AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng, IF( A.companytitle<>'', CONCAT(A.companytitle,' - ', A.companyname), A.companyname) AS title,  A.aboutcompany AS description,  
						NULL AS original_price, W.view_count AS main_time, (SELECT count(*) FROM `impression_company` IMP_C 
											WHERE IMP_C.company_id=A.company_id  AND IMP_C.impression>1) AS offer_price
					FROM company A
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					{$soterJointer_company}  {$colorJointer_company}
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					LEFT JOIN website W ON A.company_id = W.company_id
					WHERE  A.active>0  {$keyQuery_companys}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_company} {$colorFilter_companys}
					GROUP BY product_id ";
	
	$events="SELECT EV.appointment_id AS product_id,  EVR.ev_final AS created,  {$soterSelecter_events}   CONCAT(  EV.`title_img`,'/[EV]') AS photo,
					EV.lat AS lat,  EV.lng AS lng, EV.title AS title,  EV.basic_description AS description,  
					 EV.view_count AS offer_price, NULL AS original_price, EV.view_count AS main_time
					FROM appointment AS EV
					LEFT JOIN event_repeater EVR ON EVR.app_id_fk=EV.appointment_id
					{$soterJointer_events}
					WHERE   EVR.ev_start<='{$full_date}'
					AND EVR.ev_final>='{$full_date}'
					AND EV.ticketer>0  {$keyQuery_events}
					{$soterFilter_events}
				"; 
	
$tottal_array_query_end =" 	)derivedTable {$soterOrder} {$morequery} ";
			$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){

	$guery_final = $tottal_array_query_start.$sales.$union.$peoples.$union.$bookings.$union.$companys.$union.$events.$union.$promotion.$tottal_array_query_end;

	}else if (strpos($filter, 'all')=== false){
			$section_arr =array('sales', 'peoples', 'companys', 'events', 'bookings', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.$tottal_array_query_end;		
	}
	// echo $guery_final;
	
		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		return !empty($totttal_array) ? ($totttal_array) : false;
 }
 
 // finding total SEarch for all related 
 public static function search_for_key_total($key_qury,$filter,$soter, $lat, $lng, $color_id, $div_id, $category,$category2) {
      $database = new Database;
		$tottal_array = '';
  	// More Button
		$data='';
       $morequery="";
       $colorFilter="";
	$colorJointer_product= "" ;		
	$colorJointer_promotion= "" ;		
	$colorJointer_company= "" ;		
	$colorJointer_bookings= "" ;		
	$colorJointer_events= "" ;		
	$colorJointer_clouds= "" ;		
	$colorJointer_peoples= "" ;		
       $cat1Layer="";
       $cat2Layer="";
			$colorFilter_companys= "" ;		
			$colorFilter_events=" ";
			$colorFilter_clouds=" ";
			$colorFilter_peoples=" ";
			$colorFilter_products=" ";
			$colorFilter_promotion=" ";
			$colorFilter_bookings=" ";
  		$keyQuery_companys= "" ;		
		$keyQuery_events=" ";
		$keyQuery_products=" ";
		$keyQuery_promo=" ";
		$keyQuery_booking=" ";
		$keyQuery_people=" ";
 
     if(!empty($color_id) && $color_id!='' && $color_id !=NULL ){
				$colorJointer_product= " LEFT JOIN classify_product_new pc2 ON pc2.product_id_fk= M.product_id  " ;		
				$colorJointer_company= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_bookings= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_clouds= " LEFT JOIN classify_cloud_new pc2 ON pc2.cloud_id_fk= CLD.cloud_id " ;		
				$colorJointer_events= " LEFT JOIN appointmentlabels pc2 ON pc2.label_id_fk= EV.label_id " ;		
				$colorJointer_promotion= " " ;		

				$colorFilter_products=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_companys=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_bookings=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_clouds=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_events=" ";
				$colorFilter_peoples=" ";
		}
    
     if(!empty($category) && $category!='1' && $div_id !=NULL ){
				$cat1Layer=" AND pc.class_id_fk=1  AND pc.value={$category} ";		}
	if($category2){		$cat2Layer=" AND pc.value_2={$category2} ";		}
 
  
	if(!empty($key_qury) && $key_qury!='' && $key_qury !=NULL ){
			$keyQuery_products=" AND MATCH (  M.title, M.offer_description ) AGAINST ( :key_qury    IN BOOLEAN MODE )  ";
			$keyQuery_people=" AND MATCH (  P.firstname_entry, P.surname_entry , P.title_post ) AGAINST ( :key_qury    IN BOOLEAN MODE )
										OR MATCH (U.authorname) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
			$keyQuery_booking= " AND  MATCH (  bs.group_name, bs.slot_name , bs.slot_details, A.companyname,  A.companytitle ) AGAINST ( :key_qury    IN BOOLEAN MODE )  " ;		
			$keyQuery_companys= " AND   MATCH (  A.companyname,  A.companytitle ) AGAINST ( :key_qury IN BOOLEAN MODE )  " ;		
			$keyQuery_events="  AND MATCH (  EV.title, EV.comment ) AGAINST ( :key_qury IN BOOLEAN MODE )";
			$keyQuery_promo=" AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
		}
		
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_date= date("Y-m-d 00:00:00", strtotime("+1 days"));
 
	 $tottal_array_query_start = "SELECT COUNT(*)	FROM (";
		
	$sales=" SELECT M.product_id AS product_id FROM products M
				LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
					{$colorJointer_product} 
				LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
				LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
				LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
				LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
				WHERE M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1 
					{$keyQuery_products}
				{$cat1Layer} {$cat2Layer}  {$colorFilter_products}
				GROUP BY product_id  ";
		$union="	UNION ";
	$peoples="SELECT U.auid AS product_id FROM personal AS P
				LEFT JOIN `authors` AS U ON P.`author_id_fk` = U.auid 
				WHERE U.role>1 {$keyQuery_people}
				";
		$bookings="SELECT CONCAT(A.companyname,'/booking/',bs.`slot_id`) AS product_id
						FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					 {$colorJointer_bookings}
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN booking_slots_spcl bspl ON bspl.`slot_id_fk` = bs.`slot_id` AND DAYNAME( '{$full_date}' ) = bspl.spcl_weeek_day
					WHERE A.active>0 AND bs.approve>0
						{$keyQuery_booking}
					{$cat1Layer} {$cat2Layer} {$colorFilter_bookings}
					GROUP BY product_id ";

	
	$promotion ="SELECT CONCAT(A.location,' ',pm.`pm_id`) AS product_id FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_date}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
										AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
										AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
										AND B.ShowDate>=pm_t.start_date
										AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0  AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND  ADDTIME( B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
					{$keyQuery_promo}
				{$cat1Layer} {$cat2Layer} 
				GROUP BY product_id  ";
				
	$companys="SELECT A.company_id AS product_id FROM company A
				LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					{$colorJointer_company} 
				LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
				LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
				LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
				LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					WHERE A.active>0 {$keyQuery_companys}
				{$cat1Layer} {$cat2Layer}  {$colorFilter_companys}
				GROUP BY product_id  ";
	
	$events="SELECT EV.appointment_id AS product_id FROM appointment AS EV
				LEFT JOIN event_repeater EVR ON EVR.app_id_fk=EV.appointment_id
				WHERE  EVR.ev_start<='{$full_date}' 
					AND EVR.ev_final>='{$full_date}'{$keyQuery_events}
					AND EV.ticketer>0
				";
	
		
	
$tottal_array_query_end =" 	)derivedTable";
	$guery_final ='';$i=1;
if (strpos($filter, 'all') !== false){

$guery_final =$tottal_array_query_start.$sales.$union.$peoples.$union.$companys.$union.$bookings.$union.$promotion.$union.$events.$tottal_array_query_end;

}else if (strpos($filter, 'all')=== false){
			$section_arr =array('sales', 'peoples', 'companys', 'events', 'bookings', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.$tottal_array_query_end;		
}
		$param_array=array(':key_qury' => $key_qury );  
	$data = $database->fetch_assoc_cached($guery_final, $param_array );
	return !empty($data) ? $data['COUNT(*)'] : false;
 }
	
    //Category Search For facested king search
  public static function search_for_categorykey($key_qury, $filter,$color_id, $div_id, $category,$category2) {
 	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_date= date("Y-m-d 00:00:00", strtotime("+1 days"));
     $database = new Database;
		   $tottal_array = '';
		   $data='';
		   $cat1Layer="";
		   $cat2Layer=""; $cate_2_select =''; $cate_2_jointer ='';$cate_2_group='';
		   // $color_dep_both='AND (pc.class_id_fk=1 OR pc.class_id_fk=6 )';
		   $color_dep_both='AND (pc.class_id_fk=1 )';
				if($category2){		$cat2Layer=" AND pc.value_2='{$category2}' ";	
							}
	if($category!=NULL){
			$cat1Layer=" AND pc.value='{$category}' ";		
									$cate_2_select =' IF(pc.class_id_fk=1,  dsl.text, 0) as text_print2, 
													IF(pc.class_id_fk=1,  dsl.value, 0) as value_print2, ';
								$cate_2_jointer =' INNER JOIN department_second_layer dsl ON dsl.cat2_id = pc.value_2 ';
								$cate_2_group= 'value_print2, ';
		}	
			$tottal_array_query_start = " SELECT *, count(totals) as total 
									FROM ( ";
			$sales	  =" (   SELECT   cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
										IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
									FROM classify_product_new pc 
									INNER JOIN products p ON p.product_id = pc.product_id_fk
									INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
											{$cate_2_jointer}
									INNER JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
									INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
									WHERE MATCH ( p.title, p.offer_description) AGAINST ( :key_qury IN BOOLEAN MODE )
									AND p.end_date > '{$time_date}' AND p.approve>0 AND p.done=1 
											{$color_dep_both}
									{$cat1Layer} {$cat2Layer} ) ";
			$union			  =" UNION ALL ";
			$companys	  =" (   SELECT   cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
										IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
									FROM classify_company_new pc 
									INNER JOIN company p ON p.company_id = pc.company_id_fk
									INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
											{$cate_2_jointer}
									INNER JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
									INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
									WHERE MATCH ( p.companyname, companytitle ) AGAINST ( :key_qury IN BOOLEAN MODE )
									AND p.active>0
									{$color_dep_both}
									{$cat1Layer} {$cat2Layer} ) ";
			$bookings	  =" (   SELECT cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
										IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
									FROM classify_company_new pc 
									INNER JOIN company p ON p.company_id = pc.company_id_fk
									INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
											{$cate_2_jointer}
									INNER JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
									INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
									INNER JOIN booking_slots AS bs ON  p.company_id = bs.comp_id_fk
									WHERE MATCH (  bs.group_name, bs.slot_name , bs.slot_details, p.companyname,  p.companytitle ) AGAINST ( :key_qury    IN BOOLEAN MODE )
										AND p.active>0  AND bs.approve>0 {$color_dep_both}
									{$cat1Layer} {$cat2Layer} ) ";
									
	$promotion ="SELECT  cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
					IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
					INNER JOIN classify_promotion_new pc ON  pc.pm_id_fk = pm.pm_id
					INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
								{$cate_2_jointer}
					LEFT JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
					INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_date}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
							AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
							AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
							AND B.ShowDate>=pm_t.start_date
							AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0  AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
					 AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) 
					{$color_dep_both}
				{$cat1Layer} {$cat2Layer}  GROUP BY  CONCAT(A.location,' ',pm.`pm_id`) ";
				
			$tottal_array_query_end="	) data  GROUP BY {$cate_2_group} value_print ORDER BY name DESC";
						
	$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){
			$guery_final =$tottal_array_query_start.$sales.$union.$companys.$union.$bookings.$union.$promotion.$tottal_array_query_end;
	}else if (strpos($filter, 'all')=== false){
				$section_arr =array('sales',  'companys', 'bookings', 'promotion');	//<----requrired filterd datas array....
				$filter_arr=explode('-',$filter);											//<-----given filterdatas array
				$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
					foreach ($result_arr as $result){
							if(($key = array_search($result, $section_arr)) !== false) {
											unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
								}
					}
				$guery_final.= $tottal_array_query_start;
				foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
							$guery_final.= $$section;
								if($i<count($section_arr)){
									$guery_final.= $union;					//<---aad unnion variables
								}
						$i++;
					}
			$guery_final= $guery_final.$tottal_array_query_end;		
	}
	// echo $guery_final;
		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		// print_r($totttal_array);
		return !empty($totttal_array) ? ($totttal_array) : false;


 }
 
	public static function index_feed_Records($key_qury, $filter, $per_page, $offset, $soter, $lat, $lng, $color_id, $div_id, $category, $category2) {
      $database = new Database;
		$tottal_array = '';
  	// More Button
		$data='';
       $morequery="";
       $soterSelecter_promo=""; $soterSelecter_people=""; $soterSelecter_company="";
       $soterSelecter_classified=""; $soterSelecter_product=""; $soterSelecter_events="";
			$soterJointer_promo=""; $soterFilter_people=""; $soterJointer_classified= "" ;		
			$soterJointer_product= "" ; $soterJointer_company= "" ; $soterJointer_events= "" ;
				$soterFilter_events= "" ;  $soterFilter_classified= "" ; $soterFilter_company= " " ;			
				$soterFilter_promo= "" ;$soterFilter_product= "" ; $soterFilter_people="";
	$colorJointer_classified= "" ;	$colorJointer_product= "" ;		$colorJointer_company= "" ;		
	$colorJointer_events= "" ;			$colorJointer_clouds= "" ;		$colorJointer_peoples= "" ;		
	$colorJointer_promo= "" ;
		$colorFilter_companys= "" ; $colorFilter_events=" "; $colorFilter_clouds=" ";
		$colorFilter_peoples=" ";		$colorFilter_products=" ";		$colorFilter_promo=" ";
	$keyQuery_companys= "" ;	$keyQuery_clouds=" ";	$keyQuery_events=" ";	$keyQuery_products=" ";
	$keyQuery_promo=" ";
		
		$soterOrder= " ORDER BY created DESC " ;	
	    $soterOrder_extra= " , created ASC " ;	

     if(!empty($soter) && $soter=='L2H_price' ||$soter=='H2L_price' ){
				$soterSelecter_classified= " CLF.special_price AS sorter_order ," ;		
				$soterSelecter_events= " 0 AS sorter_order ," ;		
				$soterSelecter_promo= " 0 AS sorter_order ," ;		
				$soterSelecter_people= " 0 AS sorter_order ," ;		
				$soterSelecter_company= " 0 AS sorter_order ," ;		
				$soterSelecter_product= " M.offer_price AS sorter_order ," ;		
			$soterOrder= ($soter=='L2H_price') ?  " ORDER BY sorter_order ASC " : " ORDER BY sorter_order DESC " ;		
	}elseif(!empty($soter) && $soter=='distance'  && $lat!=NULL  && $lng!=NULL ){

				$soterSelecter_company= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		

									
				$soterSelecter_promo= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.lat ) ) 
									* cos( radians( A.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_product= " IF( ( M.sale_type='quick' OR M.sale_type='normal'),( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( M.lat ) ) 
									* cos( radians( M.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( M.lat ) ) ) ), 0.3 ) AS sorter_order ," ;		
				
				$soterSelecter_events= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( EV.lat ) ) 
									* cos( radians( EV.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( EV.lat ) ) ) ) AS sorter_order ," ;		
				$soterOrder= " ORDER BY sorter_order " ;		
	}else if(!empty($soter) && $soter=='reviewrank' ){
				$soterSelecter_company= " (sum(CRW.sum) div 280) AS sorter_order ," ;		
				$soterSelecter_product= " (sum(PRW.sum)div 280) AS sorter_order ," ;		
				$soterSelecter_events= " EV.view_count AS sorter_order ," ;		
				$soterSelecter_promo= " A.view_count AS sorter_order ," ;		
				
				$soterJointer_product= " LEFT JOIN productlikes PRW ON PRW.product_id= M.product_id " ;		
				$soterJointer_company= " LEFT JOIN companylikes CRW ON CRW.company_id= A.company_id " ;		
			$soterOrder= " ORDER BY sorter_order DESC " ;		
		}
    
     if(!empty($color_id) && $color_id!='' && $color_id !=NULL ){
				$colorJointer_product= " LEFT JOIN classify_product_new pc2 ON pc2.product_id_fk= M.product_id  " ;		
				$colorJointer_company= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_bookings= " LEFT JOIN classify_cloud_new pc2 ON pc2.cloud_id_fk= CLD.cloud_id " ;		

				$colorFilter_products=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_companys=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_bookings=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
		}    
		
     if(!empty($key_qury) && $key_qury!='' && $key_qury !=NULL ){
			$keyQuery_companys= " AND MATCH (  bs.group_name, bs.slot_name , bs.slot_details, A.companyname,  A.companytitle  ) AGAINST ( :key_qury    IN BOOLEAN MODE )  " ;		
			$keyQuery_promo=" AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
			$keyQuery_events=" AND   MATCH (  EV.title ) AGAINST ( :key_qury    IN BOOLEAN MODE )";
			$keyQuery_products=" AND MATCH (  M.title, M.offer_description ) AGAINST ( :key_qury    IN BOOLEAN MODE )  ";
			$keyQuery_classified=" AND  MATCH (  CLF.title ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
		}
        
    $cat1Layer=(!empty($category) && $category!='1' && $div_id !=NULL ) ? " AND pc.class_id_fk=1  AND pc.value={$category} " : "";		
	$cat2Layer= ($category2) ?	" AND pc.value_2={$category2} " : "";
    
	 
     $morequery= (!empty($per_page) || isset($offset)) ? 	"LIMIT {$per_page} OFFSET {$offset}" : "";
	 $time_instamp= time();
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_day= date("Y-m-d 00:00:00", strtotime("+1 days"));
				
	$tottal_array_query_start = "SELECT *	FROM (";
		
	$sales=" SELECT M.product_id AS product_id, M.end_date AS created, {$soterSelecter_product} CONCAT(  M.`photo`,'/[PRO]') AS photo,
					M.lat AS lat, M.lng AS lng, M.title AS title, M.offer_description AS description, M.offer_price AS offer_price,
					M.original_price AS original_price, M.sale_type AS main_time
					FROM products M
					LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
					{$soterJointer_product} {$colorJointer_product} 
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					WHERE  M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1  {$keyQuery_products}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_product} {$colorFilter_products}
					GROUP BY product_id  ";
		$union="	UNION ";
	
	$events="SELECT EV.appointment_id AS product_id,  EV.created AS created,  {$soterSelecter_events}   CONCAT(  EV.`title_img`,'/[EV]') AS photo,
					IF( EV.web_address='' , EV.lat, EV.web_address) AS lat, IF( EV.web_address='' , EV.lng, EV.web_address) AS lng, 
					EV.title AS title,  EV.basic_description AS description, EV.view_count AS offer_price, NULL AS original_price,
					EVR.ev_final AS main_time
					FROM appointment AS EV
					RIGHT JOIN event_repeater EVR ON EVR.app_id_fk=EV.`appointment_id`
						Join(
							SELECT DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
							FROM ints_multi i1   JOIN ints_multi i2
							WHERE (DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
						  ) A
					{$soterJointer_events}
					WHERE MOD(DATEDIFF(EVR.ev_start, A.ShowDate), EVR.repeater)=0
						AND A.ShowDate>=EVR.ev_start
						AND EVR.ev_final>=A.ShowDate 
						AND EV.ticketer>0
						{$keyQuery_events}
					{$soterFilter_events}
				";

	$bookings="SELECT CONCAT(A.companyname,'/booking/',bs.`slot_id`) AS product_id, ADDTIME(B.ShowDate, bs.time_end) AS created,
					{$soterSelecter_company} IF( bs.uploads!='' , CONCAT( bs.uploads,'/[COM]'), CONCAT( A.`company_logo`,'/[COM]')) AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng, CONCAT( bs.group_name,' ', bs.slot_name, ' booking from ',
					 IF( A.companytitle!='' ,A.companytitle, A.companyname)) AS title,  A.aboutcompany AS description,  
					 IF( bspl.spcl_price IS NULL , bs.slot_price, bspl.spcl_price) AS offer_price, 
						NULL AS original_price,IF( A.booking_type='long', bs.`slot_id`,'') AS main_time
					FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$full_date}')
							  ) B
					INNER JOIN slot_timer st ON st.`slot_id_fk` = bs.`slot_id` 
										AND  MOD(DATEDIFF(st.start_date, B.ShowDate), st.repeater)=0
										AND ABS(MOD(DATEDIFF(st.start_date, B.ShowDate), 7)) < st.repeater_2
										AND B.ShowDate>=st.start_date
										AND st.end_date>=B.ShowDate 
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					{$soterJointer_company}  {$colorJointer_company}
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN website AS W ON A.company_id = W.company_id
					LEFT JOIN booking_slots_spcl bspl ON bspl.`slot_id_fk` = bs.`slot_id` AND DAYNAME( B.ShowDate ) = bspl.spcl_weeek_day
					WHERE A.active>0  AND bs.approve>0
						{$keyQuery_companys}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_company} {$colorFilter_companys}
					GROUP BY product_id ";

	
	$promotion ="SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS product_id,
					ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)) AS created,
					{$soterSelecter_promo} CONCAT( pm.uploads,'/[PRM]') AS photo,
					 A.lat AS lat,  A.lng AS lng, IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) AS title,
						pm.details AS description,  
					 IF( pm_sp.spcl_percentage IS NULL , pm.percetage, pm_sp.spcl_percentage) AS offer_price, 
						NULL AS original_price, CONCAT(pm.pm_id,'/',A.location)  AS main_time
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
										AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
										AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
										AND B.ShowDate>=pm_t.start_date
										AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					{$soterJointer_promo} 
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end) <> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
						{$keyQuery_promo}
					{$cat1Layer} {$cat2Layer}  {$soterFilter_promo}
					GROUP BY main_time ";

	
	$tottal_array_query_end =" 	)derivedTable {$soterOrder}{$soterOrder_extra} {$morequery} ";
			$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){

	// $guery_final =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
		// $tottal_array_query_start.$sales.$union.$classifieds.$union.$bookings.$union.$clouds.$tottal_array_query_end 
					// :
		// $tottal_array_query_start.$sales.$union.$classifieds.$union.$bookings.$union.$events.$union.$clouds.$tottal_array_query_end;
			//<--  Hided classifieds and clouds
	$guery_final =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
		$tottal_array_query_start.$sales.$union.$bookings.$union.$promotion.$tottal_array_query_end 
					:
		$tottal_array_query_start.$sales.$union.$bookings.$union.$events.$union.$promotion.$tottal_array_query_end;

	}else if (strpos($filter, 'all')=== false){
			$section_arr =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
							array('sales', 'bookings', 'promotion') 	//<----requrired filterd datas array....
										:
							array('sales', 'bookings', 'events', 'clouds', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.$tottal_array_query_end;		
	}
		$param_array=array(':key_qury' => $key_qury );  
		// echo $guery_final;
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		return !empty($totttal_array) ? ($totttal_array) : false;
 }
 
 // finding total SEarch for all related 
	public static function index_feed_Records_total($key_qury="", $filter, $soter, $lat, $lng, $color_id, $div_id, $category, $category2) {
      $database = new Database;
		$tottal_array = '';
  	// More Button
		$data='';
       $morequery="";
		$colorJointer_classified= "" ;		
		$colorJointer_product= "" ;		
		$colorJointer_company= "" ;		
		$colorJointer_events= "" ;		
		$colorJointer_clouds= "" ;		
		$colorJointer_peoples= "" ;		
			$colorFilter_companys= "" ;		
			$colorFilter_events=" ";
			$colorFilter_clouds=" ";
			$colorFilter_peoples=" ";
			$colorFilter_products=" ";
			$colorFilter_classified=" ";
		$keyQuery_companys= "" ;		
		$keyQuery_clouds=" ";
		$keyQuery_events=" ";
		$keyQuery_products=" ";
		$keyQuery_classified=" ";
		$keyQuery_promo=" ";
			  $soterOrder= "" ;	

     if(!empty($color_id) && $color_id!='' && $color_id !=NULL ){
				$colorJointer_product= " LEFT JOIN classify_product_new pc2 ON pc2.product_id_fk= M.product_id  " ;		
				$colorJointer_company= " LEFT JOIN classify_company_new pc2 ON pc2.company_id_fk= A.company_id " ;		
				$colorJointer_bookings= " LEFT JOIN classify_cloud_new pc2 ON pc2.cloud_id_fk= CLD.cloud_id " ;		

				$colorFilter_products=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_bookings=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_companys=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
				$colorFilter_clouds=" AND pc2.class_id_fk=6  AND pc2.value={$color_id} ";	
		}    
		
     if(!empty($key_qury) && $key_qury!='' && $key_qury !=NULL ){
			$keyQuery_companys= " AND MATCH (  bs.group_name, bs.slot_name , bs.slot_details, A.companyname,  A.companytitle  ) AGAINST ( :key_qury    IN BOOLEAN MODE )  " ;		
			$keyQuery_clouds=" AND  MATCH (  CLD.cloudname ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
			$keyQuery_events="  AND  MATCH (  EV.title ) AGAINST ( :key_qury    IN BOOLEAN MODE )";
			$keyQuery_products=" AND MATCH (  M.title, M.offer_description ) AGAINST ( :key_qury    IN BOOLEAN MODE )  ";
			$keyQuery_promo=" AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
		}
    
    $cat1Layer=(!empty($category) && $category!='1' && $div_id !=NULL ) ? " AND pc.class_id_fk=1  AND pc.value={$category} " : "";		
	$cat2Layer= ($category2) ?	" AND pc.value_2={$category2} " : "";
    
	 
     $morequery= (!empty($per_page) || isset($offset)) ? 	"LIMIT {$per_page} OFFSET {$offset}" : "";
	 $time_instamp= time();
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_day= date("Y-m-d 00:00:00", strtotime("+1 days"));
			

	 $tottal_array_query_start = "SELECT COUNT(*)	FROM (";

	$sales=" SELECT M.product_id AS product_id FROM products M
				LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
				{$colorJointer_product} 
				LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
				LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
				LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
				LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
				WHERE M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1  {$keyQuery_products}
				{$cat1Layer} {$cat2Layer} {$colorFilter_products}
				GROUP BY product_id  ";
		$union="	UNION ";
	$events="SELECT EV.appointment_id AS product_id FROM appointment AS EV
					RIGHT JOIN event_repeater EVR ON EVR.app_id_fk=EV.`appointment_id`
						Join(
							SELECT DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
							FROM ints_multi i1   JOIN ints_multi i2
							WHERE (DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
						  ) A
					WHERE MOD(DATEDIFF(EVR.ev_start, A.ShowDate), EVR.repeater)=0
						AND A.ShowDate>=EVR.ev_start
						AND EVR.ev_final>=A.ShowDate 
						AND EV.ticketer>0
							{$keyQuery_events}
				";
	$bookings="SELECT bs.`slot_id` AS product_id FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
						  JOIN( SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$full_date}')
							  )  B
					INNER JOIN slot_timer st ON st.`slot_id_fk` = bs.`slot_id` 
										AND  MOD(DATEDIFF(st.start_date, B.ShowDate), st.repeater)=0
										AND ABS(MOD(DATEDIFF(st.start_date, B.ShowDate), 7)) < st.repeater_2
										AND B.ShowDate>=st.start_date
										AND st.end_date>=B.ShowDate 
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					  {$colorJointer_company}
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN website AS W ON A.company_id = W.company_id
					WHERE A.active>0  AND bs.approve>0
					{$keyQuery_companys}
				{$cat1Layer} {$cat2Layer}  {$colorFilter_companys}
				GROUP BY product_id  ";
		
	
	$promotion ="SELECT CONCAT(A.location,' ',pm.`pm_id`) AS product_id FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
										AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
										AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
										AND B.ShowDate>=pm_t.start_date
										AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
						{$keyQuery_promo}
					{$cat1Layer} {$cat2Layer}";
	
$tottal_array_query_end =" 	)derivedTable";
	$guery_final ='';$i=1;
if (strpos($filter, 'all') !== false){

	// $guery_final =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
		// $tottal_array_query_start.$sales.$union.$classifieds.$union.$companys.$union.$clouds.$tottal_array_query_end 
					// :
		// $tottal_array_query_start.$sales.$union.$classifieds.$union.$companys.$union.$events.$union.$clouds.$tottal_array_query_end;
			//<--  Hided classifieds and clouds
	$guery_final =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
		$tottal_array_query_start.$sales.$union.$bookings.$union.$promotion.$tottal_array_query_end 
					:
		$tottal_array_query_start.$sales.$union.$bookings.$union.$events.$union.$promotion.$tottal_array_query_end;

}else if (strpos($filter, 'all')=== false){
			$section_arr =(!empty($category) && $category!='1' && $div_id !=NULL ) ? 
							array('sales', 'bookings', 'promotion') 	//<----requrired filterd datas array....
										:
							array('sales', 'bookings', 'events', 'promotion');	//<----requrired filterd datas array....
			$filter_arr=explode('-',$filter);											//<-----given filterdatas array
			$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
				foreach ($result_arr as $result){
						if(($key = array_search($result, $section_arr)) !== false) {
										unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
							}
				}
			$guery_final.= $tottal_array_query_start;
			foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
						$guery_final.= $$section;
							if($i<count($section_arr)){
								$guery_final.= $union;					//<---aad unnion variables
							}
					$i++;
				}
		$guery_final= $guery_final.$tottal_array_query_end;		
}
		$param_array=array(':key_qury' => $key_qury );  
	$data = $database->fetch_assoc_cached($guery_final, $param_array );
	return !empty($data) ? $data['COUNT(*)'] : false;
 }
   	
    //Category Search For facested king search
	public static function index_feed_Records_categorykey($key_qury, $filter,$color_id, $div_id, $category,$category2) {
      $database = new Database;
		   $tottal_array = '';
		   $data='';
		   $cat1Layer="";
		    $cate_2_select =''; $cate_2_jointer ='';$cate_2_group='';
		   // $color_dep_both='AND (pc.class_id_fk=1 OR pc.class_id_fk=6 )';
		   $color_dep_both='AND (pc.class_id_fk=1)';
	$keyQuery_companys= "" ;		
	$keyQuery_clouds=" ";
	$keyQuery_events=" ";
	$keyQuery_products=" ";
	$keyQuery_classified=" ";
	$keyQuery_promo=" ";
	$cat2Layer=($category2) ? " AND pc.value_2='{$category2}' " : "" ;
	
	if($category!=NULL){
			$cat1Layer=" AND pc.value='{$category}' ";		
									$cate_2_select =' IF(pc.class_id_fk=1,  dsl.text, 0) as text_print2, 
													IF(pc.class_id_fk=1,  dsl.value, 0) as value_print2, ';
								$cate_2_jointer =' INNER JOIN department_second_layer dsl ON dsl.cat2_id = pc.value_2 ';
								$cate_2_group= 'value_print2, ';
		}
  
	if(!empty($key_qury) && $key_qury!='' && $key_qury !=NULL ){
			$keyQuery_companys= " AND MATCH ( bs.group_name, bs.slot_name , bs.slot_details, p.companyname,  p.companytitle ) AGAINST ( :key_qury    IN BOOLEAN MODE )  " ;		
			$keyQuery_clouds=" AND  MATCH (  p.cloudname ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
			$keyQuery_events="  AND  MATCH (  p.title ) AGAINST ( :key_qury    IN BOOLEAN MODE )";
			$keyQuery_products=" AND MATCH (  p.title , p.offer_description) AGAINST ( :key_qury    IN BOOLEAN MODE )  ";
			$keyQuery_promo=" AND  MATCH (  pm.title, pm.details ) AGAINST ( :key_qury    IN BOOLEAN MODE ) ";
		}
		$time_instamp= time();
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_date= date("Y-m-d 00:00:00", strtotime("+1 days"));

		
			$tottal_array_query_start = " SELECT *, count(totals) as total 
									FROM ( ";
			$sales	  =" (   SELECT   cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
										IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
									FROM classify_product_new pc 
									INNER JOIN products p ON p.product_id = pc.product_id_fk
									INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
											{$cate_2_jointer}
									INNER JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
									INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
									WHERE p.end_date > '{$time_date}' AND p.approve>0 AND p.done=1 {$keyQuery_products}
											{$color_dep_both}
									{$cat1Layer} {$cat2Layer} ) ";
			$union			  =" UNION ALL ";
			$bookings	  =" (   SELECT cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
										IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
									FROM classify_company_new pc 
									INNER JOIN company p ON p.company_id = pc.company_id_fk
									INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
											{$cate_2_jointer}
									INNER JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
									INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
									INNER JOIN booking_slots AS bs ON  p.company_id = bs.comp_id_fk
									  JOIN( SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
											FROM ints_multi i1   JOIN ints_multi i2
											WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$full_date}')
										  )  B
									INNER JOIN slot_timer st ON st.`slot_id_fk` = bs.`slot_id` 
										AND  MOD(DATEDIFF(st.start_date, B.ShowDate), st.repeater)=0
										AND ABS(MOD(DATEDIFF(st.start_date, B.ShowDate), 7)) < st.repeater_2
										AND B.ShowDate>=st.start_date
										AND st.end_date>=B.ShowDate 									
									WHERE  p.active>0   AND bs.approve>0 {$keyQuery_companys}
									{$color_dep_both}
									{$cat1Layer} {$cat2Layer} ) ";
	
	$promotion ="SELECT  cl.name, cl.text, IF(pc.class_id_fk=1,  dfl.text, cfl.text) as text_print, 
											{$cate_2_select}
					IF(pc.class_id_fk=1,  dfl.value, cfl.value) as value_print, pc.value as totals
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
					INNER JOIN classify_promotion_new pc ON  pc.pm_id_fk = pm.pm_id
					INNER JOIN department_first_layer dfl ON dfl.cat1_id = pc.value
								{$cate_2_jointer}
					LEFT JOIN color_first_layer cfl ON cfl.cat1_id = pc.value
					INNER JOIN classify_list cl ON cl.cl_list = pc.class_id_fk
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_date}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
							AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
							AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
							AND B.ShowDate>=pm_t.start_date
							AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0  AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)
						<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND  ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
					{$keyQuery_promo}  {$color_dep_both}
				{$cat1Layer} {$cat2Layer}     GROUP BY  CONCAT(A.location,' ',pm.`pm_id`) ";
				
			$tottal_array_query_end="	) data  GROUP BY {$cate_2_group} value_print ORDER BY name DESC";
						
	$guery_final ='';$i=1;
	if (strpos($filter, 'all') !== false){
			// $guery_final =$tottal_array_query_start.$sales.$union.$classifieds.$union.$bookings.$union.$clouds.$tottal_array_query_end;
						//<--  Hided classifieds and clouds
		$guery_final =$tottal_array_query_start.$sales.$union.$bookings.$union.$promotion.$tottal_array_query_end;
	}else if (strpos($filter, 'all')=== false){
				$section_arr =array('sales',  'bookings',  'promotion');	//<----requrired filterd datas array....
				$filter_arr=explode('-',$filter);											//<-----given filterdatas array
				$result_arr = array_diff($section_arr, $filter_arr);						//<----compaire the difference
					foreach ($result_arr as $result){
							if(($key = array_search($result, $section_arr)) !== false) {
											unset($section_arr[$key]);						//<----removing the unwanted valuea after compair
								}
					}
				$guery_final.= $tottal_array_query_start;
				foreach ($section_arr as $section){							//<----srot the each values as variable and then make the statement variable
							$guery_final.= $$section;
								if($i<count($section_arr)){
									$guery_final.= $union;					//<---aad unnion variables
								}
						$i++;
					}
			$guery_final= $guery_final.$tottal_array_query_end;		
	}
	
		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		
		return !empty($totttal_array) ? ($totttal_array) : false;


 }
  	

	public static function glob_ads_finder_Records($per_page=1, $key_qury="", $lat=0, $lng=0) {
      $database = new Database;
			$soterSelecter_company= "";
			$soterSelecter_product= "";
			$soterSelecter_events= "";
			$soterOrder= "";
	 $time_date= date("Y-m-d H:i:s", time());
	 $full_date= date("Y-m-d 00:00:00", time());
	 $next_day= date("Y-m-d 00:00:00", strtotime("+1 days"));
		
		if($lat && $lng){
				$soterSelecter_company= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( A.company_lat ) ) 
									* cos( radians( A.company_lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( A.company_lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_product= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( M.lat ) ) 
									* cos( radians( M.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( M.lat ) ) ) ) AS sorter_order ," ;		
				
				$soterSelecter_events= " ( 3959 * acos( cos( radians(  {$lat}   ) )
									* cos( radians( EV.lat ) ) 
									* cos( radians( EV.lng ) - radians(  {$lng}   ) ) + sin( radians(  {$lat}  ) ) 
									* sin( radians( EV.lat ) ) ) ) AS sorter_order ," ;		
				$soterOrder= " ORDER BY sorter_order " ;		
		}
			
				
	$tottal_array_query_start = "SELECT *	FROM (";
		
	$sales=" SELECT M.product_id AS product_id, M.end_date AS created, CONCAT(  M.`photo`,'/[PRO]') AS photo,
					M.lat AS lat, M.lng AS lng, M.title AS title, M.offer_description AS description, M.offer_price AS offer_price,
					M.original_price AS original_price, M.end_date AS main_time
					FROM products M
					LEFT JOIN classify_product_new pc ON M.product_id = pc.product_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer F ON c.cat1_id = pc.value
					WHERE  M.end_date > '{$time_date}' AND M.approve>0 AND M.done=1  AND  M.created > '2014-08-01 00:00:00'
					GROUP BY product_id  ";
		$union="	UNION ";
	
	$events="SELECT EV.appointment_id AS product_id, ADDTIME( EVR.ev_final,DATE_FORMAT(FROM_UNIXTIME(EV.time_stop) , '%H:%i:%s'))  AS created, CONCAT(  EV.`title_img`,'/[EV]') AS photo,
					IF( EV.web_address='' , EV.lat, EV.web_address) AS lat, IF( EV.web_address='' , EV.lng, EV.web_address) AS lng, 
					EV.title AS title,  EV.basic_description AS description, EV.view_count AS offer_price, NULL AS original_price,
					EVR.ev_final AS main_time
					FROM appointment AS EV
					RIGHT JOIN event_repeater EVR ON EVR.app_id_fk=EV.`appointment_id`
						Join(
							SELECT DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
							FROM ints_multi i1   JOIN ints_multi i2
							WHERE (DATE_ADD('{$time_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$time_date}')
						  ) A
					WHERE MOD(DATEDIFF(EVR.ev_start, A.ShowDate), EVR.repeater)=0
						AND A.ShowDate>=EVR.ev_start
						AND EVR.ev_final>=A.ShowDate 
						AND EV.ticketer>0
						
				";

	$bookings="SELECT CONCAT(A.companyname,'/booking/',bs.`slot_id`) AS product_id, ADDTIME(B.ShowDate, 
					IF( bspl.spcl_time_end IS NULL , bs.time_end, bspl.spcl_time_end)) AS created,
					IF( bs.uploads!='' , CONCAT( bs.uploads,'/[COM]'), CONCAT( A.`company_logo`,'/[COM]')) AS photo,
					 A.company_lat AS lat,  A.company_lng AS lng, CONCAT( bs.group_name,' ', bs.slot_name, ' booking from ',
					 IF( A.companytitle!='' ,A.companytitle, A.companyname)) AS title,  A.aboutcompany AS description,  
					 IF( bspl.spcl_price IS NULL , bs.slot_price, bspl.spcl_price) AS offer_price, 
						NULL AS original_price,IF( A.booking_type='long', bs.`slot_id`,'') AS main_time
					FROM company A
					INNER JOIN booking_slots AS bs ON  A.company_id = bs.comp_id_fk
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$full_date}')
							  ) B
					INNER JOIN slot_timer st ON st.`slot_id_fk` = bs.`slot_id` 
										AND  MOD(DATEDIFF(st.start_date, B.ShowDate), st.repeater)=0
										AND ABS(MOD(DATEDIFF(st.start_date, B.ShowDate), 7)) < st.repeater_2
										AND B.ShowDate>=st.start_date
										AND st.end_date>=B.ShowDate 
					LEFT JOIN classify_company_new pc ON A.company_id = pc.company_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN website AS W ON A.company_id = W.company_id
					LEFT JOIN booking_slots_spcl bspl ON bspl.`slot_id_fk` = bs.`slot_id` AND DAYNAME( B.ShowDate ) = bspl.spcl_weeek_day
					WHERE A.active>0  AND bs.approve>0
					GROUP BY product_id ";

	
	$promotion ="SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS product_id,
					ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)) AS created,
					CONCAT( pm.uploads,'/[PRM]') AS photo,
					 A.lat AS lat,  A.lng AS lng, IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) AS title,
						pm.details AS description,  
					 IF( pm_sp.spcl_percentage IS NULL , pm.percetage, pm_sp.spcl_percentage) AS offer_price, 
						NULL AS original_price, CONCAT(pm.pm_id,'/',A.location)  AS main_time
					FROM promotion_address A
					INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
						Join( 	SELECT DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
								FROM ints_multi i1   JOIN ints_multi i2
								WHERE (DATE_ADD('{$full_date}',INTERVAL (i2.i*10+i1.i) DAY)<='{$next_day}')
							  ) B
					INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
										AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
										AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
										AND B.ShowDate>=pm_t.start_date
										AND pm_t.end_date>=B.ShowDate 
					LEFT JOIN classify_promotion_new pc ON pm.pm_id = pc.pm_id_fk
					LEFT JOIN classify_list CL ON CL.cl_list = pc.class_id_fk
					LEFT JOIN department_first_layer c ON c.cat1_id = pc.value
					LEFT JOIN color_first_layer AS L ON c.cat1_id = pc.value
					LEFT JOIN manufacture_first_layer AS F ON c.cat1_id = pc.value
					LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
					WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end) <> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start)) 
					AND  ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
					AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
					GROUP BY main_time ";

	$tottal_array_query_end =" 	)derivedTable {$soterOrder} LIMIT {$per_page} ";

	$guery_final = $tottal_array_query_start.$sales.$union.$bookings.$union.$events.$union.$promotion.$tottal_array_query_end;

		$param_array=array(':key_qury' => $key_qury );  
		$totttal_array=$database->fetch_array_cached($guery_final, $param_array);	 
		return !empty($totttal_array) ? ($totttal_array) : false;
	}
 


}

?>
