<?php
namespace Models\Authors;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Impression_Author extends DatabaseObject {
	
	protected static $table_name="impression_author";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('like_id',  'author', 'author_id', 'status', 'host', 'impression');
	
	public $like_id;
	public $author;
	public $author_id;
	public $status;
	public $host;
	public $impression;
	

    
  // find to count likes OF author by author ID
 public static function like_count_by_author($author_fk=0){
	$database = new Database;
    $result_array =$database->query("SELECT COUNT(*) FROM `".static::$table_name."` WHERE author='{$author_fk}' ");
	$data = $database->fetch_assoc($result_array);
	return !empty($data) ? $data['COUNT(*)'] : false;
 }
     

      
  // find to count likes OF author by author ID
 public static function NEGATIVE_follower_data_count($author_fk=0){
	$database = new Database;
    $result_array =$database->query("SELECT COUNT(*) FROM `".static::$table_name."` WHERE author='{$author_fk}' AND impression=1 ");
	$data = $database->fetch_assoc($result_array);
	return !empty($data) ? $data['COUNT(*)'] : false;
 }
 

  public static function NEGATIVE_follower_data($profile_id, $per_page, $offset) {
     $database= new Database();

     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="	SELECT U.authorname, U.auid , U.follower_count 
					FROM ".static::$table_name." as F INNER JOIN authors as U ON U.auid=F.author_id 
					WHERE F.author='{$profile_id}'
					AND F.impression=1 
					ORDER BY U.last_login DESC,F.like_id DESC {$morequery} ";
	$result_array = $database->query($query);
	 while($row=$database->fetch_array($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;
	}
	
 
  public static function POSITIVE_follower_data($profile_id, $per_page, $offset) {
     $database= new Database();

     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}

	$query="	SELECT U.authorname, U.auid , U.follower_count 
					FROM ".static::$table_name." as F INNER JOIN authors as U ON U.auid=F.author_id 
					WHERE F.author='{$profile_id}'
					AND F.impression<>1 
					ORDER BY U.last_login DESC,F.like_id DESC {$morequery} ";
	$result_array = $database->query($query);
	 while($row=$database->fetch_array($result_array)){
				$data[]=$row;	
				}
		return !empty($data) ? ($data) : false;
	}
	
 
	  // find to count likes OF author by author ID
	 public static function POSITIVE_follower_data_count($author_fk=0){
		$database = new Database;
		$result_array =$database->query("SELECT COUNT(*) FROM `".static::$table_name."` WHERE author='{$author_fk}' AND impression>1 ");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['COUNT(*)'] : false;
	 }


 //Delete author Like data by Comapy ID 		
  public static function delete_author_data($author=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE author = '{$author}' ";
			$result_array = $database->query($query);
			return $result_array;
	}	

 	// find Impression of like to confirm the like is not available
	public static function find_impression_Like_author($author=0, $author_id=0) {
		$database = new Database;
		$result_array =$database->query("SELECT impression FROM `".static::$table_name."` WHERE
											author='{$author}' AND author_id='{$author_id}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['impression'] : false;
	}
    	
		// find last created time of like to confirm the like is not available
	public static function find_like_last_created_by_au_fri($author=0, $author_id=0) {
		$database = new Database;
		$result_array =$database->query("SELECT created FROM `".static::$table_name."` WHERE
											author='{$author}' AND author_id='{$author_id}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['created'] : NULL;
	}
     	
 	// Confirm Like by author id and author id
 public static function find_like_id_by_au_fri($author=0, $author_id=0) {
		$result_array = static::find_by_sql("SELECT like_id FROM ".self::$table_name." WHERE 
										author='{$author}' AND author_id='{$author_id}'");
		return !empty($result_array) ? true : false;
  }
   	      	
   	
	// find confirming the like count by Author ID and author ID
  public static function like_count_confirm($author=0, $author_id=0) {
		$database = new Database;
		$result_array =$database->query("SELECT COUNT(*) FROM `".static::$table_name."` WHERE
							author='{$author}' AND author_id='{$author_id}' AND status='yes'");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['COUNT(*)'] : false;
	}
   	    	
 	// Confirm Like by author id and author id
 public static function find_like_details_by_au_fri($author=0, $author_id=0) {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE 
										author='{$author}' AND author_id='{$author_id}'");
		return !empty($result_array) ? array_shift($result_array) : false;
  }
   	
        	
		// find Total Like of a author By author ID
  public static function like_count_for_author($author=0) {
		$database = new Database;
		$result_array =$database->query("SELECT COUNT(*) FROM `".static::$table_name."` WHERE
								author='{$author}'");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['COUNT(*)'] : false;
  }
     	

	// Remove Like by Using Author ID and author ID
  public static function remove_like($author, $author_id){
		$database = new Database;
		if(static::like_count_confirm($author, $author_id)==1) {		//<---- find confirming the like count by Author ID and author ID
			if (static::find_like_id_by_au_fri($author, $author_id)){	//<--- Confirm Like by author id and author id
					$query=$database->query("DELETE FROM ".self::$table_name." WHERE author='{$author}' AND author_id='{$author_id}'");
						return $query;
			}else{
				return false;
			}
		}
	}
		
	
}

?>