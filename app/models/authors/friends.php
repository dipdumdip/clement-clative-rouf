<?php
namespace Authors;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Friends extends DatabaseObject {
	
	protected static $table_name="friends";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('friend_id',  'friend_one', 'friend_two', 'role');
	
	public $friend_id;
	public $friend_one;
	public $friend_two;
	public $role;
	
 	// find to count friends OF author
 public static function friends_count_by_author($auid=0){
    $query="SELECT COUNT(*) as Total FROM `friends` WHERE friend_one='{$auid}' AND role='fri'";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
 }
    

  public static function Friends_List($profile_id, $per_page, $offset) {
     $database= new Database();

     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="	SELECT U.authorname, U.auid , U.follower_count 
					FROM authors U, friends F 
					WHERE U.auid=F.friend_one 
					AND F.friend_two='{$profile_id}'
					AND F.role='fri' 
					ORDER BY U.last_login DESC,F.friend_id DESC {$morequery} ";
	$result_array = $database->query($query);
	 while($row=$database->fetch_array($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;
	}
	
  public static function friends_list_count($profile_id) {
      $database= new Database();
	  $query="SELECT COUNT(*) FROM ".static::$table_name." WHERE friend_two='{$profile_id}' AND role='fri'" ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
	
  public static function Friends_follower_List($profile_id, $per_page, $offset) {
     $database= new Database();

     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="	SELECT U.authorname, U.auid , U.follower_count 
					FROM authors U, friends F 
					WHERE U.auid=F.friend_two 
					AND F.friend_one='{$profile_id}'
					AND F.role='fri' 
					ORDER BY U.last_login DESC,F.friend_id DESC {$morequery} ";
	$result_array = $database->query($query);
	 while($row=$database->fetch_array($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;
	}
	
  public static function friends_list_follower_count($profile_id) {
      $database= new Database();


	  $query="SELECT COUNT(*) FROM ".static::$table_name." WHERE friend_one='{$profile_id}' AND role='fri'" ;
	 $result_array = $database->query($query);
	 
		$data = $database->fetch_assoc($result_array);
			
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
	
	// find the roles of friends
  public static function find_role_friends($auid=0, $fri_id=0) {
		$result_array = static::find_by_sql("SELECT role FROM ".self::$table_name." WHERE friend_one= '{$auid}' AND friend_two= '{$fri_id}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
  }
 	
 	// find to count friends
 public static function find_friends_count($auid=0, $fri_id=0) {
		$result_num = static::find_num_sql_queryset("SELECT friend_id FROM friends WHERE friend_one='{$auid}' AND friend_two='{$fri_id}'");
		return $result_num;
  }
     	
	
 	// find friends id from author id and frind id
 public static function find_friends_id_by_au_fri($auid=0, $fri_id=0) {
	$query="SELECT 1 FROM `".static::$table_name."` WHERE friend_one='{$auid}' AND friend_two='{$fri_id}'";
		$result_array = static::find_by_sql($query);
		return !empty($result_array) ? true : false;
  }
   	
		// find confirming the friends counds
  public static function find_friends_count_confirm($auid=0, $fri_id=0) {
		$result_num = static::find_num_sql_queryset("SELECT friend_id FROM `".static::$table_name."` WHERE friend_one='{$auid}' AND friend_two='{$fri_id}' AND role='fri'");
		return $result_num;
  }
  
	// Remove Friend
	  public static function remove_friends($auid, $fri_id){
				$database = new Database;
			$query= "DELETE FROM friends WHERE friend_one='{$auid}' AND friend_two='{$fri_id}'";
			$result_array = $database->query($query);
			return $result_array;
	}
	
	
}

?>