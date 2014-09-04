<?php
namespace Authors;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class Author extends DatabaseObject {

	//list the essential database fields into an array for CRUD
		protected $fillable = array( 'authorname', 'password', 'email', 'salt', 'follower_count', 'role',  
													'last_login', 'part', 'confirm', 'active');
	

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'authors';
	protected static $table_name ='authors';
    protected $primaryKey = 'auid';
    public $timestamps = false;

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

 
		// function to find the Author Name  by Author id
	public static function find_authorname_by_auid($auid='') {
		$query= "SELECT authorname FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'authorname') : false;
	}
			//function to delete author data by author_ID
	public static function delete_author_data($auid=0) {
		$database = new Database;
			$query= "DELETE FROM `".static::$table_name."` WHERE auid = '{$auid}' LIMIT 1";
			$result_array = $database->query($query);
			return $result_array;
	}	

		// printing  the full name from the variables	
	public function full_name() {
		if(isset($this->first_name) && isset($this->last_name)) {
		  return $this->first_name . " " . $this->last_name;
		} else {
		  return "";
		}
	}

		// checking the password is correct or not
	public static function check_the_password_authorid($auid='', $password) {
    $result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE auid = '{$auid}' AND password='{$password}' LIMIT 1");
		return !empty($result_array) ? true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}
  
		// checking the authorname for the member existance
	public static function find_by_authorname($authorname='') {
    $result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE authorname= '{$authorname}' LIMIT 1");
		return !empty($result_array) ? true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}
  
		// checking the authorname for the member existance
	public static function check_password_login($authorname='', $password='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE authorname='{$authorname} AND password='{$password}' LIMIT 1");
			return !empty($result_array) ? true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}
 
		//function to find record by author name used searching users tab 
	public static function find_auid_authorname_by_word($authorname='') {
		$database = new Database;

		 $q=$database->escape_value($authorname);
		$result_array = $database->query("SELECT auid, authorname FROM ".self::$table_name." WHERE authorname LIKE '%{$q}%' ORDER BY auid LIMIT 5");
			while($row=$database->fetch_object($result_array)){
			  $data[]=$row;
			 }
		return !empty($data) ? $data : false;
	} 
 
		// function to find authors recors by email
	public static function find_userForGuest_by_word($email='') {
		$database = new Database;

			$q=$database->escape_value($email);
			$result_array = $database->query("SELECT auid, authorname, email FROM ".self::$table_name." WHERE email LIKE '%{$q}%' ORDER BY auid LIMIT 5");
		while($row=$database->fetch_object($result_array)){
		  $data[]=$row;
		  }
		return !empty($data) ? $data : false;
	} 

