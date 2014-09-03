<?php
namespace Models\Authors;

use \Models\Database\DatabaseObject as DatabaseObject;

// If it's going to need the database, then it's 
// probably smart to require it before we start.

class PersonalLocations extends DatabaseObject {
	
	//declairing the table name as static
	protected static $table_name="personallocations";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('personal_loc_id', 'personal_id_fk','home_address', 'home_lat', 'home_lng',		
										'office_address', 'office_lat', 'office_lng', 'host_ip', 'time');
	
	public $personal_loc_id;
	public $home_address;
	public $personal_id_fk;
	public $home_lat;
	public $home_lng;
	public $office_address;
	public $office_lat;
	public $office_lng;
	public $host_ip;
	public $time;
	
  public static function find_by_author_id($author_id_fk=0) {
    $primary_key=static::get_primary_key();

    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE personal_id_fk={$author_id_fk}  ORDER BY personal_loc_id DESC LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
  }
  		// checking the personal Location existance
	public static function Check_location_existance($personal_id_fk='', $home_lat='', $home_lng='') {
    $result_array = static::find_by_sql("SELECT 1 FROM ".self::$table_name." WHERE personal_id_fk= '{$personal_id_fk}'
										AND home_lat ='{$home_lat}' AND home_lng ='{$home_lng}' LIMIT 1");
		return !empty($result_array) ? true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}
  
  
  // public static function find_by_email($email='') {
    // $result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
		// return !empty($result_array) ? true : false;
  // }
  


}

?>