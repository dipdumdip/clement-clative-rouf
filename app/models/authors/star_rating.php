<?php
namespace Models\Authors;

use \Models\database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

// If it's going to need the database, then it's 
// probably smart to require it before we start.

class Star_Rating extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="star_rating";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('vote_id',  'counter',  'rating', 'profile_id_fk', 'author_id_fk');
	

	public $vote_id;
	public $counter;
	public $rating;
	public $profile_id_fk;
	public $author_id_fk;
	
 	
	// function to Delete all detas By delete_profile_data
	public static function delete_profile_data($profile_id_fk=0) {
			$database= new \models\database\Database();

				$query= "DELETE FROM `".static::$table_name."` WHERE profile_id_fk = '{$profile_id_fk}' ";
				$result_array = $database->query($query);
				return $result_array;
	}
	  
	//this functions to check already votted or not  by author_id_fk andprofile_id_fk
	public static function Check_Vote_aready_done($author_id_fk, $profile_id_fk){
		$result_array = static::find_by_sql("SELECT vote_id FROM ".static::$table_name." 
							WHERE author_id_fk='{$author_id_fk}' AND profile_id_fk='{$profile_id_fk}' LIMIT 1");
	
		return !empty($result_array) ? true : false;
	}

	//this function gives the tottal voted count
	public static function find_rate_count($profile_id_fk=0) {
		$database= new \models\database\Database();

		$result_array =$database->query("SELECT  COUNT(*) FROM ".static::$table_name." WHERE profile_id_fk='{$profile_id_fk}' ");
		$data =  $database->fetch_assoc($result_array);

	  return !empty($data) ? $data['COUNT(*)'] : false;

	} 

	//this function gets the total sm of valued value
	public static function find_total_value($profile_id_fk=0) {
		$database= new \models\database\Database();

    $result_array =$database->query("SELECT  SUM(rating) FROM ".static::$table_name." WHERE profile_id_fk='{$profile_id_fk}' ");
	$data =  $database->fetch_assoc($result_array);

	  return !empty($data) ? $data['SUM(rating)'] : false;
	}
 	
	// Delete all detas By profile_id_fk Id and author_id
	public static function delete_profile_vote_by_author_id($profile_id_fk=0, $author_id_fk=0) {

		$database= new \models\database\Database();

			$query= "DELETE FROM `".static::$table_name."` WHERE profile_id_fk ='{$profile_id_fk}' AND author_id_fk = '{$author_id_fk}' ";
			$result_array = $database->query($query);
 			return $result_array;
	}
  
	
}

?>