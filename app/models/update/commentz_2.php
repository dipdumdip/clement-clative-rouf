<?php
namespace Update;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;

class Commentz_2 extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="commentz_2";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('cmnt_id',  'comments', 'updt_id_fk', 'author_id_fk', 'ip', 'created');
	

	public $cmnt_id;
	public $comments;
	public $updt_id_fk;
	public $author_id_fk;
	public $ip;
	public $created;
	
  	
		// find the total amount of comments 
 	 public function Total_replys_no($updt_id_fk) {
	  $query="SELECT COUNT(*) as Total FROM ".static::$table_name." WHERE updt_id_fk='{$updt_id_fk}' ";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_assoc($result_holder, 'Total') : false;
	}
		 
 	// function to find the record as in after a count
	public function load_comments_by_updt_id_count($updt_id_fk, $second_count){

		$morequery= (!empty($second_count)) ? "LIMIT $second_count,2" : '';
				
	    $query="SELECT * FROM ".static::$table_name." WHERE updt_id_fk='{$updt_id_fk}'
	    						 ORDER BY cmnt_id ASC {$morequery}";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;
	}

	// Getting Comments with Pagination 
	public function load_comments_by_updt_id_pagination($updt_id_fk, $per_page,	$offset){

		$morequery=(!empty($per_page) || isset($offset)) ? "LIMIT {$per_page} OFFSET {$offset}" : "";
				
	  $query= "SELECT * FROM ".static::$table_name." WHERE updt_id_fk='{$updt_id_fk}'
	  		 ORDER BY cmnt_id ASC {$morequery}";
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? static::fetch_object($result_holder) : false;
	}

	//Insert message into the database 
  public static function get_last_entry($author_id_fk){
    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE author_id_fk={$author_id_fk} ORDER BY cmnt_id DESC LIMIT 1");
	
	return !empty($result_array) ? array_shift($result_array) : false;
       
    }

	  public static function delete_data_by_updt_id_ARR($author_id=0, $rmnd_id_str="") {
			$database = new Database;
				$query= "DELETE FROM `".static::$table_name."` WHERE updt_id_fk IN ({$rmnd_id_str}) 
																			AND author_id_fk='{$author_id}'";
				$result_array = $database->query($query);
				return $result_array;
		}	
		
	//Insert message into the database 
  public static function get_last_entry_replay($author_id_fk){
		 $database = new Database;
		 $query="SELECT comments FROM ".static::$table_name."
					WHERE author_id_fk='{$author_id_fk}' ORDER BY cmnt_id DESC LIMIT 1";
		 $result_array = $database->query($query);
			$data = $database->fetch_assoc($result_array);
			return !empty($data) ? $data['comments'] : false;
    }	
		 
  	//find  Comments by comment id 
  public static function find_details_by_cmnt_id($cmnt_id) 
	{

    $result_array = static::find_by_sql("SELECT * FROM ".static::$table_name." WHERE cmnt_id={$cmnt_id} LIMIT 1");
	
	return !empty($result_array) ? array_shift($result_array) : false;
       
    } 
	
	//Delete Comments
    public static function Delete_Comment($uid, $com_id) 
	{
			$database = new Database;
			$q=$database->query("SELECT C.author_id_fk FROM commentz_2 C WHERE C.cmnt_id='$com_id'");
			$d=$database->fetch_array($q);
			$oid=$d['author_id_fk'];

			if($uid==$oid)
			{
			$query = $database->query("DELETE FROM `commentz_2` WHERE cmnt_id='$com_id'");
				return true;
				}
			else
			{
				$query = $database->query("DELETE FROM `commentz_2` WHERE author_id_fk='$uid' and cmnt_id='$com_id'");
				return true;
			}
       }
	 	//Delete POST ID
    public static function Delete_Comment_by_post_id($uid, $post_id_fk){
		$database = new Database;
			$query = $database->query("DELETE FROM `commentz_2` WHERE author_id_fk='$uid' and updt_id_fk='$post_id_fk'");
		return true;
		
       }
}

?>