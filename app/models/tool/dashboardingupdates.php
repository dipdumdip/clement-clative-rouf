<?php
namespace Tool;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;
class DashboardingUpdates extends DatabaseObject {
	
 	 //Friends  Updates
	public static function update_friends($author_id_fk, $per_page, $offset, $privacy) {
		// More Button
		$morequery= (!empty($per_page) && isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "";	
		$privacy_st=(!empty($privacy) && isset($privacy)) ? "AND M.privacy = 1" : "" ;

		$query="SELECT *	FROM (
				SELECT  M.updt_id AS updt_id, M.privacy AS privacy, CONCAT( M.updt_id, '-updt') AS msg_id, CONCAT( U.authorname, '-/#!author') AS name, 
						CONCAT( M.author_id_fk,'#!updt') AS owner_id, M.updates AS updates, (M.created) AS created, M.uploads AS uploads,
						CONCAT(' Updates on User page of ', U.authorname) AS info, U.authorname AS link
						FROM updatez M, friends F, authors U
						WHERE M.profile_id_fk = U.auid
						{$privacy_st}
						AND M.profile_id_fk = F.friend_two
						AND F.friend_one ='{$author_id_fk}'
				UNION
				SELECT  M.updt_id AS updt_id, M.privacy AS privacy, CONCAT( M.updt_id, '-updt') AS msg_id, CONCAT( C2.companyname, '-/#!company') AS name,
						CONCAT( C2.author_id_fk,'#!updt') AS owner_id, M.updates AS updates, (M.created) AS created, M.uploads AS uploads
						,CONCAT(' Upadtes on Company page of ',  C2.companyname) AS info, C2.companyname AS link
						FROM updatez M, company C2, companylikes CL2
						WHERE M.company_id_fk = C2.company_id
						{$privacy_st}
						AND M.company_id_fk = CL2.company_id
						AND CL2.author_id ='{$author_id_fk}'


						)derivedTable ORDER BY created DESC {$morequery} ";
	
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_array($result_holder) : false;
	}

