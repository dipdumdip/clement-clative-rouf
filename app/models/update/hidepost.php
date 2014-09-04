<?php
namespace Update;

use DatabaseModel\DatabaseObject as DatabaseObject;

use DB;


class HidePost extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="hide_post";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('hide_id',  'post_id_fk', 'author_id_fk', 'created');
	

	public $hide_id;
	public $post_id_fk;
	public $author_id_fk;
	public $created;
	
  
	//Insert message into the database 
  public static function Check_hiden_update($author_id_fk, $post_id_fk) 
	{
    $query =" SELECT 1 FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}'
    						AND post_id_fk='{$post_id_fk}' LIMIT 1";
	
		$result_holder = DB::select( DB::raw( $query ));
		return  (static::num_rows($result_holder)) ? true : false;
       
    }	
	
	
	 	//Delete POST ID
    public function Delete_Comment_by_post_id($uid, $post_id_fk){
		$this->db->where(array('author_id_fk' =>$uid, 'updt_id_fk' => $post_id_fk ));
		return $this->db->delete(static::$table_name); 
       }
}

?>