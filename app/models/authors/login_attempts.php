<?php
namespace Models\Authors;

use \Models\database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Login_Attempts extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="login_attempts";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('id',  'auid_fk', 'act_time');
	

	public $id;
	public $auid_fk;
	public $act_time;
  

	//functions find the total checkbrute_count from time
	public static function checkbrute_count($auid_fk=0) {
		$database= new \Models\Database\Database();

		 $valid_attempts = time() - (2 * 60 * 60); 
			$query="SELECT COUNT(auid_fk) FROM ".static::$table_name."
								WHERE auid_fk='{$auid_fk}' AND act_time > '{$valid_attempts}' " ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['COUNT(auid_fk)'] : false;
	}
		
	
	//functions removes all login attempts enties after a successful login
	public static function Remover_existing_attempts($auid_fk=0) {
		$database= new \Models\Database\Database();

			$query="DELETE FROM ".static::$table_name."  WHERE auid_fk='{$auid_fk}' " ;
		$result_array = $database->query($query);
		return !empty($result_array) ? $result_array : false;
	}
		
	
}

?>