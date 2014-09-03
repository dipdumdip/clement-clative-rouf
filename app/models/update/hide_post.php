<?php
namespace Models\Update;

use \Database\DatabaseObject as DatabaseObject;
use \Database\Database as Database;

class Hide_Post extends DatabaseObject {
	//declairing the table name as static
	protected static $table_name="hide_post";
	//list the essential database fields into an array for CRUD
	protected static $db_fields = array('hide_id',  'post_id_fk', 'author_id_fk', 'created');
	

	public $hide_id;
	public $post_id_fk;
	public $author_id_fk;
	public $created;
	
  
	//Insert message into the database 
  public function Check_hiden_update($author_id_fk, $post_id_fk) 
	{
    $query =" SELECT 1 FROM ".static::$table_name." WHERE author_id_fk='{$author_id_fk}'
    						AND post_id_fk='{$post_id_fk}' LIMIT 1";
	
		$result_holder=$this->db->query($query);
		return  ($result_holder->num_rows() > 0) ? true : false;
       
    }	
	
	
	 	//Delete POST ID
    public function Delete_Comment_by_post_id($uid, $post_id_fk){
		$this->db->where(array('author_id_fk' =>$uid, 'updt_id_fk' => $post_id_fk ));
		return $this->db->delete(static::$table_name); 
       }
}

?>