 	// Total Updates
 	public static function update_friends_total($author_id_fk, $privacy){
		
		$privacy_st=(!empty($privacy) && isset($privacy)) ? "AND M.privacy = 1" : "" ;

		$query = "SELECT SUM(id) AS Total 	FROM (
					(SELECT  COUNT(M.updt_id) as id FROM updatez M, friends F
						WHERE M.profile_id_fk = F.friend_two
						{$privacy_str}
						AND F.friend_one ={$author_id_fk} )
				UNION

				(SELECT  COUNT(M.updt_id) as id FROM updatez M, companylikes CL2
						WHERE M.company_id_fk = CL2.company_id
						{$privacy_str}
						AND CL2.author_id ={$author_id_fk} )
						)AS T";  

		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
	}

	public function update_friends_refresh($author_id_fk, $per_page, $offset, $privacy, $time) {
		// More Button
		$data='';
		$morequery="";
		$privacy_st="";
				$time_str = date("Y-m-d H:i:s", $time);

		if(!empty($per_page) && isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
		if(!empty($privacy) && isset($privacy)){		$privacy_st="AND M.privacy = 1";		}


		$tottal_array = $database->query("SELECT *	FROM (
				SELECT  M.updt_id AS updt_id, M.privacy AS privacy, CONCAT( M.updt_id, '-updt') AS msg_id, CONCAT( U.authorname, '-/#!author') AS name, 
						CONCAT( M.author_id_fk,'#!updt') AS owner_id, M.updates AS updates, M.created AS created, M.uploads AS uploads,
						CONCAT(' Updates on User page of ', U.authorname) AS info, U.authorname AS link
						FROM updatez M, friends F, authors U
						WHERE M.profile_id_fk = U.auid
						{$privacy_st}
						AND M.created >= '{$time_str}'
						AND M.profile_id_fk = F.friend_two
						AND F.friend_one ='{$author_id_fk}'
				UNION
				SELECT  M.updt_id AS updt_id, M.privacy AS privacy, CONCAT( M.updt_id, '-updt') AS msg_id, CONCAT( C2.companyname, '-/#!company') AS name,
						CONCAT( C2.author_id_fk,'#!updt') AS owner_id, M.updates AS updates, M.created AS created, M.uploads AS uploads
						,CONCAT(' Upadtes on Company page of ',  C2.companyname) AS info, C2.companyname AS link
						FROM updatez M, company C2, companylikes CL2
						WHERE M.company_id_fk = C2.company_id
						{$privacy_st}
						AND M.created >= '{$time_str}'
						AND M.company_id_fk = CL2.company_id
						AND CL2.author_id ='{$author_id_fk}'
					
						)derivedTable ORDER BY created DESC {$morequery} ");
							
		$result_holder=$this->db->query($query);
		return  ($result_holder->num_rows() > 0) ? $result_holder->result_array() : false;
	}	

	// Total Updates
	public function update_friends_refresh_total($author_id_fk, $privacy, $time){
		$privacy_str="";
		$time_str = date("Y-m-d H:i:s", $time);
		 if(!empty($privacy) && isset($privacy)){		$privacy_str="AND M.privacy = 1";		}

		$query = "SELECT SUM(id) AS Total 	FROM (
					(SELECT  COUNT(M.updt_id) as id FROM updatez M, friends F
						WHERE M.profile_id_fk = F.friend_two
						AND M.profile_id_fk <> {$author_id_fk}
						AND M.created >= '{$time_str}'
						{$privacy_str}
						AND M.privacy <> 0
						AND F.friend_one ={$author_id_fk} )
				UNION

				(SELECT  COUNT(M.updt_id) as id FROM updatez M, companylikes CL2
						WHERE M.company_id_fk = CL2.company_id
						AND M.created >= '{$time_str}' AND M.privacy <> 0
						{$privacy_str}
						AND CL2.author_id ={$author_id_fk} )
				 )AS T";  
				 
			$result_holder=$this->db->query($query);
		return  ($result_holder->num_rows() > 0) ? $result_holder->row()->Total : false;
	}

	public static function find_company_Cloud_for_users($author_id_fk, $per_page, $offset) {
		 $morequery=(!empty($per_page) || isset($offset)) ?	"LIMIT {$per_page} OFFSET {$offset}" : '';
		$database = new Database;
		 if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
				$query="
						( SELECT C1.company_logo AS image, C1.companyname AS name, C1.category AS category, C1.created AS created, 
							C1.company_id AS ID, C1.author_id_fk AS author_id, 'company' AS checker
							FROM company C1
							INNER JOIN website w ON C1.company_id=w.company_id
							WHERE C1.author_id_fk = '{$author_id_fk}'
						)
						ORDER BY created DESC {$morequery} ";
			$result_array = $database->query($query);
		 
			while($row=$database->fetch_object($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;

	}
	
	public static function find_attached_List_by_Author_id_count($author_id_fk) {
		  $database = new Database;
	  $query="SELECT SUM(name) AS Total FROM (
				( 	SELECT COUNT(*) AS name
							FROM companylikes 
							WHERE author_id = '{$author_id_fk}'
						)
					UNION
					( SELECT COUNT(*) AS name
							FROM friends 
							WHERE friend_one = '{$author_id_fk}' AND friend_two<> '{$author_id_fk}'
						)
					)AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
	}

	public static function find_attached_List_by_Author_id($author_id_fk, $per_page, $offset) {
		 $morequery=(!empty($per_page) || isset($offset)) ?	"LIMIT {$per_page} OFFSET {$offset}" : '';
		$database = new Database;
				 $query="
						( SELECT C1.company_logo AS image, C1.companyname AS name, C1.category AS category, C1.created AS created, 
							C1.company_id AS ID, C1.author_id_fk AS author_id, 'company' AS checker,  W.view_count AS view_count
							,(select count(*) from companylikes where company_id=CL.company_id) as like_count
							FROM companylikes CL 
							INNER JOIN company C1 on C1.company_id=CL.company_id
							INNER JOIN website W on W.company_id=CL.company_id
							WHERE CL.author_id = '{$author_id_fk}' 
						)
					UNION
						( SELECT P.photo AS image, A.authorname AS name, 0 AS category,  A.created AS created, 
							A.auid AS ID, P.author_id_fk AS author_id, 'author' AS checker, P.view_count AS view_count
							,(select count(*) from friends where friend_one=A.auid) as like_count
							FROM authors A
							INNER JOIN personal P on P.author_id_fk=A.auid
							INNER JOIN friends F on F.friend_two=A.auid
							WHERE F.friend_one = '{$author_id_fk}' AND F.friend_two <> '{$author_id_fk}'
						)
					ORDER BY created DESC {$morequery} ";
			$result_array = $database->query($query);
		 
			while($row=$database->fetch_object($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;

	}
	
	public static function find_company_Cloud_for_users_count($author_id_fk) {
		  $database = new Database;
	  $query="SELECT SUM(name) AS Total FROM (
				( 	SELECT COUNT(C1.companyname) AS name
							FROM company C1 
							INNER JOIN website w ON C1.company_id=w.company_id
							WHERE C1.author_id_fk = '{$author_id_fk}'
						)
					)AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
	}


		// function to get the Total Profile TimeLine datas
	public static function update_profile_timeline($author_id_fk, $per_page, $offset, $privacy) {
			$database = new Database;
			// More Button
			$data='';
			$morequery="";
			$privacy_st="";
			if(!empty($per_page) && isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
			if(!empty($privacy) && isset($privacy)){		$privacy_st="AND M.privacy = 1";		}
		
			$query="SELECT *	FROM (	SELECT * FROM(
						(SELECT CONCAT( M.updt_id, '-updt') AS msg_id, U1.authorname AS profile, 0 AS forumPolling,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates, 
											 (M.created) AS post_date, (M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM updatez as M INNER JOIN authors as U1
								ON  U1.auid = M.profile_id_fk
								WHERE  M.profile_id_fk = M.author_id_fk
											{$privacy_st}
								AND M.profile_id_fk ='{$author_id_fk}')
						UNION ALL
						(SELECT  CONCAT( M.updt_id, '-updt') AS msg_id, U2.authorname AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											(M.created) AS post_date, (M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM updatez as M INNER JOIN authors as U2
								ON  U2.auid = M.profile_id_fk 
								INNER JOIN authors as U1
								ON  U1.auid = M.author_id_fk
								WHERE M.profile_id_fk <> M.author_id_fk
									{$privacy_st}
								AND M.profile_id_fk ='{$author_id_fk}' )
						UNION ALL
						(SELECT    CONCAT( M.updt_id, '-updt') AS msg_id, U2.authorname AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											(M.created) AS post_date, (M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
							FROM updatez as M INNER JOIN authors as U1
							  ON U1.auid = M.author_id_fk 
							  INNER JOIN authors as U2
							  ON U2.auid = M.profile_id_fk
								WHERE M.profile_id_fk <> M.author_id_fk
											{$privacy_st}
								AND M.author_id_fk ='{$author_id_fk}' )
						UNION ALL
						(SELECT   CONCAT( M.updt_id, '-updt') AS msg_id, U3.authorname AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											(M.created) AS post_date, (Z.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
							FROM updatez as M 
							INNER JOIN commentz as Z ON Z.updt =M.updt_id
							INNER JOIN  authors as U3 ON  U3.auid = M.profile_id_fk
							INNER JOIN  authors as U2 ON  U2.auid = M.author_id_fk 
							INNER JOIN  authors as U1 ON  U1.auid = Z.author_id_fk 
							  WHERE  Z.author_id_fk ='{$author_id_fk}'
										{$privacy_st}
								)
						UNION ALL
						(SELECT   CONCAT( M.updt_id, '-updt') AS msg_id, U4.authorname AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											(M.created) AS post_date, (Z2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
							FROM updatez as M 
							INNER JOIN commentz as Z1 ON Z1.updt=M.updt_id
							INNER JOIN commentz_2 as Z2 ON Z2.updt_id_fk =Z1.cmnt_id
							INNER JOIN  authors as U4 ON  U4.auid = M.profile_id_fk
							INNER JOIN  authors as U3 ON  U3.auid = Z1.author_id_fk
							INNER JOIN  authors as U2 ON  U2.auid = M.author_id_fk 
							INNER JOIN  authors as U1 ON  U1.auid = Z2.author_id_fk 
							WHERE Z2.author_id_fk ='{$author_id_fk}'
											{$privacy_st} )
						UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id,  C.companyname AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM company_post as M INNER JOIN authors as U1 ON U1.auid= M.author_id_fk
								INNER JOIN company as C ON  C.company_id = M.comp_id_fk
									WHERE M.author_id_fk ='{$author_id_fk}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id,  C.companyname  AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(Zz.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
									FROM company_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz ON Zz.psts=M.post_id
									INNER JOIN company as C ON C.company_id = M.comp_id_fk									
									INNER JOIN authors as U1 ON U1.auid=Zz.author_id_fk 
									WHERE Zz.author_id_fk ='{$author_id_fk}' )
						UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id, C.companyname  AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(Zz2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
									FROM company_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz1 ON Zz1.psts=M.post_id
									INNER JOIN commentz_2 as Zz2 ON Zz2.updt_id_fk = Zz1.cmnt_id
									INNER JOIN company as C ON C.company_id = M.comp_id_fk									
									INNER JOIN authors as U3 ON U3.auid=Zz1.author_id_fk 
									INNER JOIN authors as U1 ON U1.auid=Zz2.author_id_fk 
										WHERE U1.auid ='{$author_id_fk}' )
						UNION ALL
						(SELECT  CONCAT( M.post_id, '-evnt') AS msg_id,  C.appointment_id AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM event_post as M INNER JOIN authors as U1 ON U1.auid= M.author_id_fk
								INNER JOIN appointment as C ON C.appointment_id = M.app_id_fk	
									WHERE M.author_id_fk ='{$author_id_fk}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-evnt') AS msg_id,  C.appointment_id  AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(Zz.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
									FROM event_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz ON Zz.evnt=M.post_id
									INNER JOIN appointment as C ON C.appointment_id = M.app_id_fk									
									INNER JOIN authors as U1 ON U1.auid=Zz.author_id_fk 
									WHERE Zz.author_id_fk ='{$author_id_fk}' )
						UNION ALL
						(SELECT CONCAT( M.post_id, '-evnt') AS msg_id, C.appointment_id  AS profile, 0 AS forumPolling, 
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, FROM_UNIXTIME(Zz2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
									FROM event_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz1 ON Zz1.evnt=M.post_id
									INNER JOIN commentz_2 as Zz2 ON Zz2.updt_id_fk = Zz1.cmnt_id
									INNER JOIN appointment as C ON C.appointment_id = M.app_id_fk							
									INNER JOIN authors as U3 ON U3.auid=Zz1.author_id_fk 
									INNER JOIN authors as U1 ON U1.auid=Zz2.author_id_fk 
										WHERE U1.auid ='{$author_id_fk}' )

									)AS TABLE_tfvv ORDER BY created DESC
						
					)As trr Group by msg_id  ORDER BY created DESC {$morequery} ";
					// echo $query;
			$tottal_array = $database->query($query);				
			while($row=$database->fetch_array($tottal_array)){
					$totttal_array[]=$row;
					}		

			return !empty($totttal_array) ? ($totttal_array) : false;

	}
	 
	// function to get the Total Profile TimeLine data count
	public static function update_profile_timeline_total($author_id_fk, $privacy){
		  $database = new Database;
		$privacy_st="";
			 if(!empty($privacy) && isset($privacy)){		$privacy_st="AND M.privacy = 1";		}
					$query="
						SELECT COUNT(msg_id) as Total FROM (
							SELECT * FROM(
								(SELECT CONCAT( M.updt_id, '-updt') AS msg_id
									FROM updatez as M INNER JOIN authors as U1
									ON  U1.auid = M.profile_id_fk
									WHERE  M.profile_id_fk = M.author_id_fk
										{$privacy_st}
									AND M.profile_id_fk ='{$author_id_fk}')
								
								UNION ALL
								(SELECT  CONCAT( M.updt_id, '-updt') AS msg_id 
										FROM updatez as M INNER JOIN authors as U2
										ON  U2.auid = M.profile_id_fk 
										INNER JOIN authors as U1
										ON  U1.auid = M.author_id_fk
										WHERE M.profile_id_fk <> M.author_id_fk
										{$privacy_st}
										AND M.profile_id_fk ='{$author_id_fk}' )
								UNION ALL
								(SELECT    CONCAT( M.updt_id, '-updt') AS msg_id 
									FROM updatez as M INNER JOIN authors as U1
									  ON U1.auid = M.author_id_fk 
									  INNER JOIN authors as U2
									  ON U2.auid = M.profile_id_fk
										WHERE M.profile_id_fk <> M.author_id_fk
													{$privacy_st}
										AND M.author_id_fk ='{$author_id_fk}' )
								UNION ALL
						(SELECT   CONCAT( M.updt_id, '-updt') 
									FROM updatez as M INNER JOIN commentz as Z
									ON  Z.updt =M.updt_id
									WHERE Z.author_id_fk ='{$author_id_fk}' 
										{$privacy_st})
						UNION ALL
						(SELECT   CONCAT( M.updt_id, '-updt') AS msg_id
								FROM updatez as M INNER JOIN commentz as Z1
							ON Z1.updt = M.updt_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
										WHERE Z2.author_id_fk ='{$author_id_fk}' 
											{$privacy_st} )
						UNION ALL
								(SELECT CONCAT( M.post_id, '-psts') AS msg_id 
										FROM company_post M 
											WHERE M.author_id_fk ='{$author_id_fk}' )
								UNION ALL
						(SELECT   CONCAT( M.post_id, '-psts') 
									FROM company_post as M INNER JOIN commentz as Z
									ON  Z.psts = M.post_id
									WHERE Z.author_id_fk ='{$author_id_fk}' 
										)
						UNION ALL
						(SELECT   CONCAT(M.post_id, '-psts') AS msg_id
							FROM company_post as M INNER JOIN commentz as Z1
							ON Z1.psts = M.post_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
								WHERE Z2.author_id_fk ='{$author_id_fk}' )
						UNION ALL
						(SELECT  CONCAT( M.post_id, '-evnt') AS msg_id 
										FROM event_post M
											WHERE  M.author_id_fk ='{$author_id_fk}' )
								UNION ALL
						(SELECT   CONCAT( M.post_id, '-evnt') 
									FROM event_post as M INNER JOIN commentz as Z
									ON  Z.evnt = M.post_id
									WHERE Z.author_id_fk ='{$author_id_fk}' 
										)
						UNION ALL
						(SELECT   CONCAT(M.post_id, '-evnt') AS msg_id
							FROM event_post as M INNER JOIN commentz as Z1
							ON Z1.evnt = M.post_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
								WHERE Z2.author_id_fk ='{$author_id_fk}' )

						)As trr Group by msg_id )as t";
						// echo $query;
			$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['Total'] : false;

		}

  	// Function to find all Events page timeline record
	public static function event_post($app_id, $per_page, $offset) {
		$database = new Database;
			if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

		$query="SELECT *	FROM (	SELECT *	FROM(
						(SELECT CONCAT( M.post_id, '-evnt') AS msg_id,  C.title AS profile, 
									U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM event_post as M INNER JOIN authors as U1 ON U1.auid= M.author_id_fk
								INNER JOIN appointment as C ON  C.appointment_id = M.app_id_fk
									WHERE M.app_id_fk ='{$app_id}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-evnt') AS msg_id,  C.title  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (Zz.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
									FROM event_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz ON Zz.evnt=M.post_id
									INNER JOIN appointment as C ON C.appointment_id = M.app_id_fk									
									INNER JOIN authors as U1 ON U1.auid=Zz.author_id_fk 
									WHERE  M.app_id_fk ='{$app_id}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-evnt') AS msg_id, C.title  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (Zz2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
									FROM event_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz1 ON Zz1.evnt=M.post_id
									INNER JOIN commentz_2 as Zz2 ON Zz2.updt_id_fk = Zz1.cmnt_id
									INNER JOIN appointment as C ON C.appointment_id = M.app_id_fk									
									INNER JOIN authors as U3 ON U3.auid=Zz1.author_id_fk 
									INNER JOIN authors as U1 ON U1.auid=Zz2.author_id_fk 
										WHERE  M.app_id_fk ='{$app_id}')
									
									)AS TABLE_tfvv ORDER BY created DESC
						
					)As trr Group by msg_id  ORDER BY created DESC {$morequery} ";
			$result_array = $database->query($query);
		 
			while($row=$database->fetch_array($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;

			}		

	// Function to find all Events page timeline record count
	public static function event_post_total($app_id) {
		$database = new Database;
			$query=" 	SELECT COUNT(msg_id) as Total FROM (
							SELECT * FROM(
								
								(SELECT CONCAT( M.post_id, '-evnt') AS msg_id 
										FROM event_post M 
											WHERE M.app_id_fk ='{$app_id}' )
								UNION ALL
						(SELECT   CONCAT( M.post_id, '-evnt') 
									FROM event_post as M INNER JOIN commentz as Z
									ON  Z.evnt = M.post_id
									WHERE  M.app_id_fk ='{$app_id}' 
										)
						UNION ALL
						(SELECT   CONCAT(M.post_id, '-evnt') AS msg_id
							FROM event_post as M INNER JOIN commentz as Z1
							ON Z1.evnt = M.post_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
								WHERE  M.app_id_fk ='{$app_id}' )
						
						)As trr Group by msg_id )as t";

		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['Total'] : false;

		}

		// Function to find all company page timeline record 
	public static function update_Company($comp_id, $per_page, $offset) {
      $database = new Database;
		 if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

			$query="SELECT *	FROM (	SELECT *	FROM(
						(SELECT CONCAT( M.updt_id, '-updt') AS msg_id,  C.companyname AS profile, 
									U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											(M.created) AS post_date,  UNIX_TIMESTAMP(M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2											
								FROM updatez as M INNER JOIN authors as U1 ON U1.auid= M.author_id_fk
								INNER JOIN company as C ON  C.company_id = M.company_id_fk
									WHERE M.company_id_fk ='{$comp_id}' AND M.privacy=1) 
						UNION ALL
						(SELECT CONCAT( M.updt_id, '-updt') AS msg_id,  C.companyname  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											 UNIX_TIMESTAMP(M.created) AS post_date, (Zz.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
									FROM updatez as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz ON Zz.updt=M.updt_id
									INNER JOIN company as C ON C.company_id = M.company_id_fk									
									INNER JOIN authors as U1 ON U1.auid=Zz.author_id_fk 
									WHERE M.company_id_fk ='{$comp_id}' AND M.privacy=1)
						UNION ALL
						(SELECT CONCAT( M.updt_id, '-updt') AS msg_id, C.companyname  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.updates AS updates,  
											 UNIX_TIMESTAMP(M.created) AS post_date, (Zz2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
									FROM updatez as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz1 ON Zz1.updt=M.updt_id
									INNER JOIN commentz_2 as Zz2 ON Zz2.updt_id_fk = Zz1.cmnt_id
									INNER JOIN company as C ON C.company_id = M.company_id_fk									
									INNER JOIN authors as U3 ON U3.auid=Zz1.author_id_fk 
									INNER JOIN authors as U1 ON U1.auid=Zz2.author_id_fk 
										WHERE  M.company_id_fk ='{$comp_id}' AND M.privacy=1)
									
					UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id,  C.companyname AS profile, 
									U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (M.created) AS created, M.uploads AS uploads,
											0 as layer1, 0 as layer2
								FROM company_post as M INNER JOIN authors as U1 ON U1.auid= M.author_id_fk
								INNER JOIN company as C ON  C.company_id = M.comp_id_fk
									WHERE M.comp_id_fk ='{$comp_id}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id,  C.companyname  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (Zz.created) AS created, M.uploads AS uploads,
											U2.authorname as layer1, 0 as layer2
									FROM company_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz ON Zz.psts=M.post_id
									INNER JOIN company as C ON C.company_id = M.comp_id_fk									
									INNER JOIN authors as U1 ON U1.auid=Zz.author_id_fk 
									WHERE  M.comp_id_fk ='{$comp_id}')
						UNION ALL
						(SELECT CONCAT( M.post_id, '-psts') AS msg_id, C.companyname  AS profile,
											U1.authorname AS actionFrom_name, M.author_id_fk AS actionFrom_id, M.posting AS updates,  
											(M.created) AS post_date, (Zz2.created) AS created, M.uploads AS uploads,
											U3.authorname as layer1, U2.authorname as layer2
									FROM company_post as M INNER JOIN authors as U2 ON U2.auid= M.author_id_fk
									INNER JOIN commentz as Zz1 ON Zz1.psts=M.post_id
									INNER JOIN commentz_2 as Zz2 ON Zz2.updt_id_fk = Zz1.cmnt_id
									INNER JOIN company as C ON C.company_id = M.comp_id_fk									
									INNER JOIN authors as U3 ON U3.auid=Zz1.author_id_fk 
									INNER JOIN authors as U1 ON U1.auid=Zz2.author_id_fk 
										WHERE  M.comp_id_fk ='{$comp_id}')
									
									)AS TABLE_tfvv ORDER BY created DESC
						
					)As trr Group by msg_id  ORDER BY created DESC {$morequery} ";
			$result_array = $database->query($query);
		 // echo $query;
			while($row=$database->fetch_array($result_array)){
					$data[]=$row;	
				}
			return !empty($data) ? ($data) : false;
	}		

	// Function to find all company page timeline record count
	public static function update_Company_total($comp_id) {
		$database = new Database;
			$query=" 	SELECT COUNT(msg_id) as Total FROM (
							SELECT * FROM(
								
								(SELECT CONCAT( M.updt_id, '-updt') AS msg_id 
										FROM updatez M 
											WHERE M.company_id_fk ='{$comp_id}' AND M.privacy=1 )
								UNION ALL
						(SELECT   CONCAT( M.updt_id, '-updt') 
									FROM updatez as M INNER JOIN commentz as Z
									ON  Z.updt = M.updt_id
									WHERE  M.company_id_fk ='{$comp_id}' AND M.privacy=1 
										)
						UNION ALL
						(SELECT   CONCAT(M.updt_id, '-updt') AS msg_id
							FROM updatez as M INNER JOIN commentz as Z1
							ON Z1.updt = M.updt_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
								WHERE  M.company_id_fk ='{$comp_id}'  AND M.privacy=1)
						UNION ALL								
								(SELECT CONCAT( M.post_id, '-psts') AS msg_id 
										FROM company_post M 
											WHERE M.comp_id_fk ='{$comp_id}' )
								UNION ALL
						(SELECT   CONCAT( M.post_id, '-psts') 
									FROM company_post as M INNER JOIN commentz as Z
									ON  Z.psts = M.post_id
									WHERE  M.comp_id_fk ='{$comp_id}' 
										)
						UNION ALL
						(SELECT   CONCAT(M.post_id, '-psts') AS msg_id
							FROM company_post as M INNER JOIN commentz as Z1
							ON Z1.psts = M.post_id
							INNER JOIN commentz_2 as Z2
							ON Z2.updt_id_fk = Z1.cmnt_id
								WHERE  M.comp_id_fk ='{$comp_id}' )
						
						)As trr Group by msg_id )as t";

		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['Total'] : false;
	}


  public static function recent_feed_onBizPage($author_id_fk, $per_page, $offset) {
      $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="(SELECT  Cz.comments AS message, C1.companyname AS onto,  A.author_id_fk AS direct1, 0 AS direct2,
					Cz.created AS created,  'comment' AS activity
					FROM updatez A,commentz Cz, company C1
					WHERE Cz.updt_id_fk = A.updt_id AND A.company_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  Cz2.comments AS message, C1.companyname AS onto,  A.author_id_fk AS direct1, Cz.author_id_fk AS direct2, 
						Cz.created AS created ,  'reply' AS activity
					FROM updatez A, commentz_2 Cz2,commentz Cz, company C1
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-updt')
					AND Cz.updt_id_fk = A.updt_id AND A.company_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  A.posting AS message, C1.companyname AS onto,  0 AS direct1, 0 AS direct2,  A.created AS created,  'posting' AS activity
					FROM company_post A, company C1
					WHERE A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}' )
					UNION
			(SELECT  Cz.comments AS message, C1.companyname AS onto,  A.author_id_fk AS direct1, 0 AS direct2,  Cz.created AS created,  'comment' AS activity
					FROM company_post A,company_post_commentz Cz, company C1
					WHERE Cz.post_id_fk = A.post_id AND A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  Cz2.comments AS message, C1.companyname AS onto,  A.author_id_fk AS direct1, Cz.author_id_fk AS direct2,  Cz.created AS created ,  'reply' AS activity
					FROM company_post A, commentz_2 Cz2, company_post_commentz Cz, company C1
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
					AND Cz.post_id_fk = A.post_id AND A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
			ORDER BY created DESC {$morequery} " 
					
					;


		$result_array = $database->query($query);
	 
	 while($row=$database->fetch_object($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;

	}		
	
  public static function recent_feed_onBizPage_total($author_id_fk) {
      $database = new Database;

	  $query="select sum(message) AS Total from 
((SELECT COUNT( Cz.comments) AS message
					FROM updatez A,commentz Cz, company C1
					WHERE Cz.updt_id_fk = A.updt_id AND A.company_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  COUNT(Cz2.comments) AS message
					FROM updatez A, commentz_2 Cz2,commentz Cz, company C1
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-updt')
					AND Cz.updt_id_fk = A.updt_id AND A.company_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  COUNT(A.posting )AS message
					FROM company_post A, company C1
					WHERE A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}' )
					UNION
			(SELECT  COUNT(Cz.comments) AS message
					FROM company_post A,company_post_commentz Cz, company C1
					WHERE Cz.post_id_fk = A.post_id AND A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}')
					UNION
			(SELECT  COUNT(Cz2.comments) AS message
					FROM company_post A, commentz_2 Cz2, company_post_commentz Cz, company C1
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
					AND Cz.post_id_fk = A.post_id AND A.comp_id_fk = C1.company_id 
					AND C1.author_id_fk ='{$author_id_fk}'))AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
		 }


  public static function recent_feed_oncompanyPage($company_id, $per_page, $offset) {
      $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
		$query="	
		SELECT *	FROM (	
				(	SELECT  P.posting AS message, C1.companyname AS onto1, 0 AS onto2,  P.author_id_fk AS person_id,  A.authorname AS person, P.created AS created, 
					CONCAT(A.authorname,' have wrote on your Company TimeLine.') AS activity, CONCAT( P.post_id,'-psts') AS message_id
					FROM company_post P, company C1, authors A
					WHERE P.comp_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}' 
					AND P.author_id_fk =A.auid 
				)
					UNION
				(	SELECT  Cz.comments AS message, C1.companyname AS onto1, 0 AS onto2,  Cz.author_id_fk AS person_id,  A.authorname AS person, Cz.created AS created, 
					CONCAT( A.authorname,' have commented on your Company TimeLine Posting.') AS activity, CONCAT( Cz.cmnt_id,'-comment') AS message_id
					FROM company_post P,	commentz Cz, company C1, authors A
					WHERE Cz.updt_id_fk = CONCAT(P.post_id,'-psts') 
					AND P.comp_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
					AND Cz.author_id_fk =A.auid 
				)
					UNION
				(	SELECT  Cz2.comments AS message, C1.companyname AS onto1, 0 AS onto2,  Cz2.author_id_fk AS person_id,  A.authorname AS person, Cz2.created AS created,
					CONCAT( A.authorname,' have Replyed on a comment of your company TimeLine.') AS activity, CONCAT( Cz2.cmnt_id,'-reply') AS message_id
					FROM company_post P, commentz_2 Cz2, commentz Cz, company C1, authors A
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
					AND Cz.updt_id_fk = P.post_id
					AND P.comp_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
					AND Cz2.author_id_fk =A.auid 
				)	
					UNION
				(	SELECT  Cz.comments AS message, C1.companyname AS onto1, 0 AS onto2,  Cz.author_id_fk AS person_id,  A.authorname AS person, Cz.created AS created, 
					CONCAT( A.authorname,' have commented on your Company TimeLine Posting.') AS activity, CONCAT( Cz.cmnt_id,'-comment') AS message_id
					FROM updatez P,	commentz Cz, company C1, authors A
					WHERE Cz.updt_id_fk = CONCAT(P.updt_id,'-updt') 
					AND P.company_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
					AND Cz.author_id_fk =A.auid 
				)
					UNION
				(	SELECT  Cz2.comments AS message, C1.companyname AS onto1, 0 AS onto2,  Cz2.author_id_fk AS person_id,  A.authorname AS person, Cz2.created AS created,
					CONCAT( A.authorname,' have Replyed on a comment of your company TimeLine.') AS activity, CONCAT( Cz2.cmnt_id,'-reply') AS message_id
					FROM updatez P, commentz_2 Cz2, commentz Cz, company C1, authors A
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
					AND Cz.updt_id_fk = P.updt_id
					AND P.company_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
					AND Cz2.author_id_fk =A.auid 
				)
				)As trr ORDER BY created DESC  {$morequery} " 
					
					;


		$result_array = $database->query($query);
	 
	 while($row=$database->fetch_object($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;

	}		
	
	public static function recent_feed_oncompanyPage_total($company_id) {
		  $database = new Database;
		  $query="SELECT SUM(message) AS Total from 
				(
				(SELECT  COUNT(P.posting )AS message
						FROM company_post P, company C1
						WHERE P.comp_id_fk = C1.company_id 
						AND C1.company_id ='{$company_id}' )
						UNION
				(SELECT  COUNT(Cz.comments) AS message
						FROM company_post P, commentz Cz, company C1
						WHERE Cz.updt_id_fk = CONCAT(P.post_id,'-psts')
						AND P.comp_id_fk = C1.company_id 
						AND C1.company_id ='{$company_id}')
						UNION
				(SELECT  COUNT(Cz2.comments) AS message
						FROM company_post P, commentz_2 Cz2, commentz Cz, company C1
						WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
						AND Cz.updt_id_fk = P.post_id 
						AND P.comp_id_fk = C1.company_id 
						AND C1.company_id ='{$company_id}')
					UNION
				(SELECT  COUNT(Cz.comments) AS message
					FROM updatez P,	commentz Cz, company C1
					WHERE Cz.updt_id_fk = CONCAT(P.updt_id,'-updt') 
					AND P.company_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
				)
					UNION
				( SELECT COUNT(Cz2.comments) AS message
					FROM updatez P, commentz_2 Cz2, commentz Cz, company C1
					WHERE Cz2.updt_id_fk = CONCAT( Cz.`cmnt_id`,'-psts')
					AND Cz.updt_id_fk = P.updt_id
					AND P.company_id_fk = C1.company_id 
					AND C1.company_id ='{$company_id}'
				)
						)AS T " ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['Total'] : false;
	}


  public static function getPressRelease($company_id, $per_page, $offset) {
      $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
		
		$query=" SELECT	M.updt_id AS updt_id, M.privacy AS privacy, CONCAT( M.updt_id, '-updt') AS msg_id, 'Press Release' AS name, 
						CONCAT( M.author_id_fk,'#!updt') AS owner_id, M.updates AS updates, M.created AS created, M.uploads AS uploads,
						'Release' AS info, 'press' AS link
						FROM updatez M
					WHERE M.company_id_fk ='{$company_id}'
					ORDER BY created DESC  {$morequery} " 
					;
		$result_array = $database->query($query);
	 
	 while($row=$database->fetch_array($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;

	}		
	
	public static function getPressRelease_total($company_id) {
		  $database = new Database;
		  $query="SELECT COUNT(*) FROM updatez WHERE company_id_fk ='{$company_id}'  " ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['COUNT(*)'] : false;
	}

	
// Watching List OF Product And Appointment
  public static function watching_list_on_mainDashboard($author_id_fk, $per_page, $offset) {
      $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="(SELECT P.product_id AS company_id_fk,  0 AS author_id_fk, P.title AS title,  P.end_date AS created ,  'pro' AS activity
					FROM productswatching W, products P
					WHERE W.product_id_fk = P.product_id 
					AND W.author_id ='{$author_id_fk}')
					UNION
			(SELECT A.company_id_fk AS company_id_fk,  A.author_id_fk AS author_id_fk, A.title AS title,  W.onday AS created ,  'appoi' AS activity
					FROM appointmentwatching W, appointment A
					WHERE W.appointment_id_fk = A.appointment_id 
					AND W.author_id_fk ='{$author_id_fk}')
					
					ORDER BY created DESC {$morequery} " ;

		$result_array = $database->query($query);
	 
	 while($row=$database->fetch_object($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;

	}		
	
  public static function watching_list_on_mainDashboard_total($author_id_fk) {
      $database = new Database;

	  $query="SELECT SUM(created) AS Total FROM (
				(SELECT COUNT(P.end_date) AS created
					FROM productswatching W, products P
					WHERE W.product_id_fk = P.product_id 
					AND W.author_id ='{$author_id_fk}')
					UNION
				(SELECT COUNT(A.created) AS created
					FROM appointmentwatching W, appointment A
					WHERE W.appointment_id_fk = A.appointment_id 
					AND W.author_id_fk ='{$author_id_fk}')
					)AS T " ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['Total'] : false;
		 }


		 

}

?>