<?php
namespace Models\Authors\Survey;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;


class Survey_Profile extends DatabaseObject {
	
	protected static $table_name="survey_profile";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('surv_id',  'title', 'description', 'author_id_fk', 'num_in', 'created', 'start_date', 'end_date', 'view');
	
	public $surv_id;
	public $title;
	public $description;
	public $author_id_fk;
	public $num_in;
	public $created;
	public $start_date;
	public $end_date;
	public $view;
	
  public static function delete_data_author_id($author_id_fk=0) {

		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE author_id_fk = '{$author_id_fk}' ";
			$result_array = $database->query($query);
			return $result_array;
	}	
	
  public static function delete_data_surv_id($surv_id=0) {

		$database = new Database;
        $query = $database->query("DELETE FROM ".self::$table_name." WHERE surv_id= '{$surv_id}' LIMIT 1");
		return ($database->affected_rows() == 1) ? true : false;
	}	

	public static function find_all_by_author_id($author_id_fk=0) {
    $primary_key=static::get_primary_key();
    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk={$author_id_fk}");
		return !empty($result_array) ? ($result_array) : false;
  }
  
  
  public static function find_survs_by_surv_id($surv_id) {

  $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE surv_id={$surv_id} LIMIT 1 ");
		return !empty($result_array) ? array_shift($result_array) : false;

	}
	  
  
  public static function find_survs_by_author_id_offsets($author_id_fk, $per_page, $offset) {
     $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
	$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk={$author_id_fk} ORDER BY surv_id DESC {$morequery} ");
		return !empty($result_array) ? ($result_array) : false;

	}
	
  public static function find_survs_by_author_id_offsets_count($author_id_fk) {
      $database = new Database;
	$query="SELECT COUNT(*) FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
  public static function author_id_by_surv_id($surv_id='') {
  	$database = new Database;
	$result_array =$database->query("SELECT author_id_fk FROM ".self::$table_name." WHERE surv_id= '{$surv_id}' LIMIT 1");
	$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['author_id_fk'] : false;
 }
 
  public static function view_status_by_surv_id($surv_id='') {
  	$database = new Database;
	$result_array =$database->query("SELECT view FROM ".self::$table_name." WHERE surv_id= '{$surv_id}' LIMIT 1");
	$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['view'] : false;
 }
}

?>