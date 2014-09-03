<?php
namespace Models\Authors;

use \Models\database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;



// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Remove_Account extends DatabaseObject {
	
	//declairing the table name as static
	protected static $table_name="remove_account";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('id', 'author_id_fk');
	
	
	public $id;
	public $author_id_fk;
	
		//function to delete author data by author_ID
	public static function delete_data_byauthor_id($author_id_fk=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE author_id_fk = '{$author_id_fk}' LIMIT 1";
			$result_array = $database->query($query);
			return $result_array;
	}	
		//function to Checks the Deactivate request Existings
	public static function find_requested_date_by_author_id($author_id_fk=0) {
		$database = new Database;
		$result_array =$database->query("SELECT created FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['created'] : false;
	}	

		// find the data  by Author id used 
	public static function find_by_author_id($auid='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}		
  
}

?>