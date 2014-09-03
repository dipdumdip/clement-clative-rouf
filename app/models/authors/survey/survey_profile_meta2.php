<?php
namespace Models\Authors\Survey;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Survey_Profile_Meta2 extends DatabaseObject {
	
	protected static $table_name="survey_profile_meta2";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('met2_id',  'surv_id_fk', 'meta2_key', 'meta2_value');
	
	public $met2_id;
	public $surv_id_fk;
	public $meta2_key;
	public $meta2_value;
	
  public static function delete_data_surv_id_fk($surv_id_fk=0) {

		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE surv_id_fk = '{$surv_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}	

	public static function find_data_by_met2_id($met2_id=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE met2_id={$met2_id} LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
  }
  
	public static function find_data_by_surv_id($surv_id_fk=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE surv_id_fk={$surv_id_fk} ORDER BY met2_id ASC");
			return !empty($result_array) ? ($result_array) : false;
  }
    
	public static function find_lastcount_number_by_surv_id($surv_id_fk=0, $date='') {
      $database = new Database;
		$query="SELECT meta2_key FROM ".static::$table_name." WHERE surv_id_fk={$surv_id_fk} AND meta2_key LIKE '%_{$date}' ORDER BY met2_id DESC LIMIT 1";
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['meta2_key'] : '0_0_0';
  }
  
 	
  public static function find_last_survs_target($surv_id_fk, $met2_id) {
      $database = new Database;
	$query="SELECT meta2_value FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND meta2_key LIKE '{$met2_id}_%'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['meta2_value'] : false;
		 }
  	 	
  public static function find_meta2_value_by_surv_id_each($surv_id_fk, $date_wid_num, $per_page, $offset) {
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
  $query="SELECT * FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' 
															AND meta2_key LIKE '%_{$date_wid_num}' ORDER BY met2_id DESC {$morequery}" ;
		$result_array = static::find_by_sql($query);
			return !empty($result_array) ? ($result_array) : false;
		 }

  public static function find_meta2_value_by_surv_id_each_count($surv_id_fk, $date_wid_num) {
      $database = new Database;
	$query="SELECT COUNT(*) FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND meta2_key LIKE '%_{$date_wid_num}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
		 
  public static function find_meta2_value_by_surv_id_count($surv_id_fk, $date) {
      $database = new Database;
	$query="SELECT COUNT(*) FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND meta2_key LIKE '%_{$date}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
		 
  public static function find_data_meta2_value_by_surv_id($surv_id_fk, $meta2_key) {
      $database = new Database;
	$query="SELECT * FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND meta2_key = '{$meta2_key}' LIMIT 1" ;
		$result_array = static::find_by_sql($query);
			return !empty($result_array) ? ($result_array) : false;
		 }
  	
  public static function check_last_survs_date($surv_id_fk, $date_givn) {
      $database = new Database;
	$query="SELECT meta2_key FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND meta2_key LIKE '%_{$date_givn}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? true : false;
		 }
 
}

?>