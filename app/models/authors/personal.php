<?php
namespace Models\Authors;

use \Models\Database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Personal extends DatabaseObject {
	
	protected static $table_name="personal";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('personal_id',  'surname_entry',  'firstname_entry',  'title_post',  'home_contact_num',  'home_address', 
										'home_lat', 'home_lng', 'office_contact_num', 'fax_no', 'office_address', 'office_lat', 'office_lng', 'aboutme', 
											'photo', 'upload', 'offi_time', 'hom_time', 'author_id_fk', 'view_count');
		
	public $personal_id;
	public $surname_entry;
	public $firstname_entry;
	public $title_post;
	public $home_contact_num;
	public $home_address;
	public $home_lat;
	public $home_lng;
	public $office_contact_num;
	public $fax_no;
	public $office_address;
	public $office_lat;
	public $office_lng;
	public $aboutme;
	public $photo;
	public $upload;
	public $offi_time;
	public $hom_time;
	public $author_id_fk;
	public $view_count;

	//<-----function to delete personal recor by author ID
	public static function delete_author_id_data($author_id_fk=0) {
		$database= new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE author_id_fk = '{$author_id_fk}' LIMIT 1";
			$result_array = $database->query($query);
			return $result_array;
	}	
	
	//finding details by using author_id
	public static function find_by_author_id($author_id_fk=0) {
		$primary_key=static::get_primary_key();
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}' LIMIT 1");
			return !empty($result_array) ? array_shift($result_array) : false;
	}
   
	//Profile View updation
	public static function updte_profile_page_view($author_id_fk=0) {
		$database= new Database;
		$result_array =$database->query("UPDATE ".self::$table_name." SET `view_count` = view_count+1 WHERE author_id_fk= '{$author_id_fk}'");
	  
	}
        	
	// function to do for trend by admin
	public static function find_all_for_Trendz_ADMIN() {
		$database= new Database;
		$result_array =$database->query("SELECT author_id_fk As id, view_count FROM ".self::$table_name." ");
		while($row=$database->fetch_object($result_array)){
				$totttal_array[]=$row;
				}		
		return !empty($totttal_array) ? ($totttal_array) : false;
	} 

		//functions to find the total profile view 
	public static function find_profile_view_by_author_id($author_id_fk=0) {
		$database= new Database;
		$result_array =$database->query("SELECT view_count FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['view_count'] : false;
	}
	 
		//function to find the profile photo by authors ID
	public static function find_profile_photo_by_author_id($author_id_fk=0) {
		$database= new Database;
		$result_array =$database->query("SELECT photo FROM ".self::$table_name." WHERE author_id_fk= '{$author_id_fk}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['photo'] : false;
	}
 	 
		//function to find the profile photo by authors email
	public static function find_profile_photo_by_email($email_get='') {
		$database= new Database;
		$result_array =$database->query("SELECT P.photo as photo FROM ".self::$table_name." P 
																		INNER JOIN authors A on A.auid= P.author_id_fk
																		WHERE A.email= '{$email_get}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
	  return !empty($data) ? $data['photo'] : false;
	} 	 
		//function to find the profile photo by authors email
	public static function Main_profile_data_by_author_id($author_id_fk='') {
		$database= new Database;
			  $result_array =$database->query(" SELECT A.authorname, A.email, A.last_login, P.office_address, P.home_address, P.title_post,
												P.office_contact_num, P.surname_entry, P.firstname_entry
												FROM ".self::$table_name." P 
												INNER JOIN authors A on A.auid= P.author_id_fk
												WHERE P.author_id_fk= '{$author_id_fk}' LIMIT 1 ");
		while($row=$database->fetch_object($result_array)){
				$totttal_array[]=$row;
				}		
		return !empty($totttal_array) ? array_shift($totttal_array) : false;
	}
 		//function to find the profile photo by authors email
	public static function Owner_contact_by_author_id($author_id_fk='') {
		$database= new Database;
			  $result_array =$database->query(" SELECT 
								CASE WHEN (P.firstname_entry IS NULL OR P.surname_entry IS NULL)
									THEN  A.authorname
									ELSE CONCAT( P.firstname_entry,'  ', P.surname_entry)
								END AS Fullname,
								CASE WHEN (P.office_contact_num IS NULL)
									THEN  P.home_contact_num
									ELSE P.office_contact_num
								END AS phone,
								A.email, P.fax_no AS fax
									FROM ".self::$table_name." P 
												INNER JOIN authors A on A.auid= P.author_id_fk
												WHERE P.author_id_fk= '{$author_id_fk}' LIMIT 1 ");
		while($row=$database->fetch_object($result_array)){
				$totttal_array[]=$row;
				}		
		return !empty($totttal_array) ? array_shift($totttal_array) : false;
	}
 
	// function to find full name By author_id
	public static function find_Fullname($author_id_fk=0) {
		$database= new Database;
		$query ="SELECT CONCAT( firstname_entry,'  ', surname_entry) AS Fullname
						FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}' LIMIT 1";
		 $result_array = $database->query($query);
		$data =  $database->fetch_assoc($result_array);
		return !empty($data) ? $data['Fullname'] : false;
	}
  

}

?>