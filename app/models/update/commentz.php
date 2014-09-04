<?php
namespace Update;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class Commentz extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="commentz";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('cmnt_id',  'comments', 'updt_id_fk', 'author_id_fk', 'ip', 'created',
										 'updt', 'evnt', 'cloud', 'psts');
	

	public $cmnt_id;
	public $comments;
	public $updt_id_fk;
	public $author_id_fk;
	public $ip;
	public $created;
	public $updt;
	public $evnt;
	public $cloud;
	public $psts;
	
		// find the total amount of comments 
	public function Total_replys_no($column='updt',$updt_id_fk=0) {
		 $query="SELECT COUNT(*) as Total FROM ".static::$table_name." WHERE {$column}='{$updt_id_fk}' ";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
	}
		 
 	// function to find the record as in after a count
	public function load_comments_by_updt_id_count($column='updt', $updt_id_fk=0, $second_count)		{
		  $morequery= (!empty($second_count)) ? "LIMIT $second_count,2" : '';
	 	  $query="SELECT * FROM ".static::$table_name." WHERE {$column}='{$updt_id_fk}'
	 	  				 ORDER BY cmnt_id ASC {$morequery}";
	
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;
	}

	//functions to  Getting Comments with Pagination 
	public function load_comments_by_updt_id_pagination($column='updt', $updt_id_fk=0, $per_page, $offset){
     		$morequery=(!empty($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "";
		$query="SELECT * FROM ".static::$table_name." WHERE {$column}='{$updt_id_fk}' ORDER BY cmnt_id ASC {$morequery}";
	
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;

	}
 		//functions to find the last entered record 
	public static function get_last_entry($author_id_fk){
		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." 
				WHERE author_id_fk='{$author_id_fk}' ORDER BY cmnt_id DESC LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	}	
	
		//function find the Last entered comments 
	public static function get_last_entry_comment($author_id_fk) {
		 $database = new Database;
		 $query="SELECT comments FROM ".static::$table_name."
					WHERE author_id_fk='{$author_id_fk}' ORDER BY cmnt_id DESC LIMIT 1";
		 $result_array = $database->query($query);
			$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['comments'] : false;
	}

 	
	// function to Getting Comments IDS
	public static function load_comments_by_updt_id_all($updt_id_fk){
	   $result_array = static::find_by_sql("SELECT cmnt_id FROM ".static::$table_name." WHERE updt_id_fk='{$updt_id_fk}'");
		return !empty($result_array) ? ($result_array) : false;
	}
 	

 
 
  	//find  Comments by comment id 
	public static function find_details_by_cmnt_id($cmnt_id){

		$result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE cmnt_id='{$cmnt_id}' LIMIT 1");
		return !empty($result_array) ? array_shift($result_array) : false;
	} 
	
	//functions to Delete Comments
    public static function Delete_Comment($uid, $com_id){
		$database = new Database;
			$q=$database->query("SELECT M.author_id_fk FROM commentz C, updatez M WHERE C.updt_id_fk = M.updt_id AND C.cmnt_id='{$com_id}'");
			$d=$database->fetch_array($q);
			$oid=$d['author_id_fk'];

			if($uid==$oid) {
			$query = $database->query("DELETE FROM `commentz` WHERE cmnt_id='{$com_id}'");
				return true;
			}else{
				$query = $database->query("DELETE FROM `commentz` WHERE author_id_fk='{$uid}' and cmnt_id='{$com_id}'");
				return true;
			}
	}
	
	//functions to Delete comment by post ID
    public static function Delete_Comment_by_post_id($uid, $post_id_fk){
		$database = new Database;
			$query = $database->query("DELETE FROM `commentz` WHERE author_id_fk='{$uid}' and updt_id_fk='{$post_id_fk}'");
		return true;
	}

	//functions to Delete comment by post ID
    public static function Delete_Comment_by_Level_post_id($uid, $column='updt', $post_id_fk=0){
		$database = new Database;
			$query = $database->query("DELETE FROM `commentz` WHERE author_id_fk='{$uid}' and {$column}='{$post_id_fk}'");
		return true;
	}
	
	//functions to Delete comment by post ID
    public static function find_availavle_comment_id($uid, $column='updt', $post_id_fk=0){
		$database = new Database;
		 $query ="SELECT group_concat( cmnt_id ) as IDS
									FROM 
										(	SELECT cmnt_id FROM `commentz` WHERE author_id_fk='{$uid}' 
											and {$column}='{$post_id_fk}'
										) as dd";
			
		 $result_array = $database->query($query);
			$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['IDS'] : false;
	}

}

?>