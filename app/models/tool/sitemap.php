<?php
namespace Models\Tool;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

// If it's going to need the database, then it's
// probably smart to require it before we start.

class Sitemap extends DatabaseObject {
	
	public static function find_recent_for_sitemap($per_page, $offset) {
		$database = new Database;
			$start_date= date("Y-m-d 00:00:00", time());
			$end_date= date("Y-m-d 00:00:00", strtotime("+1 week"));
			$time_date= date("Y-m-d H:i:s", time());
		 $morequery= (!empty($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "" ;
				$query="
						( SELECT company_id AS id, companyname AS link_title, created AS created, 'webpage_view2' AS seperator
							FROM company  WHERE  active>0 AND created>'2014-07-01 00:00:00'
						)
					UNION
						( SELECT EV.appointment_id AS id, EV.title AS link_title,  FROM_UNIXTIME(EV.created) AS created, 'event_view' AS seperator
							FROM appointment EV 
							INNER JOIN event_repeater EVR ON EVR.app_id_fk=EV.`appointment_id`
							WHERE ticketer>0 AND EVR.ev_final>='{$time_date}' 
						)
					UNION
						( SELECT auid AS id, authorname AS link_title,  created AS created, 'profile' AS seperator
							FROM authors  WHERE role>1 AND created>0
						)
					UNION
						( SELECT product_id AS id, title AS link_title,  created AS created, 'product_view' AS seperator
							FROM products WHERE sale_type='normal'  AND approve>0 AND done=1 AND end_date > '{$time_date}'
							AND created>'2014-06-01 00:00:00'
						)
					UNION
						( 	SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS id,
								CONCAT (DAYNAME( B.ShowDate ),' ', IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title) ) AS link_title,
									pm.created AS created, 'promotion' AS seperator 
								FROM promotion_address A
								INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
								Join( 	SELECT DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
										FROM ints_multi i1   JOIN ints_multi i2
										WHERE (DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY)<'{$end_date}')
									  ) B
							INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
												AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
												AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
												AND B.ShowDate>=pm_t.start_date
												AND pm_t.end_date>=B.ShowDate 
							LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
							WHERE pm.approve>0
						)

						ORDER BY created DESC {$morequery}";
			$result_array = $database->query($query);
		 
			while($row=$database->fetch_object($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;

	}
	
	public static function find_recent_for_sitemap_count() {
		  $database = new Database;
			$start_date= date("Y-m-d 00:00:00", time());
			$end_date= date("Y-m-d 00:00:00", strtotime("+1 week"));
			$time_date= date("Y-m-d H:i:s", time());

	  $query="SELECT SUM(name) AS Total FROM (
					( SELECT  COUNT(*) AS name	FROM company WHERE active>0  AND created>'2014-07-01 00:00:00'
						)
					UNION
						( SELECT  COUNT(*) AS name FROM appointment EV 
							INNER JOIN event_repeater EVR ON EVR.app_id_fk=EV.`appointment_id`
							WHERE ticketer>0 AND EVR.ev_final>='{$time_date}' 
						)
					UNION
						( SELECT  COUNT(auid) AS name FROM authors WHERE role>1 AND created>0
						)
					UNION
						( SELECT COUNT(*) AS name FROM products WHERE sale_type='normal'  AND approve>0 AND done=1 AND end_date >='{$time_date}'
							AND created>'2014-06-01 00:00:00'
						)
					UNION
					(
						SELECT COUNT(*) AS name FROM promotion_address A
							INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
								Join( 	SELECT DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
										FROM ints_multi i1   JOIN ints_multi i2
										WHERE (DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY)<'{$end_date}')
									  ) B
							INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
												AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
												AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
												AND B.ShowDate>=pm_t.start_date
												AND pm_t.end_date>=B.ShowDate 
							WHERE pm.approve>0 
					)

					)AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
	}
	 
	
	public static function find_view_list_of_sitemap($per_page, $offset,  $day_name="") {
		$database = new Database;
			$start_date= date("Y-m-d 00:00:00", time());
			$end_date= date("Y-m-d 00:00:00", strtotime("+1 week"));
			$time_date= date("Y-m-d H:i:s", time());
		 $morequery= (!empty($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "" ;
				$query="
						( SELECT product_id AS id, title AS link_title,  created AS created, 'product_view' AS seperator, '0' AS main_time
							FROM products WHERE sale_type='normal'  AND approve>0 AND done=1 AND end_date > '{$time_date}'
						)
					UNION
						( 	SELECT CONCAT(pm.pm_id,'/',DAYNAME( B.ShowDate ),'/',A.location) AS id,
								CONCAT (DAYNAME( B.ShowDate ),' ', IF( pm_sp.spcl_title <>'', pm_sp.spcl_title, pm.title)) AS link_title,
									pm.created AS created, 'promotion' AS seperator, CONCAT(pm.pm_id,'/',A.location) AS main_time
								FROM promotion_address A
								INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
								Join( 	SELECT DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
										FROM ints_multi i1   JOIN ints_multi i2
										WHERE (DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY)<'{$end_date}')
									  ) B
							INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
												AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
												AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
												AND B.ShowDate>=pm_t.start_date
												AND pm_t.end_date>=B.ShowDate 
							LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
							WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)
														<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))
								AND ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
									AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
									AND DAYNAME( B.ShowDate ) = '{$day_name}'
						)

						ORDER BY created DESC {$morequery}";
			$result_array = $database->query($query);
		 
			while($row=$database->fetch_object($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;

	}
	
	public static function find_view_list_of_sitemap_count($day_name="") {
		  $database = new Database;
			$start_date= date("Y-m-d 00:00:00", time());
			$end_date= date("Y-m-d 00:00:00", strtotime("+1 week"));
			$time_date= date("Y-m-d H:i:s", time());

	  $query="SELECT SUM(name) AS Total FROM (
						( SELECT COUNT(*) AS name FROM products WHERE sale_type='normal'  AND approve>0 AND done=1 AND end_date > '{$time_date}'
						)
					UNION
					(
						SELECT COUNT(*) AS name FROM promotion_address A
							INNER JOIN promotion AS pm ON  A.pm_id_fk = pm.pm_id
								Join( 	SELECT DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY) as Showdate
										FROM ints_multi i1   JOIN ints_multi i2
										WHERE (DATE_ADD('{$start_date}',INTERVAL (i2.i*10+i1.i) DAY)<'{$end_date}')
									  ) B
							INNER JOIN promotion_timer pm_t ON pm_t.`pm_id_fk` = pm.`pm_id` 
									AND  MOD(DATEDIFF(pm_t.start_date, B.ShowDate), pm_t.repeater)=0
									AND ABS(MOD(DATEDIFF(pm_t.start_date, B.ShowDate), 7)) < pm_t.repeater_2
									AND B.ShowDate>=pm_t.start_date
									AND pm_t.end_date>=B.ShowDate 
							LEFT JOIN promotion_spcl pm_sp ON pm_sp.`pm_id_fk` = pm.`pm_id` AND DAYNAME( B.ShowDate ) = pm_sp.spcl_weeek_day
							WHERE pm.approve>0 AND( IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end)
														<> IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))
								AND ADDTIME(B.ShowDate, IF( pm_sp.spcl_time_end IS NOT NULL, pm_sp.spcl_time_end, pm.time_end))>'{$time_date}'
									AND ADDTIME(pm_t.start_date, IF( pm_sp.spcl_time_start IS NOT NULL, pm_sp.spcl_time_start, pm.time_start))<'{$time_date}'
										AND DAYNAME( B.ShowDate ) = '{$day_name}'
					)

					)AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
	}
	 

}

?>