		// used in profile.php page to find the author id 
	public static function find_auid_by_authorname($authorname='') {
		$result_array = static::find_by_sql("SELECT auid FROM ".self::$table_name." WHERE authorname= '{$authorname}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}

		// function to find record by email name
	public static function find_auid_by_email($email='') {
		$result_array = static::find_by_sql("SELECT auid FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}
 
   
		// find the Author Name  by Author id used in Admin area 
	public static function find_authorname_by_auid_ADMIN($auid='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}
   
		//function to  find the Salt  by Author id
	public static function Get_the_salt_by_auid($auid='') {
		$database = new Database;

		$result_array =$database->query("SELECT salt FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['salt'] : false;
	}
         
		//function to  find the Author Name  by Author id
	public static function authorname_by_auid($auid='') {
		$database = new Database;

		$result_array =$database->query("SELECT authorname FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['authorname'] : false;
	}
		//function to  find the Author Name  by Author id
	public static function email_by_auid($auid='') {
		$database = new Database;

		$result_array =$database->query("SELECT email FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['email'] : false;
	}
      
		//function to  find the Author Name  by Author id
	public static function authorname_by_email($email='') {
		$result_array = static::find_by_sql("SELECT authorname, confirm, role FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
			// return !empty($result_array) ? true : false;
				return !empty($result_array) ? array_shift($result_array) : false;
	}
   
		//function to find the follower_count  by Author id
	public static function follower_count_by_auid($auid='') {
		$database = new Database;

		$result_array =$database->query("SELECT follower_count FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['follower_count'] : false;
	}
   
		//function to find the last_login  by Author id
	public static function last_login_by_auid($auid='') {
		$database = new Database;

		$result_array =$database->query("SELECT if(last_login<>0,last_login, unix_timestamp(created))as last_login FROM ".self::$table_name." WHERE auid= '{$auid}' LIMIT 1");
				$data = $database->fetch_assoc($result_array);
		  return !empty($data) ? $data['last_login'] : false;
	}

	//function  used in profile.php page to find the author basic details 
	public static function find_author_details_by_auid($auid='') {
		$result_array = static::find_by_sql("SELECT auid,authorname,email,follower_count,last_login FROM ".self::$table_name." 
									WHERE auid= '{$auid}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}
  
		//function to find the datas by email address
	public static function find_by_email($email='') {
		$result_array = static::find_by_sql("SELECT auid FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
			return !empty($result_array) ? true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}
  
		//function to find the datas of Salt and Author_id
	public static function athuor_salt_auid_by_email($email='') {
		$result_array = static::find_by_sql("SELECT auid, salt, role FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
			// return !empty($result_array) ? true : false;
				return !empty($result_array) ? array_shift($result_array) : false;
	}
  
		//finding the datas by email name for chair asign
	public static function find_by_email_chair_check($email='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}
 
			//finding the datas by email name for chair asign
	public static function find_by_email_confirn_check($email='') {
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	}  
			//finding the datas by email name for chair asign
	public static function find_by_email_confirn_company_REG($email='',  $follower_count=0) {
		$result_array = self::find_by_sql("SELECT * FROM ".self::$table_name." WHERE email= '{$email}' AND follower_count='{$follower_count}' LIMIT 1");
				return !empty($result_array) ? array_shift($result_array) : false;
	} 
			//finding the datas by email name for chair asign
	public static function check_for_password_rest_confirn($email='', $confirm='') {
		$result_array = self::find_by_sql("SELECT 1 FROM ".self::$table_name." WHERE email= '{$email}' AND confirm='{$confirm}' LIMIT 1");
				return !empty($result_array) ? true : false;

		  // $database = new Database;
			// $query="SELECT P.photo FROM ".static::$table_name." A  LEFT JOIN personal P on P.author_id_fk=A.auid
									// WHERE A.email= '{$email}' AND A.confirm='{$confirm}' LIMIT 1" ;
		// $result_array = $database->query($query);
		// $data = $database->fetch_assoc($result_array);
				// return !empty($data) ? $data['photo'] : false;
	}
 
		//finding the datas by email name for chair asign
	public static function user_Check_by_email($email='') {
		$database = new Database;

		$result_array =$database->query("SELECT auid FROM ".self::$table_name." WHERE email= '{$email}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['auid'] : false;
	}
   
		//finding the auid by Authorname name for chair asign
	public static function user_Check_by_authorname($authorname='') {
		$database = new Database;

		$result_array =$database->query("SELECT auid FROM ".self::$table_name." WHERE authorname= '{$authorname}' LIMIT 1");
		$data = $database->fetch_assoc($result_array);
		return !empty($data) ? $data['auid'] : false;
	}
  
		//Function for authenticating user Authorname
	public static function authenticate($username="", $password="",$role="") {
		$database = new Database;

			$authorname = $database->escape_value($username);
			$password = $database->escape_value($password);

			$role=(empty($role)) ? 3 : $database->escape_value($role);
			
			$sql  = "SELECT * FROM ".self::$table_name." ";
			$sql .= "WHERE authorname = '{$authorname}' ";
			$sql .= "AND password = '{$password}' ";
			$sql .= "AND role = '{$role}' ";
			$sql .= "LIMIT 1";
			$result_array = self::find_by_sql($sql);
				return !empty($result_array) ? array_shift($result_array) : false;
	}
	  
		  //Function for authenticating user By Emai Address Using Salt
	public static function confirmation_email_logging($email="") {
		$database = new Database;

		$email = $database->escape_value($email);
		$sql  = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE email = '{$email}' ";
		// $sql .= "AND password = '{$password_hashed}' ";
		// $sql .= "AND role = '{$role}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
			if(!empty($result_array)){
				self::update_logging_time($email);
			}
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	  
		  //Function for authenticating user By Emai Address Using Salt
	public static function authenticate_VIA_email_salt($email="", $password="",$role="3", $passed_salt="") {
		$database= new Database;

		$email = $database->escape_value($email);
		$password = $database->escape_value($password);
		$passed_salt = $database->escape_value($passed_salt);
		$role=(empty($role)) ? 3 : $database->escape_value($role);
									//<----this below section generate the password from salt and hash...
		$password_hashed = hash('sha512', $password.$passed_salt);
			
		$sql  = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE email = '{$email}' ";
		$sql .= "AND password = '{$password_hashed}' ";
		$sql .= "AND role >= '{$role}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
			if(!empty($result_array)){
				self::update_logging_time($email);
			}
		return !empty($result_array) ? array_shift($result_array) : false;
	}

		  //Function for authenticating user By Emai Address
	public static function authenticate_VIA_email($email="", $password="",$role="") {
		$database = new Database;

		$email = $database->escape_value($email);
		$password = $database->escape_value($password);
			$role=(empty($role)) ? 3 : $database->escape_value($role);

		$sql  = "SELECT * FROM ".self::$table_name." ";
		$sql .= "WHERE email = '{$email}' ";
		$sql .= "AND password = '{$password}' ";
		$sql .= "AND role = '{$role}' ";
		$sql .= "LIMIT 1";
		$result_array = self::find_by_sql($sql);
			if(!empty($result_array)){
				self::update_logging_time($email);
			}
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
		// function to check xistancy by author name 
	public static function find_by_authorname_login($authorname='') {
		$result_array = static::find_by_sql("SELECT * FROM ".self::$table_name." WHERE authorname= '{$authorname}' LIMIT 1");
			return empty($result_array) ?  true : false;
				// return !empty($result_array) ? array_shift($result_array) : false;
	}	
	
		// Functions to update last login time  by email name
	public static function update_logging_time($email='') {
		$database = new Database;

		$time_now=time();
		$query= "UPDATE ".self::$table_name." SET last_login={$time_now} WHERE email='{$email}' LIMIT 1";
			$result_array = $database->query($query);
	}	

	// function to find author total list in pagination 
	public static function find_all_BY_ADMIN($sorter="created", $per_page=12, $offset=0, $sub='', $order="DESC"){
		 $database = new Database;
		 $morequery= (isset($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "";	
			$query ="SELECT A.auid as ID,
							CONCAT('Auid: <spant>',A.auid,'</spant> Profile: <spant>',A.authorname,'</spant> FullName: <spant>', P.firstname_entry,' ',P.surname_entry,'</spant>') as title,
							A.created as created,
							CONCAT('Job Title as: ', P.title_post)as msg					
							FROM ".static::$table_name." A 
								LEFT JOIN personal P on P.author_id_fk=A.auid
								ORDER BY A.{$sorter} {$order}  {$morequery} ";
		$result_array = $database->query($query);
		while($row=$database->fetch_object($result_array)){
				$data[]=$row;	
		}
		return !empty($data) ? ($data) : false;
	}
	
	// function to find total number of author total list in pagination 
	public static function find_all_BY_ADMIN_count($sub='') {
		  $database = new Database;
		$query="SELECT COUNT(*) FROM ".static::$table_name." " ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
				return !empty($data) ? $data['COUNT(*)'] : false;
	}
  
 // finding AUTHOR Record on ajax keypress load ADMIN Total
	public static function find_All_by_word_ADMIN_count($String_passed='', $sale_type="normal") {
		  $database = new Database;
		$q=$database->escape_value($String_passed);
		$query="SELECT COUNT(*) FROM ".static::$table_name." A 
								LEFT JOIN personal P on P.author_id_fk=A.auid
							WHERE ( MATCH (  P.firstname_entry, P.surname_entry , P.title_post ) AGAINST ( '{$String_passed}'    IN BOOLEAN MODE )
									OR MATCH (A.authorname) AGAINST ( '{$String_passed}'    IN BOOLEAN MODE ) 
									OR A.authorname like '%{$String_passed}%' )" ;
		$result_array = $database->query($query);
		$data = $database->fetch_assoc($result_array);
				return !empty($data) ? $data['COUNT(*)'] : false;
	}

   // finding Author Record on ajax keypress load ADMIN
  public static function find_All_by_word_ADMIN($sorter="created", $String_passed='', $per_page=12, $offset=0, $sale_type="normal", $order="DESC") {
		$database = new Database;
		$q=$database->escape_value($String_passed);
		 $morequery= (isset($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "";	
		$query ="SELECT  A.auid as ID,
							CONCAT('Auid: <spant>',A.auid,'</spant> Profile: <spant>',A.authorname,'</spant> FullName: <spant>', P.firstname_entry,' ',P.surname_entry,'</spant>') as title,
							A.created as created,
							CONCAT('Job Title as: ', P.title_post)as msg					
							FROM ".static::$table_name." A 
								LEFT JOIN personal P on P.author_id_fk=A.auid
							WHERE ( MATCH (  P.firstname_entry, P.surname_entry , P.title_post ) AGAINST ( '{$String_passed}'   IN BOOLEAN MODE )
									OR MATCH (A.authorname) AGAINST ( '{$String_passed}'    IN BOOLEAN MODE ) 
									OR A.authorname like '%{$String_passed}%' )
							ORDER BY A.{$sorter} {$order} {$morequery} ";
		$result_array = $database->query($query);
		while($row=$database->fetch_object($result_array)){
				$data[]=$row;
		  }
		return !empty($data) ? $data : false;
  }	
}
