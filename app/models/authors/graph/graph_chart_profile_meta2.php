<?php
namespace Models\Authors\Graph;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Graph_Chart_Profile_Meta2 extends DatabaseObject {
	
	protected static $table_name="graph_chart_profile_meta2";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('met2_id',  'chart_id_fk', 'meta2_key', 'meta2_value');
	
	public $met2_id;
	public $chart_id_fk;
	public $meta2_key;
	public $meta2_value;
	
  public static function delete_data_chart_id_fk($chart_id_fk=0) {

		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE chart_id_fk = '{$chart_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}	

	public static function find_data_by_met2_id($met2_id=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE met2_id={$met2_id} LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
  }
  
	public static function find_data_by_chart_id($chart_id_fk=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE chart_id_fk={$chart_id_fk} ORDER BY met2_id DESC");
			return !empty($result_array) ? ($result_array) : false;
  }
  
 	
  public static function find_last_Charts_target($chart_id_fk, $met2_id) {
      $database = new Database;
	$query="SELECT meta2_value FROM ".static::$table_name." WHERE chart_id_fk='{$chart_id_fk}' AND meta2_key LIKE '{$met2_id}_%'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['meta2_value'] : false;
		 }
  	 	
  public static function find_meta2_value_by_chart_id($chart_id_fk, $meta2_key) {
      $database = new Database;
	$query="SELECT meta2_value FROM ".static::$table_name." WHERE chart_id_fk='{$chart_id_fk}' AND meta2_key = '{$meta2_key}' LIMIT 1" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['meta2_value'] : false;
		 }  	 	
  public static function find_data_meta2_value_by_chart_id($chart_id_fk, $meta2_key) {
      $database = new Database;
	$query="SELECT * FROM ".static::$table_name." WHERE chart_id_fk='{$chart_id_fk}' AND meta2_key = '{$meta2_key}' LIMIT 1" ;
		$result_array = static::find_by_sql($query);
			return !empty($result_array) ? ($result_array) : false;
		 }
  	
  public static function check_last_Charts_date($chart_id_fk, $date_givn) {
      $database = new Database;
	$query="SELECT meta2_key FROM ".static::$table_name." WHERE chart_id_fk='{$chart_id_fk}' AND meta2_key LIKE '%_{$date_givn}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? true : false;
		 }
 
}

?>