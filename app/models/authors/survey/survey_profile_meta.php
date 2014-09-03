<?php
namespace Models\Authors\Survey;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Survey_Profile_Meta extends DatabaseObject {
	
	protected static $table_name="survey_profile_meta";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('met_id',  'surv_id_fk', 'surv_key', 'surv_value');
	
	public $met_id;
	public $surv_id_fk;
	public $surv_key;
	public $surv_value;
	
  public static function delete_data_surv_id_fk($surv_id_fk=0) {

		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE surv_id_fk = '{$surv_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}	

	public static function find_data_by_met_id($met_id=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE met_id={$met_id} LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
  }
  
	public static function find_data_by_surv_id($surv_id_fk=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE surv_id_fk={$surv_id_fk} ORDER BY met_id DESC");
			return !empty($result_array) ? ($result_array) : false;
  }
  
   	
  public static function find_last_survs_target_key($surv_id_fk) {
      $database = new Database;
	$query="SELECT met_id FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND surv_key='{$surv_id_fk}_max'  ORDER BY met_id DESC LIMIT 1" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['met_id'] : false;
		 }
    	
  public static function find_the_value_bu_met_d($surv_id_fk, $met_id) {
      $database = new Database;
	$query="SELECT surv_value FROM ".static::$table_name." WHERE surv_id_fk='{$surv_id_fk}' AND met_id='{$met_id}' LIMIT 1" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['surv_value'] : false;
		 }
 
}

?>