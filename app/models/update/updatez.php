<?php
namespace Models\Update;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Updatez extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="updatez";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('updt_id',  'updates', 'profile_id_fk', 'author_id_fk', 'ip', 'company_id_fk',
										 'uploads', 'privacy', 'wall', 'identify');
	
	public $perpage=3;
	
	
	public $updt_id;
	public $updates;
	public $profile_id_fk;
	public $author_id_fk;
	public $ip;
	public $company_id_fk;
	public $uploads;
	public $privacy;
	public $wall;
	public $identify;
	
 	
		// Delete all detas By Company Id and author_id
	public static function delete_company_data($comp_id_fk=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE company_id_fk = '{$comp_id_fk}' ";
			$result_array = $database->query($query);
 			return $result_array;
	}
 	 
		//Find  latest updates from same author Updtates 
	public static function get_last_entry($author_id_fk){
			$database = new Database;
		$query = "SELECT * FROM ".static::$table_name." 
									WHERE author_id_fk='{$author_id_fk}' ORDER BY updt_id DESC LIMIT 1";
			$tottal_array = $database->query($query);				
			while($row=$database->fetch_object($tottal_array)){
					$totttal_array[]=$row;
					}		
			return !empty($totttal_array) ? array_shift($totttal_array) : false;
    }	 	 
		//Find existance of automatically posted record updates from same author Updtates 
	public static function find_Automatic_created_data($author_id_fk=0, $identify=0){
			$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." 
									WHERE author_id_fk='{$author_id_fk}' AND identify='{$identify}' LIMIT 1");
	
		return !empty($result_array) ? array_shift($result_array) : false;
    }	

		//Gets  latest updates Contant from same author Updtates 
  public static function get_last_entry_Contant($author_id_fk=0) {
		$database = new Database;
		$result_array =$database->query("SELECT updates FROM ".static::$table_name." 
											WHERE author_id_fk='{$author_id_fk}' ORDER BY updt_id DESC LIMIT 1");
		$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['updates'] : false;
  }

		//Gets  latest updates Contant FOR COMPANY POSTING from same author Updtates 
  public static function get_last_entry_PressRelease($comp_id=0) {
		$database = new Database;
		$result_array =$database->query("SELECT updates FROM ".static::$table_name." 
											WHERE company_id_fk='{$comp_id}' ORDER BY updt_id DESC LIMIT 1");
		$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['updates'] : false;
  }

  //find record updates by update id 
	public static function find_details_by_updt_id($updt_id){
		$database = new Database;
		$query = "SELECT * FROM ".static::$table_name." WHERE updt_id='{$updt_id}' LIMIT 1";
		$result_array = $database->query($query);
		while($row=$database->fetch_object($result_array)){
				$data[]=$row;	
		}
		return !empty($data) ? array_shift($data) : false;
    }	
  
		//<---gets the sticky wall notes from the aurthors
	public static function find_sticky_notes_by_profile($profile_id_fk, $per_page=10, $offset=1) {
		$database = new Database;
		$morequery= !empty($per_page) ? "LIMIT {$per_page}" : "";
		$query="SELECT * FROM ".static::$table_name."
				 WHERE profile_id_fk='{$profile_id_fk}' AND wall=1 ORDER BY created DESC  {$morequery} ";
		$result_array = static::find_by_sql($query);
		return !empty($result_array) ? ($result_array) : false;
	}
	
		//Delete Updates by update_ID
	public static function delete_update_by_id($author_id_fk, $updt_id){
		$database = new Database;
        $query = $database->query("DELETE FROM `updatez` WHERE updt_id = '{$updt_id}' and author_id_fk='{$author_id_fk}'");
        return true;
	}

}
?>