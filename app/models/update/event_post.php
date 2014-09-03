<?php
namespace Models\Update;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Event_Post extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="event_post";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('post_id',  'posting', 'author_id_fk', 'app_id_fk', 'ip', 'created', 'uploads');
	

	public $post_id;
	public $posting;
	public $author_id_fk;
	public $app_id_fk;
	public $ip;
	public $created;
	public $uploads;
	
		//<----function to find total record by author ID
	public static function find_total_by_author_id_count($author_id_fk) {
      $database = new Database;
		$query="SELECT COUNT(*) FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}'" ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
				return !empty($data) ? $data['COUNT(*)'] : false;
	}
 	  
		//Gets  latest updates Contant from same author Updtates 
	public static function get_last_entry_contant($author_id_fk){
		 $database = new Database;
			$result_array =$database->query("SELECT posting FROM ".static::$table_name." 
												WHERE author_id_fk='{$author_id_fk}' ORDER BY post_id DESC LIMIT 1");
			$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['posting'] : false;
      
	}	 
	 //<----Function to delete recored BY event_ID   	
	public static function delete_data_event_id($app_id_fk=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE app_id_fk = '{$app_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}
		
	//<---function to delet record by authorID
	public static function delete_data_author_id($author_id_fk=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE author_id_fk = '{$author_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}
	  
		//Find  latest updates from same author Updtates 
	public static function get_last_entry($author_id_fk){
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name."
				WHERE author_id_fk={$author_id_fk} ORDER BY post_id DESC LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }	
	
	//<---function to delete record by authorID AND Post ID
    public static function Delete_Post($uid, $post_id){
		$database = new Database;
		$query = $database->query("DELETE FROM event_post WHERE post_id='$post_id' AND author_id_fk='$uid'");
				return true;
	}

}

?>