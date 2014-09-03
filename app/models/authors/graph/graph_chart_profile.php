<?php
namespace Models\Authors\Graph;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Graph_Chart_Profile extends DatabaseObject {
	
	protected static $table_name="graph_chart_profile";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('chart_id',  'title', 'description', 'author_id_fk', 'num_in', 'created', 'start_date', 'end_date', 'view');
	
	public $chart_id;
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
	
  public static function delete_data_chart_id($chart_id=0) {

		$database = new Database;
        $query = $database->query("DELETE FROM ".self::$table_name." WHERE chart_id= '{$chart_id}' LIMIT 1");
		return ($database->affected_rows() == 1) ? true : false;
	}	

	public static function find_all_by_author_id($author_id_fk=0) {
    $primary_key=static::get_primary_key();
    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk={$author_id_fk}");
		return !empty($result_array) ? ($result_array) : false;
  }
  
  
  public static function find_charts_by_chart_id($chart_id) {

  $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE chart_id={$chart_id} LIMIT 1 ");
		return !empty($result_array) ? array_shift($result_array) : false;

	}
	  
  
  public static function find_Charts_by_author_id_offsets($author_id_fk, $per_page, $offset) {
     $database = new Database;
     if(!empty($per_page) || isset($offset)){		$morequery="LIMIT {$per_page} OFFSET {$offset}";		}
	$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk={$author_id_fk} ORDER BY chart_id DESC {$morequery} ");
		return !empty($result_array) ? ($result_array) : false;

	}
	
  public static function find_Charts_by_author_id_offsets_count($author_id_fk) {
      $database = new Database;
	$query="SELECT COUNT(*) FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}'" ;
	$result_array = $database->query($query);
	$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['COUNT(*)'] : false;
		 }
  public static function author_id_by_chart_id($chart_id='') {
  	$database = new Database;
	$result_array =$database->query("SELECT author_id_fk FROM ".self::$table_name." WHERE chart_id= '{$chart_id}' LIMIT 1");
	$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['author_id_fk'] : false;
 }
 
  public static function view_status_by_chart_id($chart_id='') {
  	$database = new Database;
	$result_array =$database->query("SELECT view FROM ".self::$table_name." WHERE chart_id= '{$chart_id}' LIMIT 1");
	$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['view'] : false;
 }
}

?>