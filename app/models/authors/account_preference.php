<?php
namespace Models\Authors;

use \Models\database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;



// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Account_Preference extends DatabaseObject {
	
	//declairing the table name as static
	protected static $table_name="account_preference";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('id', 'update_alert', 'invitation_alert', 'author_id_fk');
	
	
	public $id;
	public $update_alert;
	public $invitation_alert;
	public $author_id_fk;
	
	
		//function to Checks the Deactivate request Existings
	public static function check_data_by_author_id($author_id_fk=0) {
		$database = new Database;
		$result_array =$database->query("SELECT 1 FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? true : false;
	}	

		// find the data  by Author id used 
	public static function find_by_author_id($author_id_fk='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}	
  
}

?>