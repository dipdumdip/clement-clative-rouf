<?php
namespace Models\Authors;

use \Models\database\DatabaseObject as DatabaseObject;
use \Models\Database\Database as Database;

class Delete_Author{
	
 	 		// Delete all detas By Company Id and comp_id
  public static function delete_author_all_data($author_id=0) {

			if(!Author::delete_author_data($author_id))
							{ $message="error on Author deletion";
								$defaulter=false;
								}


	if($defaulter){
				
				return true;
				
				}else 
					return $message;


 }
 		

	
}

?>