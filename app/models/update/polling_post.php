<?php
namespace Models\Update;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Polling_Post extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="polling_post";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('post_id',  'posting', 'author_id_fk', 'polling_id_fk', 'ip', 'created', 'uploads', 'share');
	

	public $post_id;
	public $posting;
	public $author_id_fk;
	public $polling_id_fk;
	public $ip;
	public $created;
	public $uploads;
	public $share;
	
  
	//Function to  find the last record by author_ID
	public static function get_last_entry($author_id_fk){
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name."
						WHERE author_id_fk='{$author_id_fk}' ORDER BY post_id DESC LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }	
    
	//Function to  find the record availablility
	public static function find_the_post_available_CHECK($polling_id_fk=0){
		$result_array = static::find_by_sql("SELECT posting FROM ".static::$table_name." WHERE polling_id_fk='{$polling_id_fk}' LIMIT 1");
		return !empty($result_array) ? true : false;
    }	
  
	//Function to  find the record by Post ID
	public static function get_last_entry_by_postID($post_id=0){
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE post_id='{$post_id}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
    }	

			//Gets  latest updates Contant from same author Updtates 
	public static function get_last_entry_contant($author_id_fk){
		 $database = new Database;
			$result_array =$database->query("SELECT posting FROM ".static::$table_name." 
												WHERE author_id_fk='{$author_id_fk}' ORDER BY created DESC LIMIT 1");
			$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['posting'] : false;
	}	
	
	//Delete Post By Post ID
    public static function Delete_Post($uid, $post_id){
		$database = new Database;
		$query = $database->query("DELETE FROM ".static::$table_name." WHERE post_id='{$post_id}' AND author_id_fk='{$uid}'");
			return true;
	}	

	
	//Delete Post
    public static function Delete_Post_by_polling_id_fk($polling_id_fk){
			$database = new Database;
			$query = $database->query("DELETE FROM ".static::$table_name." WHERE polling_id_fk='$polling_id_fk'");
				return true;
			}

}

?>